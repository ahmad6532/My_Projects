<?php

namespace Database\Seeders;

use App\Models\SystemResponse;
use Illuminate\Database\Seeder;

class SystemResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['message' => 'You cannot apply more then remaining leaves.','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'Failed to create the CSV file.','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'PayRoll sheet downloaded successfully!','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'File not found!','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'Approval Levels Added Successfully...','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'First Change Status of All Pending Approvals of This Module','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'No Levels For This Record','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'Your Designation Not Found','created_at'=> now(), 'updated_at'=> now()],
            ['message' => 'Approval Setting Not Found','created_at'=> now(), 'updated_at'=> now()],
        ];
        SystemResponse::insert($data);
    }
}
