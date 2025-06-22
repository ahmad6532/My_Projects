<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\databases\Database;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
//use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Jobs\DatabaseUpdateJob;

class DatabaseController extends Controller
{
    private $database_columns=
        [
            "gphc_locations"=>
            [
                0 => "GPhCRegistrationNumber",
                1 => "OwnerName",
                2 => "TradingName",
                3 => "AddressLine1",
                4 => "AddressLine2",
                5 => "AddressLine3",
                6 => "Town",
                7 => "County",
                8 => "Postcode",
                9 => "Country",
                10 => "RegisteredInternetPharmacy",
                11 => "PrimarycareTrust",
                12 => "HasNoticesOrConditions",
                13 => "ExpiryDate",
                ],
            "gphc_technicians"=>
            [
                0 => "GPhCRegistrationNumber",
                1 => "Surname",
                2 => "Forenames",
                3 => "Town",
                4 => "Status",
                5 => "ExpiryDate",
                6 => "FitnessToPractiseIssues",
                ],
            "gphc_pharmacists"=>
            [
                0 => "GPhCRegistrationNumber",
                1 => "Surname",
                2 => "Forenames",
                3 => "Town",
                4 => "SupplementaryPrescriber",
                5 => "IndependentPrescriber",
                6 => "SuperintendentPharmacist",
                7 => "StatusDescription",
                8 => "ExpiryDate",
                9 => "FitnessToPractiseIssues",
                ],
            "psi_pharmacies"=>
            [
                0 => "Registration Number",
                1 => "Trading Name",
                2 => "Street 1",
                3 => "Street 2",
                4 => "Street 3",
                5 => "Town",
                6 => "County",
                7 => "RPB Owner",
                ],
            "dispensing_doctors"=>
            [
                0 => "SICBL Name",
                1 => "PracticeName",
                2 => "PracticeCode",
                3 => "Address1",
                4 => "Address2",
                5 => "Address3",
                6 => "Address4",
                7 => "PostCode",
                8 => "Total GP's",
                9 => "Dispensing GP's",
            ],
            "northern_ireland_list"=>
            [
                0 => "pharmacy registration number ",
                1 => "owner name",
                2 => "trading name",
                3 => "address line 1 ",
                4 => "address line 2",
                5 => "town",
                6 => "postcode",
                ]

        ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $databases = Database::paginate(10);
        return view('admin.database.index', compact('databases'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try
        {


//            throw new Exception();
            $file = $request->file('upload_database');

            $db=Database::findorFail($request->database_id);
           

        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv","xlsx","xls");

        // 2MB in Bytes
        $maxFileSize = 25 * 1024 * 1024;

        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {

            // Check file size
            if ($fileSize <= $maxFileSize) {




                $admin=Auth::guard('admin')->user();
                $file_temp_name=$admin->id.'_'.$db->id .'.'.$extension;
                $path=public_path('data_images/temp/database/');
                $uploaded_file=$path.$file_temp_name;

                $request->file('upload_database')->move($path,$file_temp_name );



                $header=DB::getSchemaBuilder()->getColumnListing($db->table_name);
                $header=array_combine($header, $header);
                unset($header['id']);

                $fe = new FastExcel();
                $fe->setCustomHeaders($header, [$db->id]);
                $records = $fe->import($uploaded_file)->toArray();
                $sheet_row = $fe->get_header_row($uploaded_file);


                $required_cols=$this->database_columns[$db->table_name];
                
                $result = $required_cols==$sheet_row->toArray();

                if(!$result){
                    return redirect()->back()->withErrors(['error'=>'Wrong Database File']);
                }

//
//                dd($sheet_row,$required_cols,$result,$records);
                // Savings records temporarily for job //
                $myfile = fopen(public_path('data_images/temp/database/') . "/" . $db->id . ".json", "w") or die("System Error. Failure to hold temporary data! Contact Support");
                $txt = json_encode($records);
                fwrite($myfile, $txt);
                fclose($myfile);

                DatabaseUpdateJob::dispatch($db);

                return redirect()->route('database.index')
                    ->with('success_message', 'Database will be updated soon.');



            }

        }

    }
    catch(Exception $exception){

            return back()->withErrors(['error'=>'unexpected error cuased' . $exception->getMessage()]);

    }

    }

   private function csv_to_array($filename,$db, $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header=DB::getSchemaBuilder()->getColumnListing($db->table_name);

        $header=array_combine($header, $header);
        unset($header['id']);
//        dd($arr);

//       dd($header);
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            $row = fgetcsv($handle, 1000, $delimiter);
//            dd($header,$row);
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                $row[]=$db->id;
//                dd(count($header),count($row));

                    if (!$header)
                        $header = $row;
                    else {
//                        dd(count($header),count($row));
                        $r = array_combine($header, $row);
                           $r['expiry_date'] = Carbon::createFromFormat("d/m/Y H:i:s", $r['expiry_date'] . ":00");
                        $data[] = $r;


                    }

            }
            fclose($handle);
        }

        return $data;
    }



}
