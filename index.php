<?php

require_once __DIR__ . '/vendor/autoload.php';

#$loader = new Twig_Loader_Filesystem('views');
#$twig = new Twig_Environment($loader);

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views'));


$app['debug'] = true;
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
    }));
    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
#sets global value of active path
$app->before(function ($request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

$app->get('/', function() use ($app) {
    return $app['twig']->render('layout.twig');
})->bind('home');

$app->get('/archive', function() use ($app) {
    return $app['twig']->render('archive.twig');
})->bind('archive');

$app->run();


/*
$app -> run();

$app->get('/', function(){
    return "Hello world";
});
*/
/*
$app->get('/home', function () use ($twig) {
    $twig->render('layout.twig', array(
        'name' => 'Maddie',
        'age' => 23
    ));
    return $twig;
});
*/