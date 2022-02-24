<?php

class TableManager extends rex_form
{
    private static $instance = null;

    private function __construct()
    {
        // $this->rex_addon = rex_addon::get($this->package_name);
    }

    public static function getInstance(): ?TableManager
    {
        if (self::$instance == null) {
            self::$instance = new TableManager();
        }

        return self::$instance;
    }
}




/*
    /**
     * Callbackfunktion vor dem speichern des Formulars
     * hier kann der zu speichernde Inhalt noch beeinflusst werden.
    public function preSave($fieldsetName, $fieldName, $fieldValue, rex_sql $saveSql)
    {
        switch ($fieldName) {
            default:
                return $fieldValue;
                break;
            case 'birthdate':
                return date('Y-m-d', strtotime($fieldValue));
                break;
        }
    }
*/