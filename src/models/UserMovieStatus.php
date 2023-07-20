<?php

namespace Daw\Mediahub\Models;

class UserMovieStatus
{
    public string $status;

    function __construct(string $status)
    {
        $this->status = $status;
    }
}
