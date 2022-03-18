<?php

class TableManager
{
    private string $table;
    private string $tableHeader;
    private string $sqlSelect;

    protected string $startPosition;
    protected string $listName;
    protected int $rowsPerPage;
    protected string $sortType;
    protected string $sort;
    protected int $oldStatus;
    protected string $action;
    protected int $entityId;
    protected Addon $addon;

    public rex_list $list;

    public function __construct()
    {
        $this->addon = Addon::getInstance();
    }

    /**
     *
     * @param string $tableName sql table name without package name
     * @param string $tableHeader UI table header name
     * @return void
     */
    public function setTable(string $tableName, string $tableHeader): void
    {
        $this->table = rex::getTable($this->addon::getPackageName() . '_' . $tableName);
        $this->tableHeader = $tableHeader;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setRowsPerPage(int $rows = 30): void
    {
        $this->rowsPerPage = $rows;
    }

    public function setSqlSelect(string $sql): void
    {
        $this->sqlSelect = $sql;
    }

    public function setListName(string $name): void
    {
        $this->listName = $name;
    }

    public function setList(): void
    {
        $this->list = rex_list::Factory($this->sqlSelect, $this->rowsPerPage, $this->listName, false);
    }

    public function setStartPosition(): void
    {
        // parameter is either 'start' or 'ListName_start';
        $this->startPosition = rex_request('start', 'int', -1);
        if ($this->startPosition == -1) {
            $this->startPosition = rex_request($this->listName . '_start', 'int', 0);
        }
    }

    public function getRequest(): void
    {
        $this->setAction();
        $this->setEntityId();
        $this->setOldStatus();
        $this->setSort();
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function isAction(): bool
    {
        return ActionType::tryFrom($this->action) != NULL;
    }

    public function addCreateEditColumn(): void
    {
        $createIcon = '<a href="' . $this->list->getUrl(['func' => ActionType::ADD->value]) . '"' . rex::getAccesskey('add', ActionType::ADD->value) . ListManager::$rexIconAdd . '</a>';

        $this->list->addColumn($createIcon, ListManager::$rexIconEdit, 0, [ListManager::$rexTableIcon, ListManager::$rexTableIcon]);

        $this->list->setColumnParams($createIcon, ['func' => ActionType::EDIT->value, 'id' => ListManager::$idPlaceholder, 'start' => $this->startPosition]);
    }

    public function addActionColumn(): void
    {
        $this->list->addColumn('func', '', -1, ['<th>' . ListManager::$valuePlaceholder . '</th>', '<td nowrap="nowrap">' . ListManager::$valuePlaceholder . '</td >']);

        $this->list->setColumnFormat('func', 'custom', function () {
            $this->list->setColumnParams('delete', ['func' => ActionType::DELETE->value, 'id' => ListManager::$idPlaceholder, 'start' => $this->startPosition]);
            $this->list->addLinkAttribute('delete', 'data-confirm', '['.ListManager::$namePlaceholder .' ' . ListManager::$descriptionPlaceholder. '] - confirm');
            return $this->list->getColumnLink('delete', ListManager::$rexIconDelete . 'delete');
        });
    }

    public function addHoverEffect(): void
    {
        $this->list->addTableAttribute('class', 'table-striped table-hover');
    }

    public function deleteEntity(): void
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

    public function showList()
    {
        $fragment = new rex_fragment();
        $fragment->setVar('title', $this->tableHeader);
        $fragment->setVar('content', $this->list->get(), false);
        echo $fragment->parse('core/page/section.php');
    }

    public function addEntity()
    {
        throw new Error("method not implemented");
    }

    public function editEntity()
    {
        throw new Error("method not implemented");
    }

    public function setColumnSortable()
    {
        throw new Error("method not implemented");
    }

    public function setColumnLabels()
    {
        throw new Error("method not implemented");
    }

    private function setAction(): void
    {
        $this->action = rex_request('func', 'string', '');
    }

    private function setEntityId(): void
    {
        $this->entityId = rex_request('id', 'int', -1);
    }

    private function setSort(): void
    {
        $this->sort = rex_request('sort', 'string', '');
        $this->sortType = rex_request('sorttype', 'string', '');
    }

    private function setOldStatus(): void
    {
        $this->oldStatus = rex_request('oldstatus', 'int', -1);
    }

    protected function getOldStatus(): int
    {
        return $this->oldStatus;
    }


}