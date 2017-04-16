# pour
Generate class file interactively - Let's pour concrete!

## Usage

### Generate Package Controller

```
$ concrete/bin/concrete5 c5:pour
Please input the handle of the package:my_great_package
Please input the name of the package:My Great Package
Please input the description of the package:This is the great package.
Please input the version of concrete5 required by the package:8.2.0
Please input the version of the package:0.9
Is this package has custom autoloader entries? (y/n)y
Please input the location (e.g. src/PortlandLabs):src/Foo/Bar
Please input the namespace (e.g. PortlandLabs):Foo\Bar
Class generated to /path/to/concrete5/packages/my_great_package/controller.php
```

Result:

```
<?php
namespace Concrete\Package\MyGreatPackage;

use Concrete\Core\Package\Package;

class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = 'my_great_package';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = '8.2.0';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = '0.9';
    
    /**
     * @var array Array of location -> namespace autoloader entries for the package.
     */
    protected $pkgAutoloaderRegistries = ['src/Foo/Bar' => 'Foo\Bar'];
    
    protected $pkgAutoloaderMapCoreExtensions = true;
    
    /**
     * Returns the translated name of the package.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('My Great Package');
    }

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('This is the great package.');
    }
}
```

## TODO

* Block Controller
