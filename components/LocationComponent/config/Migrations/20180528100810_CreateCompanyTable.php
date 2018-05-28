<?php
use Migrations\AbstractMigration;

class CreateCompanyTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $table = $this->table('companies');

        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
        ])
              ->addPrimaryKey('id')
              ->addColumn('address_id', 'integer')
                ->addForeignKey('address_id', 'addresses', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addColumn('name', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('description', 'string', ['null' => false, 'default' => 'null', 'limit' => 256])
              ->addColumn('email', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('telephone', 'string', ['null' => false, 'default' => 'null', 'limit' => 12]);

        $table->create();
    }

    public function down()
    {
        if ($this->hasTable('companies'))
        {
            $this->dropTable('companies');
        }
    }
}
