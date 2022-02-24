<?php

use JetBrains\PhpStorm\NoReturn;

class EventTable extends TableManager
{

    public function modifyIsActiveColumn()
    {
        $addon = $this->addon;
        $this->list->setColumnFormat('isActive', 'custom', static function ($params) use ($addon) {
            $start = $addon->rex_addon->getProperty('list_start');
            $list = $params['list'];
            $list->addLinkAttribute('status', 'class', 'toggle');
            if ($list->getValue('isActive') == 1) {
                $list->setColumnParams('isActive', ['func' => ActionType::TOGGLESTATUS->value, 'id' => ListManager::$idPlaceholder, 'oldstatus' => ListManager::$isActivePlaceholder, 'start' => $start]);
                $string = $list->getColumnLink('isActive', '<span class="rex-online">' . ListManager::rexIconActive(true) . "active" . '</span>');
            } else {
                $list->setColumnParams('isActive', ['func' => ActionType::TOGGLESTATUS->value, 'id' => ListManager::$idPlaceholder, 'oldstatus' => ListManager::$isActivePlaceholder, 'start' => $start]);
                $string = $list->getColumnLink('isActive', '<span class="rex-offline">' . ListManager::rexIconActive(false)  . "inactive" . '</span>');
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
        exit;
    }

    #[NoReturn] public function addEntity()
    {
        $this->setForm("Add");
        exit;
    }

    private function setForm(string $title)
    {
        $form = form::factory($this->getTable(), "Dataset", 'id=' . rex_request('id', 'int', 0), 'post', false);

        $form->addParam('id', $this->entityId);

        $form->addParam('sort', rex_request('sort', 'string', ''));
        $form->addParam('sorttype', rex_request('sorttype', 'string', ''));
        $form->addParam('start', rex_request('start', 'int', 0));

        $field = $form->addTextField('name');
        $field->setLabel("label");
        $field->getValidator()->add('notEmpty', 'This is a required field');

        $field = $form->addTextField('description');
        $field->setLabel("description");

        $field = $form->addSelectField('isActive', $value = null, ['class' => 'form-control selectpicker']);
        $field->setLabel('Status');
        $select = $field->getSelect();
        $select->addOption('active', 1);
        $select->addOption('inactive', 0);

        $content = $form->get();

        $fragment = new rex_fragment();
        $fragment->setVar('class', 'edit', false);
        $fragment->setVar('title', $title);
        $fragment->setVar('body', $content, false);
        $content = $fragment->parse('core/page/section.php');

        echo $content;

    }



}

