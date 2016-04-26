<?php
namespace ClickRain\Breeze\Model;

class Server
{
    /**
     * Unique id for server
     *
     * @var string
     */
    public $id;

    /**
     * Host string
     *
     * @var string
     */
    public $host;

    /**
     * User name
     *
     * @var string
     */
    public $user;

    /**
     * Server port
     *
     * @var string
     */
    public $port;

    /**
     * Identity file
     *
     * @var string
     */
    public $identity_file;

    /**
     * Create a new Server instance.
     *
     * @param  string  $id
     * @param  string  $host
     * @return void
     */
    public function __construct($id, $host, $port, $user, $identity_file)
    {
        $this->id = $id;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->identity_file = $identity_file;
    }
}
