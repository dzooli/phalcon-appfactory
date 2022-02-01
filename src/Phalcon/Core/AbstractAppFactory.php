<?php

namespace Dzooli\Phalcon\Core;

abstract class AbstractAppFactory
{
    abstract protected function initDi();
    abstract protected function initServices();

    protected function initDefaultCliDi()
    {
        /**
         * TODO: implementation
         */
    }

    protected function initDefaultMicroDi()
    {
        /**
         * TODO: implementation
         *
         * @return void
         */
    }

    protected function initDefaultCliServices()
    {
        /**
         * TODO: implementation
         */
    }

    protected function initDefaultMicroServices()
    {
        /**
         * TODO: implementation
         *
         * @return void
         */
    }

    public static function createMicro()
    {
        /** 
         * TODO: implementation
         */
    }

    public static function createCli()
    {
        /**
         * TODO: implementation
         */
    }
}
