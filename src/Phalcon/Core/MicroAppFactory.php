<?php

namespace Dzooli\Phalcon\Core;

use Phalcon\Mvc\Micro;

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

    public function createApp(): MicroAppFactory
    {
        $this->initDefaults();
        $this->app = new Micro($this->di);
        $this->initDefaultRoutes();
        return $this;
    }
}
