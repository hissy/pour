<?php
namespace Concrete\Package\Pour;

use Concrete\Core\Package\Package;
use Concrete\Package\Pour\Console\Command\PourCommand;

class Controller extends Package
{
    protected $pkgHandle = 'pour';
    protected $appVersionRequired = '5.8.0';
    protected $pkgVersion = '0.1';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = [
        'src/C5j/Pour' => 'C5j\Pour'
    ];

    /**
     * Returns the translated name of the package.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('Pour');
    }

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('Generate class file interactively - Let\'s pour concrete!');
    }

    public function on_start()
    {
        if ($this->app->isRunThroughCommandLineInterface()) {
            try {
                $app = $this->app->make('console');
                $app->add(new PourCommand());
            } catch (\Exception $e) {}
        }
    }
}