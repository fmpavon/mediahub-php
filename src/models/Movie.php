<?php

namespace Daw\Mediahub\Models;

use DateTime;

class Movie
{
    public int $id;
    public string $title;
    public DateTime $releaseDate;
    public MovieGenre $genre;
    public string $plot;

    function __construct(int $id, string $title, DateTime $releaseDate, MovieGenre $genre, string $plot)
    {
        $this->id = $id;
        $this->title = $title;
        $this->releaseDate = $releaseDate;
        $this->genre = $genre;
        $this->plot = $plot;
    }
}
