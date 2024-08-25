<?php

namespace Apiato\Core\Generator\Commands\TestCases;

use Apiato\Core\Generator\FileGeneratorCommand;

class ApiTestCaseGenerator extends FileGeneratorCommand
{
    protected ?string $fileName = 'ApiTestCase';

    public static function getCommandName(): string
    {
        return 'apiato:generate:testcase:functional:api';
    }

    public static function getCommandDescription(): string
    {
        return 'Create an Api TestCase file';
    }

    public static function getFileType(): string
    {
        return 'api test case';
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
        return "$this->sectionName/$this->containerName/Tests/Functional/$this->fileName.php";
    }

    protected function getFileContent(): string
    {
        $file = new \Nette\PhpGenerator\PhpFile();
        $namespace = $file->addNamespace('App\Containers\\' . $this->sectionName . '\\' . $this->containerName . '\Tests\Functional');

        // imports
        $functionalTestCaseFullPath = "App\Containers\\$this->sectionName\\$this->containerName\Tests\FunctionalTestCase";
        $namespace->addUse($functionalTestCaseFullPath);

        // class
        $class = $file->addNamespace($namespace)
            ->addClass($this->fileName)
            ->setAbstract()
            ->setExtends($functionalTestCaseFullPath);

        return $file;
    }
}
