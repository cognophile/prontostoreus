<?php
use Migrations\AbstractMigration;

class AlterApplicationToIncludeConfirmation extends AbstractMigration
{
    public function up()
    {
        $tableExists = $this->hasTable('applications');

        if ($tableExists) {
            $table = $this->table('applications');

            $columnExists = $table->hasColumn('terms_and_conditions');

            if (!$columnExists) {
                $table->addColumn('terms_and_conditions', 'boolean', ['null' => false, 'default' => 0])
                    ->update();
            }
        }
    }

    public function down()
    {
        $tableExists = $this->hasTable('applications');

        if ($tableExists) {
            $table = $this->table('applications');

            $columnExists = $table->hasColumn('terms_and_conditions');

            if ($columnExists) {
                $table->removeColumn('terms_and_conditions')
                    ->save();
            }
        }
    }
}
