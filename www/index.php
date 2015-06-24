<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$vendorAutoload = '../vendor/autoload.php';

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
$loader->add('Models', '../app');
$loader->add('Controllers', '../app');
$loader->add('Components', '../app');
$loader->add('Library', '../app');

$app = new \Slim\Slim( array(
    // 'debug'         => false,
    'log.enabled'   => true,
    'slim.errors'   => "../log/app.log",
    'log.level'     => \Slim\Log::DEBUG,
    'log.writer'    => new \Slim\LogWriter(fopen('../log/app.log', 'a')),
    'templates.path' => '../app/Views',
    'view'          => new \Library\TwigView()
));

/*****Template*****/
$app->view->updateDelimiterSyntax();
// $app = new \Slim\Slim(array(
//     'view' => new \Library\TwigView()
// ));
// $app->view->setTemplatesDirectory('../app/Views');
// $twig = $app->view->getEnvironment();

// $lexer = new Twig_Lexer($twig, array(
//     'tag_comment'  => array('<%#', '%>'),
//     'tag_block'    => array('<%', '%>'),
//     'tag_variable' => array('<%=', '%>'),
// ));
// $twig->setLexer($lexer);

/*****Database*****/
$app->container->singleton('db', function () {
    $paths = array("../app/Models");
    $isDevMode = true;
    
    // the connection configuration
    $dbParams = array(
        'driver'   => 'pdo_mysql',
        'host'     => getenv('IP'),
        'user'     => getenv('C9_USER'),
        'password' => '',
        'dbname'   => 'lol',
    );
    
    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $config->setSQLLogger(new \Library\ModelsLogger());
    
    $entityManager = EntityManager::create($dbParams, $config);
    
    return $entityManager;
    
    // $connection = new Connection();
    
    // $config = new Configuration();
    // $config->setProxyDir(__DIR__ . '/Proxies');
    // $config->setProxyNamespace('Proxies');
    // $config->setHydratorDir(__DIR__ . '/Hydrators');
    // $config->setHydratorNamespace('Hydrators');
    // $config->setDefaultDB('doctrine_odm');
    // $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/Documents'));
    
    // AnnotationDriver::registerAnnotationClasses();
    
    // $dm = DocumentManager::create($connection, $config);
    
    // return $dm;
});

// session_cache_limiter(false);
// session_start();

//@TODO Enable when login is functional
// $app->get('/login', 'Controllers\AuthController:login');
$app->get('/', function() use ($app) {
    $app->render('index');
});

$app->get('/db', function() use ($app) {
    // var_dump($app->db);
    $dql = "SELECT b, t FROM Models\Image b LEFT JOIN b.tags t ORDER BY b.created DESC";

    $query = $app->db->createQuery($dql);
    // $query->setMaxResults(30);
    $images = $query->getArrayResult();
    echo json_encode($images);
    // foreach($images as $image) {
    //     echo $image->getId()."<br />";
    //     $tags = $image->getTags();
    //     foreach($tags as $tag) {
    //         echo $tag->getTag()."<br />";
    //     }
    // }
});

$app->get('/api/images/random/', 'Controllers\ImagesController:getRandomImage');

$app->get('/api/images', 'Controllers\ImagesController:getAllImages');
$app->post('/api/images', 'Controllers\ImagesController:createImage');
$app->put('/api/images/:id', 'Controllers\ImagesController:updateImage');
$app->delete('/api/images/:id', 'Controllers\ImagesController:deleteImage');

$app->error(function (\Exception $e) use ($app) {
    echo json_encode(array(
        'code'  => 500,
        'error' => $e->getMessage()
    ));
});

$app->run();

// $app->get('/api/images', function () use ($app) {
//     // $project = $app->db->find('Models\Project', '5529f37ed83537a9258b4567');
//     $images = $app->db->getRepository('Models\Image')->findAll();
    
//     $imagesArray = array();
//     foreach($images as $image) {
//         $imagesArray[] = array(
//             'id'    => $image->getId(),
//             'name'  => $image->getName(),
//             'tags'  => $image->getTags()
//         );
//     }
//     echo json_encode($imagesArray);
//     return;
    
//     // var_dump($project);
    
//     $images = $app->db->createQueryBuilder('Models\Image')
//     ->hydrate(false)
//     ->getQuery()
//     ->execute();
//     // var_dump($project->toArray());
//     // foreach($project as $proj) {
//     //     var_dump($proj->toArray());
//     // }
//     $return = array();
//     foreach($images as $image) {
//         // echo json_encode($image); die;
//         $return[] = $image;
//     } 
//     // echo json_encode($return);
//     // var_dump($images->toArray());
    
//     echo json_encode(array(
//         array('_id' => 1, 'name' => 'pic'),
//         array('_id' => 2, 'name' => 'pic2'),
//     ));
    
//     // echo json_encode(array($images->toArray()));
// });


// $app->post('/api/images', function() use ($app) {
//     $requestData = json_decode($app->request->getBody());
    
//     if(!empty($requestData->name)) {
//         $name = trim($requestData->name);
        
//         $tags = explode(",", $requestData->tags);
        
//         $newImage = new Image($name);
        
//         foreach($tags as $tag) {
//             $newImage->addTag(trim($tag));
//         }
        
//         $app->db->persist($newImage);
//         $app->db->flush();
        
//         echo json_encode(array(
//             'id'    => $newImage->getId(),
//             'name'  =>  $newImage->getName(),
//             'tags'  => $newImage->getTags()
//         ));
//         return;
//     }
    
//     $app->response->setStatus(400);
//     echo json_encode(array('code' => 400, 'message' => 'Invalid name provided.'));
//     return;
// });


// $app->put('/api/images/:id', function($id) use ($app) {
//     $image = $app->db->find('Models\Image', $id);
//     $requestData = json_decode($app->request->getBody());
    
    
//     if(!empty($image)) {
//         //@TODO: Add validation
//         $name = trim($requestData->name);
        
//         $tags = explode(",", $requestData->tags);
        
//         $image->setName($name);
        
//         $tagsToUpdate = array();
        
//         foreach($tags as $tag) {
//             $tagToUpdate = trim($tag);
//             if(!empty($tagToUpdate)) {
//                 $tagsToUpdate[] = trim($tag);
//             }
//         }
        
//         $image->updateTags($tagsToUpdate);
        
//         $app->db->persist($image);
//         $app->db->flush();
        
//     }
    
//     echo json_encode(array(
//         'id'    => $image->getId(),
//         'name'  => $image->getName(),
//         'tags'  => $image->getTags()
//     ));
    
//     return;
    
//     //@TODO: Return status code and message
// });



// $app->delete('/api/images/:id', function($id) use ($app) {
//     $image = $app->db->find('Models\Image', $id);
//     if(!empty($image)) {
//         $app->db->remove($image);
//         $app->db->flush();
//     }
    
//     //@TODO: Return status code and message
// });

