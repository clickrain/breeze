<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SiteCreateCommand extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('site:create')
            ->setDescription('Create a site')
            ->addArgument(
                'site',
                InputArgument::REQUIRED
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Overwrite existing config file if it already exists'
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
        $site = $config->getSite($this->argument('site'));

        if ($this->getHelper('apache')->siteConfigExists($site)) {
            if ($this->option('force')) {
                $this->getHelper('apache')->disableSite($site, $this->output);
                $this->getHelper('apache')->deleteSiteConfig($site, $this->output);
            } else {
                $this->error(sprintf('A configuration for %s already exists', $site->id));
                return 1;
            }
        }

        // enable the site and restart apache
        $this->getHelper('apache')->createSiteConfig($site, $this->output);
        $this->getHelper('apache')->enableSite($site, $this->output);
        $this->getHelper('apache')->restart($this->output);
    }

    /**
     * Fulfill requirement for CompletionAwareInterface
     *
     * @param  string            $optionName
     * @param  CompletionContext $context
     * @return void
     */
    public function completeOptionValues($optionName, CompletionContext $context)
    {
    }

    /**
     * Provide argument autocompletion
     *
     * @param  string            $argumentName
     * @param  CompletionContext $context
     * @return array|void
     */
    public function completeArgumentValues($argumentName, CompletionContext $context)
    {
        if ($argumentName == 'site') {
            return array_map(function ($site) {
                return $site->id;
            }, $this->getHelper('config')->get()->sites);
        }
    }
}
