<?php
use Migrations\AbstractMigration;
use Phinx\Util\Literal;

class CreateInvoicesTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('invoices');

        if (!$exists)
        {
            $table = $this->table('invoices');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('application_id', 'integer', ['null' => false, 'default' => null])
                    ->addForeignKey('application_id', 'applications', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                
                ->addColumn('reference', 'string', ['null' => false, 'default' => null])
                ->addColumn('subject', 'string', ['null' => false, 'default' => null])
                ->addColumn('issued', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('due', 'datetime', ['null' => false, 'default' => null])
                ->addColumn('paid', 'string', ['null' => false, 'default' => '0.00'])
                ->addColumn('total', 'string', ['null' => false, 'default' => '0.00'])
                ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('complete', 'boolean', ['null' => false, 'default' => 0]);

            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('invoices')) {
            $this->execute("SET FOREIGN_KEY_CHECKS=0");
            $this->dropTable('invoices');
            $this->execute("SET FOREIGN_KEY_CHECKS=1");
        }
    }
}
