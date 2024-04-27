<?php

namespace App\Http\Controllers;

use App\Models\AccountDetail;
use App\Models\Purchase;
use Twilio\Rest\Client;
use Illuminate\Routing\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use \stdClass;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorProfileDetail;
use App\Models\VendorCreditPackage;
use App\Models\VendorDrawer;
use App\Models\account_details;
use App\Models\PlayerVendorTransaction;
use App\Models\DrawerRefill;
use App\Models\DrawerWithdraw;
use App\Models\VendorCurrency;
use Illuminate\Support\Facades\Hash;
use App\Models\PlayerProfileDetail;
use PHPUnit\Exception;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use App\Models\password_reset;
use App\Models\DrawerInitial;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use App\Models\analytics;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Cookie;

class CustomController extends Controller
{


    public function loginView(){
        return view('signIn');
    }

    public function registerView(){
        return view('/signUp');
    }

    public function reminderView(){
        return view('reminder');
    }

    public function addplayerView(Request $request)
    {
        $today_date = Carbon::now();
        $vendor_id = $request->session()->get('vendor_id');
        $currency_credits = VendorCurrency::where('vendor_id', '=', $vendor_id)->get();
        if (count($currency_credits) >0) {
            $expires_credits = $currency_credits[0]['expires_on'];
            if ($expires_credits >= $today_date) {
                return view('add_player');
            } else {
                return redirect('bulk_credits')->with('alert', 'You Cannot Add the Player. Please Buy Credits First.');
            }
        }
        else
        {
            return redirect('bulk_credits')->with('alert', 'You Cannot Add the Player. Please Buy Credits First.');
        }
    }
    public function creditsView(){
        return view('bulk_credits');
    }

    public function TermsConditions(){
        return view('terms_&_conditions');
    }

    public function shiftsView(){
        return view('shifts');
    }

    public function helpView(Request $request){
        $lang=$request->session()->get('locale');
        if ($lang=="en") {
            return view('help_en');
        }
        else{
            return view('help_es');
        }
    }

    public function transactionsView(){
        return view('transactions');
    }

    public function fillAmountView(){
        return view('fillamount');
    }

    public function addPlayerCreditsView(){

        return view('add_player_credits');
    }

    public function redeemPointsView(){
        return view('redeempoints');
    }

    public function espanolView(){
        return view('espanol');
    }

