<?php
namespace Concrete\Package\Pour\Console\Command;

use C5j\Pour\PackageControllerGenerator;
use Concrete\Core\Console\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class PourCommand extends Command
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('c5:pour')
            ->setDescription('Generate class file interactively.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $pkgHandleQuestion = new Question('Please input the handle of the package:');
        $pkgHandle = $helper->ask($input, $output, $pkgHandleQuestion);

        $pkgNameQuestion = new Question('Please input the name of the package:');
        $pkgName = $helper->ask($input, $output, $pkgNameQuestion);

        $pkgDescriptionQuestion = new Question('Please input the description of the package:');
        $pkgDescription = $helper->ask($input, $output, $pkgDescriptionQuestion);

        $generator = new PackageControllerGenerator($pkgHandle, $pkgName, $pkgDescription);

        $appVersionRequiredQuestion = new Question('Please input the version of concrete5 required by the package:');
        $appVersionRequired = $helper->ask($input, $output, $appVersionRequiredQuestion);
        if ($appVersionRequired) {
            $generator->setAppVersionRequired($appVersionRequired);
        }

        $pkgVersionQuestion = new Question('Please input the version of the package:');
        $pkgVersion = $helper->ask($input, $output, $pkgVersionQuestion);
        if ($pkgVersion) {
            $generator->setPkgVersion($pkgVersion);
        }

        $hasPackageAutoloaderRegistriesQuestion = new ConfirmationQuestion(
            'Is this package has custom autoloader entries? (y/n)', false
        );
        if ($helper->ask($input, $output, $hasPackageAutoloaderRegistriesQuestion)) {
            $autoloaderLocationQuestion = new Question(
                'Please input the location (e.g. src/PortlandLabs):', 'src/PortlandLabs'
            );
            $autoloaderLocation = $helper->ask($input, $output, $autoloaderLocationQuestion);

            $autoloaderNamespaceQuestion = new Question(
                'Please input the namespace (e.g. PortlandLabs):', 'PortlandLabs'
            );
            $autoloaderNamespace = $helper->ask($input, $output, $autoloaderNamespaceQuestion);

            if ($autoloaderLocation && $autoloaderNamespace) {
                $generator->setPkgAutoloaderRegistries($autoloaderLocation, $autoloaderNamespace);
            }
        }

        $path = $generator->generate();
        $output->writeln(sprintf('Class generated to %s', $path));
    }
}