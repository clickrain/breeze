<?php
namespace ClickRain\Breeze\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Process\Process;

class SshConfigHelper extends Helper
{
    /**
     * SSh Config filename
     * @var string
     */
    private $filename = '/home/vagrant/.ssh/config';

    /**
     * Get name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'sshconfig';
    }

    /**
     * Check if an ssh config file exists
     *
     * @return boolean
     */
    public function configExists()
    {
        return file_exists($this->filename);
    }

    /**
     * Write the given file contents to the ssh config
     *
     * @param  string $contents
     * @return void
     */
    public function writeConfig($contents)
    {
        file_put_contents($this->filename, $contents);
    }
}
