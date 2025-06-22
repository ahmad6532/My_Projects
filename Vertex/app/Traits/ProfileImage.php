<?php


namespace App\Traits;

trait ProfileImage {
    public function imgFunc($image, $gender)  {
        $imagePath = storage_path('app/' . $image);
        if (file_exists($imagePath) && !empty($image)) {
            $image = url('api/storage/app/' . $image);
        } else {
            if ($gender === 'M') {
                $image = url('api/storage/app/default/male.png');
            } else {
                $image = url('api/storage/app/default/female.png');
            }
        }
        return $image;
    }
}
