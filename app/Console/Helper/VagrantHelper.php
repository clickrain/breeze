<?php
namespace ClickRain\Breeze\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class VagrantHelper extends Helper
{
    /**
     * Get the name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'vagrant';
    }

    /**
     * Run the given vagrant command
     *
     * @param  string $script
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function run($command, OutputInterface $output)
    {
        // define segments of output from typical vagrant commands that should
        // be replaced with the breeze equivalent
        $replaceLines = [
            'vagrant halt' => 'breeze halt',
            'vagrant suspend' => 'breeze suspend',
            'vagrant up' => 'breeze up',
            'vagrant destroy' => 'breeze destroy',
            'vagrant reload' => 'breeze reload',
            'vagrant status' => 'breeze status',
            'vagrant provision' => 'breeze provision',
        ];

        $process = new Process('vagrant ' . $command, breeze_app_path(), array_merge($_SERVER, $_ENV), null, null);
        return $process->run(function ($type, $buffer) use ($output, $replaceLines) {

            foreach ($replaceLines as $search => $replace) {
                if (strpos($buffer, $search) !== false) {
                    $buffer = str_replace($search, $replace, $buffer);
                }
            }

            $output->write($buffer);
        });
    }
}
