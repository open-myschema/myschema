<?php

declare(strict_types=1);

namespace MySchema\Content\Command;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\InputFilter\InputFilter;
use MySchema\Command\BaseCommand;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Content\ContentMetaStatusInterface;
use MySchema\Content\Event\ContentCreatedEvent;
use MySchema\Content\InputFilter\ContentInputFilter;
use MySchema\Content\Type\Content;
use MySchema\Content\Validator\ContentExistsValidator;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function assert;
use function is_bool;
use function json_encode;
use function sprintf;

class CreateContentCommand extends BaseCommand
{
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->setDescription('Create content');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // get content object
        $content = $input->getOption('content');
        if (! $content instanceof Content) {
            $output->writeln("No content found");
            if ($output instanceof Psr7ResponseOutputInterface) {
                $output->setStatusCode(StatusCodeInterface::STATUS_BAD_REQUEST);
            }

            return BaseCommand::FAILURE;
        }

        // validate input
        $inputFilter = $this->composeInputFilter($input->getOption('input_filters'));
        $inputFilter->setData($content->toArray());
        if (! $inputFilter->isValid()) {
            $output->writeln($inputFilter->getMessages());
            if ($output instanceof Psr7ResponseOutputInterface) {
                $output->setStatusCode(StatusCodeInterface::STATUS_BAD_REQUEST);
            }
            return BaseCommand::FAILURE;
        }

        // get a database connection
        $connection = (new ConnectionFactory($this->container))->connect();

        // check if content already exists via type and identifier
        $contentExists = (new ContentExistsValidator($connection))->exists(
            params: $input->getOption('check_exists')
        );
        if (false !== $contentExists) {
            $output->writeln(sprintf(
                "Content already exists. Conflicts with %s: %s: %s",
                $contentExists['name'],
                $contentExists['identifier'],
                json_encode($contentExists['props'])
            ));
            if ($output instanceof Psr7ResponseOutputInterface) {
                $output->setStatusCode(StatusCodeInterface::STATUS_CONFLICT);
            }

            return BaseCommand::FAILURE;
        }

        // prepare queries
        $resources = $this->getResourceManager($this->container);
        $contentQuery = $resources->getQuery('main::create-content');
        $contentMetaQuery = $resources->getQuery('main::create-content-meta');
        $contentTagQuery = $resources->getQuery('main::create-content-tag');
        $contentTypeQuery = $resources->getQuery('main::create-content-type');

        // prepare values
        $values = $inputFilter->getValues();
        $values['props'] = json_encode($values['props']);
        $tags = $values['tags'];
        $types = $values['types'];
        unset($values['tags']);
        unset($values['types']);

        // save content
        $connection->beginTransaction();
        try {
            $result = $connection->insert($contentQuery, $values);
            if (is_bool($result)) {
                throw new \Exception("Content not created");
            }
            $contentId = \intval($result);

            // initial content meta
            $connection->insert($contentMetaQuery, [
                'content_id' => $contentId,
                'status' => ContentMetaStatusInterface::STATUS_CONTENT_CREATED,
                'agent' => $inputFilter->getValue('owner'),
                'data' => json_encode($inputFilter->getValues()),
            ]);

            // tags
            $connection->insert($contentTagQuery, [
                'content_id' => $contentId,
                'data' => json_encode($tags),
            ]);

            // types
            $connection->insert($contentTypeQuery, [
                'content_id' => $contentId,
                'data' => json_encode($types),
            ]);

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollback();
            $output->writeln($e->getMessage());
            if ($output instanceof Psr7ResponseOutputInterface) {
                $output->setStatusCode(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
            }
            return BaseCommand::FAILURE;
        }

        // trigger content creation action
        $this->getEventDispatcher($this->container)->dispatch(
            new ContentCreatedEvent($content, $inputFilter->getValues())
        );

        if ($output instanceof Psr7ResponseOutputInterface) {
            $output->setData(true, 'bool');
            $output->setStatusCode(StatusCodeInterface::STATUS_CREATED);
        }

        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }

    private function composeInputFilter(array $additionalInputFilters): InputFilter
    {
        $inputFilter = new ContentInputFilter();
        if (empty($additionalInputFilters)) {
            return $inputFilter;
        }

        $inputFilterManager = $this->getInputFilterManager($this->container);
        foreach ($additionalInputFilters as $inputFilterName) {
            $additionalInputFilter = $inputFilterManager->get($inputFilterName);
            if (! assert($additionalInputFilter instanceof InputFilter)) {
                // @todo silently log
                continue;
            }

            // merge with content input filter
            $inputFilter->merge($additionalInputFilter);
        }

        return $inputFilter;
    }
}
