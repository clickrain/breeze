<?php
namespace ClickRain\Breeze\Model;

class Folder
{
    /**
     * Host directory to map
     *
     * @var string
     */
    public $map;

    /**
     * Path on breeze machine
     *
     * @var string
     */
    public $to;

    /**
     * Create a new Folder instance.
     *
     * @param  string  $map
     * @param  string  $to
     * @return void
     */
    public function __construct($map, $to)
    {
        $this->map = $map;
        $this->to = $to;
    }
}
