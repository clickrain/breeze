<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DbPullCommand extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('db:pull')
            ->setDescription('Pull a fresh copy of a database from a remote server')
            ->addArgument(
                'database',
                InputArgument::REQUIRED
            )
            ->addOption(
                'gtid-fix',
                null,
                InputOption::VALUE_NONE,
                'Include --set-gtid-purged=OFF flag in the mysql dump command'
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
        $database = $config->getDatabase($this->argument('database'));

        if (!$this->getHelper('database')->databaseExists($database)) {
            $this->error(sprintf('The database %s does not exist', $database->id));
            return 1;
        }

        $this->output->writeln(sprintf(
            'Pulling remote database %s into local database %s',
            $database->remote_name,
            $database->local_name
        ));

        $exitCode = $this->getHelper('database')->pullDatabase(
            $database,
            $this->option('gtid-fix'),
            $this->output
        );

        if ($exitCode === 0) {
            $this->output->writeln(sprintf(
                'Finished pulling remote database %s into local database %s',
                $database->remote_name,
                $database->local_name
            ));
        } else {
            $this->error('Failed');
        }

        return $exitCode;
    }

    /**
     * Fulfill requirement for CompletionAwareInterface
     *
     * @param  string            $optionName
     * @param  \Stecman\Component\Symfony\Console\BashCompletion\CompletionContext $context
     * @return void
     */
    public function completeOptionValues($optionName, CompletionContext $context)
    {
    }

    /**
     * Provide argument autocompletion
     *
     * @param  string            $argumentName
     * @param  \Stecman\Component\Symfony\Console\BashCompletion\CompletionContext $context
     * @return array|void
     */
    public function completeArgumentValues($argumentName, CompletionContext $context)
    {
        if ($argumentName == 'database') {
            return array_map(function ($database) {
                return $database->id;
            }, $this->getHelper('config')->get()->databases);
        }
    }
}
