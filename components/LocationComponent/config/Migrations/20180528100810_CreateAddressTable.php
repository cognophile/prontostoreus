<?php
use Migrations\AbstractMigration;

class CreateAddressTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('addresses');

        if (!$exists)
        {
            $table = $this->table('addresses');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])->addPrimaryKey('id')
    
              ->addColumn('company_id', 'integer')
                ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
    
              ->addColumn('line_one', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('line_two', 'string', ['null' => true, 'default' => 'null', 'limit' => 128])
              ->addColumn('town', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('county', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('postcode', 'string', ['null' => false, 'default' => 'null', 'limit' => 8])
              ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('deleted', 'boolean', ['null' => false, 'default' => 0]);
    
            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('addresses')) {
            $this->dropTable('addresses');
        }
    }
}
