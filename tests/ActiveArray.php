<?php
namespace IVIR3aM\ObjectArrayTools\Tests;

use IVIR3aM\ObjectArrayTools\AbstractActiveArray;

class ActiveArray extends AbstractActiveArray
{
    public $update = array();

    protected function updateHook($offset, $data, $oldData)
    {
        $this->update = array($offset, $oldData);
    }

    protected function removeHook($offset, $oldData)
    {
        $this->update = array($offset, $oldData);
    }

    protected function insertHook($offset, $data)
    {
        $this->update = array($offset, $data);
    }

    protected function sanitizeInputHook($offset, $data)
    {
        return ucfirst($data);
    }

    protected function filterInputHook($offset, $data)
    {
        if ($data === 'FILTER') {
            return false;
        }
    }

    protected function filterRemoveHook($offset, $data)
    {
        if ($offset === 'NO_REMOVE') {
            return false;
        }
    }

    protected function sanitizeOutputHook($offset, $data)
    {
        if ($offset === 'UPPER') {
            $data = strtoupper($data);
        }
        return $data;
    }

    protected function filterOutputHook($offset, $data)
    {
        if ($offset === 'NULL') {
            return false;
        }
    }
}
