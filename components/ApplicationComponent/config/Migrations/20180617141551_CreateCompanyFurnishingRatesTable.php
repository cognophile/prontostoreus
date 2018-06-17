<?php
use Migrations\AbstractMigration;

class CreateCompanyFurnishingRatesTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $companiesExists = $this->hasTable('companies');
        $furnishingsExists = $this->hasTable('furnishings');
        $joinExists = $this->hasTable('company_furnishing_rates');

        if ($companiesExists && $furnishingsExists && !$joinExists)
        {
            $table = $this->table('company_furnishing_rates');
            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('company_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                ->addColumn('furnishing_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('furnishing_id', 'furnishings', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])

                ->addColumn('cost', 'string', ['null' => true, 'default' => null])
                ->addColumn('deleted', 'boolean', ['null' => true, 'default' => 0]);
                    
            $table->create();
        }
    }

    public function down()
    {
        if ($this->hasTable('company_furnishing_rates')) {
            $this->dropTable('company_furnishing_rates');
        }
    }
}
