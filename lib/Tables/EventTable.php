<?php

use JetBrains\PhpStorm\NoReturn;


class EventTable extends TableManager
{

    public function setColumnLabels()
    {
        $this->list->setColumnLabel('id', "ID");
        $this->list->setColumnLabel('name', "Name");
        $this->list->setColumnLabel('description', "Description");
        $this->list->setColumnLabel('isActive', "Status");
        $this->list->setColumnLabel('func', "Action");
    }

    public function setColumnSortable()
    {
        $this->list->setColumnSortable('id', 'asc');
        $this->list->setColumnSortable('name', 'asc');
        $this->list->setColumnSortable('description', 'asc');
        $this->list->setColumnSortable('isActive', 'asc');
    }

    public function modifyIsActiveColumn()
    {
        $this->list->setColumnFormat('isActive', 'custom', function ($params) {
            $list = $params['list'];
            $list->addLinkAttribute('status', 'class', 'toggle');
            if ($list->getValue('isActive') == 1) {
                $list->setColumnParams('isActive', ['func' => ActionType::TOGGLESTATUS->value, 'id' => ListManager::$idPlaceholder, 'oldstatus' => ListManager::$isActivePlaceholder, 'start' => $this->startPosition]);
                $string = $list->getColumnLink('isActive', '<span class="rex-online">' . ListManager::rexIconActive(true) . "active" . '</span>');
            } else {
                $list->setColumnParams('isActive', ['func' => ActionType::TOGGLESTATUS->value, 'id' => ListManager::$idPlaceholder, 'oldstatus' => ListManager::$isActivePlaceholder, 'start' => $this->startPosition]);
                $string = $list->getColumnLink('isActive', '<span class="rex-offline">' . ListManager::rexIconActive(false) . "inactive" . '</span>');
            }
            return $string;
        });
    }

    public function updateStatus()
    {
        $sql = rex_sql::factory();
        $sql->setDebug(false);

        $status = ($this->getOldStatus() === 1) ? 0 : 1;

        $sql->setTable($this->getTable());
        $sql->setWhere(['id' => $this->entityId]);
        $sql->setValue('isActive', $status);
        $sql->update();

        $this->action = '';
    }

    #[NoReturn] public function editEntity()
    {
        $this->setForm("Edit");
    }

    #[NoReturn] public function addEntity()
    {
        $this->setForm("Add");
    }

    private function setForm(string $title)
    {
        $form = form::factory($this->getTable(), "Dataset", 'id=' . $this->entityId, 'post', false);

        /*----- form params -----*/
        $form->addParam('id', $this->entityId);
        $form->addParam('sort', $this->sort);
        $form->addParam('sorttype', $this->sortType);
        $form->addParam('start', $this->startPosition);

        /*----- name field -----*/
        $field = $form->addTextField('name');
        $field->setLabel("label");
        $field->getValidator()->add('notEmpty', 'This is a required field');

        /*----- description field -----*/
        $field = $form->addTextField('description');
        $field->setLabel("description");

        /*----- status field -----*/
        $field = $form->addSelectField('isActive', $value = null, ['class' => 'form-control selectpicker']);
        $field->setLabel('Status');
        $select = $field->getSelect();
        $select->addOption('active', 1);
        $select->addOption('inactive', 0);

        /*----- output -----*/
        $content = $form->get();
        $fragment = new rex_fragment();
        $fragment->setVar('class', 'edit', false);
        $fragment->setVar('title', $title);
        $fragment->setVar('body', $content, false);
        $content = $fragment->parse('core/page/section.php');

        echo $content;
    }


}

