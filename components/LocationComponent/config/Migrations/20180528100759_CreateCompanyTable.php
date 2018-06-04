<?php
use Migrations\AbstractMigration;

class CreateCompanyTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('companies');

        if (!$exists)
        {
            $table = $this->table('companies');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('name', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
                ->addColumn('description', 'string', ['null' => false, 'default' => 'null', 'limit' => 256])
                ->addColumn('email', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
                ->addColumn('telephone', 'string', ['null' => false, 'default' => 'null', 'limit' => 12])
                ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('deleted', 'boolean', ['null' => false, 'default' => 0]);

            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('companies')) {
            $this->dropTable('companies');
        }
    }
}
