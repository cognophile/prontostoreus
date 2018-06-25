<?php
use Migrations\AbstractMigration;

class CreateFurnishingsLookupTable extends AbstractMigration
{
    public $autoId = false;
    
    public function up()
    {
        $exists = $this->hasTable('furnishings');

        if (!$exists)
        {
            $table = $this->table('furnishings');
            $table->addColumn('id', 'integer', [
                'autoIncrement' => true,
            ])
                ->addPrimaryKey('id')
                ->addColumn('description', 'string', ['null' => true, 'default' => null])
                ->addColumn('size', 'integer', ['null' => true, 'default' => 0])
                ->addColumn('weight', 'integer', ['null' => true, 'default' => 0])
                ->addColumn('room_id', 'integer', ['null' => true, 'default' => null])
                    ->addForeignKey('room_id', 'rooms', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']);
                    
            $table->create();

            $livingRoom = 1;
            $kitchen = 2;
            $bedroom = 3;
            $office = 4;
            $garage = 5;
            $cupboard = 6;
            $special = 7;

            $dataSeed = [
                // Living room
                ['description' => 'Sofa - 3 seat', 'size' => 45, 'weight' => 177, 'room_id' => $livingRoom], 
                ['description' => 'Sofa - 2 seat', 'size' => 35, 'weight' => 132, 'room_id' => $livingRoom], 
                ['description' => 'Armchair', 'size' => 25, 'weight' => 74, 'room_id' => $livingRoom], 
                ['description' => 'Sideboard', 'size' => 30, 'weight' => 81, 'room_id' => $livingRoom], 
                ['description' => 'Coffee table', 'size' => 10, 'weight' => 35, 'room_id' => $livingRoom], 
                ['description' => 'Glass cabinet', 'size' => 40, 'weight' => 89, 'room_id' => $livingRoom], 
                ['description' => 'Dining table', 'size' => 35, 'weight' => 79, 'room_id' => $livingRoom], 
                ['description' => 'Bureau', 'size' => 30, 'weight' => 74, 'room_id' => $livingRoom],
                ['description' => 'Bookcase', 'size' => 20, 'weight' => 148, 'room_id' => $livingRoom], 
                ['description' => 'Mirror', 'size' => 5, 'weight' => 11, 'room_id' => $livingRoom],
                ['description' => 'Grandfather clock', 'size' => 15, 'weight' => 44, 'room_id' => $livingRoom], 
                ['description' => 'Rug', 'size' => 20, 'weight' => 29, 'room_id' => $livingRoom],
                ['description' => 'Stereo', 'size' => 5, 'weight' => 29, 'room_id' => $livingRoom], 
                ['description' => 'TV', 'size' => 10, 'weight' => 74, 'room_id' => $livingRoom],
                ['description' => 'TV cabinet', 'size' => 10, 'weight' => 59, 'room_id' => $livingRoom], 
                ['description' => 'Picture', 'size' => 5, 'weight' => 6, 'room_id' => $livingRoom],
                ['description' => 'Lamp', 'size' => 5, 'weight' => 6, 'room_id' => $livingRoom],
                
                // Kitchen
                ['description' => 'Cooker', 'size' => 15, 'weight' => 59, 'room_id' => $kitchen], 
                ['description' => 'Microwave', 'size' => 5, 'weight' => 11, 'room_id' => $kitchen], 
                ['description' => 'Fridge freezer', 'size' => 20, 'weight' => 74, 'room_id' => $kitchen], 
                ['description' => 'Fridge', 'size' => 15, 'weight' => 74, 'room_id' => $kitchen], 
                ['description' => 'Freezer', 'size' => 15, 'weight' => 74, 'room_id' => $kitchen], 
                ['description' => 'Shelving unit', 'size' => 15, 'weight' => 59, 'room_id' => $kitchen],
                ['description' => 'Washing machine', 'size' => 15, 'weight' => 44, 'room_id' => $kitchen], 
                ['description' => 'Tumble dryer', 'size' => 15, 'weight' => 44, 'room_id' => $kitchen],
                ['description' => 'Dishwasher', 'size' => 15, 'weight' => 44, 'room_id' => $kitchen], 
                ['description' => 'Table', 'size' => 23, 'weight' => 110, 'room_id' => $kitchen], 
                ['description' => 'Chair', 'size' => 5, 'weight' => 18, 'room_id' => $kitchen], 
                ['description' => 'Stool', 'size' => 5, 'weight' => 8, 'room_id' => $kitchen], 
                ['description' => 'Ironing board', 'size' => 5, 'weight' => 8, 'room_id' => $kitchen], 
                ['description' => 'Bin', 'size' => 5, 'weight' => 10, 'room_id' => $kitchen],

                // Bedroom
                ['description' => 'Wardrobe', 'size' => 45, 'weight' => 132, 'room_id' => $bedroom], 
                ['description' => 'Drawers', 'size' => 15, 'weight' => 47, 'room_id' => $bedroom], 
                ['description' => 'Dressing table', 'size' => 25, 'weight' => 74, 'room_id' => $bedroom],
                ['description' => '3ft bed frame and mattress', 'size' => 30, 'weight' => 118, 'room_id' => $bedroom], 
                ['description' => '4ft6 bed frame and mattress', 'size' => 45, 'weight' => 117, 'room_id' => $bedroom], 
                ['description' => '5ft bed frame and mattress', 'size' => 60, 'weight' => 221, 'room_id' => $bedroom], 
                ['description' => '3ft divan bed and mattress', 'size' => 30, 'weight' => 59, 'room_id' => $bedroom], 
                ['description' => '4ft6 divan bed and mattress', 'size' => 50, 'weight' => 89, 'room_id' => $bedroom],
                ['description' => '5ft divan bed and mattress', 'size' => 65, 'weight' => 221, 'room_id' => $bedroom], 
                ['description' => 'Bunk beds', 'size' => 40, 'weight' => 177, 'room_id' => $bedroom],
                ['description' => 'Bedside table', 'size' => 10, 'weight' => 18, 'room_id' => $bedroom], 
                ['description' => 'Bedside lamp', 'size' => 5, 'weight' => 6, 'room_id' => $bedroom], 

                // Office
                ['description' => 'Computer', 'size' => 10, 'weight' => 20, 'room_id' => $office], 
                ['description' => 'Printer', 'size' => 5, 'weight' => 10, 'room_id' => $office], 
                ['description' => 'Desk', 'size' => 40, 'weight' => 112, 'room_id' => $office], 
                ['description' => 'Desk chair', 'size' => 10, 'weight' => 25, 'room_id' => $office],
                ['description' => 'Bookcase', 'size' => 20, 'weight' => 148, 'room_id' => $office], 

                // Garage
                ['description' => 'Wheelbarrow', 'size' => 15, 'weight' => 44, 'room_id' => $garage], 
                ['description' => 'Push mower', 'size' => 15, 'weight' => 59, 'room_id' => $garage], 
                ['description' => 'Ride-on mower', 'size' => 60, 'weight' => 221, 'room_id' => $garage], 
                ['description' => 'Ladder', 'size' => 15, 'weight' => 89, 'room_id' => $garage], 
                ['description' => 'BBQ', 'size' => 20, 'weight' => 44, 'room_id' => $garage], 
                ['description' => 'Strimmer', 'size' => 5, 'weight' => 15, 'room_id' => $garage], 
                ['description' => 'Bicycle', 'size' => 25, 'weight' => 35, 'room_id' => $garage], 
                ['description' => 'Garden furniture', 'size' => 40, 'weight' => 120, 'room_id' => $garage], 
                
                // Cupboard
                ['description' => 'Briefcase', 'size' => 5, 'weight' => 10, 'room_id' => $cupboard], 
                ['description' => 'Suitcase', 'size' => 10, 'weight' => 20, 'room_id' => $cupboard], 
                ['description' => 'Vacuum cleaner', 'size' => 5, 'weight' => 11, 'room_id' => $cupboard], 

                // Specialist 
                ['description' => 'Piano', 'size' => 40, 'weight' => 132, 'room_id' => $special], 
                ['description' => 'Grand piano', 'size' => 60, 'weight' => 206, 'room_id' => $special],
                ['description' => 'Piano stool', 'size' => 5, 'weight' => 11, 'room_id' => $special], 
                ['description' => 'Garden shed', 'size' => 120, 'weight' => 250, 'room_id' => $special]
            ];

            $table->insert($dataSeed);
            $table->saveData();
        }
    }

    public function down()
    {
        if ($this->hasTable('furnishings')) {
            $this->dropTable('furnishings');
        }
    }
}
