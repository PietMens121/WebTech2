<?php

namespace App\container;

class ServiceLocator
{
    private static Container $instance;

    public static function getInstance(): Container
    {
        if (!isset(self::$instance)) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    public static function setInstance(Container $instance): void
    {
        self::$instance = $instance;
    }
}