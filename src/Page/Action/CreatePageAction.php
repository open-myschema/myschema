<?php

declare(strict_types=1);

namespace MySchema\Page\Action;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\InputFilter\Input;
use MySchema\Application\Action;
use MySchema\Application\ActionResult;
use MySchema\Database\ConnectionFactory;
use Psr\Container\ContainerInterface;

class CreatePageAction extends Action
{
    public function __invoke(ContainerInterface $container): ActionResult
    {
        // verify input
        $title = new Input('title');
        $description = new Input('description');
        $url = new Input('url');

        $inputFilter = $this->getInputFilter()
            ->add($title)
            ->add($description)
            ->add($url)
            ->setData($this->params);

        if (! $inputFilter->isValid()) {
            $code = StatusCodeInterface::STATUS_BAD_REQUEST;
            return new ActionResult($inputFilter->getMessages(), $code);
        }

        // prep to save
        $db = (new ConnectionFactory($container))->connect();
        $adapter = $db->getAdapter();
        $sql = "INSERT INTO page (title, description, url) VALUES(:title, :description, :url)";
        $insert = $adapter->write($sql, [
            'title' => $inputFilter->getValue('title'),
            'description' => $inputFilter->getValue('description'),
            'url' => $inputFilter->getValue('url'),
        ]);

        $statusCode = TRUE === $insert ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_EXPECTATION_FAILED;
        $message = TRUE === $insert ? "Page created" : "Failed to save page";

        return new ActionResult($insert, $statusCode, $message);
    }

    public function assertAuthorization(): bool
    {
        return TRUE;
    }
}
