<?php
namespace ClickRain\Breeze\Console\Command;

class InitCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Initialize Breeze configuration files');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $basePath = breeze_config_path();
        if (is_dir($basePath)) {
            $this->output->writeln(sprintf('Breeze directory %s already exists. Skipping...', $basePath));
        } else {
            $this->output->writeln(sprintf('Creating breeze configuration directory %s... <info>✔</info>', $basePath));
            mkdir($basePath);
        }

        $sitesPath = $basePath . DIRECTORY_SEPARATOR . 'sites-available';
        if (is_dir($sitesPath)) {
            $this->output->writeln('Breeze sites directory already exists. Skipping...');
        } else {
            $this->output->writeln('Creating breeze sites directory... <info>✔</info>');
            mkdir($sitesPath);
        }

        $configFile = $basePath . DIRECTORY_SEPARATOR . 'Breeze.yaml';
        if (file_exists($configFile)) {
            $this->output->writeln('Breeze.yaml file already exists. Skipping...');
        } else {
            $this->output->writeln('Creating Breeze.yaml file... <info>✔</info>');
            copy(breeze_app_path() . '/stubs/Breeze.yaml', $configFile);
        }
    }
}
