<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('edit')
            ->setDescription('Edit the Breeze config file');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $command = $this->executable() . ' ' . breeze_config_path() . DIRECTORY_SEPARATOR .  'Breeze.yaml';
        passthru($command);
    }

    /**
     * Find the correct executable to run depending on the OS.
     *
     * @return string
     */
    protected function executable()
    {
        if (strpos(strtoupper(PHP_OS), 'WIN') === 0) {
            return 'start ""';
        } elseif (strpos(strtoupper(PHP_OS), 'DARWIN') === 0) {
            return 'open';
        }

        return 'xdg-open';
    }
}
