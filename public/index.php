<?php

require_once '../vendor/autoload.php';
ini_set('html_errors', 0);

// Zend logging
use Zend\Log\Logger as ZendLogger;
$filter = new Zend\Log\Filter\Priority(ZendLogger::DEBUG);
$writer = new Zend\Log\Writer\Stream(__DIR__ . '/../logs/zend-log.log');
$writer->addFilter($filter);
$zendLogger = new ZendLogger;
$zendLogger->addWriter($writer);

// Monolog Logging
$monolog = new Monolog\Logger('demo');
$monolog->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../logs/monolog.log', Monolog\Logger::DEBUG));
$monolog->pushProcessor(new Monolog\Processor\IntrospectionProcessor);

$app = new \Slim\Slim();

$app->get('/', function() use ($app) {
    $app->contentType('application/json');
    echo json_encode(array('route' => 'root'));
});

$app->get('/zend', function() use ($app, $zendLogger) {
    $zendLogger->emerg('message');
    $zendLogger->crit('message');
    $zendLogger->warn('message');
    $zendLogger->debug('message');
    
    $app->contentType('application/json');
    echo json_encode(array('route' => 'zend'));
});

$app->get('/monolog', function() use ($app, $monolog) {
    $monolog->addEmergency('message', array('example' => 'usage'));
    $monolog->addCritical('message');
    $monolog->addWarning('message');
    $monolog->addDebug('message');
    
    $app->contentType('application/json');
    echo json_encode(array('route' => 'monolog'));
});
$app->run();
