<?php
namespace ClickRain\Breeze\Model;

class Site
{
    /**
     * Unique id for server
     *
     * @var string
     */
    public $id;

    /**
     * Aliases used in the ServerAlias directive in Apache
     *
     * @var string
     */
    public $aliases;

    /**
     * Path - directory on guest (breeze) machine that contains the site
     *
     * @var string
     */
    public $path;

    /**
     * Document root of the site, relative to $to
     *
     * @var string
     */
    public $document_root;

    /**
     * Id of server that hosts this site
     *
     * @var string
     */
    public $server;

    /**
     * Path of the site on hosted server
     *
     * @var string
     */
    public $server_path;

    /**
     * Path that contains uploads
     *
     * @var string
     */
    public $uploads_path;

    /**
     * Create a new Server instance.
     *
     * @return void
     */
    public function __construct($id, array $aliases, $path, $document_root, $server, $server_path, $uploads_path)
    {
        $this->id = $id;
        $this->aliases = $aliases;
        $this->path = $path;
        $this->document_root = $document_root;
        $this->server = $server;
        $this->server_path = $server_path;
        $this->uploads_path = $uploads_path;
    }
}
