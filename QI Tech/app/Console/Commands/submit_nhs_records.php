<?php

namespace App\Console\Commands;

use App\Models\Forms\LfpseSubmission;
use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Console\Command;

class submit_nhs_records extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sumbit_nhs_records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submits the NHS Records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $headOfficeCases = HeadOfficeCase::all();

        foreach ($headOfficeCases as $headOfficeCase) {
            $record = $headOfficeCase->link_case_with_form;
            if($record->record_id == null && !empty($record->json_submission) && empty($record->lfpse_deletes) && empty($record->all_linked_records() && empty($record->LfpseSubmissions))){
                $form_request = $record->get_filled_form();
                $form_request_array = json_decode(json_encode($form_request), true);
                
                $request_obj = LfpseSubmission::prepare_request($form_request_array);
                $request_json = json_encode($request_obj);
                $result = LfpseSubmission::submit_request_bulk($record, $request_json);
                dd('asofsjfa',$result);
            }
        }
        
        dd($headOfficeCases);
    }
}
