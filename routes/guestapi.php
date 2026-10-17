<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Church;
use App\Models\ChurchBranch;
use App\Models\Country;
use App\Support\CountryPhoneCode;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*Guest API Routes Start*/

//guest login
Route::get('/gender/list','Api\Guest\LoginController@index');

Route::post('/guest/create','Api\Guest\LoginController@store');

Route::post('/guest/login','Api\Guest\LoginController@login');

Route::get('/apk/church','Api\ApkController@index');

Route::get('/registration/options/{church_id}', function ($churchId) {
    $church = Church::query()->findOrFail($churchId);
    $branches = ChurchBranch::query()->forChurch($church->id)->active()->orderByDesc('is_headquarters')->orderBy('name')
        ->get(['id', 'name', 'address', 'is_headquarters']);
    $countries = Country::query()->where('status', 1)->whereNotNull('tel_prefix')->orderByRaw("CASE WHEN tel_prefix = '254' THEN 0 ELSE 1 END")
        ->orderBy('name')->get(['name', 'short_name', 'tel_prefix']);
    $countries->each(fn ($country) => $country->tel_prefix = CountryPhoneCode::for($country));

    return response()->json([
        'church_id' => $church->id,
        'default_branch_id' => $branches->firstWhere('is_headquarters', true)?->id,
        'branches' => $branches,
        'countries' => $countries,
    ]);
});

Route::group([
	'prefix' => 'v2',
	'namespace' =>'Api\Guest' ,
	//'middleware'=>['auth:sanctum']
	],
	function() {

	//events
	Route::get('/events/{church_id}', 'EventsController@index');

	Route::get('/event/show/{church_id}/{id}', 'EventsController@show');

	//events
	Route::get('/bulletins/{church_id}', 'BulletinsController@index');

	//prayer requests
	Route::get('/prayerRequests/{church_id}', 'PrayerRequestsController@index');

	//helps
	Route::get('/helps/{church_id}', 'HelpsController@index');

	//groups
	Route::get('/groups/{church_id}', 'GroupsController@index');

	//fund
	Route::post('/fund/add', 'FundController@store');

	//quote
	Route::get('/quotes/{church_id}', 'QuotesController@index');

	//feedbacks
	Route::get('/feedback/category','FeedbackController@list');

	Route::post('/feedback/send','FeedbackController@store');

	//contact
	Route::post('/add/contact','ContactController@store');

	//church detail
	Route::get('/church/details/{church_id}','ChurchDetailsController@show');

	Route::get('/socialMedia/{church_id}','ChurchDetailsController@socialMedia');

	//media files
	Route::get('/audio/{church_id}','MediaFilesController@audio');

	Route::get('/video/{church_id}','MediaFilesController@video');

	//gallery
	Route::get('/galleries/{church_id}', 'GalleryController@index');

	Route::get('/gallery/show/{church_id}/{gallery_id}', 'GalleryController@show');

	//sermon
	Route::get('/sermons/{church_id}','SermonsController@index');

	Route::get('/sermon/show/{church_id}/{sermons_id}','SermonsController@show');

	//Payaccount

	Route::get('/paymentgateway/{church_id}', 'PayaccountContorller@getlist');

	Route::get('/payaccount/{church_id}/{gateway_id}', 'PayaccountContorller@showdetails');
});

/*Guest API Routes End*/
