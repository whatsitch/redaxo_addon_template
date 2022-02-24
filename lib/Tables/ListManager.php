<?php

abstract class ListManager
{
    public static string $modifyIcon = '<i class="rex-icon rex-icon-editmode" title="[###id###]"></i>';

    public static string $rexTableIcon = '<th class="rex-table-icon">###VALUE###</th>';

    public static string $rexIconDelete = '<i class="rex-icon rex-icon-delete"></i>';

    public static function rexIconActive(bool $active): string
    {

        return '<i class="rex-icon rex-icon-active-' .  ($active ? 'true' : 'false') .'"></i>';
    }


    public static string $idPlaceholder = '###id###';
    public static string $isActivePlaceholder = '###isActive###';

}