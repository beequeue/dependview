<?php

namespace Beequeue\DependView\Table;

class Cell
{
    protected $cssClass = '';

    protected $text = '';

    protected $isHeader = false;

    protected $colSpan = null;

    public function __construct(array $options = [])
    {
        if (!empty($options['cssClass'])) {
            $this->cssClass = $options['cssClass'];
        }

        if (!empty($options['text'])) {
            $this->text = $options['text'];
        }

        if (!empty($options['isHeader']) && is_bool($options['isHeader'])) {
            $this->isHeader = $options['isHeader'];
        }

        if (!empty($options['colSpan'])) {
            $this->colSpan = $options['colSpan'];
        }
    }

    public function asArray() : array
    {
        return [
            'cssClass' => $this->cssClass,
            'text' => $this->text,
            'isHeader' => $this->isHeader,
            'colSpan' => $this->colSpan
        ];
    }
}