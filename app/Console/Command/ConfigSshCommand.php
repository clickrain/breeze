<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ConfigSshCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('config:ssh')
            ->setDescription('Generate an SSH config file based on Breeze servers')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Overwrite ssh config file if it already exists'
            );

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
        $sshHelper = $this->getHelper('sshconfig');

        // check if a configuration file for this site has already been created
        if ($sshHelper->configExists() && !$this->option('force')) {
            $this->error('An ssh config file already exists. Use the --force to overwrite.');
            return 1;
        }

        // render the apache_vhost template for the given site
        $fileContents = $this->getHelper('twig')->render('ssh_config', ['servers' => $config->servers]);
        $sshHelper->writeConfig($fileContents);
    }
}
