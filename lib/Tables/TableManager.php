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
    protected int $rowsPerPage;
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

    public function setRowsPerPage(int $rows = 30) {
        $this->rowsPerPage = $rows;
    }

    public function setSqlSelect(string $sql)
    {
        $this->sqlSelect = $sql;
    }

    public function setListName(string $name)
    {
        $this->listName = $name;
    }

    public function setList()
    {
        $this->list = rex_list::Factory($this->sqlSelect, $this->rowsPerPage, $this->listName, true);
    }

    public function setStartPosition()
    {
        $this->startPosition = rex_request('start', 'int', -1);
        if ($this->startPosition == -1) {
            $this->startPosition = rex_request($this->listName . '_start', 'int', 0);
        }
        $this->addon->rex_addon->setProperty('list_start', $this->startPosition);

        var_dump($this->startPosition);

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

        $createIcon = '<a href="' . $this->list->getUrl(['func' => ActionType::ADD->value]) . '"' . rex::getAccesskey('add', ActionType::ADD->value) . ListManager::$rexIconAdd . '</a>';

        $this->list->addColumn($createIcon, ListManager::$modifyIcon, 0, [ListManager::$rexTableIcon, ListManager::$rexTableIcon]);

        $this->list->setColumnParams($createIcon, ['func' => ActionType::EDIT->value, 'id' => ListManager::$idPlaceholder, 'start' => $this->startPosition]);
    }

    public function addActionColumn()
    {
        $this->list->addColumn('func', '', -1, ['<th>'. ListManager::$valuePlaceholder . '</th>', '<td nowrap="nowrap">'. ListManager::$valuePlaceholder . '</td >']);
        $addon = $this->addon;
        $this->list->setColumnFormat('func', 'custom', static function ($params) use ($addon) {
            $start = $addon->rex_addon->getProperty('list_start');
            $list = $params['list'];
            $list->setColumnParams('delete', ['func' => ActionType::DELETE->value, 'id' => ListManager::$idPlaceholder, 'start' => $start]);
            $list->addLinkAttribute('delete', 'data-confirm', '[###name### ###description###] - confirm');
            return $list->getColumnLink('delete', ListManager::$rexIconDelete . 'delete');
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
            echo rex_view::success("deleted");
        } else {
            echo rex_view::error("error");
            dump($sql->getError()); // display debug error message
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