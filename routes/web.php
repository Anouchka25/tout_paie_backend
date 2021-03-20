<?php

//Home
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function () {
	return view('welcome');
});

Route::group(['middleware' => ['api', 'cors']], function () {
//User Authentication
Route::post('/user/register', 'AuthenticationController@registerUser');
Route::post('/user/mpin/register', 'AuthenticationController@registerUserWithMPIN');
Route::post('/user/login', 'AuthenticationController@login');
Route::post('/user/mobilethrough/login', 'AuthenticationController@loginThroughMobileNumber');
Route::post('/user/password/forgot', 'AuthenticationController@forgotPassword');
Route::get('/user/token', 'AuthenticationController@getTokenByTokenId');
Route::post('/user/password/reset', 'AuthenticationController@resetUserPassword');
Route::get('/user/logout/{id}', 'AuthenticationController@userLogout');

//Department
Route::get('/departments', 'DepartmentController@getAllDepartments');

// Route::group(['middleware' => 'auth'], function(){

//User
Route::post('/user/update/Profile/{id}', 'AuthenticationController@updateUserProfile');
Route::get('/user/delete/{id}', 'AdminController@deleteUser');
Route::post('/user/deactivate/{id}', 'AdminController@deactivateUser');
Route::post('/user/activate/{id}', 'AdminController@activateUser');
Route::get('/user/profile/{id}', 'AdminController@getUserProfileByUserId');
Route::get('/users', 'AdminController@getAllUsers');
Route::post('/user/password/change/{id}', 'AuthenticationController@changePassword');

//User Profile photo change
Route::post('/updateUserProfilePhoto', 'AuthenticationController@updateUserProfilePhoto');


Route::post('/user/Profile/photo/remove/{user_id}', 'AuthenticationController@removeUserProfilePhoto');


//Friend
Route::get('/FriendSuggestion/get/{id}', 'FriendController@getAllFriendSuggestion');
Route::post('/FriendRequest/save', 'FriendController@saveFriendRequest');
Route::get('/FriendRequests/get/{id}', 'FriendController@getAllFriendRequestsSend');
Route::post('/acceptRequest/{id}', 'FriendController@saveAcceptRequest');
Route::post('/cancelFriendRequest/{id}', 'FriendController@FriendRequestDelete');
Route::get('/get/FriendList/{id}', 'FriendController@getAllFriendList');
Route::post('/unFriend/{id}', 'FriendController@unFriend');
Route::get('/FriendRequests/recieved/{id}', 'FriendController@getAllFriendRequests');



//Create Manager
Route::post('/manager/save', 'AdminController@saveManager');
Route::get('/users/backoffice', 'AdminController@getAllBackOfficeUsers');
Route::post('/user/backoffice/profile/{id}', 'AdminController@updateBackOfficeUserProfile');

//Get Store By Manager ID 
Route::get('/user/store/get/{id}', 'StoreController@getStoreByUserId');

//Department
Route::get('/department/change/{id}', 'DepartmentController@updateDepartment');

//Store
Route::post('/store/save', 'StoreController@saveStore');
Route::post('/store/update/{id}', 'StoreController@updateStore');
Route::get('/store/delete/{id}', 'StoreController@deleteStore');
Route::post('/store/disable/{id}', 'StoreController@disableStoreByStoreId');
Route::post('/store/enable/{id}', 'StoreController@enableStoreByStoreId');
Route::get('/store/get/{id}', 'StoreController@getStoreById');
Route::get('/store/getName', 'StoreController@getAllStoreName');
Route::get('/store', 'StoreController@getAllStore');
Route::get('/store/byDepartment/{id}', 'StoreController@getStoreByDepartmentId');


//Get Stores by store category name(id)
Route::get('/store/category/{id}', 'StoreController@getStoreByCategory');


//Store Category  to show on home page and at the time of store add in product types in store.
Route::post('/store/category/save', 'StoreController@saveStoreCategory');
Route::post('/store/category/update/{id}', 'StoreController@updateStoreCategory');
Route::get('/store/category/delete/{id}', 'StoreController@deleteStoreCategory');
Route::get('/store/category', 'StoreController@getAllStoreCategory');


//Store Favourites
Route::post('/store/favourite/save', 'StoreController@saveUserFavouriteStore');
Route::post('/store/favourite/update/{id}', 'StoreController@updateUserFavouriteStore');
Route::get('/store/favourite/get/{id}', 'StoreController@getUserFavouriteStore');

Route::get('/store/favourite/get/{storeid}/{userid}', 'StoreController@isStoreFavToUser');

//Store Comments
Route::post('/store/comment/save', 'StoreController@saveUserStoreComments');
Route::post('/store/comment/update/{comment_id}', 'StoreController@updateUserStoreComments');
Route::get('/store/comment/delete/{store_comment_id}/{user_id}/{rating_id}', 'StoreController@deleteStoreComment');

Route::post('/store/comment/deleteBySA/{id}', 'StoreController@deleteStoreCommentBySA');

Route::get('/store/comment/get/{id}', 'StoreController@getStoreComments');

//Store Ratings
Route::post('/store/rating/save', 'StoreController@saveUserStoreRatings');
Route::get('/store/rating/get', 'StoreController@getStoreRatings');
Route::get('/store/rating/get/{id}', 'StoreController@getStoreRatingsByStoreId');

//Store Comment and Rating save
Route::post('/store/comment/rating/save', 'StoreController@saveStoreCommentRating');


//Store Product
Route::post('/storeProduct/save', 'StoreController@saveStoreProduct');
Route::post('/storeProduct/update/{id}', 'StoreController@updateStoreProduct');
Route::post('/storeProduct/delete/{id}', 'StoreController@deleteStoreProduct');
Route::get('/storeProduct/get/{id}', 'StoreController@getStoreProductById');


//Product group Add product group 54
Route::post('/productGroup/save', 'ProductController@saveStoreProductGroup');
Route::post('/productGroup/update/{id}', 'ProductController@updateProductGroup');
Route::get('/productGroup/delete/{id}', 'ProductController@deleteProductGroup');
Route::get('/productGroup/get/{id}', 'ProductController@getProductGroupById');
Route::get('/productGroups', 'ProductController@getAllProductGroups');


//Get Product Group By Store ID
Route::get('/store/productGroups/{store_id}', 'ProductController@getProductGroupsByStoreID');

//Get Product list by store id and product group id

Route::get('/product/list/{store_id}/{product_group_id}', 'ProductCatalogueController@getProductListByStoreIDAndProductGroupId');


//Product Catalogue
Route::post('/productCatalogue/save', 'ProductCatalogueController@saveProductCatalogue');
Route::post('/productCatalogue/update/{id}', 'ProductCatalogueController@updateProductCatalogue');
Route::post('/productCatalogue/delete/{id}', 'ProductCatalogueController@deleteProductCatalogue');

Route::post('/productCatalogue/disable/{id}', 'ProductCatalogueController@disableProductByProductId');
Route::post('/productCatalogue/enable/{id}', 'ProductCatalogueController@enableProductByProductId');

Route::post('/deleteProductCatalogueImages/delete/{id}', 'ProductCatalogueController@deleteProductCatalogueImages');

Route::get('/productCatalogue/get', 'ProductCatalogueController@getProductCatalog');
Route::get('/productCatalogue/forAdmin/get', 'ProductCatalogueController@getProductCatalogforAdmin');
Route::post('/productCatalogue/flash/status/{id}', 'ProductCatalogueController@statusForFlashSell');
Route::get('/productCatalogue/get/{id}', 'ProductCatalogueController@getProductCatalogById');
Route::get('/productCatalogue/group/get/{id}', 'ProductCatalogueController@getProductCatalogByStoreProductGroupId');



//Product Category use to show product belong to which category
Route::post('/productCategory/save', 'ProductCategoryController@saveProductCategory');
Route::post('/productCategory/update/{id}', 'ProductCategoryController@updateProductCategory');
Route::get('/productCategory/delete/{id}', 'ProductCategoryController@deleteProductCategory');
Route::get('/productCategory', 'ProductCategoryController@getAllProductCategory');


//Posts
Route::post('/post/save', 'PostController@saveUserPost');
Route::post('/post/mobile/save', 'PostController@saveUserMobilePost');
Route::post('/post/update/{id}', 'PostController@updateUserPost');
Route::get('/post/delete/{id}', 'PostController@deletePost');
Route::post('/post/delete/documents/{id}', 'PostController@deletePostImages');
Route::get('/post/get/byPostId/{id}', 'PostController@getPostByPostId');
Route::get('/post/get/byUserId/{id}', 'PostController@getAllPostByUserId');
Route::post('/post/sendMailTo/admin/{id}', 'PostController@sendMailToAdmin');

//get my post and my frinds posts
Route::get('/post/byUserId/friends/{id}', 'PostController@getMyAndFreindsPosts');

//Change post status
Route::post('/post/status/update/{id}', 'PostController@updatePostStatus');

//Post Comments
Route::post('/post/comment/save', 'PostController@savePostComments');
Route::get('/posts', 'PostController@getAllPost');
Route::get('/post/comment/count/{id}', 'PostController@getPostCommentCount');
Route::get('/post/comment/{id}', 'PostController@getPostComment');

//Post Likes
Route::post('/post/like/save', 'PostController@savePostLikes');
Route::get('/post/like/count/{id}', 'PostController@getPostLikeCount');

//Advertisements
Route::post('/advertisements/save', 'AdvertisementsController@saveAdvertisements');
Route::post('/advertisements/mobile/save', 'AdvertisementsController@saveMobileAdvertisements');
Route::post('/advertisements/update/{id}', 'AdvertisementsController@updateAdvertisements');
Route::post('/advertisements/update/status/{id}', 'AdvertisementsController@updateAdvertisementsStatus');
Route::get('/advertisements/delete/{id}', 'AdvertisementsController@deleteAdvertisements');
Route::get('/advertisements/get/sell', 'AdvertisementsController@getAdvertisementsForSell');
Route::get('/advertisements/get/buy', 'AdvertisementsController@getAdvertisementsForBuy');
Route::get('/advertisements/images/{id}', 'AdvertisementsController@getAdvertisementsImagesById');
Route::get('/advertisements/get/{id}', 'AdvertisementsController@getAdvertisementsById');

Route::get('/user/advertisements/get/{id}', 'AdvertisementsController@getAllAdvertisementsByUserId');

//get department wise adv
Route::get('/advertisements/get/sell/{dept_id}', 'AdvertisementsController@getAdvertisementsForSellByDept');
Route::get('/advertisements/get/buy/{dept_id}', 'AdvertisementsController@getAdvertisementsForBuyByDept');

//Product Basket
Route::post('/basket/product/save', 'BasketController@saveProductBasket');
Route::post('/basket/product/save/product/type', 'BasketController@saveProductBasketForProductType');
Route::post('/basket/product/savepluse', 'BasketController@saveProductBasketPluse');
Route::post('/basket/product/update/{id}', 'BasketController@updateProductBasket');
Route::get('/basket/product/delete/{id}', 'BasketController@deleteBasketProduct');
Route::get('/basket/get/{id}', 'BasketController@getProductBasketByUserId');
Route::get('/basket/product/get/{id}', 'BasketController@getProductBasketByBasketId');


//Decrease product basket quantity with ProductBasketID
Route::post('/basket/product/quantity/change/{id}', 'BasketController@reduceProductBasketQuantity');

//For Single product in basket
Route::post('/basket/product/quantity/remove/ByIds', 'BasketController@reduceProductBasketQuantityForSingleItem');

//Set Address Type to basket (AddressType)
Route::post('/basket/address/set/{user_id}', 'BasketController@setAddressTypeForBasket');

//Set Delivery Mode for basket (DeliveryMode)
Route::post('/basket/deliverymode/set/{user_id}/{store_id}', 'BasketController@setDeliveryModeForBasket');

//get product info in basket by ProductCatalogueID, StoreID and UserID
Route::post('/basket/product/info', 'BasketController@getBasketProductInfo');

//Orders
Route::post('/order/save', 'OrderController@saveOrder');
Route::post('/order/update/{id}', 'OrderController@updateOrder');
Route::post('/order/status/update/{id}', 'OrderController@updateOrderStatus');
Route::get('/order/get/{id}', 'OrderController@getOrderByOrderId');
Route::get('/order/user/get/{id}', 'OrderController@getUserOrders');
Route::get('/orders', 'OrderController@getAllOrders');
Route::get('/orders/user/store/{id}', 'OrderController@getAllOrdersByStoreId');
Route::get('/order/store/{id}', 'OrderController@getOrdersByStoreId');
Route::get('/order/{status}', 'OrderController@getOrdersByStatus');


Route::post('/order/store/status/update/{store_id}/{order_id}', 'OrderController@updateOrderStatusForStoreLevel');


//Get Order Master Status
Route::get('/order/master/status', 'OrderController@getOrderMasterStatus');

//Update user address for delivery of order.
Route::post('/user/address/Profile/{id}', 'AuthenticationController@updateUserProfileAddress');

//Get flash images to show on home page
Route::get('/home/images/{dept_id}', 'StoreController@getFlashImageToShowOnHomePage');

//Get Product Flash Sell
Route::get('/home/product/flashSell/{dept_id}', 'StoreController@getFlashSellOnHomePage');

//Get Product Flash Sell
Route::get('/home/product/flash/{dept_id}', 'StoreController@getFlashProductOnHomePage');


//Search Stores or Store Products
Route::get('/search/{dept}/{name?}', 'StoreController@searchStoreOrProductByDepartment');
Route::get('/search/store/product/{name?}', 'StoreController@searchStoreByProductNameOrStoreName');

//Get all store and restoes using km - km pending
// Route::post('/local/{restorant_id}', 'StoreController@getAllLocalStore');
Route::post('/local', 'StoreController@getAllLocalStore');

//get store  by home store categories
Route::get('/home/store/get/{id}/{dept_id}', 'StoreController@getStoreForHomeCategory');


//Using user log in dept get all stores 
Route::get('/shops', 'StoreController@getAllShops');

//Payment information
Route::post('/payment/save', 'PaymentInfoController@savePaymentInformation');
Route::post('/payment/update/{id}', 'PaymentInfoController@updatePaymentInformation');
Route::get('/payment/delete/{id}', 'PaymentInfoController@deletePaymentInformation');
Route::get('/payment/get/{id}', 'PaymentInfoController@getPaymentInformationByID');
Route::get('/payments', 'PaymentInfoController@getPaymentInformation');
Route::get('/user/payment/info/{user_id}', 'PaymentInfoController@getPaymentInformationByUserID');

//Store Adv
Route::post('/store/adv/save', 'StoreController@saveStoreAdv');
Route::get('/store/adv/get/{deptID}', 'StoreController@getStoreAdv');
Route::get('/store/adv/get/all', 'StoreController@getStoreAdvAll');
Route::get('/store/all/adv/get/adv', 'StoreController@getAllStoreAdv');

Route::post('/store/adv/update/{id}', 'StoreController@updateStoreAdv');
Route::post('/store/adv/delete/{id}', 'StoreController@deleteStoreAdv');

//Token Test
Route::post('/testToken', 'AdminController@testToken');

//Payment for stripe
Route::post('stripe', 'PaymentInfoController@stripePost')->name('stripe.post');


//Sports order Json API
Route::post('/sportOrders', 'UserAdsController@sportOrders');

//type = Shop/Mode
Route::get('/shop/mode/{type}', 'StoreController@getAllLocalStoreByShopAndMode');

//Get product catalouge by store user
Route::get('/productCatalogue/group/bystore/user/{user_id}', 'ProductCatalogueController@getProductCatalogByStoreProductGroupIdByStoreUser');

Route::get('/productGroups/user/{user_id}', 'ProductController@getAllProductGroupsByUser');


Route::get('testNotifications', 'SendNotification@testNotifications');

Route::get('/deleteAdvertisementsImage/{AdvertisementsPhotoID}', 'AdvertisementsController@deleteAdvertisementsImage');

Route::post('/setDeviceId', 'AdminController@setDeviceId');

});
// });