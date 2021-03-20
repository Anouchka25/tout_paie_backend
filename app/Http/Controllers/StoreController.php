<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\ResponseData;
use App\Store;
use App\StoreAddress;
use App\StoreCategory;
use App\StoreCategoriesItems;
use App\StoreProductGroup;
use App\StoreRatings;
use App\StoreFavourite;
use App\StoreComments;
use App\StoreDepartment;
use App\StoreProducts;
use App\Department;
use App\StoreAdvertisements;
use App\HomeCategories;
use Mail;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use App\Http\Controllers\UploadController;
use Validator;

class StoreController extends Controller {

	//Save Store
	public function saveStore(Request $request) {
		$storeReqData = $request->all();
		$store = new Store;
		$storeAddress = new StoreAddress;
		$responseData = new ResponseData;
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreName.required'=>'enter_store_name',
			'StoreName.unique'=>'store_name_allready_exits',
			'Description.required'=>'enter_store_description',
			'TelephoneNumber.required'=>'enter_store_phone_no',
			'DeliveryPrice.required'=>'enter_store_delivery_price',
			'MinimumDelivery.required'=>'enter_store_min_for_delivery',
			'StoreLogo.required'=>'select_store_logo',
			'StoreLogo.image'=>'only_image_allowed',
			'StoreLogo.uploaded'=>'only_image_allowed',	
			'StoreCategoryID.required'=>'select_type_of_product',
			'IsNonPhysicalStore.required'=>'is_non_physical_store',
			'StoreLink.url'=>'url_format_invalid',
			'BackgroundPhoto.mimes'=>'only_image_or_video_allowed',	
			'BackgroundPhoto.uploaded'=>'unable_to_upload_file',
			'Addresses.required'=>'select_address',	
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreName' => 'required|unique:tblStore,StoreName',
			'Description' => 'required',
			'TelephoneNumber' => 'required',
			'DeliveryPrice' => 'required',
			'MinimumDelivery' => 'required',
			'StoreLogo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
			'StoreCategoryID' => 'required|array',
			'Addresses' => 'required|array',
			'IsNonPhysicalStore' => 'required',
			'StoreLink' => 'url',
			'BackgroundPhoto' => 'max:10000kb|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg',
		],$messsages);
	
		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}

		if($storeReqData['IsNonPhysicalStore']=='Y'){
			$messsages = array(
				'DepartmentID.required'=>'select_department',
			);
			$validator = Validator::make($request->all(), [
			'DepartmentID' => 'required|array',
			],$messsages);

			if ($validator->fails()) {
				$msg = $validator->errors()->first();
				$responseData->message = $msg;
				$responseData->status = 'Error';	
				$response = array(
					$responseData
				);
				return json_encode($response);
			}
		}
		//Laravel validation end

		try {
			\DB::beginTransaction();
			$store->StoreName = $storeReqData['StoreName'];
			if (input::file('StoreLogo') != '') {
				$file = input::file('StoreLogo');
				$uploadController = new UploadController;
				$store->StoreLogo = $uploadController->uploadFile($file);
			}else{
				$store->StoreLogo = '';
			}

			if (input::file('BackgroundPhoto') != '') {
				$file = input::file('BackgroundPhoto');
				$uploadController = new UploadController;
				$store->BackgroundPhoto = $uploadController->uploadFile($file);
			}else{
				$store->BackgroundPhoto = '';
			}
			
			$store->Presentation = $storeReqData['Presentation'];
			$store->Description = $storeReqData['Description'];
			$store->TelephoneNumber = $storeReqData['TelephoneNumber'];
			$store->DeliveryPrice = $storeReqData['DeliveryPrice'];
			$store->MinimumDelivery = $storeReqData['MinimumDelivery'];
			$store->ShowOnHomepage = 
				isset($storeReqData['ShowOnHomepage'])?$storeReqData['ShowOnHomepage']:'';
			$store->CategoryType = 
				isset($storeReqData['CategoryType'])?$storeReqData['CategoryType']:'';
			$store->ShippingCost = 
				isset($storeReqData['ShippingCost'])?$storeReqData['ShippingCost']:0;
			$store->IsActive = $storeReqData['IsActive'];
			$store->IsNonPhysicalStore = $storeReqData['IsNonPhysicalStore'];
			$store->StoreLink = $storeReqData['StoreLink'];
			$response = $store->save();
			try {
				//Save store multiple address
				$Addresses = $storeReqData['Addresses'];
				$storeAddress=[];

					for($i=0;$i<sizeof($Addresses);$i++) {
						$encode =json_decode($Addresses[$i]);
						$storeAddress[] = array(
							'StreetName' =>$encode->StreetName,
							'PostalCode' =>$encode->PostalCode,
							'City' =>$encode->City,
							'AddressType' =>$encode->AddressType,
							'Lat' =>$encode->Lat,
							'Lng' =>$encode->Lng,
							'StoreID' => $store->StoreID,
						);
					}
					//Batch insert to reduce to many DB callls
					StoreAddress::insert($storeAddress);

					//Add Store Department
					$DepartmentID = $storeReqData['DepartmentID'];
					$storeDept=[];
					for($j=0;$j<sizeof($DepartmentID);$j++) {
						$storeDept[] = array(
							'DepartmentID' =>$storeReqData['DepartmentID'][$j],
							'StoreID' => $store->StoreID,
						);
					}
					//Batch insert to reduce to many DB callls
					StoreDepartment::insert($storeDept);

					//Add Store Product Types
					$StoreCategoryID = $storeReqData['StoreCategoryID'];
					$storeProd=[];
					for($j=0;$j<sizeof($StoreCategoryID);$j++) {
						$storeProd[] = array(
							'StoreCategoryID' =>$storeReqData['StoreCategoryID'][$j],
							'StoreID' => $store->StoreID,
						);
					}
				StoreProducts::insert($storeProd);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			}catch (Exception $e) {
				\DB::rollback();
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}			
		} catch (Exception $e) {
			\DB::rollback();
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		\DB::commit();
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}
	

	//Update Store info and store address
	public function updateStore(Request $request, $id){

		$storeReqData = $request->all();
		$user = new User;
		$store = new Store;
		$store_address = new StoreAddress;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreName.required'=>'enter_store_name',
			'StoreName.unique'=>'store_name_allready_exits',
			'Description.required'=>'enter_store_description',
			'TelephoneNumber.required'=>'enter_store_phone_no',
			'DeliveryPrice.required'=>'enter_store_delivery_price',
			'MinimumDelivery.required'=>'enter_store_min_for_delivery',
			'StoreCategoryID.array'=>'select_type_of_product',
			'IsNonPhysicalStore.required'=>'is_non_physical_store',
			'StoreLink.url'=>'url_format_invalid',
			'Addresses.required'=>'select_address',	
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreName' => 'required|unique:tblStore,StoreName,'.$id.',StoreID',
			'Description' => 'required',
			'TelephoneNumber' => 'required|numeric',
			'DeliveryPrice' => 'required|numeric',
			'MinimumDelivery' => 'required|numeric',
			'StoreCategoryID' => 'array',
			'IsNonPhysicalStore' => 'required',
			'StoreLink' => 'url',
			'Addresses' => 'required|array',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}

		if($storeReqData['IsNonPhysicalStore']=='Y'){
			$messsages = array(
			'DepartmentID.required'=>'select_department',
			);
			$validator = Validator::make($request->all(), [
			'DepartmentID' => 'required|array',
			],$messsages);

			if ($validator->fails()) {
				$msg = $validator->errors()->first();
				$responseData->message = $msg;
				$responseData->status = 'Error';	
				$response = array(
					$responseData
				);
				return json_encode($response);
			}
		}
		//Laravel validation end
			
		try {
			\DB::beginTransaction();
			$data['StoreName'] = $storeReqData['StoreName'];
			if (input::file('StoreLogo') != '') {
				$file = input::file('StoreLogo');
				$uploadController = new UploadController;
				$data['StoreLogo'] = $uploadController->uploadFile($file);
			}
			if (input::file('BackgroundPhoto') != '') {
				$file = input::file('BackgroundPhoto');
				$uploadController = new UploadController;
				$data['BackgroundPhoto'] = $uploadController->uploadFile($file);
			}

			$data['IsActive'] = $storeReqData['IsActive'];
			$data['Presentation'] = $storeReqData['Presentation'];
			$data['Description'] = $storeReqData['Description'];
			$data['TelephoneNumber'] = $storeReqData['TelephoneNumber'];
			$data['DeliveryPrice'] = $storeReqData['DeliveryPrice'];
			$data['MinimumDelivery'] = $storeReqData['MinimumDelivery'];
			$data['ShowOnHomepage'] = isset($storeReqData['ShowOnHomepage'])
			?$storeReqData['ShowOnHomepage']:'';
			$data['CategoryType'] = isset($storeReqData['CategoryType'])
			?$storeReqData['CategoryType']:'';
			$data['ShippingCost'] = isset($storeReqData['ShippingCost'])
			?$storeReqData['ShippingCost']:0;
			$data['IsNonPhysicalStore'] = $storeReqData['IsNonPhysicalStore'];		
			$data['StoreLink'] = $storeReqData['StoreLink'];
			$store::where('StoreID', $id)->update($data);
			
			try {
					$Addresses = $storeReqData['Addresses'];
					$address =sizeof($Addresses);
					if($address >=1){
						StoreAddress::where('StoreID', $id)->delete();
					}

					$Addresses = $storeReqData['Addresses'];
				    $storeAddress=[];
					for($i=0;$i<sizeof($Addresses);$i++) {
						
						$encode =json_decode($Addresses[$i]);
						$storeAddress[] = array(
							'StreetName' =>$encode->StreetName,
							'PostalCode' =>$encode->PostalCode,
							'City' =>$encode->City,
							'AddressType' =>$encode->AddressType,
							'Lat' =>$encode->Lat,
							'Lng' =>$encode->Lng,
							'StoreID' => $id,
						);
					}
					//Batch insert to reduce to many DB callls
					StoreAddress::insert($storeAddress);

					//Add Store Department
					$DepartmentID = $storeReqData['DepartmentID'];
					$dept =sizeof($DepartmentID);
					if($dept >=1 || $storeReqData['IsNonPhysicalStore']=='Y'){
						StoreDepartment::where('StoreID', $id)->delete();
					}
					$storeDept=[];
					for($j=0;$j<sizeof($DepartmentID);$j++) {
						$storeDept[] = array(
							'DepartmentID' =>$storeReqData['DepartmentID'][$j],
							'StoreID' => $id,
						);
					}
					//Batch insert to reduce to many DB callls
					StoreDepartment::insert($storeDept);

					//Add Store Product Types
					$StoreCategoryID = $storeReqData['StoreCategoryID'];
					$cat =sizeof($StoreCategoryID);

					if($cat >=1){
						StoreProducts::where('StoreID', $id)->delete();
					}

					$storeProd=[];
					for($j=0;$j<sizeof($StoreCategoryID);$j++) {
						$storeProd[] = array(
							'StoreCategoryID' =>$storeReqData['StoreCategoryID'][$j],
							'StoreID' => $id,
						);
					}
					//Batch insert to reduce to many DB callls
					StoreProducts::insert($storeProd);
				$responseData->message = 'Success';
				$responseData->status = 'Success';

				}catch (Exception $e) {
				\DB::rollback();
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		} catch (Exception $e) {
			\DB::rollback();
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		\DB::commit();
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Delete store
	public function deleteStore($ids) {

		$data = new Store;
		$storeAddress = new StoreAddress;
		$responseData = new ResponseData;
		//$ids = $request->Sid;
		$flag = Store::where('StoreID', $ids)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			Store::where('StoreID', $ids)->delete();
			StoreAddress::where('StoreID', $ids)->delete();
			StoreDepartment::where('StoreID', $ids)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}

		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	
	//Disable store
	public function disableStoreByStoreId(Request $request, $id) {
		$store = new Store;
		$responseData = new ResponseData;
		$flag = Store::where('StoreID', $id)->exists();
		if ($flag) {
			try {
				$data['IsActive'] = 'N';
				$store::where('StoreID', $id)->update($data);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}	
		}else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}


	//Disable store
	public function enableStoreByStoreId(Request $request, $id) {
		$store = new Store;
		$responseData = new ResponseData;
		$flag = Store::where('StoreID', $id)->exists();
		if ($flag) {
			try {
				$data['IsActive'] = 'Y';
				$store::where('StoreID', $id)->update($data);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}	
		}else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Get All Store 
	public function getAllStoreName(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblStore')
		   ->select('tblStore.StoreID','tblStore.StoreName')
		   ->where('IsActive', 'Y')
          ->orderBy('tblStore.StoreID', 'DSC')
          ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Store By User ID
	public function getStoreByUserId($id) {
		$responseData = new ResponseData;
        $store =DB::table('tblUsers')
			->join('tblStore', 'tblStore.StoreID', '=','tblUsers.StoreID')
		   ->select('tblUsers.*','tblStore.*')
		   ->where('tblUsers.UserID',$id)
		   ->get();
		   
		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->get();
			$store[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('*')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeDepartment = $allStoreDept;	
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Store By Id
	public function getStoreById($id) {

		$responseData = new ResponseData;
		$store =DB::table('tblStore')
		->select('tblStore.*')
		->where('StoreID', $id)
        ->orderBy('tblStore.StoreID', 'DSC')
        ->get();
		
		$storeAddress =DB::table('tblStoreAddress')
		->select('tblStoreAddress.*')
		->where('StoreID', $id)
        ->orderBy('tblStoreAddress.StoreID', 'DSC')
        ->get();

        $storeDept =DB::table('tblStoreDepartment')
        ->leftjoin('tblDepartment', 'tblDepartment.DepartmentID', '=', 'tblStoreDepartment.DepartmentID')
		->Where('tblStoreDepartment.StoreID', $id)
		->select('tblStoreDepartment.*','tblDepartment.*')
        ->orderBy('tblStoreDepartment.StoreDepartment', 'DSC')
        ->get();

        $storeProducts =DB::table('tblStoreProducts')
        ->leftjoin('tblStoreCategory', 'tblStoreCategory.StoreCategoryID', '=', 'tblStoreProducts.StoreCategoryID')
		->Where('tblStoreProducts.StoreID', $id)
		->select('tblStoreProducts.*','tblStoreCategory.*')
        ->orderBy('tblStoreProducts.StoreProductID', 'DSC')
        ->get();

        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $id)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->get();

        foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);
		}	


        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = ['store'=>$store,'storeAddress'=>$storeAddress,'storeDept'=>$storeDept,'storeProducts'=>$storeProducts,'storeComments'=>$storeComments];
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Get All Store data
	public function getAllStore(Request $request) {
		$request =  $request->all();
		$responseData = new ResponseData;
		
			$store =DB::table('tblStore')
			->leftjoin('tblStoreCategory', 'tblStoreCategory.StoreCategoryID', '=','tblStore.StoreCategoryID')
		   ->select('tblStore.*','tblStore.StoreID as StoreID','tblStoreCategory.*','tblStore.IsActive as IsActive')
           ->orderBy('tblStore.StoreID', 'DSC')
		   ->get();
		
		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('*')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeDepartment = $allStoreDept;	
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save Store Product
	public function saveStoreProduct(Request $request) {
		$storeProducts = new StoreProducts;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreCategoryID.required'=>'select_store_category_name',
			'ProductCategoryID.required'=>'select_product_category_name',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreCategoryID' => 'required',
			'ProductCategoryID' => 'required',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {
			$storeProducts->StoreCategoryID = $request->StoreCategoryID;
			$storeProducts->ProductCategoryID = $request->ProductCategoryID;
			$storeProducts->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update Store Product
	public function updateStoreProduct(Request $request, $id) {
		$storeProducts = new StoreProducts;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreCategoryID.required'=>'select_store_category_name',
			'ProductCategoryID.required'=>'select_product_category_name',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreCategoryID' => 'required',
			'ProductCategoryID' => 'required',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end

		try {
			$data['StoreCategoryID'] = $request->StoreCategoryID;
			$data['ProductCategoryID'] = $request->ProductCategoryID;
			$storeProducts::where('StoreProductID', $id)->update($data);
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Save User Favourite Store
	public function saveUserFavouriteStore(Request $request) {
		$storeFavourite = new StoreFavourite;
		$responseData = new ResponseData;
		$flag = StoreFavourite::where('StoreID', $request->StoreID)->where('UserID', $request->UserID)->exists();
			if ($flag) {
				$storeFavouriteData['StoreID'] = $request->StoreID;
				$storeFavouriteData['UserID'] = $request->UserID;
				$storeFavouriteData['Status'] = $request->Status;
				$storeFavourite::where('StoreID', $request->StoreID)->where('UserID', $request->UserID)->update($storeFavouriteData);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
				$responseData->data = $request->Status;

			} else {
				try {
					$storeFavourite->StoreID = $request->StoreID;
					$storeFavourite->UserID = $request->UserID;
					$storeFavourite->Status = 'Y';
					$storeFavourite->save();
					$responseData->message = 'Success';
					$responseData->status = 'Success';
					$responseData->data = 'Y';
				} catch (Exception $e) {
					$responseData->message = 'Error';
					$responseData->status = 'Error';
				}
			}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update User Favourite Store
	public function updateUserFavouriteStore(Request $request, $id) {
		$storeFavourite = new StoreFavourite;
		$responseData = new ResponseData;
			try {
				$storeFavouriteData['StoreID'] = $request->StoreID;
				$storeFavouriteData['UserID'] = $request->UserID;
				$storeFavouriteData['Status'] = $request->Status;
				$storeFavourite::where('StoreFavouritesID', $id)->update($storeFavouriteData);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
				$responseData->data = $request->Status;
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}
	
	//Get All User Fav Store 
	public function getUserFavouriteStore(Request $request, $id) {
		//$token = $request->header('token');

		$responseData = new ResponseData;
		$store =DB::table('tblStoreFavourites')
		->select('tblStoreFavourites.*','tblStore.*')

		->join('tblStore', 'tblStore.StoreID', '=','tblStoreFavourites.StoreID')

		->Where('tblStoreFavourites.Status', 'Y')
		->Where('tblStoreFavourites.UserID', $id)
		->orderBy('tblStoreFavourites.StoreFavouritesID', 'DSC')
		->get();

		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$store[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeDepartment = $allStoreDept;	
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get User Fav Store By User Id And Store Id
	public function isStoreFavToUser($store_id, $user_id) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreFavourites')
		->select('tblStoreFavourites.Status','tblStoreFavourites.StoreFavouritesID')
		->Where('tblStoreFavourites.StoreID', $store_id)
		->Where('tblStoreFavourites.UserID', $user_id)
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save User Store Comments
	public function saveUserStoreComments(Request $request) {
		$storeComments = new StoreComments;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Comment.required'=>'enter_comment',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Comment' => 'required',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end

		try {
			$storeComments->StoreID = $request->StoreID;
			$storeComments->UserID = $request->UserID;
			$storeComments->Comment = $request->Comment;
			$storeComments->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update User Store Comments
	public function updateUserStoreComments(Request $request, $comment_id) {
		$responseData = new ResponseData;
		$flag = StoreComments::where('StoreCommentID', $comment_id)
				->where('UserID', $request->UserID)->exists();
		if(empty($flag)){
			$responseData->message = 'you_are_not_able_to_edit_this_comment';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}
		//Laravel Validation start
		$messsages = array(
			'Rating' => 'required|numeric',
			'Comment.required'=>'enter_comment',
		);
		$validator = Validator::make($request->all(), [
			'Comment' => 'required',
		],$messsages);
		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end

		try {
			$storeComments['StoreID'] = $request->StoreID;
			$storeComments['UserID'] = $request->UserID;
			$storeComments['Comment'] = $request->Comment;
			StoreComments::where('StoreCommentID', $comment_id)->update($storeComments);
			 
			 if($request->Rating){

			$storeRatings['StoreID'] = $request->StoreID;
			$storeRatings['UserID'] = $request->UserID;
			$storeRatings['Rating'] = $request->Rating;
			storeRatings::where('CommentedOn', $request->CommentedOn)
			->where('UserID', $request->UserID)->update($storeRatings);
			 }
			




			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			echo "exception :"+$e;
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Delete Store Comments By User
	public function deleteStoreComment($comment_id, $user_id,$rating_id) {

		$responseData = new ResponseData;
		
		$flag = StoreComments::where('StoreCommentID', $comment_id)
				->where('UserID', $user_id)->exists();
		if($flag !=1){
			$responseData->message = 'you_are_not_able_to_delete_this_comment';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}
		
		$flag = StoreComments::where('StoreCommentID', $comment_id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreComments::where('StoreCommentID', $comment_id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}

		$flag = StoreRatings::where('StoreRatingID', $rating_id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreRatings::where('StoreRatingID', $rating_id)->delete();
		}

		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Delete Store Comments By Sysytem Administrator
	public function deleteStoreCommentBySA($comment_id) {

		$responseData = new ResponseData;			
		$flag = StoreComments::where('StoreCommentID', $comment_id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreComments::where('StoreCommentID', $comment_id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get All User Store Comments
	public function getStoreComments(Request $request, $id) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreComments')
		->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
		->leftjoin('tblStore', 'tblStoreComments.StoreID', '=', 'tblStore.StoreID')
		->leftjoin('tblStoreRatings',function($join){
            $join->on('tblStoreRatings.StoreID', '=', 'tblStoreComments.StoreID');
            $join->on('tblStoreRatings.CommentedOn', '=', 'tblStoreComments.CommentedOn');
        })
		->select('tblStore.*','tblStoreComments.*','tblUsers.*','tblStore.StoreID','tblStoreRatings.StoreRatingID','tblStoreRatings.Rating')
		->Where('tblStoreComments.StoreID', $id)
		->orderBy('tblStoreComments.StoreCommentID', 'DSC')
		->get();

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save User Store Ratings
	public function saveUserStoreRatings(Request $request) {
		$storeRatings = new StoreRatings;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Rating.required'=>'select_store_ratings',
			'Rating.numeric'=>'numeric_value_allowed',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Rating' => 'required|numeric',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {
			$storeRatings->StoreID = $request->StoreID;
			$storeRatings->UserID = $request->UserID;
			$storeRatings->Rating = $request->Rating;
			$storeRatings->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Get All User Store Comments
	public function getStoreRatings(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreRatings')
		->leftjoin('tblStore', 'tblStoreRatings.StoreID', '=', 'tblStore.StoreID')
		->select('tblStore.*','tblStoreRatings.*', DB::raw('Avg(Rating) as Rating'))
		->groupBy('tblStoreRatings.StoreID')
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save Store Category
	public function saveStoreCategory(Request $request) {
		$storeCatReqData = $request->all();
		$storeCategory = new StoreCategory;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreCategoryName.required'=>'enter_store_category',
			'StoreCategoryIconURL.required'=>'select_store_category_image',
			'StoreCategoryIconURL.image'=>'only_image_allowed',
			'StoreCategoryIconURL.uploaded'=>'only_image_allowed',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreCategoryName' => 'required',
			'StoreCategoryIconURL' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {
			$storeCategory->StoreCategoryName = $storeCatReqData['StoreCategoryName'];
			if (input::file('StoreCategoryIconURL') != '') {
				$file = input::file('StoreCategoryIconURL');
				$uploadController = new UploadController;
				$storeCategory->StoreCategoryIconURL = $uploadController->uploadFile($file);
			}
			$response = $storeCategory->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update Store Category
	public function updateStoreCategory(Request $request, $id) {
		$storeCatReqData = $request->all();
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreCategoryName.required'=>'enter_store_category',
			'StoreCategoryIconURL.image'=>'only_image_allowed',
			'StoreCategoryIconURL.uploaded'=>'only_image_allowed',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreCategoryName' => 'required',
			'StoreCategoryIconURL' => 'image|mimes:jpeg,png,jpg,gif,svg',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {
			$storeCategory['StoreCategoryName'] = $storeCatReqData['StoreCategoryName'];
			if (input::file('StoreCategoryIconURL') != '') {
				$file = input::file('StoreCategoryIconURL');
				$uploadController = new UploadController;
				$storeCategory['StoreCategoryIconURL'] = $uploadController->uploadFile($file);
			}
			StoreCategory::where('StoreCategoryID', $id)->update($storeCategory);
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Delete Store Category
	public function deleteStoreCategory($id) {

		$responseData = new ResponseData;
		$flag = StoreCategory::where('StoreCategoryID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreCategory::where('StoreCategoryID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Store Category
	public function getAllStoreCategory(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreCategory')
		   ->select('tblStoreCategory.*')
          ->orderBy('tblStoreCategory.StoreCategoryID', 'DSC')
          ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Store By Department Id
	public function getStoreByDepartmentId(Request $request, $id) {
		$responseData = new ResponseData;
		$store =DB::table('tblStore')
           ->join('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   ->select('tblStore.*','tblStoreDepartment.*', 'tblStore.StoreID as StoreID')
		    ->where('tblStoreDepartment.DepartmentID', $id)
		   ->groupBy('tblStore.StoreID')
           ->orderBy('tblStore.StoreID', 'DSC')
		   ->get();

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Flash Image To Show On Home Page
	public function getFlashImageToShowOnHomePage(Request $request, $id) {
		$responseData = new ResponseData;

		$DepartmentID = $id;
		$store =DB::table('tblStore')
		   ->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   ->select('tblStore.*','tblStoreDepartment.*', 'tblStore.StoreID as StoreID')
		   	->where('ShowOnHomepage', 'Y')
		   	->where('IsActive', 'Y')
		   	->orderBy('tblStore.StoreID', 'DSC')
		   	//->limit(8)
		   	->get();
		
		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblStore')
			->select('tblStore.*')
			->where('IsNonPhysicalStore', 'N')
			->where('IsActive', 'Y')
		    ->orderBy('tblStore.StoreID', 'DSC')
		   	->get();

		  $store_ids = array();

		 if($DepartmentID == 0){
			foreach ($store as $value) {  
				array_push($store_ids,$value->StoreID);
			}

		 }else{
		 	$query = $store->where('DepartmentID', $DepartmentID);
			foreach ($query as $value) {  
				array_push($store_ids,$value->StoreID);
			}
		 }
		
		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->StoreID);
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));
		$filterStore =DB::table('tblStore')
			->select('tblStore.*')
			->whereIn('tblStore.StoreID',$result)
			->limit(8)
			->orderBy('tblStore.StoreID', 'DSC')
			->get();

		foreach ($filterStore as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $filterStore[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$filterStore[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$filterStore[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeDepartment = $allStoreDept;	
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $filterStore;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Product Flash shell_exec(cmd)																
	public function getFlashSellOnHomePage(Request $request, $id) {								

		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblProductCalaloguePrice.*','tblStore.CategoryType','tblStoreDepartment.*')
		   ->join('tblProductCalaloguePrice', 'tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   
		   ->Where('AddFlashSell','=' ,'1')
		   ->where('tblStore.IsActive', 'Y')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		$DepartmentID = $id;
		
		
		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblStore.CategoryType')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->where('tblStore.IsActive', 'Y')
		   ->where('tblStore.IsNonPhysicalStore', 'N')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		$store_ids = array();

		if($DepartmentID == 0){
			foreach ($store as $value) {  
				array_push($store_ids,$value->ProductCatalogueID);
			}

		}else{
		 	$query = $store->where('DepartmentID', $DepartmentID);
			foreach ($query as $value) {  
				array_push($store_ids,$value->ProductCatalogueID);
			}
		}


		

		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->ProductCatalogueID);
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));

		$filterStore =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblProductCalaloguePrice.*','tblStore.CategoryType')
		   ->leftjoin('tblProductCalaloguePrice', 'tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->whereIn('tblProductCatalogue.ProductCatalogueID',$result)
		   ->Where('AddFlashSell','=' ,'1')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->limit(20)
		   ->get();
		

		$catlogueArray = array();
		$priceArray = array();	
		foreach ($filterStore as $key => $value) {
			if(array_key_exists($value->ProductCatalogueID, $catlogueArray)){

				if($priceArray[$value->ProductCatalogueID] > $value->SellPrice){

					$catlogueArray[$value->ProductCatalogueID] = $value;
					$priceArray[$value->ProductCatalogueID] = $value->SellPrice;
				}
			}else{

				$catlogueArray[$value->ProductCatalogueID] = $value;
				$priceArray[$value->ProductCatalogueID] = $value->SellPrice;
			}
		}

		foreach ($catlogueArray as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$catlogueArray[$key]->ProductCatalogueImages = $storeComments;
		}	


		foreach ($catlogueArray as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $catlogueArray[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$catlogueArray[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$catlogueArray[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$catlogueArray[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$catlogueArray[$key]->storeDepartment = $allStoreDept;	
		}	

		$resultArray = array();
		foreach ($catlogueArray as $value) {
			$resultArray[] = $value;
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $resultArray;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Search Store or Product By Department Name
	public function searchStoreOrProductByDepartment($dept_id,$name=null) {
		$responseData = new ResponseData;
		try{
		$store =DB::table('tblStore')
		   ->leftjoin('tblProductCatalogue', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   ->select('tblStore.*','tblStoreDepartment.*','tblProductCatalogue.*', 'tblStore.StoreID as StoreID')
		
			->orwhere(function ($result) use ($name) {
				return $result->where('tblProductCatalogue.ProductName', 'LIKE', '%'. $name . '%')
				->whereNotNull('tblProductCatalogue.ProductName');
			})

		    ->orwhere(function ($result) use ($name) {
				return $result->where('tblStore.StoreName', 'LIKE', '%'. $name . '%')
				->whereNotNull('StoreName');
			})
		   	->orderBy('tblStore.StoreID', 'DSC')
		   	->where('IsActive', 'Y')
		   	->get();
		$query = $store->where('DepartmentID', $dept_id);

		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblStore')
			->select('tblStore.*')
		    ->where(function ($result) use ($name) {
				return $result->where('tblStore.StoreName', 'LIKE', '%'. $name . '%')
				->whereNotNull('StoreName');
			})

		->where('IsNonPhysicalStore', 'N')
		->where('IsActive', 'Y')
	    ->orderBy('tblStore.StoreID', 'DSC')
	   	->get();

		$store_ids = array();
		foreach ($query as $value) {  
			array_push($store_ids,$value->StoreID);
		}

		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->StoreID);
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));
		
		$store =DB::table('tblStore')
			->select('tblStore.*')
			->whereIn('tblStore.StoreID',$result)
			->get();

		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$store[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeDepartment = $allStoreDept;	
		}	

		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;			
		}catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Search Store or Product
	public function searchStoreByProductNameOrStoreName($name=null) {
		$responseData = new ResponseData;
		try{
		$store =DB::table('tblStore')
		   ->leftjoin('tblProductCatalogue', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->select('tblStore.*','tblProductCatalogue.*', 'tblStore.StoreID as StoreID')
		
			->orwhere(function ($result) use ($name) {
				return $result->where('tblProductCatalogue.ProductName', 'LIKE', '%'. $name . '%')
				->whereNotNull('tblProductCatalogue.ProductName');
			})

		    ->orwhere(function ($result) use ($name) {
				return $result->where('tblStore.StoreName', 'LIKE', '%'. $name . '%')
				->whereNotNull('StoreName');
			})

		   	->orderBy('tblStore.StoreID', 'DSC')
		   	->where('IsActive', 'Y')
		   	->get();

		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);
		}

		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;			
		}catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Store Product By ID
	public function getStoreProductById($id) {
		$responseData = new ResponseData;
		$storeProducts =StoreProducts::where('StoreProductID', $id)->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $storeProducts;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Shops By Dept
	public function getAllShops() {
		$responseData = new ResponseData;
		try{

		$DepartmentID = Session::get('DepartmentID');
		$store =DB::table('tblStore')
		   ->leftjoin('tblProductCatalogue', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   ->select('tblStore.*','tblStoreDepartment.*','tblProductCatalogue.*', 'tblStore.StoreID as StoreID')
		   	->orderBy('tblStore.StoreID', 'DSC')
		   	->where('IsActive', 'Y')
		   	->get();
		$query = $store->where('DepartmentID', $DepartmentID);

		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblStore')
			->select('tblStore.*')
			->where('IsNonPhysicalStore', 'N')
			->where('IsActive', 'Y')
		    ->orderBy('tblStore.StoreID', 'DSC')
		   	->get();

		$store_ids = array();
		foreach ($query as $value) {  
			array_push($store_ids,$value->StoreID);
		}

		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->StoreID);
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));
		$filterStore =DB::table('tblStore')
			->select('tblStore.*')
			->whereIn('tblStore.StoreID',$result)
			->get();

		foreach ($filterStore as $key => $value) {  
	
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $filterStore[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$filterStore[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('*')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeDepartment = $allStoreDept;	

		}	

		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $filterStore;			
		}catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Get Store By Store category
	public function getStoreByCategory(Request $request, $id) {
		$responseData = new ResponseData;
		$store =DB::table('tblStore')
		   ->select('tblStore.*')
		   ->where('StoreCategoryID', $id)
		   ->where('IsActive', 'Y')
           ->orderBy('StoreID', 'DSC')
		   ->get();
		foreach ($store as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);
		}	
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) 
	{
		$point1_lat = floatval($point1_lat);
		$point1_long = floatval($point1_long);
		$point2_lat = floatval($point2_lat);
		$point2_long = floatval($point2_long);

		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
			 
			switch($unit) {
				case 'km':
				$distance = $degrees * 111.13384; 
				break;
				case 'mi':
				$distance = $degrees * 69.05482;
				break;
				case 'nmi':
				$distance =  $degrees * 59.97662; 
			}

			// Your code here!
		   // $details = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyDTe_6hYgBy-zcArWdJc8GngdrgzCvED0U&origins='$point1_lat','$point1_long'&destinations='$point2_lat','$point2_long'&mode=driving&sensor=false";
		   //  $json = file_get_contents($details);

		   //  $details = json_decode($json, TRUE);

		   //  echo "<pre>"; print_r($details); echo "</pre>";
			return round($distance, $decimals);
	}



	//Save Store Comment and Ratings
	public function saveStoreCommentRating(Request $request) {
		$storeRatings = new StoreRatings;
		$responseData = new ResponseData;
		$storeComments = new StoreComments;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Rating.required'=>'select_store_ratings',
			'Rating.numeric'=>'numeric_value_allowed',
			'Comment.required'=>'enter_comment',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Rating' => 'required|numeric',
			'Comment' => 'required',
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {
			$storeRatings->StoreID = $request->StoreID;
			$storeRatings->UserID = $request->UserID;
			$storeRatings->Rating = $request->Rating;
			$storeRatings->save();

			$storeComments->StoreID = $request->StoreID;
			$storeComments->UserID = $request->UserID;
			$storeComments->Comment = $request->Comment;
			$storeComments->save();

			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Get Store Rating By Store Id
	public function getStoreRatingsByStoreId($id) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreRatings')
		->select('tblStoreRatings.*', DB::raw('Avg(Rating) as Rating'))
		->where('tblStoreRatings.StoreID',$id)
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Product Flash on home page not in flash but in home
	public function getFlashProductOnHomePage(Request $request, $id) {
		$responseData = new ResponseData;

		$store =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblProductCalaloguePrice.*','tblStore.CategoryType','tblStoreDepartment.*')
		   ->join('tblProductCalaloguePrice', 'tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
		   ->Where('AddFlashSell','=' ,'2')
		   ->where('tblStore.IsActive', 'Y')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		$DepartmentID = $id;

		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblStore.CategoryType')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->where('tblStore.IsActive', 'Y')
		   ->where('tblStore.IsNonPhysicalStore', 'N')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		$query = $store->where('DepartmentID', $DepartmentID);

		
		$store_ids = array();

		if($DepartmentID == 0){
			foreach ($store as $value) {  
				array_push($store_ids,$value->ProductCatalogueID);
			}

		}else{
		 	$query = $store->where('DepartmentID', $DepartmentID);
			foreach ($query as $value) {  
				array_push($store_ids,$value->ProductCatalogueID);
			}
		}

		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->ProductCatalogueID);
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));

		

		$filterStore =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*','tblProductCalaloguePrice.*','tblStore.CategoryType')
		   ->leftjoin('tblProductCalaloguePrice', 'tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->whereIn('tblProductCatalogue.ProductCatalogueID',$result)
		   ->Where('AddFlashSell','=', '2')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->limit(16)
		   ->get();




		$catlogueArray = array();
		$priceArray = array();	
		foreach ($filterStore as $key => $value) {
			if(array_key_exists($value->ProductCatalogueID, $catlogueArray)){

				if($priceArray[$value->ProductCatalogueID] > $value->SellPrice){

					$catlogueArray[$value->ProductCatalogueID] = $value;
					$priceArray[$value->ProductCatalogueID] = $value->SellPrice;
				}
			}else{

				$catlogueArray[$value->ProductCatalogueID] = $value;
				$priceArray[$value->ProductCatalogueID] = $value->SellPrice;
			}
		}

		foreach ($catlogueArray as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$catlogueArray[$key]->ProductCatalogueImages = $storeComments;
		}	


		foreach ($catlogueArray as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $catlogueArray[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$catlogueArray[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$catlogueArray[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$catlogueArray[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$catlogueArray[$key]->storeDepartment = $allStoreDept;	
		}	

		$resultArray = array();
		foreach ($catlogueArray as $value) {
			$resultArray[] = $value;
		}	


        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $resultArray;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save Store Adv
	public function saveStoreAdv(Request $request) {
		$storeCatReqData = $request->all();
		$storeCategory = new StoreAdvertisements;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'StoreImage.required'=>'select_store_image',
			// 'StoreImage.image'=>'only_image_allowed',
			// 'StoreImage.uploaded'=>'only_image_allowed',
			// 'StoreURL.required'=>'please_select_department',
			'DepartmentID.required'=>'please_select_department',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
			// 'StoreURL' => 'required',
			// 'DepartmentID' => 'required',
			'StoreID' => 'required'
		],$messsages);

		if ($validator->fails()) {
			$msg = $validator->errors()->first();
			$responseData->message = $msg;
			$responseData->status = 'Error';	
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		//Laravel validation end
		try {

			$flag = $storeCategory::where('DepartmentID', $storeCatReqData['DepartmentID'])->exists();
			
			if ($flag) {
				$responseData->message = 'department_allready_exits';
				$responseData->status = 'Error';
			
			}else{
				$storeCategory->StoreID = $storeCatReqData['StoreID'];
				$storeCategory->DepartmentID = $storeCatReqData['DepartmentID'];
				if (input::file('StoreImage') != '') {
					$file = input::file('StoreImage');
					$uploadController = new UploadController;
					$storeCategory->StoreImage = $uploadController->uploadFile($file);
				}
				$response = $storeCategory->save();

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			}

		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update store Adv
	public function updateStoreAdv(Request $request,$id) {
		$storeCatReqData = $request->all();
		$responseData = new ResponseData;

		try{
			// $storeCategory['StoreURL'] = $storeCatReqData['StoreURL'];
			$storeCategory['StoreID'] = $storeCatReqData['StoreID'];
			$storeCategory['DepartmentID'] = $storeCatReqData['DepartmentID'];
			if (input::file('StoreImage') != '') {
				$file = input::file('StoreImage');
				$uploadController = new UploadController;
				$storeCategory['StoreImage'] = $uploadController->uploadFile($file);
			}
			
			// StoreAdvertisements::where('StoreAdvertisementID', $storeCatReqData['StoreAdvertisementID'])->update($storeCategory);
			StoreAdvertisements::where('StoreAdvertisementID', $id)->update($storeCategory);
			
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			

		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	public function deleteStoreAdv(Request $request,$id) {
		$storeCatReqData = $request->all();
		$responseData = new ResponseData;
		$flag = StoreAdvertisements::where('StoreAdvertisementID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreAdvertisements::where('StoreAdvertisementID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}

		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Store Adv
	public function getStoreAdvAll(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreAdvertisements')
		   ->select('tblStoreAdvertisements.*')
		   ->orderBy('tblStoreAdvertisements.StoreAdvertisementID', 'DESC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	public function getAllStoreAdv(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreAdvertisements')
		->select('tblStoreAdvertisements.*')
		->orderBy('tblStoreAdvertisements.StoreAdvertisementID', 'DESC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Store Adv
	public function getStoreAdv(Request $request,$deptID) {
		$responseData = new ResponseData;
		$store =DB::table('tblStoreAdvertisements')
		->join('tblStore', 'tblStore.StoreID', '=', 'tblStoreAdvertisements.StoreID')
		//->leftjoin('tblStoreComments', 'tblStoreComments.StoreID', '=', 'tblStoreAdvertisements.StoreID')
		//->leftjoin('tblStoreRatings', 'tblStoreRatings.StoreID', '=', 'tblStoreAdvertisements.StoreID')
		->where('tblStoreAdvertisements.DepartmentID', $deptID)
		   ->select('tblStoreAdvertisements.*','tblStore.*','tblStore.StoreID')
		   ->orderBy('tblStoreAdvertisements.StoreAdvertisementID', 'DSC')
		   ->limit(1)
		   ->get();



							
							
			foreach ($store as $key => $value) {  

			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $store[$key]->Rating = floatval($data[0]->Rating);
			


	        $storeComments =DB::table('tblStoreComments')
	        ->select('tblStoreComments.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$store[$key]->storeAddress = $address;	        

		
				
		}
		
		
		$resultArray = array();
		foreach ($store as $value) {
			$resultArray[] = $value;
		}	

		



        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $resultArray;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	
	//Get Store ForHome Category by store category id and dept id
	public function getStoreForHomeCategory($id,$dept_id) {

	  	$responseData = new ResponseData;

		$store =DB::table('tblStoreProducts')
			->join('tblStore', 'tblStore.StoreID', '=','tblStoreProducts.StoreId')
			->leftjoin('tblStoreDepartment', 'tblStore.StoreID', '=', 'tblStoreDepartment.StoreID')
			->where('tblStoreProducts.StoreCategoryID', $id)
			->where('IsActive', 'Y')
		   ->select('tblStore.*','tblStoreDepartment.*')
           ->orderBy('tblStore.StoreID', 'DSC')
		   ->get();
		
		$store_ids = array();

		//Get IsNonPhysical Store
		$primaryStore =DB::table('tblStore')
			->select('tblStore.*')
			->where('IsNonPhysicalStore', 'N')
			->where('IsActive', 'Y')
		    ->orderBy('tblStore.StoreID', 'DSC')
		   	->get();

		$non_physical_store_ids = array();
		foreach ($primaryStore as $value) {  
			array_push($non_physical_store_ids,$value->StoreID);
		}


		if($dept_id != 0){
			$query = $store->where('DepartmentID', $dept_id);
			foreach ($query as $value) {  
				array_push($store_ids,$value->StoreID);
			}

		}else{
			foreach ($store as $value) {  
				array_push($store_ids,$value->StoreID);
			}
		}

		$result = array_unique(array_merge($store_ids, $non_physical_store_ids));
		

		$filterStore =DB::table('tblStore')
			->select('tblStore.*')
			->whereIn('tblStore.StoreID',$result)
			->get();

		foreach ($filterStore as $key => $value) {  
			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();
	        $filterStore[$key]->Rating = floatval($data[0]->Rating);

	        $storeComments =DB::table('tblStoreComments')
	        ->join('tblUsers', 'tblUsers.UserID', '=','tblStoreComments.UserID')
			->select('tblStoreComments.*','tblUsers.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$filterStore[$key]->Comments = $storeComments;

			$address = DB::table('tblStoreAddress')
			->select('tblStoreAddress.AddressType','tblStoreAddress.StreetName','tblStoreAddress.PostalCode','tblStoreAddress.City')
			->where('tblStoreAddress.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeAddress = $address;	        

			$allStoreProductsTypes = DB::table('tblStoreProducts')
			->select('tblStoreProducts.StoreCategoryID')
			->where('tblStoreProducts.StoreID', $value->StoreID)
	        ->get();	        

			$filterStore[$key]->storeProductsTypes = $allStoreProductsTypes;	

			$allStoreDept = DB::table('tblStoreDepartment')
			->select('tblStoreDepartment.DepartmentID')
			->where('tblStoreDepartment.StoreID', $value->StoreID)
	        ->get();	        
			$filterStore[$key]->storeDepartment = $allStoreDept;	
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $filterStore;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Get All Store data using km show restos and shops
	public function getAllLocalStore(Request $request) {
		$request = $request->all();
		$lng = isset($request['Lng']) ? $request['Lng']:0;//55.45834
		$lat = isset($request['Lat']) ? $request['Lat']:0;//-20.88837

		$StoreCategoryID = isset($request['StoreCategoryID']) ? $request['StoreCategoryID']:'';

		// $lng = 75.1750;
		// $lat = 19.1761;

		$responseData = new ResponseData;
	    $store =DB::table('tblStore')
	       ->join('tblStoreAddress', 'tblStoreAddress.StoreID', '=','tblStore.StoreID')
		   ->select('tblStore.*','tblStore.StoreID as StoreID','tblStoreAddress.Lat','tblStoreAddress.Lng')
		   ->where('tblStoreAddress.AddressType','P')
		   ->where('tblStore.IsActive','Y')
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();

		foreach ($store as $key => $value) {  

			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();

	        $store[$key]->Rating = floatval($data[0]->Rating);
	        $point1 = array("lat" => "$value->Lat", "long" => "$value->Lng");
			$point2 = array("lat" => "$lat" , "long" => "$lng");
			$km = $this->distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
	        $store[$key]->DistanceInKM = floatval($km);

	        $storeComments =DB::table('tblStoreComments')
			->select('tblStoreComments.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;
		}	
		
		$filterResult = $store->where('DistanceInKM','<', '10');

		$store_ids = array();
		foreach ($filterResult as $value) {  
			array_push($store_ids,$value->StoreID);
		}

		$result = array_unique($store_ids);

		if($StoreCategoryID !=''){

			$store =DB::table('tblStore')
		   ->join('tblStoreAddress', 'tblStoreAddress.StoreID', '=','tblStore.StoreID')

		   ->rightjoin('tblStoreProducts', 'tblStoreProducts.StoreID', '=','tblStore.StoreID')

		  ->select('tblStore.*','tblStore.StoreID as StoreID','tblStoreAddress.Lat','tblStoreAddress.Lng','tblStoreProducts.StoreCategoryID')
			->when($StoreCategoryID !='', function ($query) use ($StoreCategoryID) {
				return $query->Where('tblStoreProducts.StoreCategoryID', $StoreCategoryID);
   			})
		   ->where('tblStoreAddress.AddressType','P')
		   ->whereIn('tblStore.StoreID',$result)
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();

		}else{
			
			$store =DB::table('tblStore')
		   ->join('tblStoreAddress', 'tblStoreAddress.StoreID', '=','tblStore.StoreID')
		  ->select('tblStore.*','tblStore.StoreID as StoreID','tblStoreAddress.Lat','tblStoreAddress.Lng')
		   ->where('tblStoreAddress.AddressType','P')
		   ->whereIn('tblStore.StoreID',$result)
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();
		}


		foreach ($store as $key => $value) {  

			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();

	        $store[$key]->Rating = floatval($data[0]->Rating);
	        $point1 = array("lat" => "$value->Lat", "long" => "$value->Lng");
			$point2 = array("lat" => "$lat" , "long" => "$lng");
			$km = $this->distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
	        $store[$key]->DistanceInKM = floatval($km) * 1000;

	        $storeComments = DB::table('tblStoreComments')
			->select('tblStoreComments.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;

		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		
		return json_encode($response);
	}


	//Get All Store data using km show restos and shops
	public function getAllLocalStoreByShopAndMode($type) {
	
		$responseData = new ResponseData;
		
		if($type == 'Shop'){
			$request_ids = [13, 11, 10, 9, 6, 3, 2, 1];
			$store2 =DB::table('tblStore')
			->leftjoin('tblStoreProducts', 'tblStoreProducts.StoreId', '=','tblStore.StoreID')
		   ->whereNotIn('tblStoreProducts.StoreCategoryID', $request_ids)
		   ->select('tblStore.*','tblStore.StoreID as StoreID')
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();
		}else{

			$request_ids = [1, 10, 2];
			$store2 =DB::table('tblStore')
			->leftjoin('tblStoreProducts', 'tblStoreProducts.StoreId', '=','tblStore.StoreID')
		   ->whereIn('tblStoreProducts.StoreCategoryID', $request_ids)
		   ->select('tblStore.*','tblStore.StoreID as StoreID')
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();
		}
		

		$ids = array();
		foreach ($store2 as $key => $value) {  
			array_push($ids, $value->StoreID);
	    }

	    $store_ids = array_unique($ids);

	    $store =DB::table('tblStore')
		   ->whereIn('tblStore.StoreID', $store_ids)
		   ->select('tblStore.*','tblStore.StoreID as StoreID')
           ->orderBy('tblStore.StoreID', 'DSC')
           ->distinct('tblStore.StoreID')
		   ->get();

		foreach ($store as $key => $value) {  

			$data = DB::table('tblStoreRatings')
			->select(DB::raw('Avg(Rating) as Rating'))
			->where('tblStoreRatings.StoreID', $value->StoreID)
	        ->get();

	        $store[$key]->Rating = floatval($data[0]->Rating);

	        $store[$key]->DistanceInKM = 0;

	        $storeComments =DB::table('tblStoreComments')
			->select('tblStoreComments.*')
			->Where('tblStoreComments.StoreID', $value->StoreID)
			->orderBy('tblStoreComments.StoreCommentID', 'DSC')
			->limit(15)
			->get();
			$store[$key]->Comments = $storeComments;
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


}

//https://maps.googleapis.com/maps/api/distancematrix/json?origins=Shevgaon&destinations=ahmednagar&mode=driving&language=pl-PL&key=AIzaSyDLsSpp4G4_P0UqeSV5upj_Wu2KkXtUK9A
