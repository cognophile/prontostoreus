<?php
use Migrations\AbstractMigration;

class BeforeInsertTriggerForNonNullCustomerCompanyConstraint extends AbstractMigration
{
    public function up()
    {
        $customerExists = $this->hasTable('customers');
        $companyExists = $this->hasTable('companies');
        $addressesExists = $this->hasTable('addresses');

        if ($companyExists && $customerExists && $addressesExists)
        {
            $this->execute("DROP TRIGGER IF EXISTS InsertCompanyCustomerNonNullConstraint");

            $this->execute(
                "CREATE TRIGGER InsertCompanyCustomerNonNullConstraint BEFORE INSERT ON addresses
                FOR EACH ROW 
                BEGIN
                    IF (NEW.company_id IS NULL AND NEW.customer_id IS NULL) THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'An address must be related to a company OR a customer';
                    END IF;
                    IF (NEW.company_id IS NOT NULL AND NEW.customer_id IS NOT NULL) THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'An address cannot be linked to a company AND a customer';
                    END IF;
                END"
            );
        }
    }

    public function down()
    {  
        $this->execute("DROP TRIGGER IF EXISTS InsertCompanyCustomerNonNullConstraint");
    }
}
