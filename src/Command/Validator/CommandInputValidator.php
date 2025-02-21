<?php

declare(strict_types=1);

namespace MySchema\Command\Validator;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputDefinition;

final class CommandInputValidator extends AbstractValidator
{
    public const string INPUT_NOT_ARRAY = 'inputNotArray';
    public const string REQUIRED_ARGUMENT_NOT_FOUND = 'requiredArgumentNotFound';
    public const string REQUIRED_OPTION_EMPTY = 'requiredOptionEmpty';
    protected array $messageTemplates = [
        self::INPUT_NOT_ARRAY => "Input to validate is not an array",
        self::REQUIRED_ARGUMENT_NOT_FOUND => "Required argument '%value%' not found",
        self::REQUIRED_OPTION_EMPTY => "Required option '%value%' not found or is empty",
    ];

    public function __construct(private InputDefinition $inputDefinition, private ?ValidatorInterface $commandValidator = null)
    {
        parent::__construct();
    }

    public function isValid($value): bool
    {
        // expect value to be an array
        // i.e InputInterface::getOptions
        if (! is_array($value)) {
            $this->error(self::INPUT_NOT_ARRAY);
            return false;
        }

        // required options and arguments
        foreach ($this->inputDefinition->getArguments() as $argument) {
            if ($argument->isRequired()) {
                if (! array_key_exists($argument->getName(), $value)) {
                    $this->error(self::REQUIRED_ARGUMENT_NOT_FOUND, $argument->getName());
                    return false;
                }
            }
        }

        foreach ($this->inputDefinition->getOptions() as $option) {
            if ($option->isValueRequired()) {
                if (! array_key_exists($option->getName(), $value) || empty($value[$option->getName()])) {
                    $this->error(self::REQUIRED_OPTION_EMPTY, $option->getName());
                    return false;
                }
            }
        }

        // any additional validator
        if ($this->commandValidator instanceof ValidatorInterface) {
            return $this->commandValidator->isValid($value);
        }

        return true;
    }
}
