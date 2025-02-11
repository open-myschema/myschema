<?php

declare(strict_types=1);

namespace MySchema\Content\InputFilter;

use Laminas\InputFilter\InputFilter;

class ContentInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add($this->getDescriptionInput());
        $this->add($this->getIdentifierInput());
        $this->add($this->getImageInput());
        $this->add($this->getNameInput());
        $this->add($this->getOwner());
        $this->add($this->getPropsInput());
        $this->add($this->getTagsInput());
        $this->add($this->getTypesInput());
        $this->add($this->getUrlInput());
        $this->add($this->getVisibilityInput());
    }

    private function getDescriptionInput(): array
    {
        return [
            'name' => 'description',
            'required' => false,
            'filters' => [
                [
                    'name' => \Laminas\Filter\HtmlEntities::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => false,
            'continue_if_empty' => true,
        ];
    }

    private function getIdentifierInput(): array
    {
        return [
            'name' => 'identifier',
            'required' => false,
            'filters' => [
                [
                    'name' => \Laminas\Filter\HtmlEntities::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getImageInput(): array
    {
        return [
            'name' => 'image',
            'required' => false,
            'filters' => [
                [
                    'name' => \Laminas\Filter\HtmlEntities::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getNameInput(): array
    {
        return [
            'name' => 'name',
            'required' => false,
            'filters' => [
                [
                    'name' => \Laminas\Filter\StringTrim::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getOwner(): array
    {
        return [
            'name' => 'owner',
            'required' => true,
            'filters' => [
                [
                    'name' => \Laminas\Filter\ToInt::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => false,
            'continue_if_empty' => true,
        ];
    }

    private function getPropsInput(): array
    {
        return [
            'name' => 'props',
            'required' => true,
            'filters' => [],
            'validators' => [
                [
                    'name' => \Laminas\Validator\IsArray::class,
                ]
            ],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getTagsInput(): array
    {
        return [
            'name' => 'tags',
            'required' => true,
            'filters' => [],
            'validators' => [
                [
                    'name' => \Laminas\Validator\IsArray::class,
                ]
            ],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getTypesInput(): array
    {
        return [
            'name' => 'types',
            'required' => true,
            'filters' => [],
            'validators' => [
                [
                    'name' => \Laminas\Validator\IsArray::class,
                ]
            ],
            'allow_empty' => false,
            'continue_if_empty' => true,
        ];
    }

    private function getUrlInput(): array
    {
        return [
            'name' => 'url',
            'required' => false,
            'filters' => [
                [
                    'name' => \Laminas\Filter\HtmlEntities::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ];
    }

    private function getVisibilityInput(): array
    {
        return [
            'name' => 'visibility',
            'required' => true,
            'filters' => [
                [
                    'name' => \Laminas\Filter\ToInt::class,
                ]
            ],
            'validators' => [],
            'allow_empty' => false,
            'continue_if_empty' => true,
        ];
    }
}
