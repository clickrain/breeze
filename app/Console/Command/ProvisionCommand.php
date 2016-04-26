<?php
namespace ClickRain\Breeze\Console\Command;

class ProvisionCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('provision')
            ->setDescription('Provision the Breeze machine');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->getHelper('vagrant')->run('provision', $this->output);
    }
}
