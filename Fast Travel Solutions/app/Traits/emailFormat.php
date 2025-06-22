<?php
namespace App\Http\Traits;
use App\Http\Models\Config;
use App\Models\Config as ModelsConfig;

trait emailFormat {

    public function emailStructure($header, $footer) {

        // return [$header,$footer];
        $config = ModelsConfig::all();
        $headerpatterns = [
            '/\{(logo)}]?/',
            '/(public_path)/'
        ];

        $headerreplacements = [
            asset('assets/images/app_icon/' . $config[27]['value'])
            // $config[27]['value']
        ];
        $headermail = preg_replace($headerpatterns, $headerreplacements, $header);

        $footerpatterns = [
            '/(app_scan_link)/',
           '/(facebook_link)/',
            '/(instagram_link)/',
            '/(email_link)/',
            '/(youtube_link)/',
            '/(app_store_link)/',
            '/(play_store_link)/',
        ];
        $footerReplacement = [
            asset('assets/images/app_icon/'.$config[31]['value']),
            $config[28]['value'],
            $config[29]['value'],
            $config[30]['value'],
            $config[32]['value'],
            $config[33]['value'],
            $config[34]['value']
        ];
        $footermail = preg_replace($footerpatterns, $footerReplacement, $footer);
        return [$headermail,$footermail];
    
    }
}
