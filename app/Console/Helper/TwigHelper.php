<?php
namespace ClickRain\Breeze\Console\Helper;

use Symfony\Component\Console\Helper\Helper;

class TwigHelper extends Helper
{
    /**
     * Twig instance
     *
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Render the given template
     *
     * @param  string $name     name of the template
     * @param  array  $data     data to assign to the template
     * @return string
     */
    public function render($name, $data = [])
    {
        return $this->getTwig()->render($name . '.twig', $data);
    }

    /**
     * Get the name of the helper
     *
     * @return string
     */
    public function getName()
    {
        return 'twig';
    }

    /**
     * Get the twig environment
     *
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        if (null === $this->twig) {
            $templatePath = realpath(breeze_app_path() . DIRECTORY_SEPARATOR . 'templates');

            $loader = new \Twig_Loader_Filesystem($templatePath);
            $this->twig = new \Twig_Environment($loader);
        }

        return $this->twig;
    }
}
