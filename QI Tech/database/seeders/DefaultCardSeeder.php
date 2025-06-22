<?php

namespace Database\Seeders;

use App\Models\DefaultCard;
use App\Models\DefaultCardField;
use App\Models\DefaultField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // $card = new DefaultCard();
        // $card->type = "Patient";
        // $card->save();
        $fields = [
            ['field_name' => 'Title', 'db_field_name' => 'title'],
            ['field_name' => 'First Name','db_field_name' => 'first_name'],
            ['field_name' => 'Middle Name','db_field_name' => 'middle_name'],
            ['field_name' => 'Last Name','db_field_name' => 'last_name'],
            ['field_name' => 'Address','db_field_name' => 'address'],
            ['field_name' => 'Telephone No.','db_field_name' => 'telephone_no'],
            ['field_name' => 'Email Address','db_field_name' => 'email_address'],
            ['field_name' => 'NHS Number','db_field_name' => 'nhs_number'],
            ['field_name' => 'Gender','db_field_name' => 'gender'],
        // ];
        // DefaultCardField::insert($fields);
       
        // $card = new DefaultCard();
        // $card->type = "Prescriber";
        // $card->save();
        // $fields = [
            ['field_name' => 'Prescriber Type (this can be doctor/nurse/pharmacist etc)','db_field_name' => 'prescriber'],
            ['field_name' => 'Sur Name','db_field_name' => 'sur_name'],
            ['field_name' => 'Building field','db_field_name' => 'building_field'],
            ['field_name' => 'Registration No.','db_field_name' => 'registration_no'],
            ['field_name' => 'Practice name','db_field_name' => 'practice_name'],
            ['field_name' => 'Practice address','db_field_name' => 'practice_address'],
        ];
        DefaultField::insert($fields);

    }
}
