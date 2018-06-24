<?php
use Migrations\AbstractMigration;

class CreateApplicationLinesTable extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $furnishingsExists = $this->hasTable('furnishings');
        $ratesExists = $this->hasTable('company_furnishing_rates');
        $applicationsExists = $this->hasTable('applications');
        $applicationLinesExists = $this->hasTable('application_lines');

        if ($applicationsExists && $furnishingsExists && $ratesExists && !$applicationLinesExists)
        {
            $table = $this->table('application_lines');

            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('applicaition_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('applicaition_id', 'applications', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('furnishing_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('furnishing_id', 'furnishings', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('quantity', 'integer', ['null' => false, 'default' => 0, 'signed' => false])
                ->addColumn('line_cost', 'decimal', ['null' => false, 'default' => 0, 'precision' => 2, 'signed' => false]);

            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('application_lines')) {
            $this->dropTable('application_lines');
        }
    }
}
