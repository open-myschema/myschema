<?php

declare(strict_types=1);

namespace MySchema\Page\Action;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\InputFilter\Input;
use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;
use Psr\Container\ContainerInterface;

class CreatePageAction extends Action
{
    use ServiceFactoryTrait;

    public function __invoke(ContainerInterface $container): ActionResult
    {
        if ($this->hasParam('requestMethod') && $this->getParam('requestMethod') === 'GET') {
            return new ActionResult;
        }

        // verify input
        $title = new Input('title');
        $description = new Input('description');
        $url = new Input('url');

        $inputFilter = $this->getInputFilter()
            ->add($title)
            ->add($description)
            ->add($url)
            ->setData($this->getParam('parsedBody'));

        if (! $inputFilter->isValid()) {
            return new ActionResult(
                data: false,
                code: StatusCodeInterface::STATUS_BAD_REQUEST,
                messages: $inputFilter->getMessages()
            );
        }

        // prep to save
        $db = (new ConnectionFactory($container))->connect();
        $resourceManager = $this->getResourceManager($container);
        $adapter = $db->getAdapter();
        $sql = $resourceManager->getQuery('admin::create-page');
        $insert = $adapter->write($sql, [
            'title' => $inputFilter->getValue('title'),
            'description' => $inputFilter->getValue('description'),
            'url' => $inputFilter->getValue('url'),
        ]);

        $statusCode = TRUE === $insert ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_EXPECTATION_FAILED;
        $message = TRUE === $insert ? "Page created" : "Failed to save page";

        return new ActionResult($insert, $statusCode, messages: [$message]);
    }

    public function assertAuthorization(): bool
    {
        return TRUE;
    }
}
