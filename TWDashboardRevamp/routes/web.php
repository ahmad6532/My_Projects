<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'signIn');
Route::post('signin', [CustomController::class, 'login'])->name('login');
Route::post('signup', [CustomController::class, 'registration'])->name('vendor_registration');
//Middleware Groups...
Route::group(['middleware' => 'disable_back_btn'], function () {
    Route::group(['middleware' => 'emptySession'], function () {
        Route::get('bulk_credits', [CustomController::class, 'creditsView'])->name('bulkcredits');
        Route::get('shifts', [CustomController::class, 'shiftData'])->name('shiftbtn');
        Route::post('shift-data',[CustomController::class,'ViewShiftData'])->name('shiftdata');
        Route::get('shift-data',[CustomController::class,'ViewShiftData']);

        // Route::get('shiftData',[CustomController::class,'ViewShiftData']);

        Route::get('help', [CustomController::class, 'helpView'])->name('helpbtn');
        Route::get('transactions', [CustomController::class, 'transactionsView'])->name('transbtn');
        Route::post('view-transactions', [CustomController::class, 'SearchTransactions'])->name('trans');
        Route::get('fill-amount', [CustomController::class, 'fillAmountView'])->name('famtbtn');
        Route::get('add-player-credits', [CustomController::class, 'addPlayerCreditsView'])->name('pcreditsbtn');
        Route::post('addpcredits',[CustomController::class,'addPlayerCredits'])->name('pcbtn');
        Route::post('redeempoints',[CustomController::class,'redeemPoints'])->name('redeembtn');
        Route::get('redeem-points', [CustomController::class, 'redeemPointsView'])->name('rpbtn');
        Route::get('espanol', [CustomController::class, 'espanolView'])->name('espanolbtn');
        Route::get('players', [CustomController::class, 'ShowplayerData'])->name('player_view');
        Route::get('add_player', [CustomController::class, 'addplayerView']);
        Route::get('edit_account', [CustomController::class, 'edit_accountView'])->name('editaccountbtn');
        Route::get('/editplayer{player_id}', [CustomController::class, 'editPlayerView']);
        Route::get('/deleteplayer{player_id}', [CustomController::class, 'deleteplayer']);
        Route::post('/editplayer', [CustomController::class, 'updatePlayer'])->name('updatebtn');
        Route::post('add_player', [CustomController::class, 'addPlayer'])->name('addplayer');
        Route::post('vendor-updated', [CustomController::class, 'updateVendor'])->name('vendorbtn');
        Route::get('vendordeleted', [CustomController::class, 'deleteaccount'])->name('deletebtn');
        Route::get('logout', [CustomController::class, 'logout'])->name('logout');
        Route::get('drawer', [CustomController::class, 'vendor_drawer'])->name('vdraw');
        Route::post('initial_value', [CustomController::class, 'initialAmount'])->name('iamt');
        Route::post('close_drawer', [CustomController::class, 'closeDrawer'])->name('closedraw');
        Route::post('refill_value', [CustomController::class, 'refillAmount'])->name('reamt');
        Route::post('withdraw_amount', [CustomController::class, 'withdrawAmount'])->name('wdrawbtn');
        Route::get('paypal', [CustomController::class, 'payment'])->name('payment');
        Route::get('payment_cancel', [CustomController::class, 'payment_cancel'])->name('payment_cancel');
        Route::get('payment_success', [CustomController::class, 'payment_success']);
        Route::get('ipn', [CustomController::class, 'ipn'])->name('ipn');
        Route::post('plan', [CustomController::class, 'TriggerPaypal'])->name('plan');
        Route::get('terms-&-conditions',[CustomController::class,'TermsConditions'])->name('t&c');
    });
    Route::get('changelang/{lang}', [CustomController::class, 'change'])->name('changeLang');
    if (is_null(session('locale'))) {
        session(['locale' => "en"]);
    }

    if (Request::hasCookie('lang')) {
        App::setLocale(Crypt::decrypt(Cookie::get('lang'), false));
    } else {
        app()->setLocale(session('locale'));
    }
});

Route::group(['middleware' => 'bound'], function () {
    Route::get('signin', [CustomController::class, 'loginView']);
    Route::get('signup', [CustomController::class, 'registerView']);
    Route::get('reminder', [CustomController::class, 'reminderView']);
    Route::post('password/forget', [CustomController::class, 'forgetpassword'])->name('forgetpass');
    Route::get('/reset-password', [CustomController::class, 'resetpasswordLoad']);
    Route::post('reset_pass', [CustomController::class, 'resetpassword'])->name('resetpassbtn');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
