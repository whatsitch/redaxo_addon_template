<?php

$eventTable = new EventTable('events', 'Events');

$eventTable->setTable('events');

$eventTable->setListName('EventsList');

$eventTable->setRowsPerPage(3);

$eventTable->setSqlSelect("SELECT * FROM " . $eventTable->getTable() . ' ORDER BY `name` ASC');

/*----- get request data -----*/
$eventTable->getRequest();

/*----- set entity id for pagination -----*/
$eventTable->setStartPosition();

/*----- actions -----*/
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
            exit;
        case ActionType::EDIT->value:
            $eventTable->editEntity();
            exit;
        default:
            break;
    }
}

/*----- create new rex_list instance -----*/
$eventTable->setList();

/*----- UI options -----*/
$eventTable->addHoverEffect();

/*----- add create, edit column -----*/
$eventTable->addCreateEditColumn();

/*----- add action column -----*/
$eventTable->addActionColumn();

/*----- modify isActive column -----*/
$eventTable->modifyIsActiveColumn();

/*----- set  column labels -----*/
$eventTable->setColumnLabels();

/*----- column sortable -----*/
$eventTable->setColumnSortable();

/*----- output -----*/

// no rows message
$eventTable->list->setNoRowsMessage("no rows");

$eventTable->show();
