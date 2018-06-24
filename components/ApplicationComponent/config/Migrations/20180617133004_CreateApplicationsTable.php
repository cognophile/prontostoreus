<?php
use Migrations\AbstractMigration;

class CreateApplicationsTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $companiesExists = $this->hasTable('companies');
        $customersExists = $this->hasTable('customers');
        $applicationsExists = $this->hasTable('applications');

        if ($companiesExists && $customersExists && !$applicationsExists)
        {
            $table = $this->table('applications');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('customer_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('customer_id', 'customers', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('company_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('delivery', 'boolean', ['null' => false, 'default' => 0])
                ->addColumn('start_date', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('end_date', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('total_cost', 'decimal', ['null' => false, 'precision' => 2, 'default' => 0.00, 'signed' => false])
                ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('cancelled', 'boolean', ['null' => false, 'default' => 0]);

            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('applications')) {
            $this->execute("SET FOREIGN_KEY_CHECKS=0");
            $this->dropTable('applications');
            $this->execute("SET FOREIGN_KEY_CHECKS=1");
        }
    }
}
