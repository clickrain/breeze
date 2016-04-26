<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;

class SiteListCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('site:list')
            ->setDescription('List sites that are in the Breeze config');

        $this->runOnGuest = true;
    }

    /**
     * Fire the command.
     *
     * @return void
     */
    public function fire()
    {
        $config = $this->getHelper('config')->get();

        $this->output->writeln("");

        foreach ($config->sites as $site) {
            $this->output->writeln($site->id);
        }

        $this->output->writeln("");
    }
}
