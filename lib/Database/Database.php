<?php

class Database
{
    private static ?Database $instance = null;
    private bool $isSeederEnabled = true;
    protected array $tables;
    private bool $enableAddonPrefix = true;

    private function __construct()
    {
        $this->tables = [];
        $this->defineTables();
    }

    public function up()
    {
        $this->defineTables();
        $this->createTables();
        /*----- seeder -----*/
        if ($this->isSeederEnabled) {
            $seeder = new Seeder();
            $seeder->populate();
        }
    }

    public function down()
    {
        $this->dropTables();
    }

    public function enableSeeder(bool $active)
    {
        $this->isSeederEnabled = $active;
    }

    public function defineTables()
    {
        $this->tables[0] = new Table("events", $this->enableAddonPrefix, [
            new rex_sql_column("name", "varchar(255)", true, 1,),
            new rex_sql_column("description", "varchar(255)", true, 1),
            new rex_sql_column("isActive", "tinyint(1)", true, 1)
        ]);
        $this->tables[1] = new Table("test-oop-2", $this->enableAddonPrefix, [
            new rex_sql_column("isTrue", "tinyint(1)", true, 1)
        ]);
    }


    public function createTables()
    {
        foreach ($this->tables as $table) {

            rex_sql_table::get(rex::getTable($table->name))->ensurePrimaryIdColumn();
            foreach ($table->columns as $column) {
                rex_sql_table::get(rex::getTable($table->name))->ensureColumn($column);
            }
            rex_sql_table::get(rex::getTable($table->name))->ensure();
        }
    }

    private function dropTables()
    {
        foreach ($this->tables as $table) {
            rex_sql_table::get(rex::getTable($table->name))->drop();
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
}