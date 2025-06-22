<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CompanyDocuments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyDocumentsController extends BaseController
{
    public function index()
    {
        $companyDocuments = CompanyDocuments::where('is_deleted','0')->get();
        if(count($companyDocuments) > 0){
            return $this->sendResponse($companyDocuments,'Company documents fetched successfully!',200);
        }else{
            return $this->sendResponse([],'Data not found!',404);
        }
    }

    public function saveCompanyDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'document_type' => 'required',
            'document_name' => 'required',
            'file' => 'required',
            'role_id' => 'required',
            'location' => 'required',
            'description' => 'required',
            'expiry_date' => 'required',
            'show_before_expire' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 500);
        }

        $roles = implode(',', $request->role_id);
        $companies = implode(',', $request->company_id);
        $branches = implode(',',$request->branch_id);

        $companyDocuments = new CompanyDocuments();
        $companyDocuments->company_id = $companies;
        $companyDocuments->branch_id = $branches;
        $companyDocuments->role_id = $roles;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->extension();
            $fileName = time() . '-' . $file->getClientOriginalName();
            $directory = public_path('assets/images/companies/company-documents/');
            $file->move($directory, $fileName);
            $companyDocuments->files = $fileName;
            $companyDocuments->document_extension = $extension;
        }
        $companyDocuments->document_name = $request->document_name;
        $companyDocuments->document_type = $request->document_type;
        $companyDocuments->location = $request->location;
        $companyDocuments->description = $request->description;
        
        $companyDocuments->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
        $companyDocuments->show_before_expire = Carbon::parse($request->show_before_expire)->format('Y-m-d');
        if($companyDocuments->save()){
            return $this->sendResponse($companyDocuments,'Company document save successfully!',200);
        }else{
            return $this->sendError([],'Form not submited',500);
        }
    }

    public function changeStatusCompanyDocument(Request $request)
    {
        $companyDocument = CompanyDocuments::where('id',$request->document_id)->where('is_deleted','0')->first();
        if($companyDocument){
            $companyDocument->status = $request->status;
            $updateStatus = $companyDocument->update();
            if($updateStatus){
                return $this->sendResponse($companyDocument,'Status update successfully!',200);
            }
        }else{
            return $this->sendResponse([],'Data not found!',404);
        }
    }

    public function deleteCompanyDocument(Request $request)
    {
        $companyDocument = CompanyDocuments::where('id',$request->document_id)->where('is_deleted','0')->first();
        if($companyDocument){
            $companyDocument->is_deleted = '1';
            $deleteDocument =$companyDocument->save();
            if($deleteDocument){
                return $this->sendResponse($companyDocument,'Document delete successfully!',200);
            }
        }else{
            return $this->sendResponse([],'Data not found!',404);
        }
    }

    public function editCompanyDocument(Request $request)
    {
        $companyDocument = CompanyDocuments::where('id',$request->document_id)->where('is_deleted','0')->first();
        if($companyDocument){
            return $this->sendResponse($companyDocument,'Company document fetched successfully!',200);
        }else{
            return $this->sendResponse([],'Data not found!',404);
        }
    }

    public function updateCompanyDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'document_type' => 'required',
            'document_name' => 'required',
            'file' => 'required',
            'role_id' => 'required',
            'location' => 'required',
            'description' => 'required',
            'expiry_date' => 'required',
            'show_before_expire' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 500);
        }

        $roles = implode(',', $request->role_id);
        $companies = implode(',', $request->company_id);
        $branches = implode(',', $request->branch_id);

        $companyDocuments =  CompanyDocuments::where('id', $request->document_id)->first();
        if ($companyDocuments) {
            $companyDocuments->company_id = $companies;
            $companyDocuments->branch_id = $branches;
            $companyDocuments->role_id = $roles;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->extension();
                $fileName = time() . '-' . $file->getClientOriginalName();
                $directory = public_path('assets/images/companies/company-documents/');
                $file->move($directory, $fileName);
                $companyDocuments->files = $fileName;
                $companyDocuments->document_extension = $extension;
            }
            $companyDocuments->document_name = $request->document_name;
            $companyDocuments->document_type = $request->document_type;
            $companyDocuments->location = $request->location;
            $companyDocuments->description = $request->description;

            $companyDocuments->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
            $companyDocuments->show_before_expire = Carbon::parse($request->show_before_expire)->format('Y-m-d');
            if($companyDocuments->save()){
                return $this->sendResponse($companyDocuments,'Company document update successfully!',200);
            }else{
                return $this->sendError([],'Form not submitted');
            }
        } else {
            return $this->sendResponse([], 'Data not found!', 404);
        }
    }
}
