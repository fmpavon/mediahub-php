<?php

namespace Daw\Mediahub\Models;

class UserMovie
{
    public Usuario $user;
    public Movie $movie;
    public UserMovieStatus $status;

    function __construct(Usuario $user, Movie $movie, UserMovieStatus $status)
    {
        $this->user = $user;
        $this->movie = $movie;
        $this->status = $status;
    }
}
