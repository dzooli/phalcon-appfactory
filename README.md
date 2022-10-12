# Application factories for the Phalcon framework

## Sonarcube analisys

[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=dzooli_phalcon-appfactory&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=dzooli_phalcon-appfactory)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=dzooli_phalcon-appfactory&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=dzooli_phalcon-appfactory)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=dzooli_phalcon-appfactory&metric=bugs)](https://sonarcloud.io/summary/new_code?id=dzooli_phalcon-appfactory)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=dzooli_phalcon-appfactory&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=dzooli_phalcon-appfactory)

## Description

Application skeleton for Phalcon CLI and Micro and API application. You can override the service and DI (and routes) configuration and just use your simple preconfigured application.

## Installation

```bash
composer config repositories.phalcon-appfactory vcs https://github.com/dzooli/phalcon-appfactory.git
composer require -o -vv dzooli/phalcon-appfactory:dev-master
```

## Usage

### Example for MicroAppFactory

Create your customized application factory:

```php
<?php

namespace App;

use Dzooli\Phalcon\Core\AbstractAppFactory;
use Dzooli\Phalcon\Core\MicroAppFactory;
use Dzooli\Phalcon\Core\RouterDefinitionInterface;

class MyAppFactory extends MicroAppFactory implements RouterDefinitionInterface
{
    public function addRoutes(): AbstractAppFactory
    {
        $app = $this->app;
        $this->app->get('/', function () use ($app) {
            echo $app['view']->render('index');
        });
        return $this;
    }
}
```

And use it in your main program (such as `index.php`):

```php
<?php

use App\MyAppFactory; /* This is your overrided Application Factory definition. */

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require_once(BASE_PATH . '/vendor/autoload.php');

try {
    $appFactory = new MyAppFactory(APP_PATH);
    $appFactory->createApp()
        ->addRoutes()
        ->getApp()
        ->handle($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}

```

## Contribution

Pull requests are welcome on the _develop_ branch.
