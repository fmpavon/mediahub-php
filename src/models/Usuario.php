<?php

namespace Daw\Mediahub\Models;

class Usuario
{
    public int $id;
    public string $name;
    public string $username;
    public string $password;

    function __construct(int $id, string $name, string $username, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
    }
}
