<?php
namespace ClickRain\Breeze\Console\Helper;

use ClickRain\Breeze\Model\Site;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

class ApacheHelper extends Helper
{
    /**
     * Get name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'apache';
    }

    /**
     * Restart Apache
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function restart(OutputInterface $output)
    {
        return $this->run('sudo service apache2 restart', $output);
    }

    /**
     * Reload Apache
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function reload(OutputInterface $output)
    {
        return $this->run('sudo service apache2 reload', $output);
    }

    /**
     * Enable site
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function enableSite(Site $site, OutputInterface $output)
    {
        return $this->run(
            sprintf('sudo a2ensite %s', $site->id),
            $output
        );
    }

    /**
     * Disable site
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function disableSite(Site $site, OutputInterface $output)
    {
        return $this->run(
            sprintf('sudo a2dissite %s', $site->id),
            $output
        );
    }

    /**
     * Delete the configuration file for the given site
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    public function deleteSiteConfig(Site $site, OutputInterface $output)
    {
        return $this->run(
            sprintf('sudo rm %s', $this->getSiteConfigPath($site)),
            $output
        );
    }

    /**
     * Create a configuration file for the given site
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function createSiteConfig(Site $site, OutputInterface $output)
    {
        $contents = $this
            ->getHelperSet()
            ->get('twig')
            ->render('apache_vhost', ['site' => $site]);

        file_put_contents($this->getSiteConfigPath($site), $contents);
    }

    /**
     * Checks whether the configuration file for the given site exists
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @return boolean
     */
    public function siteConfigExists(Site $site)
    {
        $path = $this->getSiteConfigPath($site);
        return file_exists($path);
    }

    /**
     * Get the site config path
     *
     * @param  \ClickRain\Breeze\Model\Site $site
     * @return string
     */
    protected function getSiteConfigPath(Site $site)
    {
        return sprintf('/etc/apache2/sites-available/%s.conf', $site->id);
    }

    /**
     * Run the given script, writing buffer to output
     *
     * @param  string $script
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return integer
     */
    protected function run($script, OutputInterface $output)
    {
        $process = new Process($script);
        return $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });
    }
}
