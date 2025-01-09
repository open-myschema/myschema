<?php

declare(strict_types=1);

namespace MySchema\Content\Action;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\InputFilter\InputFilter;
use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use MySchema\Content\ContentMetaStatusInterface;
use MySchema\Content\InputFilter\ContentInputFilter;
use MySchema\Content\Model\Content;
use MySchema\Content\Validator\ContentExistsValidator;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;
use Psr\Container\ContainerInterface;

class CreateContentAction extends Action
{
    use ServiceFactoryTrait;

    public function __invoke(ContainerInterface $container): ActionResult
    {
        // get content object
        $content = $this->getParam('content');
        if (! $content instanceof Content) {
            return new ActionResult(
                code: StatusCodeInterface::STATUS_BAD_REQUEST,
                messages: [
                    'content' => 'No content found',
                ]
            );
        }

        // validate input
        $inputFilter = $this->composeInputFilter($container, $this->getParam('input_filters', []));
        $inputFilter->setData($content->toArray());
        if (! $inputFilter->isValid()) {
            return new ActionResult(
                code: StatusCodeInterface::STATUS_BAD_REQUEST,
                messages: $inputFilter->getMessages()
            );
        }

        // get a database connection
        $connection = (new ConnectionFactory($container))->connect();

        // check if content already exists via type and identifier
        $contentExists = (new ContentExistsValidator($connection))->exists(
            identifier: $inputFilter->getValue('identifier'),
            args: $this->getParam('check_exists', [])
        );
        if ($contentExists) {
            return new ActionResult(
                code: StatusCodeInterface::STATUS_CONFLICT,
                messages: [
                    "Content already exists"
                ]
            );
        }

        // prepare queries
        $contentQuery = "INSERT INTO content
            (description, identifier, image, name, owner, props, url, visibility)
            VALUES(:description, :identifier, :image, :name, :owner, :props, :url, :visibility)";

        $contentMetaQuery = "INSERT INTO content_meta
            (content_id, status, description, agent, data)
            VALUES(:content_id, :status, :description, :agent, :data)";

        $contentTagQuery = "INSERT INTO content_tag (content_id, name) VALUES(:content_id, :name)";
        $contentTypeQuery = "INSERT INTO content_type (content_id, name) VALUES(:content_id, :name)";

        // prepare values
        $values = $inputFilter->getValues();
        $values['props'] = \json_encode($values['props']);
        $tags = $values['tags'];
        $types = $values['types'];
        unset($values['tags']);
        unset($values['types']);

        // save content
        $connection->beginTransaction();
        try {
            $result = $connection->insert($contentQuery, $values);
            if (\is_bool($result)) {
                throw new \Exception("Content not created");
            }
            $contentId = \intval($result);

            // initial content meta
            $connection->insert($contentMetaQuery, [
                'content_id' => $contentId,
                'status' => ContentMetaStatusInterface::STATUS_CONTENT_CREATED,
                'description' => null,
                'agent' => $inputFilter->getValue('owner'),
                'data' => \json_encode($inputFilter->getValues()),
            ]);

            // tags
            foreach ($tags as $tag) {
                $connection->insert($contentTagQuery, [
                    'content_id' => $contentId,
                    'name' => $tag
                ]);
            }

            // types
            foreach ($types as $type) {
                $connection->insert($contentTypeQuery, [
                    'content_id' => $contentId,
                    'name' => $type
                ]);
            }

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollback();
            return new ActionResult(
                code:StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                messages: [$e->getMessage()]
            );
        }

        // trigger content creation action
        $this->getEventDispatcher($container)->dispatch(
            new ContentCreatedAction($content, $inputFilter->getValues())
        );

        return new ActionResult(TRUE, StatusCodeInterface::STATUS_CREATED);
    }

    public function assertAuthorization(): bool
    {
        return TRUE;
    }
    
    private function composeInputFilter(ContainerInterface $container, array $additionalInputFilters): InputFilter
    {
        $inputFilter = new ContentInputFilter();
        $inputFilterManager = $this->getInputFilterManager($container);

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
