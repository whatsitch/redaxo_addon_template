<?php

class Addon
{
    private static $instance = null;
    private $package_name = "redaxo-addon-template";
    public $name = "Redaxo Addon Template";
    public $rex_addon;

    public function __construct()
    {
        $this->rex_addon = rex_addon::get($this->package_name);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Addon();
        }

        return self::$instance;
    }
}