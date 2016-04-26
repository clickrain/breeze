<?php
namespace ClickRain\Breeze\Console\Command;

class DestroyCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('destroy')
            ->setDescription('Destroy the Breeze machine');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->getHelper('vagrant')->run('destroy --force', $this->output);
    }
}
