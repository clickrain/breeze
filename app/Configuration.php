<?php
namespace ClickRain\Breeze;

use ClickRain\Breeze\Model\Folder;
use ClickRain\Breeze\Model\Server;
use ClickRain\Breeze\Model\Database;
use ClickRain\Breeze\Model\Site;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Configuration
{
    /**
     * Breeze IP address
     *
     * @var string
     */
    public $ip;

    /**
     * Array of folders
     *
     * @var array
     */
    public $folders = [];

    /**
     * Array of servers
     *
     * @var array
     */
    public $servers = [];

    /**
     * Array of sites
     *
     * @var array
     */
    public $sites = [];

    /**
     * Array of databases
     *
     * @var array
     */
    public $databases = [];

    /**
     * Load the configuration file from the given path
     *
     * @param string $path
     * @throws Symfony\Component\Yaml\Exception\ParseException
     * @return array
     */
    public function load($path)
    {
        $yaml = new Parser();

        try {
            $config = $yaml->parse(file_get_contents($path));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
            exit(1);
        }

        $this->ip = array_get($config, 'ip', null);

        foreach (array_get($config, 'folders', []) as $folderConfig) {
            $folder = new Folder(
                array_get($folderConfig, 'map', null),
                array_get($folderConfig, 'to', null)
            );

            $this->folders[] = $folder;
        }

        foreach (array_get($config, 'servers', []) as $serverConfig) {
            $id = array_get($serverConfig, 'id', null);

            $server = new Server(
                $id,
                array_get($serverConfig, 'host', null),
                array_get($serverConfig, 'port', null),
                array_get($serverConfig, 'user', null),
                array_get($serverConfig, 'identity_file', null)
            );

            $this->servers[$id] = $server;
        }

        foreach (array_get($config, 'sites', []) as $siteConfig) {
            $id = array_get($siteConfig, 'id', null);

            $site = new Site(
                $id,
                array_get($siteConfig, 'aliases', []),
                array_get($siteConfig, 'path', null),
                array_get($siteConfig, 'document_root', null),
                array_get($siteConfig, 'server', null),
                array_get($siteConfig, 'server_path', null),
                array_get($siteConfig, 'uploads_path', null)
            );

            $this->sites[$id] = $site;
        }

        foreach (array_get($config, 'databases', []) as $databaseConfig) {
            $id = array_get($databaseConfig, 'id', null);

            $database = new Database(
                $id,
                array_get($databaseConfig, 'local_name', null),
                array_get($databaseConfig, 'local_user', null),
                array_get($databaseConfig, 'local_password', null),
                array_get($databaseConfig, 'remote_name', null),
                array_get($databaseConfig, 'remote_user', null),
                array_get($databaseConfig, 'remote_password', null),
                array_get($databaseConfig, 'remote_host', null),
                array_get($databaseConfig, 'remote_port', null),
                array_get($databaseConfig, 'server', null),
                array_get($databaseConfig, 'ignore_tables_on_pull', array())
            );

            $this->databases[$id] = $database;
        }
    }

    /**
     * Get server for the given id
     *
     * @param string $id
     * @return ClickRain\Breeze\Model\Server
     */
    public function getServer($id)
    {
        if (! array_key_exists($id, $this->servers)) {
            throw new \Exception('Server [' . $id . '] is not defined.');
        }

        return array_get($this->servers, $id);
    }

    /**
     * Get site for the given id
     *
     * @param string $id
     * @return ClickRain\Breeze\Model\Site
     */
    public function getSite($id)
    {
        if (! array_key_exists($id, $this->sites)) {
            throw new \Exception('Site [' . $id .'] is not defined.');
        }

        return array_get($this->sites, $id);
    }

    /**
     * Get database for the given id
     *
     * @param string $id
     * @return ClickRain\Breeze\Model\Database
     */
    public function getDatabase($id)
    {
        if (! array_key_exists($id, $this->databases)) {
            throw new \Exception('Database [' . $id .'] is not defined.');
        }

        return array_get($this->databases, $id);
    }
}
