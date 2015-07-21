<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$vendorAutoload = '../../vendor/autoload.php';

// require '../vendor/autoload.php';

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Models\Project;
use Models\Image;
use Components\User;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

if ( ! file_exists($vendorAutoload)) {
    throw new RuntimeException('Install dependencies to run this script.');
}

$loader = require_once $vendorAutoload;
$loader->add('Models', '../../app');
$loader->add('Controllers', '../../app');
$loader->add('Components', '../../app');
$loader->add('Library', '../../app');

//@TODO: Enable logging.
$app = new \Slim\Slim( array(
    // 'debug'         => false,
    // 'log.enabled'   => true,
    // 'slim.errors'   => "../log/app.log",
    // 'log.level'     => \Slim\Log::DEBUG,
    // 'log.writer'    => new \Slim\LogWriter(fopen('../log/app.log', 'a')),
    // 'templates.path' => '../app/Views',
    // 'view'          => new \Library\TwigView()
));

/*****Configuration*****/
$app->container->singleton('config', function () {
    $configurationComponent = new \Components\Configuration();
    return $configurationComponent->getConfiguration();
});

/*****Database*****/
$app->container->singleton('db', function () use ($app) {
    $paths = array("../../app/Models");
    $isDevMode = true;

    // the connection configuration
    $dbParams = array(
        'driver'   => 'pdo_mysql',
        'host'     => '0.0.0.0', //getenv('IP'),
        'user'     => 'javenlalla',
        'password' => '',
        'dbname'   => 'lol',
    );
    
    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $config->setSQLLogger(new \Library\ModelsLogger());
    
    $entityManager = EntityManager::create($dbParams, $config);
    
    return $entityManager;
});

//Logic

$dql = "
    SELECT
        a
    FROM
        Models\Image a
    WHERE
        a.compressed_filename IS NULL
        OR a.compressed_filename = ''
";

$query = $app->db->createQuery($dql);

try {
    $images = $query->getResult();
    
    if(!empty($images)) {
        
        $imageProcessor = new \Components\ImageProcessor();
        
        foreach($images as $image) {
            $compressedImageFilename = $imageProcessor->compressImage($image->getFilename(), '../../www/i/');
            
            $image->setCompressedFilename($compressedImageFilename);
            
            $app->db->persist($image);
            $app->db->flush();
        }
    }
    
} catch(Exception $e) {
    echo $e->getMessage()."\n";
}