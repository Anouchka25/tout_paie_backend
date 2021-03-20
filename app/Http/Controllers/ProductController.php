<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\ResponseData;
use App\StoreProductGroup;
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

class ProductController extends Controller {

	//Save Store Product Groups
	public function saveStoreProductGroup(Request $request) {
		$requestData = $request->all();
		$data = new StoreProductGroup;
		$responseData = new ResponseData;
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'GroupName.required'=>'enter_group_name',
			'GroupPhotoURL.required'=>'select_group_photo',
			'GroupPhotoURL.image'=>'only_image_allowed',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'GroupName' => 'required',
			'GroupPhotoURL' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
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
			$data->StoreID = $requestData['StoreID'];
			$data->GroupName = $requestData['GroupName'];
			if (input::file('GroupPhotoURL') != '') {
				$file = input::file('GroupPhotoURL');
				$uploadController = new UploadController;
				$data->GroupPhotoURL = $uploadController->uploadFile($file);
			}else{
				$data->GroupPhotoURL = '';
			}

			$data->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} catch (Exception $e) {
			$responseData->message = $e;
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update Store Product Groups
	public function updateProductGroup(Request $request, $id) {
		$requestData = $request->all();
		$storeProductGroup = new StoreProductGroup;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'GroupName.required'=>'enter_group_name',
			'GroupPhotoURL.required'=>'select_group_photo',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'GroupName' => 'required',
			'GroupPhotoURL' => 'required',
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
			$data['StoreID'] = $requestData['StoreID'];
			$data['GroupName'] = $requestData['GroupName'];
			if (input::file('GroupPhotoURL') != '') {
				$file = input::file('GroupPhotoURL');
				$uploadController = new UploadController;
				$data['GroupPhotoURL'] = $uploadController->uploadFile($file);
			}
			$storeProductGroup::where('StoreProductGroupID', $id)->update($data);
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

	//Delete Product Group
	public function deleteProductGroup($id) {
		
		$responseData = new ResponseData;
		$flag = StoreProductGroup::where('StoreProductGroupID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			StoreProductGroup::where('StoreProductGroupID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Get Product Group By ID
	public function getProductGroupById($id) {
		$responseData = new ResponseData;
		$storeProductGroup =StoreProductGroup::where('StoreProductGroupID', $id)->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $storeProductGroup;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Get All Product Group
	public function getAllProductGroups() {
		$responseData = new ResponseData;
		$storeProductGroup =DB::table('tblStoreProductGroup')
		   ->select('tblStoreProductGroup.*','tblStore.StoreName')
		   ->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblStoreProductGroup.StoreID')
		   ->orderBy('tblStoreProductGroup.StoreProductGroupID', 'DSC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $storeProductGroup;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Product Group By Store ID
	public function getProductGroupsByStoreID($store_id) {
		$responseData = new ResponseData;
		$storeProductGroup =DB::table('tblStoreProductGroup')
		   ->select('tblStoreProductGroup.*')
		   ->where('StoreID', $store_id)
		   ->orderBy('tblStoreProductGroup.StoreProductGroupID', 'DSC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $storeProductGroup;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Product Group
	public function getAllProductGroupsByUser($user_id) {
		$responseData = new ResponseData;
		$user = new User;
		$user_data = $user->select('StoreID')->where('UserID', $user_id)->first();
		$store_id = $user_data['StoreID'];

		$storeProductGroup =DB::table('tblStoreProductGroup')
		   ->select('tblStoreProductGroup.*','tblStore.StoreName')
		   ->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblStoreProductGroup.StoreID')
		   ->Where('tblStoreProductGroup.StoreID', $store_id)
		   ->orderBy('tblStoreProductGroup.StoreProductGroupID', 'DSC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $storeProductGroup;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
}
