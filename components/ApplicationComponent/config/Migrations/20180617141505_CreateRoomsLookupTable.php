<?php
use Migrations\AbstractMigration;

class CreateRoomsLookupTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('rooms');

        if (!$exists)
        {
            $table = $this->table('rooms');
            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('description', 'string', ['null' => false, 'default' => null]);
            $table->create();

            $dataSeed = [
                [
                    'id' => 1,
                    'description' => 'Living room'
                ],
                [
                    'id' => 2,
                    'description' => 'Kitchen'    
                ],
                [
                    'id' => 3,
                    'description' => 'Bedroom'
                ],
                [
                    'id' => 4,
                    'description' => 'Office'
                ],
                [
                    'id' => 5,
                    'description' => 'Garage'
                ],
                [
                    'id' => 6,
                    'description' => 'Cupboard'
                ],
                [
                    'id' => 7,
                    'description' => 'Specialist'
                ]
            ];

            $table->insert($dataSeed);
            $table->saveData();
        }
    }

    public function down()
    {
        if ($this->hasTable('rooms')) {
            $this->dropTable('rooms');
        }
    }
}
