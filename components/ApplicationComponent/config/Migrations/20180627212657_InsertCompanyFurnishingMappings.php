<?php
use Migrations\AbstractMigration;

class InsertCompanyFurnishingMappings extends AbstractMigration
{
    public function up()
    {
        $mappingTableExists = $this->hasTable('company_furnishing_rates');
        $furnishingsExists = $this->hasTable('furnishings');
        $companiesExists = $this->hasTable('companies');

        if ($companiesExists && $furnishingsExists && $mappingTableExists) {
            $companiesTable = $this->table('companies');
            $furnishingsTable = $this->table('furnishings');
            $mappingsTable = $this->table('company_furnishing_rates');

            $companies = $this->fetchAll('SELECT * FROM companies');
            $furnishings = $this->fetchAll('SELECT * FROM furnishings');

            $data = [];

            if ($companies && $furnishings) {
                foreach ($companies as $company) {
                    foreach ($furnishings as $furnishing) {
                        $randomCost = mt_rand (0, 1000) / 10;
                        $row = [
                            'company_id' => $company['id'],
                            'furnishing_id' => $furnishing['id'],
                            'cost' => number_format((double)$randomCost, 2, '.', '')
                        ];
    
                        $mappingsTable->insert($row)->save();
                    }
                }
            }
        }
    }

    public function down()
    {
        if ($this->hasTable('company_furnishing_rates')) {
            $this->execute('DELETE FROM company_furnishing_rates');
        }
    }
}
