<?php 

namespace App\Traits\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    // store User Avatar
    public function uploadImage($avatar){
        try{
            return basename($avatar->store('public'));
        } catch (\Exception $e) {
            return response()->json(['response'=>['status'=>false,'message'=> $e->getMessage()]],JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
