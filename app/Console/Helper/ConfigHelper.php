<?php
namespace ClickRain\Breeze\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Process\Process;
use ClickRain\Breeze\Configuration;

class ConfigHelper extends Helper
{

    /**
     * Configuration object
     *
     * @var \ClickRain\Breeze\Configuration
     */
    protected $config;

    /**
     * Get name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'config';
    }

    /**
     * Get the config object
     *
     * @return \ClickRain\Breeze\Configuration
     */
    public function get()
    {
        if (!$this->config) {
            $this->config = $this->load();
        }

        return $this->config;
    }

    /**
     * Load the configuration file
     *
     * @return \ClickRain\Breeze\Configuration
     */
    protected function load()
    {
        $breezeFile = breeze_config_path() . '/Breeze.yaml';

        if (! file_exists($breezeFile)) {
            echo "Breeze.yaml not found.\n";
            exit(1);
        }

        $config = new Configuration;
        $config->load($breezeFile);

        return $config;
    }
}