    public function logout(Request $request){
        $request->session()->forget('data');

        return redirect('signin');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],
            [
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be Numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'
            ]);
        $today_date=Carbon::now();
        $user = VendorProfileDetail::where('email', '=', $request->email)->get();

        if (count($user) > 0) {
            $vid = $user[0]['vendor_id'];
            $vpm = $user[0]['vendor_promocode'];
            $vcbal = $user[0]['credits'];

            $request->session()->put('vendor_id', $vid);


            $user_pass = base64_encode($request->password);
            $urp = VendorProfileDetail::where('password', '=', $user_pass)->first();

            if ($urp) {
                $status=VendorProfileDetail::where('status','=','D')->where('vendor_id', '=', $vid)->first();
                if ($status)
                {
                    return redirect()->back()->with('status', 'You account has been Deactivate');
                }
                else {
                    $currency = VendorCurrency::where('vendor_id', '=', $vid)->get();
                    if (count($currency) > 0) {
                        $credits_expires = $currency[0]['expires_on'];
                        if ($credits_expires >= $today_date) {
                            $request->session()->put('data', $request->input());
                            $request->session()->put('vdata', $vpm);
                            $request->session()->put('vbalance', $vcbal);
                            return redirect('players');
                        } else {
                            $request->session()->put('data', $request->input());
                            $request->session()->put('vdata', $vpm);
                            $request->session()->put('vbalance', $vcbal);
                            return redirect('bulk_credits');
                        }
                    }
                    else{
                        $request->session()->put('data', $request->input());
                        $request->session()->put('vdata', $vpm);
                        $request->session()->put('vbalance', $vcbal);
                        return redirect('bulk_credits');
                    }
                }

            } else {
                return redirect()->back()->with('pass', 'Invalid Password');
            }

        } else {
            return redirect()->back()->with('mail', 'Invalid Email Address');
        }

    }

    public function registration(Request $request){
        $today_date=Carbon::now();

        $validatedData = $request->validate([
            'email' => 'required|email|unique:vendor_profile_details,email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password',
            'first_name' => 'required|min:6',
            'last_name' => 'required',
            'phone_number' => 'required|numeric|unique:vendor_profile_details,phone_number',
            'vendor_promocode' => 'required|unique:vendor_profile_details,vendor_promocode',
             'signup-terms' => 'required',
            'captcha-code'=>'required',
        ],
            [
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be Numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'
            ]);


        $vendor = $request->all();
        VendorProfileDetail::create([
            'email' => $request->email,
            'password' => base64_encode($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'vendor_promocode' => $request->vendor_promocode,
            'created_on'=>$today_date,
            'updated_on'=>$today_date,
            'user_type' => 'V',
            'credits' => '0',
            'address' => 'World',
            'status' =>'A',
            'is_verified' => '1',
            'is_drawer_start' => '0',

        ]);

        Alert::success('Registered successfully!')->persistent('Close')->autoclose(3500);
        return redirect('signin');


    }


    public function edit_accountView(Request $request)
    {
        if (session('data')) {
            $vendor_id = $request->session()->get('vendor_id');
            $vendor_data = VendorProfileDetail::find($vendor_id);
            return view('edit_account', compact('vendor_data'));
        }
        return "No Data Found";
    }

    public function updateVendor(Request $request)
    {
        $request->validate([

            'contact_no' => 'required|numeric|unique:vendor_profile_details,phone_number',



        ],
            [
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be Numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'

            ]);
        $vdata = VendorProfileDetail::find($request->vendor_id);
        $vdata->first_name = $request->first_name;
        $vdata->last_name = $request->last_name;
        $vdata->phone_number = $request->contact_no;
        $vdata->address = $request->address;
        $vdata->update();
        return back()->with('message', __('Record Updated Successfully...'));
    }

    public function payment_success(Request $request)
    {
        $vendor_id=$request->session()->get('vendor_id');
        $vendor_data=VendorCurrency::where('vendor_id','=',$vendor_id)->get();
        if (count($vendor_data)>0)
        {
           dd($request);

            return redirect('players')->with('alert', 'Your Transaction has been Completed Successfully...');
        }
        else{
            return "Not a Good Job";
        }

    }

    public function ipn(Request $request)
    {

    }

    public function payment_cancel(Request $request)
    {
        return redirect('bulk_credits')->with('alert', 'Your Transaction has been Canceled.');

    }

    public function payment(Request $request)
    {
        $package_data = VendorCreditPackage::find($request->c_id);
        $vendor_id = $request->session()->get('vendor_id');
        $vendor_data = VendorProfileDetail::find($vendor_id);
        if (!$package_data) {
            return redirect('players');
        }
        $p_data = new stdClass;
        $p_data->id = $package_data->credit_package_id;
        $p_data->user_id = $vendor_id;
        $p_data->amount = $package_data->amount;
        $p_data->c_id = $request->c_id;
        $p_data->credits_value_count = $package_data->credits_value_count;

        //dd($package_data);

        return view('paypal.paypal', compact('p_data'));
    }

    public function TriggerPaypal(Request $request)
    {
        $package_data = VendorCreditPackage::find($request->c_id);
        $vendor_id = $request->session()->get('vendor_id');
        $vendor_data = VendorProfileDetail::find($vendor_id);
        if (!$package_data) {
            return redirect('players');
        }

        $obj = new Purchase;
        $obj->user_id = $vendor_id;
        $obj->user_type = $vendor_data->user_type;
        $obj->credits = $package_data->credits_value_count;
        $obj->credit_package_id = $package_data->credit_package_id;
        $obj->amount = $package_data->amount;
        $obj->payment_status = "P";
        $obj->status = "A";
        $obj->save();

        $p_data = new stdClass;
        $p_data->id = $obj->id;
        $p_data->user_id = $vendor_id;
        $p_data->amount = $package_data->amount;
        $p_data->c_id = $package_data->c_id;
        $p_data->credits_value_count = $package_data->credits_value_count;

        return view('paypal.plan', compact('p_data'));
    }

    public function change(Request $request)
    {
        app()->setLocale($request->lang);
        session()->put('locale', $request->lang);
        session()->save();
        Cookie::queue(Cookie::make('lang', $request->lang, 1440));
        return redirect()->back();
    }

    public function deleteaccount(Request $request)
    {
        $vendor_id = $request->session()->get('vendor_id');
        $vendor_data = VendorProfileDetail::find($vendor_id);
        $vendor_data->status="D";
        $vendor_data->update();
        session()->flush();
        return redirect('signin');
    }

    public function editPlayerView($player_id)
    {
        $user = account_details::where('player_id', '=', $player_id)->get();
        if (count($user) > 0) {
            $tdata = DB::table('player_profile_details')
                ->join('account_details', 'account_details.player_id', '=', 'player_profile_details.player_id')
                ->join('vendor_profile_details', 'vendor_profile_details.vendor_id', '=', 'account_details.vendor_id')
                ->select('player_profile_details.first_name', 'player_profile_details.player_id',
                    'player_profile_details.last_name',
                    'player_profile_details.email',
                    'player_profile_details.street_name',
                    'player_profile_details.state',
                    'player_profile_details.country',
                    'player_profile_details.zip_code',
                    'player_profile_details.phone_number', 'account_details.credits', 'vendor_profile_details.vendor_promocode')
                ->where('player_profile_details.player_id', '=', $player_id)->first();
            //dd($tdata);
            return view('editplayer', compact('tdata'));
        } else {
            return ("Invalid Player");
        }
    }

    public  function deleteplayer($pid){

        $today_date=Carbon::now();
        $pdata=AccountDetail::where('player_id','=',$pid)->first();
        $pdata->is_deleted=1;
        $pdata->updated_on=$today_date;
        $pdata->update();

        return redirect('players');

    }

    public function updatePlayer(Request $request)
    {




        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact_no' => 'required|numeric|unique:player_profile_details,phone_number',
            'email_id' => 'required|email|unique:player_profile_details,email',


        ],
            [
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be Numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'

            ]);
        $today_date=Carbon::now();
        $obj = PlayerProfileDetail::find($request->player_id);

        $obj->first_name = $request->first_name;
        $obj->last_name = $request->last_name;
        $obj->email = $request->email_id;
        $obj->phone_number = $request->contact_no;
        $obj->street_name = $request->address;
        $obj->state = $request->state;
        $obj->country = $request->country;
        $obj->zip_code = $request->zipcode;
        $obj->updated_on=$today_date;
        $obj->update();


        return back()->with('message', 'Player Updated Successfully...');
    }

    public function ShowplayerData(Request $request)
    {
        $vendor_id = $request->session()->get('vendor_id');
        $data = DB::table('player_profile_details')
            ->join('account_details', 'account_details.player_id', '=', 'player_profile_details.player_id')
            ->select('player_profile_details.phone_number', 'account_details.player_id', 'account_details.player_PIN',
                'account_details.credits', 'account_details.points', 'account_details.created_on')
            ->where('account_details.vendor_id', '=', $vendor_id)
            ->where('account_details.is_deleted','=','0')
            ->paginate(1000);


        return view('players', compact('data'));
    }

    public function addPlayer(Request $request)
    {
        $today_date = Carbon::now();

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email_id' => 'required|email|unique:player_profile_details,email',
            'code' => 'required',
            'contact_no' => 'required|unique:player_profile_details,phone_number|numeric',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zipcode' => 'required|numeric',

        ],
            [
                'string' => 'The :atribute Should be numeric',
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'

            ]);
            $otp=random_int(1000,9999);
        $vendor_id=$request->session()->get('vendor_id');
        $contactt=$request->contact_no;
        $code=$request->code;
        $phone_number=$code.$contactt;
        $player_data=PlayerProfileDetail::where('phone_number','=',$phone_number)->get();

        if (count($player_data) > 0) {
            $player_id=$player_data[0]['player_id'];
            $account_data=AccountDetail::where('player_id','=',$player_id)->where('vendor_id','=',$vendor_id)->get();

            if (count($account_data)>0)
            {
                $player_status=$account_data[0]['is_deleted'];
                if ($player_status==1) {
                    try {


                $promocode=$request->session()->get('vdata');

//                $account_sid= env('TWILIO_SID');
//                $account_token= env('TWILIO_TOKEN');
//                $account_from= env('TWILIO_FROM');
//                $client=new Client( $account_sid,$account_token);
//                $client->messages->create('+'.$request->code.$request->contact_no,[
//                'from'=>$account_from,
//                'body'=>"Your touchwon promocode is: ".$promocode. " and your pin code is: ". $otp ]);


                        $accountobj = AccountDetail::where('player_id', '=', $player_id)->where('vendor_id', '=', $vendor_id)->first();
                        $accountobj->player_PIN = $otp;
                        $accountobj->is_verified = "0";
                        $accountobj->is_deleted = "0";
                        $accountobj->points = "0";
                        $accountobj->credits = "0";
                        $accountobj->updated_on = $today_date;
                        $accountobj->update();

                        return back()->with('message', 'Player Added Successfully...');
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }
                }
                else{
                    return back()->with('not', 'Player Already Added');
                }
            }
            else{

                $promocode=$request->session()->get('vdata');
//                $otp=random_int(1000,9999);
//                $account_sid= env('TWILIO_SID');
//                $account_token= env('TWILIO_TOKEN');
//                $account_from= env('TWILIO_FROM');
//                $client=new Client( $account_sid,$account_token);
//                $client->messages->create('+'.$request->code.$request->contact_no,[
//                'from'=>$account_from,
//                'body'=>"Your touchwon promocode is: ".$promocode. " and your pin code is: ". $otp ]);


                $accountobj = new AccountDetail;
                $accountobj->vendor_id = $request->session()->get('vendor_id');
                $accountobj->player_id = $player_id;
                $accountobj->player_PIN = $otp;
                $accountobj->is_verified = "0";
                $accountobj->is_deleted = "0";
                $accountobj->points = "0";
                $accountobj->credits = "0";
                $accountobj->is_active = "1";
                $accountobj->created_on = $today_date;
                $accountobj->updated_on = $today_date;
                $accountobj->save();

                return back()->with('message', 'Player Added Successfully...');
            }
        }
        else {
            $obj = new PlayerProfileDetail;
            $obj->first_name = $request->first_name;
            $obj->last_name = $request->last_name;
            $obj->email = $request->email_id;
            $obj->phone_number = $request->contact_no;
            $country_code = $request->code;
            $contact = $request->contact_no;
            $combine = $country_code . $contact;
            $obj->phone_number = $combine;
            $obj->street_name = $request->address;
            $obj->state = $request->state;
            $obj->country = $request->country;
            $obj->zip_code = $request->zipcode;
            $obj->save();

        $promocode=$request->session()->get('vdata');
        $otp=random_int(1000,9999);
//        $account_sid= env('TWILIO_SID');
//        $account_token= env('TWILIO_TOKEN');
//        $account_from= env('TWILIO_FROM');
//        $client=new Client( $account_sid,$account_token);
//        $client->messages->create('+'.$request->code.$request->contact_no,[
//           'from'=>$account_from,
//            'body'=>"Your touchwon promocode is: ".$promocode. " and your pin code is: ". $otp
//        ]);


            $accountobj = new AccountDetail;
            $accountobj->vendor_id = $request->session()->get('vendor_id');
            $accountobj->player_id = $obj->player_id;
            $accountobj->player_PIN = $otp;
            $accountobj->is_verified = "0";
            $accountobj->is_deleted = "0";
            $accountobj->points = "0";
            $accountobj->credits = "0";
            $accountobj->is_active = "1";
            $accountobj->created_on = $today_date;
            $accountobj->updated_on = $today_date;
            $accountobj->save();

            return back()->with('message', 'Player Added Successfully...');

        }
    }



    public  function  addPlayerCredits(Request $request){
        $today_date=Carbon::now();
        $account_data=PlayerProfileDetail::where('phone_number','=',$request->phone)->get();
        $vendor_id=$request->session()->get('vendor_id');

        if (count($account_data)>0) {
            $player_id = $account_data[0]['player_id'];
            $account_detail = AccountDetail::where('player_id', '=', $player_id)->get();
            $account_id = $account_detail[0]['account_id'];
            $player_credits=$account_detail[0]['credits'];
            $vendor_data=VendorProfileDetail::where('vendor_id','=',$vendor_id)->get();
            $vendor_credits=$vendor_data[0]['credits'];
            $credits_update = AccountDetail::find($account_id);
            $given_credits=$request->amt;
            $total_credits=$given_credits+$player_credits;
            if ($given_credits <=$vendor_credits)
            {
                $credits_update->credits = $total_credits;
                $credits_update->updated_on = $today_date;
                if ($total_credits >= 0) {
                    $credits_update->update();
                    $vendor_remaining_credits=$vendor_credits-$given_credits;
                    $vendor_detail=VendorProfileDetail::find($vendor_id);
                    $vendor_detail->credits=$vendor_remaining_credits;
                    $vendor_detail->updated_on=$today_date;
                    $vendor_detail->update();

                        $amount = $given_credits / 100;

                        PlayerVendorTransaction::create([
                            'account_id' => $account_id,
                            'vendor_id' => $vendor_id,
                            'date' => $today_date,
                            'creds' => $given_credits,
                            'points' => 0,
                            'amount' => $amount
                        ]);


                        return redirect('players');
                    }
                else{
                    return "Credits must be greater than zero";
                }

                } else {

                    return redirect('bulk_credits')->with('alert', 'Your Balance is not sufficient for this transaction. Please Buy Credits First.');
                }
            }
        else{
            return back()->with('go', 'You are Trying to Bad with it.');

        }
    }



    public  function  redeemPoints(Request $request){
        $today_date=Carbon::now();
        $account_data=PlayerProfileDetail::where('phone_number','=',$request->phone)->get();
        $vendor_id=$request->session()->get('vendor_id');

        if (count($account_data)>0) {
            $player_id = $account_data[0]['player_id'];
            $account_detail = AccountDetail::where('player_id', '=', $player_id)->get();
            $account_id = $account_detail[0]['account_id'];
            $redeem_update = AccountDetail::find($account_id);
            if($request->tpoints >=100) {

                $tpoints = $request->tpoints;
                $amount = $request->amt;
                $mod=$tpoints%100;
                $remaining_points=$tpoints-$mod;
                PlayerVendorTransaction::create([
                    'account_id' => $account_id,
                    'vendor_id' => $vendor_id,
                    'date' => $today_date,
                    'creds' => 0,
                    'points' => $remaining_points,
                    'amount' => $amount
                ]);
                $redeem_update->points = $mod;
                $redeem_update->update();
                return redirect('players');
            }
            else{
                return back()->with('gone', 'Points Must be Greater than 100.');
            }
        }
        else{
            return back()->with('go', 'You are Trying to Bad with it.');
        }
    }



    public function vendor_drawer(Request $request)
    {
        $vid = $request->session()->get('vendor_id');
        $did = $request->session()->get('drawer_id');

        $today_date = Carbon::now()->format('Y-m-d');
        $today_datetime = Carbon::now()->format('Y-m-d H:i:s');


        $drawer_d = DB::table('vendor_drawer')
            ->select('drawer_id', 'drawer_started_on')
            ->whereDate('drawer_started_on', '=', $today_date)
            //->where('match_date', '=', $today_date)
            ->where('vendor_id', '=', $vid)
            ->where('is_active', '=', 1)->get();
        $drawer_data = json_decode(json_encode($drawer_d), true);
       // dd($vid);

        $initial_data = DB::table('vendor_drawer')
            ->join('drawer_initial', 'drawer_initial.drawer_id', '=', 'vendor_drawer.drawer_id')
            ->where('vendor_drawer.vendor_id', '=', $vid)
            ->whereDate ('vendor_drawer.drawer_started_on', '=', $today_date)
            ->whereDate('drawer_initial.created_at', '=', $today_date)
            ->where('vendor_drawer.drawer_id', '=', $did)->get();

        if (count($initial_data) > 0) {
            $data_in_array = json_decode(json_encode($initial_data), true);
            $inival = $data_in_array[0]['initial_amount'];
            $balance = $inival;

            $refilval = DB::table('drawer_refill')->select('refill_amount')->where('drawer_id', $did)
                ->whereDate('refill_done_on', $today_date)->sum('refill_amount');
            $withdraw = DB::table('drawer_withdraw')->select('withdraw_amount')->where('drawer_id', $did)
                ->whereDate('withdraw_done_on', $today_date)->sum('withdraw_amount');
            $start_date = $drawer_data[0]['drawer_started_on'];

            $credits = DB::table('player_vendor_transaction')
                ->where('vendor_id', '=', $vid)
                ->whereBetween('date', [$start_date, $today_datetime])
                ->sum('creds');
            $points = DB::table('player_vendor_transaction')
                ->where('vendor_id', '=', $vid)
                ->whereBetween('date', [$start_date, $today_datetime])
                ->sum('points');

            $tbalance =($refilval + $credits) - ($points + $withdraw);
            if ($refilval > 0 || $withdraw > 0) {
                $balance = $balance + $refilval - $withdraw;

                $request->session()->put('balance', $balance);



                return view('drawer', compact('drawer_data', 'inival', 'refilval', 'withdraw', 'balance', 'start_date', 'credits', 'points','tbalance','vid'));
            } else {
                return view('drawer', compact('drawer_data', 'inival', 'balance', 'start_date', 'credits', 'points','tbalance','vid'));
            }
        } else {
            return view('drawer');
        }

    }


    public function initialAmount(Request $request)
    {
        $vid = $request->session()->get('vendor_id');
        $today_date = Carbon::now()->format('Y-m-d');
        $today_datetime = Carbon::now();

        $objd = new VendorDrawer;
        $objd->vendor_id = $vid;
        $objd->is_active = "1";
        $objd->drawer_started_on = $today_datetime;
        $objd->drawer_ended_on = $today_datetime;
        //$objd->match_date = $today_date;
        $objd->save();
        $request->session()->put('drawer_id', $objd->drawer_id);

        $request->validate([
            'initialAmount'=>'required'
            ],
            [
                'required'=>'The :attribute field is required.'
        ]);
        DrawerInitial::create([
            'drawer_id' => $objd->drawer_id,
            'initial_amount' => $request->initialAmount,
            'created_at' => $today_datetime,
            'updated_at' => $today_datetime
            //'match_date' => $today_date

            ]);
        $vendor_val = VendorProfileDetail::find($vid);
        $vendor_val->is_drawer_start = '1';
        $vendor_val->update();


        $drawer_data = DB::table('vendor_drawer')
            ->select('drawer_id')
            ->whereDate ('vendor_drawer.drawer_started_on', '=', $today_date)
            ->where('vendor_id', '=', $vid)
            ->where('is_active', '=', 1)->first();

        $fun = $this->vendor_drawer($request);


        return view('drawer', compact('drawer_data', 'fun'));


    }

    public function refillAmount(Request $request)
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $drawer_id = $request->session()->get('drawer_id');
        $today_datetime = Carbon::now();

        $request->validate([

           'refillAmount'=>'required'
        ],
            [

                'required' => 'The :attribute field is required.',
            ]);

        DrawerRefill::create([
            'drawer_id' => $drawer_id,
            'refill_amount' => $request->refillAmount,
            'refill_done_on' => $today_datetime
            //'match_date' => $today_date
        ]);
        return redirect('drawer');
    }

    public function withdrawAmount(Request $request)
    {
        $did = $request->session()->get('drawer_id');
        $today_date = Carbon::now()->format('Y-m-d');
        $today_datetime = Carbon::now();
        $balance = $request->session()->get('balance');
        $var = $request->withdrawAmount;
        if ($var > $balance) {

            return redirect('drawer')->with('bal_error', 'Please Refill before Withdraw');
        } else {

            if ($balance != $var) {
                $request->validate([
                    'withdrawAmount'=>'required'
                ],
                    [
                        'required' => 'The :attribute field is required.',
                    ]);
                DrawerWithdraw::create([

                    'drawer_id' => $did,
                    'withdraw_amount' => $request->withdrawAmount,
                    'withdraw_done_on' => $today_datetime
                    //'match_date' => $today_date
                ]);
                return redirect('drawer');
            } else {
                return redirect('drawer')->with('bal_error', 'Please Refill before Withdraw');
            }
        }

    }

    public function closeDrawer(Request $request)
    {
        $today_date=Carbon::now();
        $vid = $request->session()->get('vendor_id');
        $did = $request->session()->get('drawer_id');
        $drawer_val = VendorDrawer::find($did);
        $drawer_val->is_active = 0;
        $drawer_val->drawer_ended_on=$today_date;
        $drawer_val->update();

        $vendor_val = VendorProfileDetail::find($vid);
        $vendor_val->is_drawer_start = '0';
        $vendor_val->update();

        $request->session()->forget('drawer_id');

        return redirect('players');
    }

    public function ViewShiftData(Request $request){
        if (request()->fdate || request()->tdate) {
            $start_date = Carbon::parse(request()->fdate)->toDateTimeString();
            $end_date = Carbon::parse(request()->tdate)->toDateTimeString();
            $data = VendorDrawer::createdBetweenDates([$start_date,$end_date])->get();

            $shift_count = 0;
            $total_Purchases = 0;
            $total_Withdraw = 0;
            $total_Credits = 0;
            $total_Redeem = 0;
            $total_Balance = 0;



            foreach ($data as $p) {
                $shift_count++;
                $drawer_id = $p->drawer_id;
                $vendor_id = $p->vendor_id;
                $drawer_started_on[] = $p->drawer_started_on;
                $drawer_ended_on[] = $p->drawer_ended_on;

                $drawer_started = $p->drawer_started_on;
                $drawer_ended = $p->drawer_ended_on;

                $p->initial_amount = DrawerInitial::where('drawer_id', '=', $drawer_id)
                    ->whereDate('created_at', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('updated_at', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('created_at', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('updated_at', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('initial_amount');

                $p->refill_amount = DrawerRefill::where('drawer_id', '=', $drawer_id)
                    ->whereDate('refill_done_on', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('refill_done_on', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('refill_done_on', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('refill_done_on', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('refill_amount');

                $total_Withdraw += $p->withdraw_amount = DrawerWithdraw::where('drawer_id', '=', $drawer_id)
                    ->whereDate('withdraw_done_on', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('withdraw_done_on', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('withdraw_done_on', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('withdraw_done_on', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('withdraw_amount');

                $total_Purchases += $p->amount = PlayerVendorTransaction::where('vendor_id', '=', $vendor_id)
                    ->whereDate('date', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('date', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('date', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('date', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('amount');
                $total_Credits += $p->creds = PlayerVendorTransaction::where('vendor_id', '=', $vendor_id)
                    ->whereDate('date', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('date', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('date', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('date', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('creds');
                $total_Redeem += $p->points = PlayerVendorTransaction::where('vendor_id', '=', $vendor_id)
                    ->whereDate('date', Carbon::create($drawer_started)->toDateString())
                    ->whereDate('date', Carbon::create($drawer_ended)->toDateString())
                    ->whereTime('date', '>=', Carbon::create($drawer_started)->toTimeString())
                    ->whereTime('date', '<=', Carbon::create($drawer_ended)->toTimeString())->sum('points');
            }

        }
        $profit =  ($total_Withdraw + $total_Credits) - ($total_Redeem + $total_Withdraw);

        $data->shift_count = $shift_count;
        $data->total_Purchases = $total_Purchases;
        $data->total_Withdraw = $total_Withdraw;
        $data->total_Credits = $total_Credits;
        $data->total_Redeem = $total_Redeem;
        $data->total_Balance = $profit;


        return view('shifts')->with(compact('data'));
    }



    public function SearchTransactions(Request $request)
    {
        $vid = $request->session()->get('vendor_id');
        $tdata = DB::table('player_vendor_transaction')
            ->join('account_details', 'account_details.account_id', '=', 'player_vendor_transaction.account_id')
            ->join('player_profile_details', 'player_profile_details.player_id', '=', 'account_details.player_id')
            ->select('player_vendor_transaction.date', 'player_profile_details.phone_number',
                'player_vendor_transaction.creds', 'player_vendor_transaction.points', 'player_vendor_transaction.amount')
            ->where('player_vendor_transaction.vendor_id', '=', $vid)
            ->whereBetween('date', [$request->fdate . " 00:00:00", $request->tdate . " 23:59:59"])->get();

        $countdata = DB::table('player_vendor_transaction')
            ->select('player_vendor_transaction.date')
            ->where('player_vendor_transaction.vendor_id', '=', $vid)
            ->whereBetween('date', [$request->fdate . " 00:00:00", $request->tdate . " 23:59:59"])->count();

        $tsum = DB::table('player_vendor_transaction')
            ->where('vendor_id', '=', $vid)
            ->whereBetween('date', [$request->fdate . " 00:00:00", $request->tdate . " 23:59:59"])
            ->sum('creds');

        $tredeems = DB::table('player_vendor_transaction')
            ->where('vendor_id', '=', $vid)
            ->whereBetween('date', [$request->fdate . " 00:00:00", $request->tdate . " 23:59:59"])
            ->sum('points');

        $tbalance = DB::table('player_vendor_transaction')
            ->where('vendor_id', '=', $vid)
            ->whereBetween('date', [$request->fdate . " 00:00:00", $request->tdate . " 23:59:59"])
            ->sum('amount');


        return view('transactions', compact('tdata', 'countdata', 'tsum', 'tredeems', 'tbalance'));


    }

//Forgot Password working strts here...
    public function forgetpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ],
            [
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be Numeric',
                'email' => 'Please provide a valid :attribute',
                'same' => 'The :attribute field should match the Password'
            ]);
        $mdata = VendorProfileDetail::where('email', $request->email)->get();
        if (count($mdata) > 0) {
            $vmail = $mdata[0]['vendor_id'];

            require base_path("vendor/autoload.php");
            $mail = new PHPMailer(true);

            try {

                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/reset-password?token=' . $token;

                // Email server settings
                $mail->SMTPDebug = 3;
                $mail->isSMTP();
                $mail->Host = 'localhost';                  //  smtp host
                $mail->SMTPAuth = true;
                $mail->Username = 'support@touchwon.com';   //  sender username
                $mail->Password = 'Getout!26';              // sender password
                $mail->SMTPSecure = 'None';                 // encryption - ssl/tls
                $mail->Port = 25;                           // port - 587/465

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );/**/
                $mail->setFrom('support@touchwon.com', 'TouchWon Team');
                $mail->addAddress($request->email);
                //$mail->addCC($request->emailCc);
                //$mail->addBCC($request->emailBcc);

                $mail->addReplyTo('noreply@touchwon.com', 'noreply');


                $mail->isHTML(true);                // Set email content format to HTML

                $mail->Subject = 'Reset Password';
                $mail->Body = 'To reset your password click on the given link. ' . $url;


                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                password_reset::updateOrCreate(
                    ['mail' => $request->email],
                    ['vendor_id' => $vmail,
                        'mail' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );

                if (!$mail->send()) {
                    return back()->with("fail", "Email not sent. Please try again later!")->withErrors($mail->ErrorInfo);
                } else {
                    return back()->with("success", "An email has been sent on your email address. Please check your email.");
                }

            } catch (Exception $e) {
                return back()->with('error', 'Message could not be sent.');
            }
        } else {
            return back()->with('fail', 'Email Does not Exist');
        }


    }

    public function resetpasswordLoad(Request $request)
    {

        $tdata = password_reset::where('token', $request->token)->get();

        if (isset($request->token) && count($tdata) > 0) {
            $vData = VendorProfileDetail::where('vendor_id', $tdata[0]['vendor_id'])->get();

            return view('re_enter_password', compact('vData'));
        } else {
            return view('token_expire');
        }
    }

    public function resetpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|string|confirmed',
            'password_confirmation' => 'required'
        ],
            [
                'string' => 'The :atribute Should be numeric',
                'required' => 'The :attribute field is required.',
                'unique' => 'The :attribute is already taken',
                'min' => 'The :attribute Should be Minimum of :min Character',
                'numeric' => 'The :attribute must be numeric',
                'email' => 'Please provide a valid :attribute',
                'confirmed' => 'The :attribute field should match the Password'
            ]);
        $tdata = password_reset::where('vendor_id', $request->id)->get();
        if (count($tdata) > 0) {
            $pass = VendorProfileDetail::find($request->id);
            $pass->password = base64_encode($request->password);
            $pass->save();
            $data_deleted = password_reset::where('mail', $pass->email)->delete();
            if ($data_deleted != 0) {
                Alert::success('Password has been changed successfully!')->persistent('Close')->autoclose(4000);
                return view('password_success')->with('pass', 'Your password has been changed successfully!');
            }

        } else {
            return back();
        }
        return back();
    }
//Forgot Password working ends here...

    //API TouchWon Client Side Communication

    public function login_api(Request $request)
    {
        $table_data = VendorProfileDetail::where('email', '=', $request->email)->first();

        $database_pass = base64_decode($table_data->password);
        $user_pass = $request->password;

        if ($table_data && ($database_pass == $user_pass)) {

            return response([$table_data]);

        } else {
            return response(['Alert' => 'Invalid Email address or Password']);
        }

    }

    public function register_api(Request $request)
    {
        VendorProfileDetail::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => base64_encode($request->password),
            'vendor_promocode' => $request->promo_code,
            'phone_number' => $request->phone,
            'user_type' => $request->user,
            'credits' => $request->credits,
            'address' => $request->address,
            'status' => $request->status,
            'is_verified' => $request->is_verified,
            'is_drawer_start' => $request->is_drawer_start,

        ]);
        return response(["submitted"]);
    }

    public function delete_api(Request $request)
    {
        $delete_data = VendorProfileDetail::find($request->id);
        $delete_data->delete();
        return response(["Data Deleted"]);
    }

    public function update_api(Request $request)
    {

        $up_data = AccountDetail::find($request->id);
        $up_data->credits = $request->credits;
        $up_data->points = $request->points;
        $up_data->update();


        return response(["Updated"]);
    }


//API TouchWon Client Side Communication End


    public function shiftData(){


        return view('shifts');
    }

}
