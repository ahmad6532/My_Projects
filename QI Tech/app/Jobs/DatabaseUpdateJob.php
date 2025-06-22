<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\databases\Database;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Support\Facades\File;
use Vtiful\Kernel\Excel;

class DatabaseUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $database;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $db = $this->database;

        if($db->in_process)
            throw new Exception("Job is already in process");

        try{
            $db->in_process = true;
            $db->save();
            // Logic to insert all records into database //
            DB::table($db->table_name)->truncate();
            $file_path = public_path('data_images/temp/database/') . "/" . $db->id . ".json";
            $records = File::get($file_path);//, "r") or throw new Exception("Either json file is missing or unable to open. Job Failed.");
            //$records = fread($myfile,filesize($file_path));
            //fclose($myfile);
            $records = json_decode($records, true);
            
            if(!$records)
            {
                throw new Exception('Json code was not parsed. File corrupted or missing');
            }

            $parts = array_chunk($records,500);
            $max = count($parts);
            foreach($parts as $ck => $p)
            {
                DB::table($db->table_name)->insert($p);
                $db->percentage = (($ck + 1) / $max) * 100;
                $db->save();
                
            }
            // we can monitor progress in some way depending on insert function //
            
            $db->in_process = false;
            $db->percentage = 100;
            $db->save();
        }
        catch(Exception $ex)
        {
            $db->in_process = false;
            $db->save();
            throw $ex;
        }

    }
}
