<?php

namespace App\Entity;

class Classement
{
    private $rang;
    private $bulletin;

    public function __construct(int $rang, Bulletin $bulletin)
    {
        $this->rang = $rang;
        $this->bulletin = $bulletin;
    }

    public function getRang(): int
    {
        return $this->rang;
    }

    public function getBulletin(): Bulletin
    {
        return $this->bulletin;
    }
}
