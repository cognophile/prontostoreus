<?php
use Migrations\AbstractMigration;

class CreateCustomerTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('customers');

        if (!$exists)
        {
            $table = $this->table('customers');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('title', 'string', ['null' => false, 'default' => 'null', 'limit' => 4])
                ->addColumn('firstname', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
                ->addColumn('surname', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
                ->addColumn('dob', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('email', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
                ->addColumn('telephone', 'string', ['null' => false, 'default' => 'null', 'limit' => 12])
                ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('deleted', 'boolean', ['null' => false, 'default' => 0]);

            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('customers')) {
            $this->dropTable('customers');
        }
    }
}
