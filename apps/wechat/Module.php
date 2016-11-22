<?php
namespace Wechat;

class Module
{
    public function registerAutoloaders(){
        $loader = include CONFIG_PATH . '/loader.php';
		$loader->registerNamespaces([
            'Wechat\Controllers' => __DIR__.'/controllers/'
		]);
		$loader->register();
    }

    public function registerServices($di) {
        $di->set('view', function(){
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(__DIR__ . '/views/');
			$view->registerEngines([
				'.php' => function($view, $di) {
					$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
					$volt->setOptions([
						'compiledPath' => VIEW_PATH,
						'compiledExtension' => ".php",
                        'stat' => true,
                        'compileAlways' => true
				    ]);
					return $volt;
				}
			]);
			return $view;
        });
	}
}

