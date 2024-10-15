<?php

namespace Apiato\Core\Generator\Commands\TestCases;

use Apiato\Core\Generator\FileGeneratorCommand;
use Nette\PhpGenerator\PhpFile;

class ContainerTestCaseGenerator extends FileGeneratorCommand
{
    protected string|null $fileName = 'ContainerTestCase';

    public static function getCommandName(): string
    {
        return 'apiato:make:testcase:container';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a Container TestCase file';
    }

    public static function getFileType(): string
    {
        return 'container test case';
    }

    protected static function getCustomCommandArguments(): array
    {
        return [];
    }

    protected function askCustomInputs(): void
    {
    }

    protected function getFilePath(): string
    {
        return "$this->sectionName/$this->containerName/Tests/$this->fileName.php";
    }

    protected function getFileContent(): string
    {
        $file = new PhpFile();
        $namespace = $file->addNamespace('App\Containers\\' . $this->sectionName . '\\' . $this->containerName . '\Tests');

        // imports
        $parentTestCaseFullPath = 'App\Ship\Parents\Tests\TestCase';
        $namespace->addUse($parentTestCaseFullPath, 'ParentTestCase');

        // class
        $file->addNamespace($namespace)
            ->addClass($this->fileName)
            ->setAbstract()
            ->setExtends($parentTestCaseFullPath);

        return $file;
    }
}
