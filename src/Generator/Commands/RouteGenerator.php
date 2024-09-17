<?php

namespace Apiato\Core\Generator\Commands;

use Apiato\Core\Generator\FileGeneratorCommand;
use Apiato\Core\Generator\Traits\HasTestTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class RouteGenerator extends FileGeneratorCommand
{
    use HasTestTrait;

    protected string $ui;

    protected string $visibility;

    protected string $docVersion;

    protected string $url;

    protected string $method;

    protected string $controller;

    public static function getCommandName(): string
    {
        return 'apiato:make:route';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a Route for a Container';
    }

    public static function getFileType(): string
    {
        return 'route';
    }

    protected static function getCustomCommandArguments(): array
    {
        return [
            ['ui', null, InputOption::VALUE_OPTIONAL, 'The UI of the route. (API, WEB, ...)'],
            ['visibility', null, InputOption::VALUE_OPTIONAL, 'The visibility of the route. (public, private)'],
            ['docversion', null, InputOption::VALUE_OPTIONAL, 'The version of the route. (1, 2, ...)'],
            ['url', null, InputOption::VALUE_OPTIONAL, 'The URL of the route. (/users, /products, ...)'],
            ['method', null, InputOption::VALUE_OPTIONAL, 'The method of the route. (GET, POST, PUT, DELETE, ...)'],
            ['controller', null, InputOption::VALUE_OPTIONAL, 'The controller of the route.'],
        ];
    }

    protected function askCustomInputs(): void
    {
        $this->ui = $this->checkParameterOrSelect(
            param: 'ui',
            label: 'Select the UI of the route:',
            options: ['API', 'WEB'],
            default: 'API',
        );
        $this->visibility = $this->checkParameterOrSelect(
            param: 'visibility',
            label: 'Select the visibility of the route:',
            options: ['private', 'public'],
            default: 'private',
        );
        $this->docVersion = $this->checkParameterOrAskText(
            param: 'docversion',
            label: 'Enter the version of the route:',
            default: '1',
        );
        $this->url = $this->checkParameterOrAskText(
            param: 'url',
            label: 'Enter the URL of the route:',
            default: '/' . Str::plural(Str::lower($this->containerName)),
        );
        $this->method = $this->checkParameterOrSelect(
            param: 'method',
            label: 'Select the method of the route:',
            options: ['GET', 'POST', 'PUT', 'DELETE'],
            default: 'GET',
        );
        $this->controller = $this->checkParameterOrAskTextSuggested(
            'controller',
            'Enter the controller of the route:',
            suggestions: $this->getControllersList(
                section: $this->sectionName,
                container: $this->containerName,
            ),
        );
    }

    protected function getFilePath(): string
    {
        return "$this->sectionName/$this->containerName/UI/API/Routes/$this->fileName.v$this->docVersion.$this->visibility.php";
    }

    protected function getFileContent(): string
    {
        $routeTitle = Str::headline($this->fileName);

        return "
<?php

/**
 * @apiGroup           $this->containerName
 *
 * @apiName            $this->fileName
 *
 * @api                {{$this->method}} /v$this->docVersion$this->url $routeTitle
 *
 * @apiDescription     Endpoint description here...
 *
 * @apiVersion         $this->docVersion.0.0
 *
 * @apiPermission      Authenticated | Resource Owner
 *
 * @apiHeader          {String} accept=application/json
 * @apiHeader          {String} authorization=Bearer
 *
 * @apiParam           {String} parameters here...
 *
 * @apiSuccessExample  {json} Success-Response:
 * HTTP/1.1 200 OK
 * {
 *     // Insert the response of the request here...
 * }
 */

use App\Containers\\$this->sectionName\\$this->containerName\UI\\$this->ui\Controllers\\$this->controller;
use Illuminate\Support\Facades\Route;

Route::$this->method('$this->url', $this->controller::class)
    ->middleware(['auth:api']);
";
    }

    protected function getTestPath(): string
    {
        return $this->sectionName . '/' . $this->containerName . "/Tests/Functional/$this->ui/" . $this->fileName . 'Test.php';
    }

    protected function getTestContent(): string
    {
        $file = new \Nette\PhpGenerator\PhpFile();
        $namespace = $file->addNamespace('App\Containers\\' . $this->sectionName . '\\' . $this->containerName . "\Tests\Functional\\$this->ui");

        // imports
        $parentTestCaseFullPath = "App\Containers\AppSection\\$this->containerName\Tests\Functional\\" . Str::ucfirst(Str::lower($this->ui)) . 'TestCase';
        $namespace->addUse($parentTestCaseFullPath);
        $userModelFullPath = 'App\Containers\AppSection\User\Models\User';
        $namespace->addUse($userModelFullPath);

        // class
        $class = $file->addNamespace($namespace)
            ->addClass($this->fileName . 'Test')
            ->setFinal()
            ->setExtends($parentTestCaseFullPath);

        // properties
        $class->addProperty('endpoint')
            ->setVisibility('protected')
            ->setType('string')
            ->setValue("$this->method@v$this->docVersion$this->url");

        // test methods
        $testMethod1 = $class->addMethod('testEndpoint')->setPublic();
        $testMethod1->addBody("
\$this->markTestSkipped('This test has not been implemented yet.');
");
        $testMethod1->setReturnType('void');

        $testMethod2 = $class->addMethod('testEndpointWhileUnauthenticated')->setPublic();
        $testMethod2->addBody('
$this->testingUser = User::factory()->create();

$response = $this->auth(false)->makeCall();

$response->assertUnauthorized();
');
        $testMethod2->setReturnType('void');

        $testMethod3 = $class->addMethod('testEndpointWhileUnverified')->setPublic();
        $testMethod3->addBody('
$this->testingUser = User::factory()->unverified()->create();

$response = $this->makeCall();

$response->assertForbidden();
');
        $testMethod3->setReturnType('void');

        // return the file
        return $file;
    }
}
