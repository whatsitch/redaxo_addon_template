<?php
enum ActionType: string
{
    case TOGGLESTATUS = "togglestatus";
    case ADD = "add";
    case EDIT = "edit";
    case DELETE = "delete";
}

class TableManager
{
    private string $table;
    private string $tableHeader;
    private string $sqlSelect;

    protected string $listName;
    protected string $startPosition;
    protected string $action;
    protected int $entityId;
    protected int $oldStatus;
    protected Addon $addon;

    public rex_list $list;

    public function __construct($listName, $tableHeader)
    {
        $this->addon = Addon::getInstance();
        $this->listName = $listName;
        $this->tableHeader = $tableHeader;
    }


    public function setTable(string $tableName)
    {
        $this->table = rex::getTable($this->addon::getPackageName() . '_' . $tableName);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setSqlSelect(string $sql)
    {
        $this->sqlSelect = $sql;
    }

    public function setList(string $name)
    {
        $this->list = rex_list::Factory($this->sqlSelect, 30, $name, false);
        $this->listName = $name;
    }

    public function setStartPosition()
    {
        $this->startPosition = rex_request('start', 'int', -1);
        if ($this->startPosition == -1) {
            $this->startPosition = rex_request($this->listName, 'int', 0);
        }
        $this->addon->rex_addon->setProperty('list_start', $this->startPosition);
    }

    public function getRequest()
    {
        $this->setAction();
        $this->setEntityId();
        $this->setOldStatus();

    }

    public function getAction(): string
    {
        return $this->action;
    }
    public function isAction(): bool
    {
        return ActionType::tryFrom($this->action) != NULL;
    }

    public function addCreateEditColumn()
    {

        $createIcon = '<a href="' . $this->list->getUrl(['func' => 'add']) . '"' . rex::getAccesskey('title1?', 'add') . ' title="title2? "><i class="rex-icon rex-icon-add"></i></a>';

        $this->list->addColumn($createIcon, ListManager::$modifyIcon, 0, [ListManager::$rexTableIcon, ListManager::$rexTableIcon]);
        $this->list->setColumnParams($createIcon, ['func' => 'edit', 'id' => '###id###', 'start' => $this->startPosition]);
    }

    public function addActionColumn()
    {
        $this->list->addColumn('func', '', -1, ['<th>###VALUE###</th>', '<td nowrap="nowrap">###VALUE###</td >']);
        $addon = $this->addon;
        $this->list->setColumnFormat('func', 'custom', static function ($params) use ($addon) {
            $start = $addon->rex_addon->getProperty('list_start');
            $list = $params['list'];
            $list->setColumnParams('delete', ['func' => 'delete', 'id' => '###id###', 'start' => $start]);
            $list->addLinkAttribute('delete', 'data-confirm', '[###name### ###description###] - bestätigen');
            return $list->getColumnLink('delete', ListManager::$rexIconDelete . 'löschen');
        });
    }

    public function addHoverEffect()
    {
        $this->list->addTableAttribute('class', 'table-striped table-hover');
    }

    public function deleteEntity()
    {
        $sql = rex_sql::factory();
        $sql->setDebug(false);

        $sql->setTable($this->getTable());
        $sql->setWhere(['id' => $this->entityId]);
        $sql->delete();

        if (!$sql->hasError()) {
            echo rex_view::success("Erfolgreich gelöscht");
        } else {
            echo rex_view::error("Fehler");
            dump($sql->getError()); // Fehlerinformationen ausgeben
        }
        $this->action = '';
    }

    public function addEntity()
    {
        throw new Error("method not implemented");
    }

    public function editEntity()
    {
        throw new Error("method not implemented");
    }

    public function show()
    {
        $fragment = new rex_fragment();
        $fragment->setVar('title', $this->tableHeader);
        $fragment->setVar('content', $this->list->get(), false);
        echo $fragment->parse('core/page/section.php');
    }

    private function setAction()
    {
        $this->action = rex_request('func', 'string', '');
    }

    private function setEntityId()
    {
        $this->entityId = rex_request('id', 'int', -1);
    }

    private function setOldStatus()
    {
        $this->oldStatus = rex_request('oldstatus', 'int', -1);
    }

    protected function getOldStatus(): int
    {
        return $this->oldStatus;
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