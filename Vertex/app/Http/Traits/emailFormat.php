<?php
namespace App\Http\Traits;
use App\Models\Setting;

trait emailFormat {

    public function emailStructure($header,$footer,$getUserCompanyLogo) {

        // return [$header,$footer];
        $config = Setting::all();
        $logo = Setting::where('perimeter','App_Logo')->first()['value'];
        $headerpatterns = [
            '/\{(logo)}]?/',
            '/(public_path)/'
        ];

        $headerreplacements = [
            asset('assets/images/companies/' . $getUserCompanyLogo)
            // $config[27]['value']
        ];
        $headermail = preg_replace($headerpatterns, $headerreplacements, $header);

        $footerpatterns = [
           '/\{(logo)}]?/',
        //    '/(facebook_link)/',
        //     '/(instagram_link)/',
        //     '/(email_link)/',
        //     '/(youtube_link)/',
        //     '/(app_store_link)/',
        //     '/(play_store_link)/',
        ];
        $footerReplacement = [
            asset('assets/images/companies/' . $getUserCompanyLogo)
            // $config[28]['value'],
            // $config[29]['value'],
            // $config[32]['value'],
            // $config[30]['value'],
            // $config[33]['value'],
            // $config[34]['value']
        ];
        $footermail = preg_replace($footerpatterns, $footerReplacement, $footer);
        return [$headermail,$footermail];

    }
}
