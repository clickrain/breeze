<?php
namespace ClickRain\Breeze\Model;

class Database
{
    /**
     * Unique id for server
     *
     * @var string
     */
    public $id;

    /**
     * Name of database on breeze machine
     *
     * @var string
     */
    public $local_name;

    /**
     * Name of database user on breeze machine
     *
     * @var string
     */
    public $local_user;

    /**
     * Password for database on breeze machine
     *
     * @var string
     */
    public $local_password;

    /**
     * Name of database on remote machine
     *
     * @var string
     */
    public $remote_name;

    /**
     * Name of database user on remote machine
     *
     * @var string
     */
    public $remote_user;

    /**
     * Password for database on remote machine
     *
     * @var string
     */
    public $remote_password;

    /**
     * Host on remote machine
     *
     * @var string
     */
    public $remote_host;

    /**
     * Port on remote machine
     *
     * @var string
     */
    public $remote_port;

    /**
     * Server identifier for this database
     *
     * @var string
     */
    public $server;

    /**
     * Tables to ignore on a db:pull
     *
     * @var array
     */
    public $ignore_tables_on_pull;

    /**
     * Create a new Server instance.
     *
     * @param  string  $id
     * @param  string  $host
     * @return void
     */
    public function __construct(
        $id,
        $local_name,
        $local_user,
        $local_password,
        $remote_name,
        $remote_user,
        $remote_password,
        $remote_host,
        $remote_port,
        $server,
        $ignore_tables_on_pull
    ) {
        $this->id = $id;
        $this->local_name = $local_name;
        $this->local_user = $local_user;
        $this->local_password = $local_password;
        $this->remote_name = $remote_name;
        $this->remote_user = $remote_user;
        $this->remote_password = $remote_password;
        $this->remote_host = $remote_host;
        $this->remote_port = $remote_port;
        $this->server = $server;
        $this->ignore_tables_on_pull = $ignore_tables_on_pull;
    }
}
