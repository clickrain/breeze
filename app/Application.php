<?php
namespace ClickRain\Breeze;

class Application extends \Symfony\Component\Console\Application
{
    public function getDefaultHelperSet()
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new Console\Helper\DatabaseHelper);
        $helperSet->set(new Console\Helper\SshConfigHelper);
        $helperSet->set(new Console\Helper\ApacheHelper);
        $helperSet->set(new Console\Helper\TwigHelper);
        $helperSet->set(new Console\Helper\VagrantHelper);
        $helperSet->set(new Console\Helper\ConfigHelper);

        return $helperSet;
    }

    public function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        // vagrant commands
        $commands[] = new Console\Command\UpCommand;
        $commands[] = new Console\Command\SuspendCommand;
        $commands[] = new Console\Command\ResumeCommand;
        $commands[] = new Console\Command\HaltCommand;
        $commands[] = new Console\Command\ReloadCommand;
        $commands[] = new Console\Command\ProvisionCommand;
        $commands[] = new Console\Command\DestroyCommand;
        $commands[] = new Console\Command\SshCommand;
        $commands[] = new Console\Command\StatusCommand;

        // breeze management tools
        $commands[] = new Console\Command\DbCreateCommand;
        $commands[] = new Console\Command\DbPullCommand;
        $commands[] = new Console\Command\DbDropCommand;
        $commands[] = new Console\Command\DbDumpCommand;
        $commands[] = new Console\Command\SiteEnableCommand;
        $commands[] = new Console\Command\SiteCreateCommand;
        $commands[] = new Console\Command\SiteDeleteCommand;
        $commands[] = new Console\Command\SiteListCommand;
        $commands[] = new Console\Command\SiteSyncUploadsCommand;
        $commands[] = new Console\Command\SitePullRsyncCommand;
        $commands[] = new Console\Command\WebReloadCommand;
        $commands[] = new Console\Command\WebRestartCommand;

        // breeze utilities
        $commands[] = new Console\Command\InitCommand;
        $commands[] = new Console\Command\ConfigSshCommand;
        $commands[] = new Console\Command\EditCommand;

        // autocompletion
        $commands[] = new \Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;

        return $commands;
    }
}
