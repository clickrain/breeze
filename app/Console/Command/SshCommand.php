<?php
namespace ClickRain\Breeze\Console\Command;

class SshCommand extends \Symfony\Component\Console\Command\Command
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('ssh')
            ->setDescription('Login to the Breeze machine via SSH');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        chdir(breeze_app_path());

        passthru($this->setEnvironmentCommand() . ' vagrant ssh');
    }

    protected function setEnvironmentCommand()
    {
        if ($this->isWindows()) {
            return 'SET VAGRANT_DOTFILE_PATH=' . breeze_vagrant_dotfile_path() . ' &&';
        }

        return 'VAGRANT_DOTFILE_PATH="' . breeze_vagrant_dotfile_path() . '"';
    }

    protected function isWindows()
    {
        return strpos(strtoupper(PHP_OS), 'WIN') === 0;
    }
}
