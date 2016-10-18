<?php

namespace Beequeue\DependView\Table;

class DependencyTable
{
    protected $rows = [];

    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    public function asArray()
    {
        $table = ['rows' => []];
        foreach ($this->rows as $row) {
            $table['rows'][] = $row->asArray();
        }
        return $table;
    }
}