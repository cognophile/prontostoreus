
<?php
use Migrations\AbstractMigration;

class CreateApplicationsConfirmationsTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $tableExists = $this->hasTable('confirmations');
        $applicationsExists = $this->hasTable('applications');

        if (!$tableExists && $applicationsExists) {
            $table = $this->table('confirmations');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('application_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('application_id', 'applications', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('accepted', 'boolean', ['null' => false, 'default' => null])
                ->addColumn('date_accepted', 'datetime', ['null' => true, 'default' => null]);

            $table->create();
        }
    }

    public function down()
    {
        $tableExists = $this->hasTable('confirmations');

        if ($tableExists) {
            $this->dropTable('confirmations');
        }
    }
}