<?php

namespace Apiato\Core\Generator\Commands\TestCases;

use Apiato\Core\Generator\FileGeneratorCommand;
use Nette\PhpGenerator\PhpFile;

class FunctionalTestCaseGenerator extends FileGeneratorCommand
{
    protected string|null $fileName = 'FunctionalTestCase';

    public static function getCommandName(): string
    {
        return 'apiato:make:testcase:functional';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a Functional TestCase file';
    }

    public static function getFileType(): string
    {
        return 'functional test case';
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
        $containerTestCaseFullPath = "App\Containers\\$this->sectionName\\$this->containerName\Tests\ContainerTestCase";
        $namespace->addUse($containerTestCaseFullPath);

        // class
        $file->addNamespace($namespace)
            ->addClass($this->fileName)
            ->setAbstract()
            ->setExtends($containerTestCaseFullPath);

        return $file;
    }
}
