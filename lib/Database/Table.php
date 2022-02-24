<?php

class Table
{
    public string $name;
    public array $columns;
    private bool $enableAddonPrefix;

    public function __construct(string $tableName, bool $enableAddonPrefix, array $columns)
    {
        if ($enableAddonPrefix) {

            $this->name = Addon::getPackageName() . "_" . $tableName;
        } else {
            $this->name = $tableName;
        }
        $this->columns = $columns;
        $this->enableAddonPrefix = $enableAddonPrefix;
    }

    public function addColumn(string $name)
    {

        array_push($this->columns, new rex_sql_column($name, "tinyint(1)", true, 1));
    }
}