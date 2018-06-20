<?php
use Migrations\AbstractMigration;

class AlterApplicationToIncludeConfirmation extends AbstractMigration
{
    public function up()
    {
        $tableExists = $this->hasTable('applications');

        if ($tableExists) {
            $table = $this->table('applications');

            $termsExists = $table->hasColumn('terms_accepted');
            $termsUpdatedExists = $table->hasColumn('terms_updated');

            if (!$termsExists) {
                $table->addColumn('terms_accepted', 'boolean', ['null' => false, 'default' => 0])
                    ->update();
            }

            if (!$termsUpdatedExists) {
                $table->addColumn('terms_updated', 'datetime', ['null' => true, 'default' => null])
                    ->update();
            }
        }
    }

    public function down()
    {
        $tableExists = $this->hasTable('applications');

        if ($tableExists) {
            $table = $this->table('applications');

            $termsExists = $table->hasColumn('terms_accepted');
            $termsUpdatedExists = $table->hasColumn('terms_updated');

            if ($termsExists) {
                $table->removeColumn('terms_accepted')
                    ->save();
            }

            if ($termsUpdatedExists) {
                $table->removeColumn('terms_updated')
                    ->save();
            }
        }
    }
}
