<?php

require_once __DIR__.'/../vendor/autoload.php';

use Beequeue\DependView\DependencyAnalyser;
use Beequeue\DependView\ConfigHelper;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client as HttpClient;

define('ROOT_DIR', __DIR__ . '/..');
define('PROJECTS_CONFIG_FILE', ROOT_DIR . '/app/config/projects.yml');

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => ROOT_DIR . '/app/views',
));

$configHelper = new ConfigHelper(file_get_contents(PROJECTS_CONFIG_FILE));
$configHelper->substituteEnvironmentVars();

$app['dependencyAnalyser'] = new DependencyAnalyser([
    'projects' => Yaml::parse($configHelper->getConfig()),
    'cacheDir' => ROOT_DIR . '/cache'
]);

/**
 * This renders the table of dependencies
 */
$app->get('/', function () use ($app) {

    // First, check the cache - @todo...

    // Next, invoke the object that generates the template data to be rendered
    $dependencyAnalyser = $app['dependencyAnalyser'];

    // Next, execute the analysis
    $table = $dependencyAnalyser->analyse();

    // Then, render the results
    return $app['twig']->render('main.twig', array(
        'table' => $table->asArray(),
    ));
});

/**
 * Can act as a web hook to trigger a cache update for a given project,
 * e.g. /update?project=project-a
 */
$app->get('/update', function(Request $request) use ($app) {

    $projectId = $request->get('projectid');

    if (!$projectId) {
        return "'projectid' must be a valid project ID in query string";
    }

    $dependencyAnalyser = $app['dependencyAnalyser'];

    $project = $dependencyAnalyser->getProjectById($projectId);

    if (!$project) {
        return "Requested project ID not found";
    }

    $httpClient = new HttpClient;
    $project->updateCache($httpClient);

    return "OK";
});

$app['debug'] = 1;

$app->run();