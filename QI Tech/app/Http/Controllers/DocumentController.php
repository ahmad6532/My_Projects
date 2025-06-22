<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function view($id = null){
        $id = explode('.', $id);
        $id = array_shift($id);
        $file =  Document::where('unique_id',$id)->first();
       
        if(!$file){
            abort(404);
        }
        $path = storage_path('app/'.$file->path($file->folder).'/'.$file->file_name);
        $mime = mime_content_type($path);
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
        ];
        
        //dd( $headers );
        return response()->file($path,$headers);
    }
    public function new_view($id = null)
{
    // Extract the unique ID and fetch the document from the database
    $id = explode('.', $id);
    $id = array_shift($id);
    $file = Document::where('unique_id', $id)->first();

    if (!$file) {
        abort(404); // File not found
    }

    // Determine the file path
    $path = storage_path('app/'.$file->path($file->folder).'/'.$file->file_name);
    $mime = mime_content_type($path);

    // Check the file extension or MIME type to decide how to display it
    $fileExtension = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));

    return view('file_preview', [
        'file' => $file,
        'path' => route('headoffice.view.attachment',['id' => $file->unique_id]),
        'fileExtension' => $fileExtension
    ]);
}

    public function remove_item($id = null)
    {
        $file =  Document::where('unique_id',$id)->first();
        $path = storage_path('app/'.$file->path($file->folder).'/'.$file->file_name);
        if(unlink($path))
        {
            return back()->with('success_message','Document removed successfully');
        }
        
        return back()->with('error','Some error occured'); 

    }
    public function get($id = null){

        $file =  Document::where('unique_id',$id)->first();
        if(!$file){
            abort(404);
        }
        $path = storage_path('app/'.$file->path($file->folder).'/'.$file->file_name);
        $mime = mime_content_type($path);
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
        ];
        return response()->file($path,$headers);

    }
    public function upload(Request $request){
        $request->validate([
            'file' => 'required',
        ]);
       $name =  time().'_'.$request->file->getClientOriginalName();
       $unwanted_files = array('.php','.exe','php5');
       foreach($unwanted_files as $unwatned){
        if(Str::contains(strtolower($name),$unwatned)){
            abort(403, 'Unauthorized action.');
        }
       }
        
       $file = new Document();
       $file->file_name = $name;
       $file->folder = '';
        if ($request->type == 'national_alerts') {
            $file->folder = 'national_alerts';
        }
       $filePath = $request->file('file')->storeAs($file->path( $file->folder ), $name);
       $file->save();
       return response()->json(array('file_name' => $file->file_name, 'id' => $file->id));
    }
    public function uploadHashed(Request $request){
        $request->validate([
            'file' => 'required',
        ]);
       $name =  time().'_'.$request->file->getClientOriginalName();
       $unwanted_files = array('.php','.exe','.php5', '.html', '.bat', '.cmd', '.sh');
       foreach($unwanted_files as $unwatned){
        if(Str::contains(strtolower($name),$unwatned)){
            abort(403, 'Unauthorized action.');
        }
       }
        
       $file = new Document();
       $file->file_name = $name;
       $file->folder = '';
        if ($request->type == 'national_alerts') {
            $file->folder = 'national_alerts';
        }elseif($request->type == 'case_manager'){
            $file->folder = 'case_manager';
        }else{
            $file->folder = 'other';
        }
       $filePath = $request->file('file')->storeAs($file->path( $file->folder ), $name);
       $file->save();
       
       $route = trim(route('view.remove_item',$file->unique_id));
       return response()->json(array('file_name' => $file->file_name, 'id' => $file->unique_id,'route' => $route));
    }

    public function uploadHashedAudio(Request $request){
        $request->validate([
            'audioFiles.*' => 'required',
        ]);
       $name =  time().'_'.$request->file('audioFiles')[0]->getClientOriginalName();
       $unwanted_files = array('.php','.exe','.php5', '.html', '.bat', '.cmd', '.sh');
       foreach($unwanted_files as $unwatned){
        if(Str::contains(strtolower($name),$unwatned)){
            abort(403, 'Unauthorized action.');
        }
       }
        
       $file = new Document();
       $file->file_name = $name;
       $file->folder = '';
        if ($request->type == 'national_alerts') {
            $file->folder = 'national_alerts';
        }elseif($request->type == 'case_manager'){
            $file->folder = 'case_manager';
        }else{
            $file->folder = 'other';
        }
       $filePath = $request->file('audioFiles')[0]->storeAs($file->path( $file->folder ), $name);
       $file->save();
       
       $route = trim(route('view.remove_item',$file->unique_id));
       return response()->json(array('file_name' => $file->file_name, 'id' => $file->unique_id,'route' => $route));
    }
    public function download(Request $request){

    }
    public function removedHashed(request $request)
    {
        $document = Document::where('unique_id',$request->hashed)->first();
        if($document)
        {
            $document->delete();
            return true;
        }
        return false;
        
    }
}
