<?php

use JetBrains\PhpStorm\Pure;

class Table
{
    public string $name;
    public array $columns;

    #[Pure] public function __construct(string $tableName, bool $enableAddonPrefix, array $columns)
    {
        if ($enableAddonPrefix) {

            $this->name = Addon::getPackageName() . "_" . $tableName;
        } else {
            $this->name = $tableName;
        }
        $this->columns = $columns;
    }
}