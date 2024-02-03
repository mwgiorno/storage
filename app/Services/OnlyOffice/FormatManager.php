<?php

namespace App\Services\OnlyOffice;

class FormatManager
{
    public array $formats;

    public function __construct(array $formats)
    {
        foreach($formats as $format) {
            $this->formats[] = new Format(
                $format['name'],
                $format['type'],
                $format['actions'],
                $format['convert'],
                $format['mime'],
            );
        }
    }

    public function getFormatByExtension(string $extension): Format | null
    {
        foreach($this->formats as $format) {
            if($format->name == $extension) {
                return $format;
            }
        }
        return null;
    }

    public function viewableExtension(string $extension): bool
    {
        $format = $this->getFormatByExtension($extension);

        if($format) {
            return $format->viewable();
        }

        return false;
    }

    public function convertibleTo(string $extension, string $type): bool
    {
        $format = $this->getFormatByExtension($extension);

        if($format) {
            return $format->convertibleTo($type);
        }

        return false;
    }

    public function getDocumentType(string $extension): string
    {
        $documentType = 'word';
        $format = $this->getFormatByExtension($extension);

        if($format) {
            $documentType = $format->type;
        }

        return $documentType;
    }
}