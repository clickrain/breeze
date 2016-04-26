<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SiteSyncUploadsCommand extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('site:sync-uploads')
            ->setDescription('Pull uploads from the remote server')
            ->addArgument(
                'site',
                InputArgument::REQUIRED
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
        $server = $config->getServer($site->server);

        if (!$site->uploads_path) {
            $this->output->writeln(sprintf('The site "%s" does not have an uploads directory set in the config file.', $site->id));
            return 1;
        }

        $script = $this->getHelper('twig')->render('rsync_uploads', [
            'server' => $server,
            'site' => $site,
        ]);

        $process = new Process($script);
        $process->setTimeout(7200);
        $process->run(function ($type, $line) {
            $this->output->write($line);
        });
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
