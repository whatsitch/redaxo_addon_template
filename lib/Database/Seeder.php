<?php

class Seeder
{
    protected rex_sql $sql;

    public function __construct()
    {
        $this->sql = rex_sql::factory();
    }

    /**
     * @throws rex_sql_exception
     */
    public function populate()
    {
        /*----- define events data -----*/
        $events = [
            ['id' => 1, 'name' => 'event 01', 'description' => 'description 01', 'date' => '2022-03-18', 'location' => 'TEAMS Meeting', 'isActive' => 1],
            ['id' => 2, 'name' => 'event 02', 'description' => 'description 02', 'date' => '2022-03-15', 'location' => 'ZOOM Meeting', 'isActive' => 1],
        ];

        $this->sql->setTable(rex::getTable(Addon::getPackageName() . '_' . 'events'));

        /*----- populate data -----*/
        foreach ($events as $event) {
            $this->sql->addRecord(static function (rex_sql $record) use ($event) {
                $record->setValues($event);
            });
        }

        $this->sql->insertOrUpdate();
    }
}