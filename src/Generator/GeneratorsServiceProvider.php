<?php

namespace Apiato\Generator;

use Apiato\Core\Providers\ServiceProvider;
use Apiato\Generator\Commands\ActionGenerator;
use Apiato\Generator\Commands\ConfigurationGenerator;
use Apiato\Generator\Commands\ControllerGenerator;
use Apiato\Generator\Commands\CriteriaGenerator;
use Apiato\Generator\Commands\EndpointGenerator;
use Apiato\Generator\Commands\EventGenerator;
use Apiato\Generator\Commands\EventListenerGenerator;
use Apiato\Generator\Commands\ExceptionGenerator;
use Apiato\Generator\Commands\FactoryGenerator;
use Apiato\Generator\Commands\JobGenerator;
use Apiato\Generator\Commands\MigrationGenerator;
use Apiato\Generator\Commands\ModelGenerator;
use Apiato\Generator\Commands\NotificationGenerator;
use Apiato\Generator\Commands\PolicyGenerator;
use Apiato\Generator\Commands\RepositoryGenerator;
use Apiato\Generator\Commands\RequestGenerator;
use Apiato\Generator\Commands\RouteGenerator;
use Apiato\Generator\Commands\TestCases\ApiTestCaseGenerator;
use Apiato\Generator\Commands\TestCases\CliTestCaseGenerator;
use Apiato\Generator\Commands\TestCases\ContainerTestCaseGenerator;
use Apiato\Generator\Commands\TestCases\FunctionalTestCaseGenerator;
use Apiato\Generator\Commands\TestCases\TestCasesGenerator;
use Apiato\Generator\Commands\TestCases\UnitTestCaseGenerator;
use Apiato\Generator\Commands\TestCases\WebTestCaseGenerator;
use Apiato\Generator\Commands\TransformerGenerator;

class GeneratorsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->getGeneratorCommands());
        }
    }

    private function getGeneratorCommands(): array
    {
        return [
            ActionGenerator::class,
            ApiTestCaseGenerator::class,
            CliTestCaseGenerator::class,
            ConfigurationGenerator::class,
            ContainerTestCaseGenerator::class,
            ControllerGenerator::class,
            CriteriaGenerator::class,
            EndpointGenerator::class,
            EventGenerator::class,
            EventListenerGenerator::class,
            ExceptionGenerator::class,
            FunctionalTestCaseGenerator::class,
            FactoryGenerator::class,
            JobGenerator::class,
            MigrationGenerator::class,
            ModelGenerator::class,
            NotificationGenerator::class,
            PolicyGenerator::class,
            RepositoryGenerator::class,
            RequestGenerator::class,
            RouteGenerator::class,
            TestCasesGenerator::class,
            TransformerGenerator::class,
            UnitTestCaseGenerator::class,
            WebTestCaseGenerator::class,
        ];
    }
}
