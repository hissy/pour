<?php

namespace C5j\Pour;

use Concrete\Core\File\Service\File;
use Concrete\Core\Utility\Service\Text;

class PackageControllerGenerator
{
    /** @var $pkgHandle string */
    protected $pkgHandle;
    /** @var $pkgNamespace string */
    protected $pkgNamespace;
    /** @var $pkgName string */
    protected $pkgName;
    /** @var $pkgDescription string */
    protected $pkgDescription;
    /** @var $appVersionRequired string */
    protected $appVersionRequired = '8.0.0';
    /** @var $pkgVersion string */
    protected $pkgVersion = '0.1';
    /** @var $pkgAutoloaderRegistries string */
    protected $pkgAutoloaderRegistries;
    protected $classTemplate = '<?php
namespace Concrete\Package\{pkgNamespace};

use Concrete\Core\Package\Package;

class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = \'{pkgHandle}\';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = \'{appVersionRequired}\';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = \'{pkgVersion}\';
    
    /**
     * @var array Array of location -> namespace autoloader entries for the package.
     */
    protected $pkgAutoloaderRegistries = [{pkgAutoloaderRegistries}];
    
    protected $pkgAutoloaderMapCoreExtensions = true;
    
    /**
     * Returns the translated name of the package.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t(\'{pkgName}\');
    }

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t(\'{pkgDescription}\');
    }
}
';

    /**
     * PackageControllerGenerator constructor.
     * @param string $pkgHandle
     * @param string $pkgName
     * @param string $pkgDescription
     */
    public function __construct($pkgHandle, $pkgName, $pkgDescription)
    {
        $this->pkgHandle = $pkgHandle;
        $this->pkgName = $pkgName;
        $this->pkgDescription = $pkgDescription;

        /** @var Text $th */
        $th = \Core::make('helper/text');
        $this->pkgNamespace = $th->camelcase($pkgHandle);
    }

    /**
     * @param string $appVersionRequired
     */
    public function setAppVersionRequired($appVersionRequired)
    {
        $this->appVersionRequired = $appVersionRequired;
    }

    /**
     * @param string $pkgVersion
     */
    public function setPkgVersion($pkgVersion)
    {
        $this->pkgVersion = $pkgVersion;
    }

    /**
     * @param string $autoloaderLocation
     * @param string $autoloaderNamespace
     */
    public function setPkgAutoloaderRegistries($autoloaderLocation, $autoloaderNamespace)
    {
        $this->pkgAutoloaderRegistries = sprintf(
            '\'%s\' => \'%s\'', $autoloaderLocation, $autoloaderNamespace
        );
    }

    public function generate()
    {
        $class = $this->classTemplate;
        $class = preg_replace_callback(
            '/{([a-zA-Z0-9_]+)}/',
            function ($matches) {
                $key = $matches[1];
                return $this->$key;
            },
            $class
        );

        $path = $this->getPackageDirectory();
        if (!file_exists($path)) {
            mkdir($path, \Config::get('concrete.filesystem.permissions.directory'), true);
        }

        /** @var File $fh */
        $fh = \Core::make('helper/file');
        $fh->append($path . DIRECTORY_SEPARATOR . 'controller.php', $class);

        return $path . DIRECTORY_SEPARATOR . 'controller.php';
    }

    protected function getPackageDirectory()
    {
        return DIR_BASE . DIRECTORY_SEPARATOR . DIRNAME_PACKAGES . DIRECTORY_SEPARATOR . $this->pkgHandle;
    }
}