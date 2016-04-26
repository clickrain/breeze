<?php
namespace ClickRain\Breeze\Console\Helper;

use ClickRain\Breeze\Model\Database;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DatabaseHelper extends Helper
{
    /**
     * Get name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'database';
    }

    /**
     * Check if the given database already exists
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @return boolean
     */
    public function databaseExists(Database $database)
    {
        $script = sprintf('mysql --user="root" --password="secret" -e "use %s"', $database->local_name);
        $process = new Process($script);
        $process->run();
        return !$process->getExitCode();
    }

    /**
     * Create the given database
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function createDatabase(Database $database, OutputInterface $output)
    {
        $script = $this
            ->getHelperSet()
            ->get('twig')
            ->render('create_database', ['database' => $database]);

        return $this->run($script, $output);
    }

    /**
     * Drop the given database
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function dropDatabase(Database $database, OutputInterface $output)
    {
        $script = $this
            ->getHelperSet()
            ->get('twig')
            ->render('drop_database', ['database' => $database]);

        return $this->run($script, $output);
    }

    /**
     * Dump the given database from the remote server
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @param  boolean $gtidFix
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function dumpRemoteDatabase(Database $database, $gtidFix, OutputInterface $output)
    {
        $config = $this->getHelperSet()->get('config')->get();
        $server = $config->getServer($database->server);

        $script = $this->getHelperSet()->get('twig')->render('dump_database_remote', [
            'database' => $database,
            'server' => $server,
            'gtidFix' => $gtidFix,
        ]);

        return $this->run($script, $output);
    }

    /**
     * Dump the given local database
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function dumpLocalDatabase(Database $database, OutputInterface $output)
    {
        $script = $this->getHelperSet()->get('twig')->render('dump_database_local', [
            'database' => $database,
        ]);

        return $this->run($script, $output);
    }

    /**
     * Pull database from remote server to local
     *
     * @param  \ClickRain\Breeze\Model\Database $database
     * @return boolean  TRUE if successful
     */
    public function pullDatabase(Database $database, $gtidFix, OutputInterface $output)
    {
        $config = $this->getHelperSet()->get('config')->get();
        $server = $config->getServer($database->server);

        $script = $this->getHelperSet()->get('twig')->render('pull_database', [
            'database' => $database,
            'server' => $server,
            'gtidFix' => $gtidFix,
        ]);

        return $this->run($script, $output);
    }

    /**
     * Run the given script, writing buffer to output. Filters out mysql
     * warnings and makes suggestions if there are problems.
     *
     * @param  string $script
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    protected function run($script, OutputInterface $output)
    {
        $process = new Process($script);
        $process->setTimeout(3600);
        $exitCode = $process->run(function ($type, $line) use ($output) {
            if (Process::ERR === $type) {
                if (strpos($line, 'Warning') !== false) {
                    return;
                }
                if (strpos($line, '@@GLOBAL.GTID_PURGED can only be set when @@GLOBAL.GTID_MODE = ON') !== false) {
                    $output->writeln('<error>Unable to set GTID_PURGED. Include the --gtid-fix option to fix this.</error>');
                    return;
                }

                $output->write('<error>' . $line . '</error>');
            } else {
                $output->write($line);
            }
        });

        return $exitCode;
    }
}
