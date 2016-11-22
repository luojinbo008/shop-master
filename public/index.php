<?php
include __DIR__ . '/../bootstrap/bootstrap.php';
$app = new \Phalcon\Mvc\Application();
$app->setDI($di);
$app->registerModules($module);
try {
    echo $app->handle()->getContent();
} catch(\Exception $e) {
    $message = $e->getMessage() . ", \r file:" . $e->getFile() . ", \r line:" . $e->getLine();
    $di->get('log')->info($message);
    if($di->get('request')->isAjax() === true){
        $di->get('response')->setContentType('application/json', 'UTF-8');
        $di->get('response')->setJsonContent(["status" => 1, "info"=> $e->getMessage(), 'data' => []]);
        $di->get('response')->send();
    } else {
        echo $e->getMessage();
    }
}

