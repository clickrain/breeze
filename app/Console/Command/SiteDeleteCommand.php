<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SiteDeleteCommand extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('site:delete')
            ->setDescription('Delete a site')
            ->addArgument(
                'site',
                InputArgument::REQUIRED
            )
            ->addOption(
                'purge',
                null,
                InputOption::VALUE_NONE,
                'Delete all website files'
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

        if ($this->option('purge')) {
            $process = new Process(sprintf('rm -rf %s', $site->path));
            $process->run(function ($type, $buffer) {
                $this->output->write($buffer);
            });
        }

        // delete the site and restart apache
        $this->getHelper('apache')->disableSite($site, $this->output);
        $this->getHelper('apache')->deleteSiteConfig($site, $this->output);
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
