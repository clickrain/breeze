<?php
namespace ClickRain\Breeze\Console\Command;

use Symfony\Component\Process\Process;
use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DbCreateCommand extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('db:create')
            ->setDescription('Create a database')
            ->addArgument(
                'database',
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
        $database = $config->getDatabase($this->argument('database'));

        if ($this->getHelper('database')->databaseExists($database)) {
            $this->error(sprintf('The database %s already exists', $database->id));
            return 1;
        }

        return $this->getHelper('database')->createDatabase($database, $this->output);
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
        if ($argumentName == 'database') {
            return array_map(function ($database) {
                return $database->id;
            }, $this->getHelper('config')->get()->databases);
        }
    }
}
