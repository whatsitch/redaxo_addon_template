<?php

class Addon
{
    private static ?Addon $instance = null;
    private static string  $package_name = "redaxo_addon_template";
    public string $name = "Redaxo Addon Template";
    public Database $db;
    public rex_addon $rex_addon;

    private function __construct()
    {
        $this->rex_addon = rex_addon::get(self::$package_name);
        $this->db = Database::getInstance();

        /*----- populate db with sample data -----*/
        $this->db->enableSeeder(true);
    }

    public static function getInstance(): ?Addon
    {
        if (self::$instance == null) {
            self::$instance = new Addon();
        }
        return self::$instance;
    }

    public static function getPackageName(): string
    {
        return self::$package_name;
    }
}