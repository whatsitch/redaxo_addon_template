<?php

class Seeder
{
    protected rex_sql $sql;

    public function __construct()
    {
        $this->sql = rex_sql::factory();
    }

    public function populate()
    {
        /*----- events table -----*/
        $events = [
            ['id' => 1, 'name' => 'event 01', 'description', 'event 01 description', 'isActive', 1],
            ['id' => 2, 'name' => 'event 02', 'description', 'event 02 description', 'isActive', 1],
        ];

        $this->sql->setTable(rex::getTable('events'));

        foreach ($events as $event) {
            $this->sql->addRecord(static function (rex_sql $record) use ($event) {
                $record->setValues($event);
            });
        }

        $this->sql->insertOrUpdate();
    }
}