<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Template\Engine\Twig;

use MySchema\Platform\Web\Template\TemplateRendererInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MySchema\Resource\ResourceManager;
use Twig\Loader\ArrayLoader;
use Twig\Environment;
use RuntimeException;
use MySchema\Command\Output\Psr7ResponseOutput;

class TwigTemplateRenderer implements TemplateRendererInterface
{
    private string $template;

    public function __construct(private ResourceManager $resourceManager, private $twigEnvironmentOptions = [])
    {
    }

    public function render(OutputInterface $output): string
    {
        if (! isset($this->template)) {
            throw new RuntimeException(
                "Twig template not set"
            );
        }

        if ($output instanceof Psr7ResponseOutput) {
            $context = match ($output->getDataType()) {
                "array" => $output->getData(),
                "int", "string", "bool" => ['value' => $output->getData()],
                default => [],
            };
        }

        $template = $this->resourceManager->getTemplate($this->template);
        $loader = new ArrayLoader([
            'template' => $template['contents']
        ]);
        $twig = new Environment($loader, $this->twigEnvironmentOptions);
        return $twig->render('template', $context ?? []);
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
}
