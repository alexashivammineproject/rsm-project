<?php

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
use App\Http\Controllers\Admin\SitemapController;
// Guest Area
Route::get('/', 'Guest\HomeController@homenew')->name('homepage');

Route::get('post', function () {
    return redirect('/');  
});
Route::get('other-products', 'Guest\HomeController@others');
Route::get('documents/pdf-document/{id}', 'Guest\HomeController@getDocument');

Route::get('contactus', 'Guest\HomeController@contactus');
//Route::post('contactus', 'Guest\HomeController@postcontactus');
Route::match(['POST'],'contactus', 'Guest\HomeController@postcontactus')->name('contactus');
Route::post('enquire', 'Guest\HomeController@submitEnquireModal')->name('enquire');

Route::get('thank-you', 'Guest\HomeController@thankYouPage')->name('thank-you');
Route::get('search', 'Guest\BlogsController@search');
Route::get('blog/', 'Guest\BlogsController@blog');
Route::get('blog/{blog}', 'Guest\BlogsController@single');
Route::get('category/{category}', 'Guest\BlogsController@category');
Route::post('post/comment', 'Guest\BlogsController@comment');

Route::post('subscribe', 'Guest\HomeController@subscribe');
Route::get('subscribe/verify/{token}', 'Guest\HomeController@subscribeVerify');

Route::get('feed', 'FeedsController@index');

Route::get('product/categorylist', 'Guest\HomeController@categorylist');
Route::get('product/catproducts/{slug}', 'Guest\HomeController@catgorylistshow');
Route::get('product/viewdetail/{slug}', 'Guest\HomeController@productshow');
Route::get('searchpage', 'Guest\HomeController@searchpage');
Route::get('resizeImage', 'ImageController@resizeImage');
Route::post('resizeImagePost',['as'=>'resizeImagePost','uses'=>'ImageController@resizeImagePost']);
// User Area
Auth::routes();
Route::get('auth/verify/{token}', 'Auth\RegisterController@verify');
Route::get('content/view/{pages}', 'Guest\HomeController@pages');

// Admin Area
Route::get('unauthorized', function () {
    return view('unauthorized');
});
// OTP routes removed - direct access with auth only
Route::resource('admin/enquiry', 'Admin\EnqueryController', ['except' => ['create', 'store' ]])->middleware('auth');
 
