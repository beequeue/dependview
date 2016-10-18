<?php

namespace Beequeue\DependView\Table;

class Row
{
    protected $cells = [];

    public function __construct(array $options = [])
    {

    }

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
    }

    public function asArray() : array
    {
        $row = ['cells' => []];
        foreach ($this->cells as $cell) {
            $row['cells'][] = $cell->asArray();
        }
        return $row;
    }
}