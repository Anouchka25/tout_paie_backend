<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\ResponseData;
use App\Department;
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

class AuthenticationController extends Controller {

	//Register User
	public function registerUser(Request $request) {
		$requestData = $request->all();
		$user = new User;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Email.required'=>'Please_enter_user_email',
			'password.required'=>'Please_enter_password',
			'FirstName.required'=>'Please_enter_user_first_name',
			'confirm_password.required'=>'Please_enter_confirm_password',
			'Email.unique'=>'Email_id_allready_exits',
			'Email.email'=>'enter_valid_email_address',
			'password.min'=>'password_must_contains_8_char',
			'confirm_password.min'=>'password_must_contains_8_char',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Email' => 'required|email|max:255|unique:tblUsers,Email',
			'password' => 'required|max:255|min:8',
			'FirstName' => 'required|max:255',
			'confirm_password'=>'required|max:255|min:8',
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
				$user->Email 		= $requestData['Email'];
				$user->RememberToken = '';
				$user->Role = 'U';
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
	





    public function registerUserWithMPIN(Request $request) {
		$requestData = $request->all();
		$user = new User;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
      

		$messsages = array(
			'Email.required'=>'Please_enter_user_email',
			'password.required'=>'Please_enter_password',
			'FirstName.required'=>'Please_enter_user_first_name',
			'confirm_password.required'=>'Please_enter_confirm_password',
			'Email.unique'=>'Email_id_allready_exits',
			'Email.email'=>'enter_valid_email_address',
			'password.min'=>'password_must_contains_8_char',
			'confirm_password.min'=>'password_must_contains_8_char',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Email' => 'required|email|max:255|unique:tblUsers,Email',
			'password' => 'required|max:255|min:4',
			'FirstName' => 'required|max:255',
			'confirm_password'=>'required|max:255|min:4',
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
				$user->Email 		= $requestData['Email'];
				$user->RememberToken = '';
				$user->Role = 'U';
				$user->password = bcrypt($requestData['password']);
				$user->MobileNumber = $requestData['MobileNumber'];
				$user->DeviceID = $requestData['DeviceID'];
				$user->DeviceType = $requestData['DeviceType'];
				$response = $user->save();
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
				$responseData->data=$e;
			}
		} else {
			$responseData->message = 'Password_and_confirm_password_does_not_match';
			$responseData->status = 'Error';
			$responseData->data=$e;
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}







	//Login user
	public function login(Request $request) {
		$userData = new User;
		$responseData = new ResponseData;
		$loginFrom = $request->From;
		$users = DB::table('tblUsers')->select('*')->where('Email',$request->Email)->get();

		if(!empty($users[0])){
			$Status = $users[0]->isActive;
			if($Status == 'N'){
				$responseData->message = 'User_not_active';
				$responseData->status = 'Error';
				$response = array(
					$responseData,
				);
				return json_encode($response);
			}

			$role = $users[0]->Role;

			if($loginFrom == 'W' && $role == 'U'){
				$responseData->message = 'this_user_is_not_web_user';
				$responseData->status = 'Error';
				$response = array(
					$responseData,
				);
				return json_encode($response);
			}

			if($loginFrom == 'M' && $role != 'U'){
				$responseData->message = 'this_user_is_not_mobile_user';
				$responseData->status = 'Error';
				$response = array(
					$responseData,
				);
				return json_encode($response);
			}

		}

		$credentials = $request->only('Email', 'password');

		$role ='';
		$userId = '';
		if (Auth::attempt($credentials)) {

			$user = Auth::user();
			$userId = $user->UserID;
			$role = $user->Role;
			
			if(isset($request->DeviceID)){
				$userNewData['DeviceID'] = $request->DeviceID;
				$userNewData['DeviceType'] = $request->DeviceType;
			}

			$userNewData['RememberToken'] = Str::random(60);
			$userNewData['DepartmentID'] = $request->DepartmentID;
			Session::put('DepartmentID', $request->DepartmentID);
			
			$response = $userData::where('UserID', $userId)->update($userNewData);
			if ($response == 1) {
				$remember_token = $userNewData['RememberToken'];
				Session::put('my_content', $remember_token);

				$responseData->message = 'Successfully_login';
				$responseData->status = 'Success';
				$responseData->data =  [
									        'Role' => $role,
									        'UserID' => $userId,
										    'headers' => [
										        'RememberToken' => Session::get('my_content'),
										        'Accept' => 'application/json',
											],
											'Users'=>$users,
										];
				
			} else {
				$responseData->message = 'Please_enter_correct_username_and_password';
				$responseData->status = 'Error';
			}

		} else {
			$responseData->message = 'Please_enter_correct_username_and_password';
			$responseData->status = 'Error';
		}

		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Update Profile
	public function updateUserProfile(Request $request, $id) {
		$responseData = new ResponseData;

		$token = $request->header('RememberToken');

		// $flag = User::where('RememberToken', $token)
		// 		->where('UserID',$id)
		// 		->exists();

		// if (!$flag) {
		// 	$responseData->message = 'Token_Expired';
		// 	$responseData->status = 'Error';

		// 	$response = array(
		// 		$responseData,
		// 	);
		// 	return json_encode($response);
		// }

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
			//'DateOfBirth.date'=>'enter_valid_date_format',
			'ProfilePhotoURL.image'=>'only_image_allowed',
			'ProfilePhotoURL.uploaded'=>'only_image_allowed',
				
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Email' => 'required|email|unique:tblUsers,Email,'.$id.',UserID',
			'FirstName' => 'required|max:255',
			'LastName'=>'required|max:255',
			//'DateOfBirth'=>'date',
			'ProfilePhotoURL' => 'image|mimes:jpeg,png,jpg,gif,svg',
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

				$userNewData['Email'] = $requestData['Email'];
				$userNewData['FirstName'] = $requestData['FirstName'];
				$userNewData['LastName'] = $requestData['LastName'];
				$userNewData['UserStatus'] = $requestData['UserStatus'];
				
				if(isset($requestData['isActive'])){
					$userNewData['isActive'] = $requestData['isActive'];
				}

				$userNewData['MobileNumber'] = isset($requestData['MobileNumber'])? 
				$requestData['MobileNumber']:'';

				if(isset($requestData['CountryCode'])){
					$userNewData['CountryCode'] = $requestData['CountryCode'];
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
				
				if(isset($requestData['DepartmentID'])){
					$userNewData['DepartmentID'] = $requestData['DepartmentID'];
				}

				if(isset($requestData['StreetName'])){
					$userNewData['StreetName'] = $requestData['StreetName'];
				}
				if(isset($requestData['PostalCode'])){
					$userNewData['PostalCode'] = $requestData['PostalCode'];
				}
				if(isset($requestData['City'])){
					$userNewData['City'] = $requestData['City'];
				}

				//Billing Address
				$userNewData['BillStreetName'] = isset($requestData['BillStreetName']) ?
				 $requestData['BillStreetName']:'';

				$userNewData['BillPostalCode'] = isset($requestData['BillPostalCode']) ? 
				$requestData['BillPostalCode']:'';

				$userNewData['BillCity'] = isset($requestData['BillCity']) ? $requestData['BillCity']:'';

				//Residence Address
				$userNewData['ResidenceStreetName'] = isset($requestData['ResidenceStreetName']) ?
				 $requestData['ResidenceStreetName']:'';

				$userNewData['ResidencePostalCode'] = isset($requestData['ResidencePostalCode']) ? 
				$requestData['ResidencePostalCode']:'';

				$userNewData['ResidenceCity'] = isset($requestData['ResidenceCity']) ? $requestData['ResidenceCity']:'';

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

	//Forgot password mail send functionality
	public function forgotPassword(Request $request){
        $data = new User;

        $responseData = new ResponseData;
   	    if (User::where('Email', '=', Input::get('Email'))->exists()) { 

   	    	$loginFrom = $request->From;
   	    	$users = DB::table('tblUsers')->select('*')->where('Email',$request->Email)->get();

				if(!empty($users[0])){
					$Status = $users[0]->isActive;
					if($Status == 'N'){
						$responseData->message = 'User_not_active';
						$responseData->status = 'Error';
						$response = array(
							$responseData,
						);
						return json_encode($response);
					}

					$role = $users[0]->Role;

					if($loginFrom == 'W' && $role == 'U'){
						$responseData->message = 'this_user_is_not_web_user';
						$responseData->status = 'Error';
						$response = array(
							$responseData,
						);
						return json_encode($response);
					}

					if($loginFrom == 'M' && $role != 'U'){
						$responseData->message = 'this_user_is_not_mobile_user';
						$responseData->status = 'Error';
						$response = array(
							$responseData,
						);
						return json_encode($response);
					}
				}


	        $new_data['RememberToken']=Str::random(60);
	        $response = $data::where('Email',Input::get('Email'))->update($new_data);
	        //'.$_SERVER['HTTP_HOST'].'

	        // $link = 'http://' . $_SERVER['HTTP_HOST'] . '/#/change-password/'."?RememberToken=".$new_data['RememberToken'];
	        

	        $link = 'http://ec2-35-180-86-44.eu-west-3.compute.amazonaws.com:8084/#/reset-password?RememberToken='.$new_data['RememberToken'];

	        $password = Str::random(8);
			$data = array('FirstName'=>"",'Url'=>$link,'NewPassword'=>$password);

			Mail::send(['text'=>'mail'], $data, function($message) {
				$message->to(Input::get('Email'), 'User')->subject
				('Password Reset');
				$message->from('mindnervesdemo@gmail.com','ToutPaie Team');
			});

			// $new_data['password'] = bcrypt($password);
	        $response = User::where('Email',Input::get('Email'))->update($new_data);
			$responseData->message = 'email_sent_msg';
		    $responseData->status = 'Success';
        }
      	else{
        	$responseData->message = 'email_not_exits_msg';
			$responseData->status = 'Error';
      	}
     	$response = array(
			$responseData,
		);
		return json_encode($response);
   }

   	public function getTokenByTokenId(Request $request) {
   		$responseData = new ResponseData;
		$data = new User;
		$token = $request->header('RememberToken');

		$flag = $data::where('RememberToken', $token)->exists();
		if ($flag) {
			$responseData->message = 'success';
			$responseData->status = 'Error';
			$UserId = $data::select('UserID')->where('RememberToken', $token)->get();
			$responseData->data = ['UserID' => $UserId];
		} else {
			$responseData->message = 'Token_Expired';
			$responseData->status = 'Error';
			$UserId = '';
			$responseData->data = ['UserID' => $UserId];
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	public function resetUserPassword(Request $request) {
		$requestData = $request->all();
		$responseData = new ResponseData;
		$data = new User;
		$flag = User::where('RememberToken', $requestData['RememberToken'])->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} else {
			$responseData->message = 'Token_Expired';
			$responseData->status = 'Error';
		}
		
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'RememberToken.required'=>'token_not_found',
			'password.required'=>'Please_enter_password',
			'confirm_password.required'=>'Please_enter_confirm_password',
			'password.min'=>'password_must_contains_8_char',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'RememberToken' => 'required',
			'password' => 'required|min:8',
			'confirm_password'=>'required',

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
				if ($requestData['password'] != '') {
					$new_data['password'] = hash::make($requestData['password']);
				}
				$response = $data::where('RememberToken', $requestData['RememberToken'])->update($new_data);

				$users = DB::table('tblUsers')->select('*')->where('RememberToken',$requestData['RememberToken'])->first();

				$responseData->message = 'Success';
				$responseData->status = 'Success';
				$responseData->data =  ['Users'=>$users];

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

	public function userLogout(Request $request, $id) {
		$data = new User;
		$token_data = $request->all();
		$flag = $data::where('UserID', $id)->exists();
		if ($flag) {
			$message = 'success';
			$status = 'Success';
		} else {
			$message = 'user_id_not_exit';
			$status = 'Error';
		}
		try {
			$token_data_array['RememberToken'] = '';
			$response = $data::where('UserID', $id)->update($token_data_array);
			$message = 'success';
			$status = 'Success';
		} catch (Exception $e) {
			$message = $e->getMessage();
			$status = 'Error';
		}
		$return_data = array(
			'status' => $status,
			'message' => $message,
		);
		return json_encode($return_data);
	}

	//Change password
	public function changePassword(Request $request, $id) {
		$requestData = $request->all();
		$responseData = new ResponseData;
		$data = new User;
		$flag = User::where('UserID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		} else {
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
		}
		
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'password.required'=>'Please_enter_password',
			'confirm_password.required'=>'Please_enter_confirm_password',
			'password.min'=>'password_must_contains_8_char',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'password' => 'required|min:8',
			'confirm_password'=>'required',

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

		if ($request->password == $request->confirm_password) {
			try {
				if ($requestData['password'] != '') {
					$new_data['password'] = hash::make($requestData['password']);
				}
				$response = $data::where('UserID', $id)->update($new_data);
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

	//Update Profile Address
	public function updateUserProfileAddress(Request $request, $id) {
		$responseData = new ResponseData;
		$token = $request->header('RememberToken');
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
			//Billing Address
			if(isset($requestData['BillStreetName'])){
				$userNewData['BillStreetName'] = isset($requestData['BillStreetName']) ?
			$requestData['BillStreetName']:'';

			$userNewData['BillPostalCode'] = isset($requestData['BillPostalCode']) ? 
			$requestData['BillPostalCode']:'';

			$userNewData['BillCity'] = isset($requestData['BillCity']) ? $requestData['BillCity']:'';
			}
			if(isset($requestData['ResidenceStreetName'])){
			
			$userNewData['ResidenceStreetName'] = isset($requestData['ResidenceStreetName']) ?
			 $requestData['ResidenceStreetName']:'';

			$userNewData['ResidencePostalCode'] = isset($requestData['ResidencePostalCode']) ? 
			$requestData['ResidencePostalCode']:'';

			$userNewData['ResidenceCity'] = isset($requestData['ResidenceCity']) ? $requestData['ResidenceCity']:'';
			}
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


	//Update Profile Photo
	public function updateUserProfilePhoto(Request $request) {
		$responseData = new ResponseData;
		$requestData = $request->all();		
		$data = new User;
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
				if(input::file('ProfilePhotoURL') != '') {
					$file = input::file('ProfilePhotoURL');
					$uploadController = new UploadController;
				  	$userNewData['ProfilePhotoURL'] = $uploadController->uploadFile($file);
				}
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

		//Update Profile Photo
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
	

	
	


	//Login user
	 public function loginThroughMobileNumber(Request $request) {
		$userData = new User;
		$responseData = new ResponseData;
		$loginFrom = $request->From;
		$users = DB::table('tblUsers')->select('*')->where('DeviceID',$request->DeviceID)->get();




		if(!$users){

			$responseData->message = 'User Not found';
			$responseData->status = 'Error'; 
			$responseData->data=$users ;
			$responseData->data2=$request->password;
			$responseData->data3=$users[0];


			$response = array(
				$responseData,
			);
			return json_encode($response);
              
		}   


		// if(empty($users[0])){
		// 	$Status = $users[0]->isActive;
		// 	if($Status == 'N'){
		// 		$responseData->message = 'User_not_active';
		// 		$responseData->status = 'Error';
		// 		$response = array(
		// 			$responseData,
		// 		);
		// 		return json_encode($response);
		// 	}

	// 		$role = $users[0]->Role;

	// 		if($loginFrom == 'W' && $role == 'U'){
	// 			$responseData->message = 'this_user_is_not_web_user';
	// 			$responseData->status = 'Error';
	// 			$response = array(
	// 				$responseData,
	// 			);
	// 			return json_encode($response);
	// 		}

	// 		if($loginFrom == 'M' && $role != 'U'){
	// 			$responseData->message = 'this_user_is_not_mobile_user';
	// 			$responseData->status = 'Error';
	// 			$response = array(
	// 				$responseData,
	// 			);
	// 			return json_encode($response);
	// 		}

	// 	}

		$credentials = $request->only('DeviceID', 'password');

		$role ='';
		$userId = '';
		if (Auth::attempt($credentials)) {

			$user = Auth::user();
			$userId = $user->UserID;
			$role = $user->Role;
			
			if(isset($request->DeviceID)){
				$userNewData['DeviceID'] = $request->DeviceID;
				$userNewData['DeviceType'] = $request->DeviceType;
			}

			$userNewData['RememberToken'] = Str::random(60);
			$userNewData['DepartmentID'] = '76';
			Session::put('DepartmentID', $request->DepartmentID);
			
			$response = $userData::where('UserID', $userId)->update($userNewData);
			//$response=1;
			 
			if ($response == 1) {
				$remember_token = $userNewData['RememberToken'];
				Session::put('my_content', $remember_token);

				$responseData->message = 'Successfully_login';
				$responseData->status = 'Success';
				$responseData->data =  [
									        'Role' => $role,
									        'UserID' => $userId,
										    'headers' => [
										        'RememberToken' => Session::get('my_content'),
										        'Accept' => 'application/json',
											],
											'Users'=>$users,
										];
				
			} else {
				$responseData->message = 'Please_enter_correct_username_and_password_inner';
				$responseData->status = 'Error';
				$responseData->data=$users ;
			}

		} else {
			$responseData->message = 'Please_enter_MPIN _or_DeviceID_outar';
			$responseData->status = 'Error'; 
			$responseData->data=$users ;
			$responseData->data2=$request->password;
			$responseData->data3=$users;
		} 

		
		
		



		$response = array(
			$responseData,
		);
		return json_encode($response);
	}






	
}
