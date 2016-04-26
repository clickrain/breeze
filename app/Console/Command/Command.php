<?php
namespace ClickRain\Breeze\Console\Command;

use ClickRain\Breeze\Configuration;
use ClickRain\Breeze\VagrantProxy;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait Command
{
    /**
     * Whether the command that uses this trait should use the breeze machine
     * as a proxy to run the command
     *
     * @var  boolean
     */
    protected $runOnGuest = false;

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return integer
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        if ($this->runOnGuest && !getenv('BREEZE_ENV')) {
            $proxy = new VagrantProxy;
            return (int) $proxy->run($input, $output);
        } else {
            return (int) $this->fire();
        }
    }

    /**
     * Get an argument from the input.
     *
     * @param  string  $key
     * @return string
     */
    public function argument($key)
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get an option from the input.
     *
     * @param  string  $key
     * @return string
     */
    public function option($key)
    {
        return $this->input->getOption($key);
    }

    /**
     * Ask the user the given question.
     *
     * @param  string  $question
     * @return string
     */
    public function ask($question)
    {
        $question = '<comment>' . $question . '</comment> ';

        return $this->getHelperSet()->get('dialog')->ask($this->output, $question);
    }

    /**
     * Confirm the operation with the user.
     *
     * @param  string  $task
     * @param  string  $question
     * @return bool
     */
    public function confirmTaskWithUser($task, $question)
    {
        $question = $question === true ? 'Are you sure you want to run the ['.$task.'] task?' : (string) $question;

        $question = '<comment>'.$question.' [y/N]:</comment> ';

        return  $this->getHelperSet()->get('dialog')->askConfirmation($this->output, $question, false);
    }

    /**
     * Ask the user the given secret question.
     *
     * @param  string  $question
     * @return string
     */
    public function secret($question)
    {
        $question = '<comment>' . $question . '</comment> ';

        return $this->getHelperSet()->get('dialog')->askHiddenResponse($this->output, $question, false);
    }

    /**
     * Write an error message to output
     *
     * @param  string $message
     * @return string
     */
    public function error($message)
    {
        $message = '<error>' . $message . '</error> ';

        return $this->output->writeln($message);
    }
}
