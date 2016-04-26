<?php
namespace ClickRain\Breeze;

use Closure;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VagrantProxy
{
    /**
     * Run the input and output through the vagrant machine
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return integer
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix('breeze');
        $builder->setArguments(array_slice($_SERVER['argv'], 1));
        $script = $builder->getProcess()->getCommandLine();

        // change to the root directory
        chdir(breeze_app_path());

        // build the ssh command
        $ssh =
            $this->setEnvironmentCommand() .
            " vagrant ssh -c \"cat <<PROXY | sh" . PHP_EOL .
              $script .
              PHP_EOL .
            "PROXY\" -- -o StrictHostKeyChecking=no -o ForwardAgent=yes";

        return passthru($ssh);
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
