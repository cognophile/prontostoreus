<?php
use Migrations\AbstractMigration;

class CreateAddressTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $table = $this->table('addresses');

        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
        ])
              ->addPrimaryKey('id')
              ->addColumn('line_one', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('line_two', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('town', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('county', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('postcode', 'string', ['null' => false, 'default' => 'null', 'limit' => 8]);

        $table->create();
    }

    public function down()
    {
        if ($this->hasTable('addresses'))
        {
            $this->dropTable('addresses');
        }
    }
}
