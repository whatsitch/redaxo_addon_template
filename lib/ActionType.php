<?php

enum ActionType: string
{
    case TOGGLESTATUS = "togglestatus";
    case ADD = "add";
    case EDIT = "edit";
    case DELETE = "delete";
}