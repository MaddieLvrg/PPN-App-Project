<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views'));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array('dbname' => 'ppnblogdb'),
));

$app['debug'] = true;
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
    }));
    $twig->addGlobal('posts', $app['db']->fetchAll('SELECT * FROM posts'));
    return $twig;
}));

$app->get('/post/{id}', function ($id) use ($app) {
    $sql = "SELECT * FROM posts WHERE id = ? ORDER BY date ASC";
    $post = $app['db']->fetchAssoc($sql, array($id));
    return $app['twig']->render('post.twig', [
        'post' => $post
    ]);
});

$app->get('/archive/{year}/{month}', function($year, $month) use ($app) {
    $sql = "SELECT * FROM posts WHERE YEAR(date) = ? AND MONTH(date) = ? ORDER BY date ASC";
    $archivePosts = $app['db']->fetchAll($sql, array($year, $month));
    return $app['twig']->render('archive.twig', [
    'archivePosts' => $archivePosts, 'selectedYear' => $year, 'selectedMonth' => $month
    ]);
});

$app->get('/', function() use ($app) {
    return $app['twig']->render('layout.twig');
})->bind('home');


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