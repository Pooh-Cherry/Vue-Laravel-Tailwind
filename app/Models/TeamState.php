<?php

namespace App\Models;

class TeamState
{
    public $name;
    public $pts;
    public $p;
    public $w;
    public $d;
    public $l;
    public $gd;
    public $strength;

    function __construct($name, $pts, $p, $w, $d, $l, $gd, $strength)
    {
        $this->name = $name;
        $this->pts = $pts;
        $this->p = $p;
        $this->w = $w;
        $this->d = $d;
        $this->l = $l;
        $this->gd = $gd;
        $this->strength = $strength;
    }
}
