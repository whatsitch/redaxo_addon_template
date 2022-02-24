<?php
$addon = Addon::getInstance();

$eventTable = new EventTable('events', 'Events');

$eventTable->setTable('events');

$eventTable->setSqlSelect("SELECT * FROM " . $eventTable->getTable() . ' ORDER BY `name` ASC');


/*----- action -----*/
$eventTable->getRequest();

/*----- set entity id for pagination -----*/
$eventTable->setStartPosition();


if ($eventTable->isAction()) {

    switch ($eventTable->getAction()) {
        case ActionType::TOGGLESTATUS->value:
            $eventTable->updateStatus();
            break;
        case ActionType::DELETE->value:
            $eventTable->deleteEntity();
            break;
        case ActionType::ADD->value:
            $eventTable->addEntity();
        case ActionType::EDIT->value:
            $eventTable->editEntity();
        default:
            break;
    }

}

$eventTable->setList('EventsList');

/*----- UI -----*/
$eventTable->addHoverEffect();


/*----- column sortable -----*/
$eventTable->list->setColumnSortable('id', 'asc');
$eventTable->list->setColumnSortable('name', 'asc');
$eventTable->list->setColumnSortable('description', 'asc');
$eventTable->list->setColumnSortable('isActive', 'asc');

/*----- add create, edit column -----*/
$eventTable->addCreateEditColumn();

/*----- add action column -----*/
$eventTable->addActionColumn();

/*----- modify isActive column -----*/
$eventTable->modifyIsActiveColumn();

/*----- set  column labels -----*/
$eventTable->list->setColumnLabel('id', "ID");
$eventTable->list->setColumnLabel('name', "Name");
$eventTable->list->setColumnLabel('description', "Description");
$eventTable->list->setColumnLabel('isActive', "Status");
$eventTable->list->setColumnLabel('func', "Action");


/*----- output -----*/

// no rows message
$eventTable->list->setNoRowsMessage("no rows");

$eventTable->show();
