<?php

class File
{
    private string $name;
    private int $sizeBytes;
    private string $mimeType;

    public function __construct(string $name, int $sizeBytes, string $mimeType)
    {
        $this->name = $name;
        $this->sizeBytes = $sizeBytes;
        $this->mimeType = $mimeType;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function extension(): string
    {
        // just reads ITS OWN name: "report.pdf" -> "pdf"
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
    }

    public function humanReadableSize(): string
    {
        // turns ITS OWN bytes into something a human reads: 1536000 -> "1.5 MB"
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $value = $this->sizeBytes;
        $i = 0;
        while ($value >= 1024 && $i < count($units) - 1) {
            $value /= 1024;
            $i++;
        }
        return round($value, 1) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        // looks at ITS OWN type: "image/png" starts with "image/"
        return str_starts_with($this->mimeType, 'image/');
    }
}
