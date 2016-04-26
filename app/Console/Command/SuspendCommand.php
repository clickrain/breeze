<?php
namespace ClickRain\Breeze\Console\Command;

class SuspendCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('suspend')
            ->setDescription('Suspend the Breeze machine');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->getHelper('vagrant')->run('suspend', $this->output);
    }
}