// Admin Routes
Route::prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard')->middleware('role:dashboard');

    // Blogs
    Route::get('blogsData', 'Admin\BlogsController@blogsData')->name('blogs.ajaxData');

    Route::get('blogs/bulk/trash', 'Admin\BlogsController@bulkTrash')->name('blogs.bulkTrash');
    Route::get('blogs/bulk/restore', 'Admin\BlogsController@bulkRestore')->name('blogs.bulkRestore');
    Route::get('blogs/trashed', 'Admin\BlogsController@trashed')->name('blogs.trashedData');
    Route::get('blogsTrashedData', 'Admin\BlogsController@blogsAjaxTrashedData')->name('blogs.ajaxTrashedData');
    Route::get('blogs/restore/{id}', 'Admin\BlogsController@restore')->name('blogs.restore');
    Route::get('blogs/delet/permanent/{id}', 'Admin\BlogsController@permanentDelets')->name('blogs.permanentDelet');
    Route::get('blogs/trash/empty', 'Admin\BlogsController@emptyTrash')->name('blogs.emptyTrash');
    Route::get('blogs/publish/status/{id}', 'Admin\BlogsController@updateActiveStatus')->name('blogs.publishStatus');

    Route::resource('blogs', 'Admin\BlogsController');



    // CMS
    Route::get('cmsData', 'Admin\CmsController@cmsData')->name('cms.ajaxData');


    Route::resource('cms', 'Admin\CmsController');


    // Testimonial
    Route::get('testimonialsData', 'Admin\TestimonialController@testimonialsData')->name('testimonials.ajaxData');


    Route::resource('testimonial', 'Admin\TestimonialController');

    // Products
    
    Route::get('productsData', 'Admin\ProductsController@productsData')->name('products.ajaxData');


    Route::get('products/bulk/trash', 'Admin\ProductsController@bulkTrash')->name('products.bulkTrash');
    Route::get('products/bulk/restore', 'Admin\ProductsController@bulkRestore')->name('products.bulkRestore');

    Route::get('products/trashed', 'Admin\ProductsController@trashed')->name('products.trashedData');

    Route::get('productsTrashedData', 'Admin\ProductsController@productsAjaxTrashedData')->name('products.ajaxTrashedData');

    Route::get('products/restore/{id}', 'Admin\ProductsController@restore')->name('products.restore');
    Route::get('products/delet/permanent/{id}', 'Admin\ProductsController@permanentDelets')->name('products.permanentDelet');
    Route::get('products/trash/empty', 'Admin\ProductsController@emptyTrash')->name('products.emptyTrash');
    Route::get('products/publish/status/{id}', 'Admin\ProductsController@updateActiveStatus')->name('products.publishStatus');

    Route::get('products/deletemulti/{id}', 'Admin\ProductsController@deletemulti')->name('products.deletemulti');
    
    Route::resource('products', 'Admin\ProductsController');

    // Product Category

    Route::resource('productcategories', 'Admin\ProductCategoriesController');
    Route::get('pcategoriesData', 'Admin\ProductCategoriesController@pcategoriesData')->name('pcategoriesData.ajaxData');
    Route::get('pcategoriesData/bulk/delete', 'Admin\ProductCategoriesController@bulkDelete')->name('pcategoriesData.bulkDelete');
    Route::get('catlist/ajax-select', 'Admin\ProductCategoriesController@categoriesAjaxSelectData')->name('catlist.ajaxSelectData');

    // Product Sub Category
    Route::resource('subproductcategories', 'Admin\SubproductCategoriesController');
    Route::get('subpcategoriesData', 'Admin\SubproductCategoriesController@subpcategoriesData')->name('subpcategoriesData.ajaxData');

    Route::get('subpcategoriesData/bulk/delete', 'Admin\SubproductCategoriesController@bulkDelete')->name('subpcategoriesData.bulkDelete');
    Route::get('subcatlist/ajax-select', 'Admin\SubproductCategoriesController@categoriesAjaxSelectData')->name('subcatlist.ajaxSelectData');


    // Categories
    Route::get('categories/bulk/delete', 'Admin\CategoriesController@bulkDelete')->name('categories.bulkDelete');
    Route::get('categories/ajax-select', 'Admin\CategoriesController@categoriesAjaxSelectData')->name('categories.ajaxSelectData');
    Route::get('categoriesData', 'Admin\CategoriesController@categoriesData')->name('categories.ajaxData');
    Route::resource('categories', 'Admin\CategoriesController');

    // Comments
    Route::get('commentsData', 'Admin\CommentsController@commentsData')->name('comments.ajaxData');
    Route::get('comments/bulk/delete', 'Admin\CommentsController@bulkDelete')->name('comments.bulkDelete');
    Route::get('comments/bulk/spam', 'Admin\CommentsController@bulkSpam')->name('comments.bulkSpam');
    Route::get('comments/spam/status/{id}', 'Admin\CommentsController@updateSpamStatus')->name('comments.spamStatus');
    Route::resource('comments', 'Admin\CommentsController', ['except' => [
        'create', 'store'
    ]]);

    // Users
    Route::get('usersData', 'Admin\UsersController@usersData')->name('users.ajaxData');
    Route::get('users/bulk/delete', 'Admin\UsersController@bulkDelete')->name('users.bulkDelete');
    Route::get('users/active/status/{id}', 'Admin\UsersController@updateActiveStatus')->name('users.activeStatus');
    Route::get('users/roles/{id}/edit', 'Admin\UsersController@editRoles')->name('users.editRoles');
    Route::put('users/roles/{id}', 'Admin\UsersController@updateRoles')->name('users.updateRoles');
    Route::resource('users', 'Admin\UsersController');

    // Settings
    Route::get('settings', 'Admin\SettingsController@index')->name('settings.index');
    Route::put('settings/update', 'Admin\SettingsController@update')->name('settings.update');

    // Subscribers
    Route::get('subscribers/active/{id}', 'Admin\SubscribersController@updateActiveStatus')->name('subscribers.activeStatus');
    Route::get('subscribersData', 'Admin\SubscribersController@subscribersData')->name('subscribers.ajaxData');
    Route::resource('subscribers', 'Admin\SubscribersController', ['except' => [
        'create', 'store', 'show'
    ]]);
	
	 // Enquires
	
	 // Sitemap
	Route::get('upload-sitemap', function() {
			return view('admin.sitemap.upload');
		})->name('sitemap.form');
	Route::post('upload-sitemap', [SitemapController::class, 'upload'])->name('sitemap.upload');

    // Email Recipients Management
    Route::get('email-recipients', 'Admin\EmailRecipientsController@index')->name('email-recipients.index');
    Route::post('email-recipients', 'Admin\EmailRecipientsController@store')->name('email-recipients.store');
    Route::put('email-recipients/{id}', 'Admin\EmailRecipientsController@update')->name('email-recipients.update');
    Route::delete('email-recipients/{id}', 'Admin\EmailRecipientsController@destroy')->name('email-recipients.destroy');
    Route::get('email-recipients/{id}/toggle', 'Admin\EmailRecipientsController@toggleStatus')->name('email-recipients.toggle');
    
    // CRM Integration Settings
    Route::get('crm-settings', 'Admin\CrmSettingsController@index')->name('crm-settings.index');
    Route::put('crm-settings', 'Admin\CrmSettingsController@update')->name('crm-settings.update');
    Route::get('crm-settings/test', 'Admin\CrmSettingsController@test')->name('crm-settings.test');
    
    // Email Logs - View email send history
    Route::get('email-logs', 'Admin\EnqueryController@index')->name('email-logs.index')->middleware('role:manage_settings');
});


Route::any('blog/{url}', function(){
    return redirect('/404');
})->where('url', '.*');

