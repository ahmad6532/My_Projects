<?php
namespace App\Helpers;

use App\Models\CaseCommentLink;
use App\Models\ServiceMessage;
use App\Models\SystemLink;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class Helper
{
    public static function ServiceMessage($user_type,$guard)
    {
        $service_messages=[];
        $now = Carbon::now(env('TIMEZONE'));
        $serviceMessages = ServiceMessage::where('expires_at', '>', $now)->get();
        if(count($serviceMessages)>0){
            if($guard=='web')
                $country=Auth::guard('web')->user()->country_of_practice;
            else
                $country=Auth::guard($guard)->user()->country;
            foreach ($serviceMessages as $serviceMessage)
            {
                if(in_array($user_type, $serviceMessage->receiver_list) & in_array($country, $serviceMessage->country_list))
                {
                    $service_messages[]=$serviceMessage;
                }
            }
        }
        return $service_messages;
    }

    public static function url($route,$params = array(),$queryParams = array()){
        $query = request()->query();
        $query = array_merge($query,$queryParams);
        return route($route,array_merge($params,$query));       
    }

    public static function fancy_qr($url)
    {
        
        // This is a temporarily free qr code generation facility. 
        // Use it carefully. Bulk request may block it in future.
        // Generate QR codes and save in storage. use the saved QR codes in future.
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.qrcode-monkey.com//qr/custom',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "data": "' . $url .'",
        "download": "imageUrl",
        "file": "svg",
        "size": 500,
        "config": {
            "body": "round",
            "eye": "frame13",
            "eyeBall": "ball15",
            "erf1": [],
            "erf2": [],
            "erf3": [],
            "brf1": [],
            "brf2": [],
            "brf3": [],
            "bodyColor": "#000000",
            "bgColor": "#FFFFFF",
            "eye1Color": "#000000",
            "eye2Color": "#000000",
            "eye3Color": "#000000",
            "eyeBall1Color": "#000000",
            "eyeBall2Color": "#000000",
            "eyeBall3Color": "#000000",
            "gradientColor1": "",
            "gradientColor2": "",
            "gradientType": "linear",
            "gradientOnEyes": "true",
            "logo": "",
            "logoMode": "default"
        }
        }',
        CURLOPT_HTTPHEADER => array(
            'Cache-Control: no-cache',
            'Accept: */*',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function countries(){
       return $countries = [
            "England",
            "Scotland",
            "Wales",
            "Channel Islands",
            "Northern Ireland",
            "Republic of Ireland"
        ];
    }

    public static function time_elapsed_string($ptime)
    {
        $etime = time() - $ptime;
        if ($etime < 1)
        {
            return '0 seconds';
        }
        $a = array( 365 * 24 * 60 * 60  =>  'year',
                     30 * 24 * 60 * 60  =>  'month',
                          24 * 60 * 60  =>  'day',
                               60 * 60  =>  'hour',
                                    60  =>  'minute',
                                     1  =>  'second'
                    );
        $a_plural = array( 'year'   => 'years',
                           'month'  => 'months',
                           'day'    => 'days',
                           'hour'   => 'hours',
                           'minute' => 'minutes',
                           'second' => 'seconds'
                    );
    
        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . '';
            }
        }
    }

    public static function imageFileExtensionsList(){
        return $imageExtensions = array('.jpg','.jpeg','.png','.bmp','.gif','.tif','.tiff','.jpe','.jfif','.pcx','.heic','.svg','.eps','.webp');
    }   
    static function bankHolidays() {
        $uk_bank_holidays = [];
        try{
        $uk_bank_holidays =  json_decode(file_get_contents('https://www.gov.uk/bank-holidays.json'));
        }
        catch(\Exception $e)
        {}
        $to_return = [];
        foreach($uk_bank_holidays as $division)
        {
            foreach($division->events as $key => $event)
            {
                $event_date = Carbon::createFromFormat('Y-m-d h:i:s',$event->date . " 00:00:00");
                if($event_date >= Carbon::now() && $event_date <= Carbon::now()->addMonths(6))
                {
                    $event->string_date = $event->date;
                    $event->date = $event_date;
                    $event->reference_id = $key;
                    $event->divsion = $division->division;
                    $entry = Auth::guard('web')->user()->selected_head_office_user_bank_holiday_selections->where('reference_id', $key)->first();
                    $event->yes =  $entry && $entry->is_working == 1;
                    $event->no =  $entry && $entry->is_working == 0;
                    $to_return[$event->string_date] = $event;
                }
            }   
        }
        $to_return = collect($to_return)->sortBy('date')->all();
        return $to_return;
    }
    static function check_link($link_title=null,$link_comment=null,$comment,$case_id=null,$comment_id) 
    {
        $regex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
        $start = 0;
        $ret = preg_replace_callback($regex, function ($matches) use ($start,$link_title,$link_comment,$comment,$case_id,$comment_id) {
            if (empty(trim($link_title))) {
                return $matches[0]; // Return the original match if link title is empty
            }
            
            $random_link = bin2hex(random_bytes(20));
            $matches[0] = trim(preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $matches[0]), ".");

            
            $position = strpos($comment, $matches[0], $start);

            $href_start_position = $position - 6;
            if ($href_start_position > 0) {
                $start = $position + 1; // update start so that we don't look it again //
                $left_value = substr($comment, $href_start_position, 5);
                $is_a_link = str_contains(strtolower($left_value), "href");
                if ($is_a_link) {
            

                    $find_link = new SystemLink();
                    $find_link->link = $matches[0];
                    $find_link->random = $random_link;
                    $find_link->case_id = $case_id;
                    $find_link->comment_id = $comment_id;
                    $find_link->title = $link_title;
                    $find_link->description = $link_comment;
                    $find_link->save();
                    return route('case_manager.random_link', $find_link->random) . "\" oncontextmenu='link_context_menu(event,\"" . route('case_manager.random_link', $find_link->random) . "\",\"$find_link->link\")' target=\"_blank";
                }
            }
            $find_link = new SystemLink();
            $find_link->link = $matches[0];
            $find_link->random = $random_link;
            $find_link->case_id = $case_id;
            $find_link->comment_id = $comment_id;
            $find_link->title = $link_title;
            $find_link->description = $link_comment;
            $find_link->save();
            return "<a href='" . route('case_manager.random_link', $find_link->random) . "' target='_blank' oncontextmenu='link_context_menu(event,\"" . route('case_manager.random_link', $find_link->random) . "\",\"$find_link->link\")'>$find_link->link</a>";

        }, $comment);

        return $ret;
        
        
    }
}



