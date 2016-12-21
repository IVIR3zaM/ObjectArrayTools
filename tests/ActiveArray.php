<?php
namespace IVIR3aM\ObjectArrayTools\Tests;

use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class ActiveArray extends AbstractActiveArray
{
    public $update = array();

    protected function UpdateHook($offset, $data, $oldData)
    {
        $this->update = array($offset, $oldData);
    }

    protected function RemoveHook($offset, $oldData)
    {
        $this->update = array($offset, $oldData);
    }

    protected function InsertHook($offset, $data)
    {
        $this->update = array($offset, $data);
    }

    protected function SanitizeInputHook($offset, $data)
    {
        return ucfirst($data);
    }

    protected function FilterInputHook($offset, $data)
    {
        if ($data === 'FILTER') {
            return false;
        }
    }

    protected function SanitizeOutputHook($offset, $data)
    {
        if ($offset === 'UPPER') {
            $data = strtoupper($data);
        }
        return $data;
    }

    protected function FilterOutputHook($offset, $data)
    {
        if ($offset === 'NULL') {
            return false;
        }
    }
}