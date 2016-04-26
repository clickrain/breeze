<?php
namespace ClickRain\Breeze\Console\Command;

class StatusCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('status')
            ->setDescription('Display the status of the Breeze machine');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->getHelper('vagrant')->run('status', $this->output);
    }
}
