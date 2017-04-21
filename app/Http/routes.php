<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::model( 'entities', 'Entity' );
Route::model( 'sources', 'Source' );
Route::model( 'datasets', 'Dataset' );
Route::model( 'variables', 'Variable' );
Route::model( 'values', 'DataValue' );
Route::model( 'charts', 'Chart' );
Route::model( 'categories', 'DatasetCategory' );
Route::model( 'subcategories', 'DatasetSubcategory' );
Route::model( 'tags', 'DatasetTag' );
Route::model( 'apiKeys', 'ApiKey' );
Route::model( 'logos', 'Logo' );

Route::group(['middleware' => ['basic', 'session', 'auth']], function()
{
	Route::resource( 'entities', 'EntitiesController' );
	Route::resource( 'sources', 'SourcesController' );
	Route::get('datasets/{dataset}.json', 'DatasetsController@showJson');
	Route::get('datasets/{dataset}.csv', [ 'as' => 'datasets.exportCSV', 'uses' => 'DatasetsController@exportCSV' ]);
	Route::resource( 'datasets', 'DatasetsController' );
	Route::post('variables/{variable}/batchDestroy', [ 'as' => 'valuesBatchDestroy', 'uses' => 'VariablesController@batchDestroy' ]);
	Route::resource( 'variables', 'VariablesController' );
	Route::resource( 'values', 'ValuesController' );
	Route::post('charts/{id}/star', 'ChartsController@star');
	Route::post('charts/{id}/unstar', 'ChartsController@unstar');
	Route::resource( 'charts', 'ChartsController' );
	Route::resource( 'categories', 'CategoriesController' );
	Route::resource( 'subcategories', 'SubcategoriesController' );
	Route::resource( 'tags', 'TagsController' );
	Route::post('users/invite', 'UsersController@invite');
	Route::resource( 'users', 'UsersController' );
	Route::resource( 'licenses', 'LicensesController' );
	Route::resource( 'apiKeys', 'ApiKeysController' );
	Route::resource( 'logos', 'LogosController' );


	//Route::resource( 'dataValues', 'DataValuesController' );
	Route::bind( 'entities', function($value, $route) {
		return App\Entity::whereId($value)->first();
	});
	Route::bind( 'sources', function($value, $route) {
		return App\Source::whereId($value)->first();
	});
	Route::bind( 'datasets', function($value, $route) {
		return App\Dataset::whereId($value)->first();
	});
	Route::bind( 'variables', function($value, $route) {
		return App\Variable::whereId($value)->first();
	});
	Route::bind( 'values', function($value, $route) {
		return App\DataValue::whereId($value)->first();
	});
	Route::bind( 'charts', function($value, $route) {
		return App\Chart::whereId($value)->first();
	});
	Route::bind( 'categories', function($value, $route) {
		return App\DatasetCategory::whereId($value)->first();
	});
	Route::bind( 'subcategories', function($value, $route) {
		return App\DatasetSubcategory::whereId($value)->first();
	});
	Route::bind( 'tags', function($value, $route) {
		return App\DatasetTag::whereId($value)->first();
	});
	Route::bind( 'licenses', function($value, $route) {
		return App\License::whereId($value)->first();
	});
	Route::bind( 'apiKeys', function($value, $route) {
		return App\ApiKey::whereId($value)->first();
	});
	Route::bind( 'logos', function($value, $route) {
		return App\Logo::whereId($value)->first();
	});

	Route::get( 'import', [ 'as' => 'import', 'uses' => 'ImportController@index' ] );
	Route::post( 'import/store', 'ImportController@store' );
	Route::post('import/variables', 'ImportController@variables');

	Route::get( 'entityIsoNames/validate', 'EntitiesController@validateISO' );

	//Route::get( 'logo', [ 'as' => 'logo', 'uses' => 'LogoController@index' ] );
	//Route::post('logo/upload', 'LogoController@upload');

	Route::post( 'inputfile/import', 'ImportController@inputfile' );
	Route::post( 'source/import', 'ImportController@source' );
	Route::post( 'dataset/import', 'ImportController@dataset' );
	Route::post( 'variable/import', 'ImportController@variable' );
	Route::post( 'entity/import', 'ImportController@entity' );

	Route::get( 'sourceTemplate', [ 'as' => 'sourceTemplate', 'uses' => 'SourceTemplateController@edit' ] );
	Route::patch( 'sourceTemplate', [ 'as' => 'sourceTemplate.update', 'uses' => 'SourceTemplateController@update' ] );
});

Route::group(['middleware' => ['basic', 'session']], function() {
	Route::get('/', 'HomeController@index');

	Route::get('signup', 'UsersController@signup')->name('signup');
	Route::post('signup', 'UsersController@signupSubmit')->name('signupSubmit');

	Route::get('login', 'Auth\AuthController@getLogin');
	Route::post('login', 'Auth\AuthController@postLogin')->name('login');
	Route::get('logout', 'Auth\AuthController@logout')->name('logout');

    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);
});

Route::group(['middleware' => ['basic']], function () {
    Route::get('data/variables/{ids}', 'DataController@variables');

    Route::get('config/{chartId}', 'ViewController@config');
    Route::get('testall', 'ViewController@testall');
    Route::get('latest', 'ViewController@latest');
    Route::any('{all}.csv', ['uses' => 'ViewController@exportCSV']);
    Route::any('{all}.svg', ['uses' => 'ViewController@exportSVG']);
    Route::any('{all}.png', ['uses' => 'ViewController@exportPNG']);
    Route::any('{all}.export', ['uses' => 'ViewController@show']);
    Route::any('{all}', ['uses' => 'ViewController@show']);
});



/*use App\Chart;
Route::get( '/temp', function() {

	$charts = Chart::all();
	foreach($charts as $chart) {
		$config = json_decode($chart->config);
		echo $chart->id. ': '. $config->{'chart-description'};
		echo '<br />';
	}

});*/
