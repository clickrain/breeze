<?php
namespace ClickRain\Breeze\Console\Command;

class ReloadCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('reload')
            ->setDescription('Reload the Breeze machine');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->getHelper('vagrant')->run('reload', $this->output);
    }
}
