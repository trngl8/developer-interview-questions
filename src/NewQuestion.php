<?php

namespace App;

class NewQuestion
{
    public ?string $name;
    public ?string $title;

    public function __construct(?string $name = null, ?string $title = null)
    {
        $this->name = $name;
        $this->title = $title;
    }
}