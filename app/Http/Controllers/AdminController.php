<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\Store;
use App\ResponseData;
// use Maatwebsite\Excel\Facades\Excel;
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

class AdminController extends Controller {

	//Delete users
	public function deleteUser($id) {
		$responseData = new ResponseData;
		$flag = User::where('UserID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			User::where('UserID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Deactivate User
	public function deactivateUser(Request $request, $id) {
		$user = new User;
		$responseData = new ResponseData;
		$flag = User::where('UserID', $id)->exists();
		if ($flag) {
			try {
				$data['isActive'] = 'N';
				$user::where('UserID', $id)->update($data);
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

	//Activate User
	public function activateUser(Request $request, $id) {
		$user = new User;
		$responseData = new ResponseData;
		$flag = User::where('UserID', $id)->exists();
		if ($flag) {
			try {
				$data['isActive'] = 'Y';
				$user::where('UserID', $id)->update($data);
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
	

	//Get User Profile By User Id
	public function getUserProfileByUserId(Request $request, $id) {
		$responseData = new ResponseData;
		$user =DB::table('tblUsers')
		->select('tblUsers.*')
		->Where('tblUsers.UserID', $id)
		->get();

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $user;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Users
	public function getAllUsers() {
		$responseData = new ResponseData;
		$user =User::where('Role', 'U')->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $user;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Manager
	public function getAllBackOfficeUsers() {

		$responseData = new ResponseData;
		$user =DB::table('tblUsers')
	        ->leftjoin('tblStore', 'tblStore.StoreID', '=','tblUsers.StoreID')
			->select('tblUsers.*','tblStore.StoreName')
			->Where('tblUsers.Role','!=', 'U')
			->orderBy('tblUsers.UserID', 'DSC')
			->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $user;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save Manager
	public function saveManager(Request $request) {
		$requestData = $request->all();
		$user = new User;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Email.required'=>'Please_enter_user_email',
			'password.required'=>'Please_enter_password',
			'FirstName.required'=>'Please_enter_user_first_name',
			'LastName.required'=>'Please_enter_user_last_name',
			'confirm_password.required'=>'Please_enter_confirm_password',
			'Email.unique'=>'Email_id_allready_exits',
			'Email.email'=>'enter_valid_email_address',
			'Role.required'=>'Please_select_user_role',
			//'DateOfBirth.date'=>'enter_valid_date_format',
			// 'ProfilePhotoURL.image'=>'only_image_allowed',
			// 'ProfilePhotoURL.uploaded'=>'only_image_allowed',
			'password.min'=>'password_must_contains_8_char',	
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Email' => 'required|email|max:255|unique:tblUsers,Email',
			'password' => 'required|max:255|min:8',
			'FirstName' => 'required|max:255',
			'LastName' => 'required|max:255',
			'confirm_password'=>'required|max:255',
			'Role' => 'required',
			//'DateOfBirth'=>'date',
			// 'ProfilePhotoURL' => 'image|mimes:jpeg,png,jpg,gif,svg',
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

		if ($requestData['password'] == $requestData['confirm_password']) {
			try {
				$user->FirstName 	= $requestData['FirstName'];
				$user->LastName 	= $requestData['LastName'];
				$user->Email 		= $requestData['Email'];
				$user->RememberToken = '';
				$user->Role = $requestData['Role'];

				$user->StoreID = 0;
				if(isset($requestData['StoreID'])){
					$user->StoreID = $requestData['StoreID'];
				}

				if (input::file('ProfilePhotoURL') != '') {
					$file = input::file('ProfilePhotoURL');
					$uploadController = new UploadController;
					$user->ProfilePhotoURL = $uploadController->uploadFile($file);
				}else{
					$user->ProfilePhotoURL = '';
				}

				$birth_date ='1001-01-01';// isset($requestData['DateOfBirth'])? $requestData['DateOfBirth']:'';
                $user->DateOfBirth = date('Y-m-d', strtotime($birth_date));
				// if($birth_date !='' || $birth_date !=null){
				// 	$user->DateOfBirth = date('Y-m-d', strtotime($birth_date));
				// }else{
				// 	$user->DateOfBirth = null;
				// }

				$user->MobileNumber = isset($requestData['MobileNumber'])? 
					$requestData['MobileNumber']:'';

				if(isset($requestData['CountryCode'])){
					$user->CountryCode = $requestData['CountryCode'];
				}
				
				$user->DepartmentID = isset($requestData['DepartmentID']) ?
					$requestData['DepartmentID']:0;

				$user->StreetName = isset($requestData['StreetName']) ?
					$requestData['StreetName']:'';

				$user->PostalCode = isset($requestData['PostalCode']) ? 
				$requestData['PostalCode']:'';

				if(isset($requestData['isActive'])){
					$user->isActive = $requestData['isActive'];
				}

				$user->City = isset($requestData['City']) ? $requestData['City']:'';

				$user->password = bcrypt($requestData['password']);
				$response = $user->save();
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		} else {
			$responseData->message = 'Password_and_confirm_password_does_not_match';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update Profile
	public function updateBackOfficeUserProfile(Request $request, $id) {
		$responseData = new ResponseData;
		$requestData = $request->all();		
		$data = new User;
		$flag = User::where('UserID', $id)->exists();
		if(!$flag){
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

		$userNewData = array();

		//Laravel Validation start
		//Messages set in laravel validation

		$messsages = array(
			'Email.required'=>'Please_enter_user_email',
			'FirstName.required'=>'Please_enter_user_first_name',
			'LastName.required'=>'Please_enter_user_last_name',
			'Email.email'=>'enter_valid_email_address',
			'Email.unique'=>'Email_id_allready_exits',
			'DateOfBirth.date'=>'enter_valid_date_format',
			'Role.required'=>'Please_select_user_role',	
			'password.min'=>'password_must_contains_8_char',
		);
		
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Email' => 'required|email|unique:tblUsers,Email,'.$id.',UserID',
			'FirstName' => 'required|max:255',
			'LastName'=>'required|max:255',
			'DateOfBirth'=>'date',
			'Role' => 'required',
			'password' => 'min:8',
			//'ProfilePhotoURL' => 'image|mimes:jpeg,png,jpg,gif,svg',
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

			if ($requestData['password'] == $requestData['confirm_password']) {
				try {

					$userNewData['Email'] = $requestData['Email'];
					$userNewData['FirstName'] = $requestData['FirstName'];
					$userNewData['LastName'] = $requestData['LastName'];
					$userNewData['password'] = bcrypt($requestData['password']);
					$userNewData['StoreID'] = isset($requestData['StoreID']) ? $requestData['StoreID']:0;

					$userNewData['MobileNumber'] = isset($requestData['MobileNumber'])? 
					$requestData['MobileNumber']:'';

					if(isset($requestData['CountryCode'])){
						$userNewData['CountryCode'] = $requestData['CountryCode'];
					}

					if(isset($requestData['isActive'])){
						$userNewData['isActive'] = $requestData['isActive'];
					}

					if (input::file('ProfilePhotoURL') != '') {
						$file = input::file('ProfilePhotoURL');
						$uploadController = new UploadController;
					  $userNewData['ProfilePhotoURL'] = $uploadController->uploadFile($file);
					}

					$birth_date = isset($requestData['DateOfBirth'])? $requestData['DateOfBirth']:'';

					if($birth_date !='' || $birth_date !=null){
						$userNewData['DateOfBirth'] = date('Y-m-d', strtotime($birth_date));
					}else{
						$userNewData['DateOfBirth'] = null;
					}
					
					$userNewData['DepartmentID'] = isset($requestData['DepartmentID']) ?
					 $requestData['DepartmentID']:0;

					$userNewData['StreetName'] = isset($requestData['StreetName']) ?
					 $requestData['StreetName']:'';

					$userNewData['PostalCode'] = isset($requestData['PostalCode']) ? 
					$requestData['PostalCode']:'';

					$userNewData['City'] = isset($requestData['City']) ? $requestData['City']:'';
					$userNewData['Role'] = $requestData['Role'];
					$response = $data::where('UserID', $id)->update($userNewData);

					$responseData->message = 'Success';
					$responseData->status = 'Success';

				} catch (Exception $e) {
					$responseData->message = 'Error';
					$responseData->status = 'Error';
				}
			} else {
				$responseData->message = 'Password_and_confirm_password_does_not_match';
				$responseData->status = 'Error';
			}

		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	public function testToken(Request $request)
	{
		header('Accept: application/json');
		$data = $request->all();
    	$header = $request->header('Authorization');
		$message = 'Fail';
		$status = 'Error';
		$response = array(
			'status' => $status,
			'message' => $message,
			'test' => $request->test,
			'headers' => [
		        'Authorization' => $header,
		        'Accept' => 'application/json',
			],
		);
		return json_encode($response);
	}


	//Update Profile
	public function removeUserProfilePhoto(Request $request, $id) {
		$responseData = new ResponseData;
		$requestData = $request->all();		
		$data = new User;
		$flag = User::where('UserID', $id)->exists();
		if(!$flag){
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}
		$userNewData = array();
		try {
				$userNewData['ProfilePhotoURL'] = '';
				$response = $data::where('UserID', $id)->update($userNewData);
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

	public function setDeviceId(Request $request) {
		$responseData = new ResponseData;
		$requestData = $request->all();		
		$data = new User;

		$messsages = array(
			'DeviceID.required'=>'device_id_not_empty',
			'DeviceType.required'=>'device_type_not_empty',
		);
		
		//Validation rule
		$validator = Validator::make($request->all(), [
			'DeviceID' => 'required',
			'DeviceType' => 'required',
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


		$flag = User::where('UserID', $requestData['UserID'])->exists();

		if(!$flag){
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

		
		$userNewData = array();
		try {
				$userNewData['DeviceID'] = $requestData['DeviceID'];
			    $userNewData['DeviceType'] = $requestData['DeviceType'];
				$response = $data::where('UserID', $requestData['UserID'])->update($userNewData);
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

}
