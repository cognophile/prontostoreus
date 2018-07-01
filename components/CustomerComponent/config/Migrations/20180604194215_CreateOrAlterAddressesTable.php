<?php
use Migrations\AbstractMigration;

class CreateOrAlterAddressesTable extends AbstractMigration
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
    
              // * FK Delete has no action as we want to preserve these links, should a customer ever need to view past storage applications as a future requirement. 
              ->addColumn('company_id', 'integer', ['null' => true, 'default' => null])
                ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

              ->addColumn('line_one', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('line_two', 'string', ['null' => true, 'default' => 'null', 'limit' => 128])
              ->addColumn('town', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('county', 'string', ['null' => false, 'default' => 'null', 'limit' => 128])
              ->addColumn('postcode', 'string', ['null' => false, 'default' => 'null', 'limit' => 8])
              ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('deleted', 'boolean', ['null' => false, 'default' => 0])

              ->addColumn('customer_id', 'integer', ['null' => true, 'default' => null])
                ->addForeignKey('customer_id', 'customers', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']);
    
            $table->create();
        }
        else 
        {
            $table = $this->table('addresses');
            $columnExists = $table->hasColumn('customer_id');

            if (!$columnExists)
            {
                $table->addColumn('customer_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('customer_id', 'customers', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                        ->save();
            }
        }
    }

    public function down()
    {
        // * If column exists, drop it. 
        $exists = $this->hasTable('addresses');

        if ($exists)
        {
            $table = $this->table('addresses');
            $columnExists = $table->hasColumn('customer_id');
            $constraintExists = $table->hasForeignKey('customer_id');

            if ($constraintExists) 
            {
                $table->dropForeignKey('customer_id')
                    ->save();
            }

            if ($columnExists)
            {
                $table->removeColumn('customer_id')
                    ->save();
            }
        }
    }
}
