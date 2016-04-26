<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Console\Input\InputOption;

class UpCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('up')
            ->setDescription('Start the Breeze machine')
            ->addOption('provision', null, InputOption::VALUE_NONE, 'Run the provisioners on the box.');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $command = 'up';

        if ($this->input->getOption('provision')) {
            $command .= ' --provision';
        }

        $this->getHelper('vagrant')->run($command, $this->output);
    }
}
