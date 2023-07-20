<?php

namespace Daw\Mediahub\Models;

class MovieGenre
{
    public int $id;
    public string $text;

    function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }
}
