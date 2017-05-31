<?php

namespace Local\Simplestats\Tables;

use Bolt\Storage\Database\Schema\Table\BaseTable;

class LogTable extends BaseTable
{
    /**
     * {@inheritdoc}
     */
    protected function addColumns()
    {
        $this->table->addColumn('id',           'integer',  ['autoincrement' => true]);
        $this->table->addColumn('timestamp',    'datetime', ['notnull' => true]);
        $this->table->addColumn('ip',           'string',   ['length' => 32]);
        $this->table->addColumn('browseragent', 'string',   ['length' => 256, 'notnull' => false, 'default' => null]);
        $this->table->addColumn('route',        'string',   ['length' => 64, 'notnull' => false, 'default' => null]);
        $this->table->addColumn('uri',          'string',   ['length' => 256, 'notnull' => false, 'default' => null]);
        $this->table->addColumn('query',        'string',   ['length' => 256, 'notnull' => false, 'default' => null]);
        $this->table->addColumn('referrer',     'string',   ['length' => 1024, 'notnull' => false, 'default' => null]);
        $this->table->addColumn('aggregated',   'integer',  ['default' => 0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function addIndexes()
    {
        $this->table->addIndex(['id']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setPrimaryKey()
    {
        $this->table->setPrimaryKey(['id']);
    }
}