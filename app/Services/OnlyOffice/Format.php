<?php

namespace App\Services\OnlyOffice;

class Format
{
    public string $name;
    public string $type;
    public array $actions;
    public array $convert;
    public array $mime;

    public function __construct(
        string $name,
        string $type,
        array $actions,
        array $convert,
        array $mime
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->actions = $actions;
        $this->convert = $convert;
        $this->mime = $mime;
    }

    public function viewable(): bool
    {
        foreach($this->actions as $action) {
            if($action == 'view') {
                return true;
            }
        }

        return false;
    }

    public function convertibleTo($type): bool
    {
        foreach($this->convert as $convert) {
            if($convert == $type) {
                return true;
            }
        }

        return false;
    }
}