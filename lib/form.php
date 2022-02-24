<?php

class form extends rex_form
{
    /**
     * Callbackfunktion vor dem speichern des Formulars
     * hier kann der zu speichernde Inhalt noch beeinflusst werden.
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
