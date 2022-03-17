<?php

namespace Dzooli\Phalcon\Core;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Url as UrlResolver;

class MicroAppFactory extends AbstractAppFactory
{
    public function __construct(string $path)
    {
        $this->appPath = $path;
    }

    protected function initDefaultRoutes()
    {
        $app = $this->app;
        $this->app->notFound(function () use ($app) {
            $app->response->setStatusCode(404, "Not Found")->sendHeaders();
            echo $app['view']->render('404');
        });
    }

    protected function initMicroServices(): void
    {
        $this->di->setShared('view', function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setViewsDir($config->application->viewsDir);
            return $view;
        });

        $this->di->setShared('url', function () {
            $config = $this->getConfig();

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        });
    }

    public function createApp(): MicroAppFactory
    {
        $this->initDefaults();
        $this->initMicroServices();
        $this->app = new Micro($this->di);
        $this->initDefaultRoutes();
        return $this;
    }
}
