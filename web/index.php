<?php

require_once __DIR__.'/../vendor/autoload.php';

use Beequeue\DependView\DependencyAnalyser;
use Symfony\Component\Yaml\Yaml;

define('ROOT_DIR', __DIR__ . '/..');
define('PROJECTS_CONFIG_FILE', ROOT_DIR . '/app/config/projects.yml');

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => ROOT_DIR . '/app/views',
));

/**
 * This renders the table of dependencies
 */
$app->get('/', function () use ($app) {

    // First, check the cache - @todo...

    // Next, invoke the object that generates the template data to be rendered
    $dependencyAnalyser = new DependencyAnalyser([
        'projects' => Yaml::parse(file_get_contents(PROJECTS_CONFIG_FILE)),
        'cacheDir' => ROOT_DIR . '/cache'
    ]);

    // Next, execute the analysis
    $table = $dependencyAnalyser->analyse();

    // Then, render the results
    return $app['twig']->render('main.twig', array(
        'table' => $table->asArray(),
    ));
});

$app->run();