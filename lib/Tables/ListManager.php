<?php

abstract class ListManager
{

    public static string $rexTableIcon = '<th class="rex-table-icon">###VALUE###</th>';

    public static string $rexIconDelete = '<i class="rex-icon rex-icon-delete"></i>';

    public static string $rexIconEdit = '<i class="rex-icon rex-icon-editmode"></i>';

    public static string $rexIconAdd = '<i class="rex-icon rex-icon-add"></i>';

    public static function rexIconActive(bool $active): string
    {
        return '<i class="rex-icon rex-icon-active-' . ($active ? 'true' : 'false') . '"></i>';
    }

    public static string $idPlaceholder = '###id###';

    public static string $valuePlaceholder = '###VALUE###';

    public static string $isActivePlaceholder = '###isActive###';

    /*----- database column placeholders -----*/
    public static string $namePlaceholder = '###name###';
    public static string $descriptionPlaceholder = '###description###';

}