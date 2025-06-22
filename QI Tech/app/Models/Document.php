<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Document extends Model
{
    use HasFactory;
    protected $dir = 'documents';

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            # 20 digit random number
            $model->unique_id = Str::random(20);;
        });
    }

    public function path($folder = ''){
        if ($folder){
            return $this->dir . '/' . $folder;
        }else{
            return $this->dir;
        }
    }
    public function original_file_name(){
        $parts = explode('_', $this->file_name);
        # Remove first string.
        $zero = array_shift($parts);
        return implode('',$parts);
    }
    public function extension(){
        $ext = explode('.',$this->file_name);
        return '.'.end($ext);
    }
    public function isImage(){
        $imageExtensions = Helper::imageFileExtensionsList();
        $extension = $this->extension();
        if(in_array(strtolower($extension),$imageExtensions)){
            return true;
        }
        return false;
    }
}
