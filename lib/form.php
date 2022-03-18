<?php

class Form extends rex_form
{
    /**
     * callback function before form save
     * validate form
     */
    public function preSave($fieldsetName, $fieldName, $fieldValue, rex_sql $saveSql): int|string|null
    {
        switch ($fieldName) {
            default:
                return $fieldValue;
                break;
        }
    }
}
