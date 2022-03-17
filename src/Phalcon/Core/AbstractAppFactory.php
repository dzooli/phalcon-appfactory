<?php

namespace Dzooli\Phalcon\Core;

use Phalcon\Config;
use Phalcon\Di\Di;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Url as UrlResolver;

use Phalcon\Config\Exception as ConfigException;

/**
 * Base class for the application factories.
 */
abstract class AbstractAppFactory
{
    protected ?string $appPath = null;
    protected $di = null;
    protected ?Config $appConfig = null;
    protected $loader = null;
    protected $app = null;

    public function getConfig(): Config
    {
        return $this->appConfig;
    }

    public function getDi(): Di
    {
        return $this->di;
    }

    public function getPath()
    {
        return $this->appPath;
    }

    public function getLoader()
    {
        return $this->loader;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function initDefaults()
    {
        $this->initDi();
        $this->initBaseServices();
        $this->initConfig();
        $this->initLoader();
    }

    protected function initConfig(): void
    {
        $this->appConfig = $this->di->getShared('config');
    }
    protected function initBaseServices(): void
    {
        $factory = $this;
        $this->di->setShared('config', function () use ($factory) {
            return require $factory->appPath . "/config/config.php";
        });

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

        $this->di->setShared('db', function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }
            return new $class($params); // The connection
        });
    }

    protected function initDi(): void
    {
        $this->di = new FactoryDefault();
    }

    /**
     * Initializes the Phalcon autoloader from config.php's 'application' array.
     * 
     * Must have directories:
     *   - modelsDir
     *   - controllersDir
     *   - viewsDir
     * Optional:
     *   - libDir 
     *
     * @return void
     * @throws Phalcon\Config\Exception
     */
    protected function initLoader(): void
    {
        if (!$this->appConfig) {
            throw new ConfigException("Configuration not found!");
        }
        $configArray = $this->appConfig->toArray();
        if (!$this->appConfig->get('application')) {
            throw new ConfigException("Application configuration not found!", 1);
        }
        $appConfigArray = $configArray['application'];
        if (
            array_key_exists('modelsDir', $appConfigArray)
            && array_key_exists('controllersDir', $appConfigArray)
            && array_key_exists('viewsDir', $appConfigArray)
        ) {
            $this->loader = new Loader();
            $dirs = [
                $this->appConfig->application->modelsDir,
                $this->appConfig->application->controllersDir,
                $this->appConfig->application->viewsDir
            ];
            if (array_key_exists('libDir', $appConfigArray)) {
                $dirs += $this->appConfig->application->libDir;
            }
            $this->loader->registerDirs($dirs)->register();
            return;
        }
        throw new ConfigException("Application configuration is incomplete! Has to have modelsDir, controllersDir, viewsDir and optionally a libDir", 1);
    }
}
