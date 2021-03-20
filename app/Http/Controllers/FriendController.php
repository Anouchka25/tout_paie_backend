<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\FriendRequests;
use App\Friends;
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

class FriendController extends Controller {

    //Get All Friend
	public function getAllFriendSuggestion($id) {
		$responseData = new ResponseData;
    
		$user=array();
		$Friends=array();
		$FriendRequests=array();

        $Friends =DB::table('tblFriends')
	   ->Where('tblFriends.UserID',$id)
	   ->select('tblFriends.FriendUserID')
	   ->get();
		
		$FriendRequests =DB::table('tblFriendRequests')
	   ->Where('tblFriendRequests.UserID',$id)
	   ->select('tblFriendRequests.FriendUserID')
	   ->get();
		
		$FriendsIDS=array();
		
		foreach ($Friends as $value) {
			array_push($FriendsIDS,$value->FriendUserID);
		}
		
		$FriendRequestsIDS=array();
		
		foreach ($FriendRequests as $value) {
			array_push($FriendRequestsIDS,$value->FriendUserID);
		}



		$FriendRQ =DB::table('tblFriendRequests')
	   ->Where('tblFriendRequests.FriendUserID',$id)
	   ->select('tblFriendRequests.UserID')
	   ->get();
		
		$FriendsRQIDS=array();
		
		foreach ($FriendRQ as $value) {
			array_push($FriendsRQIDS,$value->UserID);
		}



	
		$userIDD=array();
	
		$userIDD=array_merge($FriendsIDS,$FriendRequestsIDS);

		$userIDs=array();

		$userIDs=array_merge($userIDD,$FriendsRQIDS);

		array_push($userIDs,$id);
		//print_r($userIDs);
		
		$user =DB::table('tblUsers')
	   ->Where('tblUsers.Role', 'U')
	   ->whereNOTIn('tblUsers.UserID',$userIDs)
	   ->where('tblUsers.UserID','!=',$id)
	   ->select('tblUsers.UserID','tblUsers.FirstName','tblUsers.Email','tblUsers.ProfilePhotoURL','tblUsers.LastName','tblUsers.MobileNumber')
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

    

    //Save FriendRequest
	public function saveFriendRequest(Request $request) {
		$requestData = $request->all();
		$FriendRequests = new FriendRequests;
        $user = new User;
		$responseData = new ResponseData;


        $flag = User::where('UserID', $requestData['FriendUserID'])->exists();
		if(!$flag){
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

			try {
				// $user->FirstName 	= $requestData['FirstName'];
				// $user->LastName 	= $requestData['LastName'];
				// $user->Email 		= $requestData['Email'];
				// $user->MobileNumber = isset($requestData['MobileNumber'])? 
				// 	$requestData['MobileNumber']:'';
				$FriendRequests->UserID=$requestData['UserID'];
				$FriendRequests->FriendUserID=$requestData['FriendUserID'];
				$FriendRequests->status=$requestData['status'];
               
				$response = $FriendRequests->save();

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				echo"erroorrrr".$e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
	
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}



	public function getAllFriendRequestsSend( $id) {
		$responseData = new ResponseData;
		//$FriendRequests =FriendRequests::where('UserID',$id )->get();


		$FriendRequests =DB::table('tblFriendRequests')
		->Where('tblFriendRequests.UserID',$id)
		->select('tblFriendRequests.RequestID')
		->get();


		$FriendRequestsIDS=array();
		
		foreach ($FriendRequests as $value) {
			array_push($FriendRequestsIDS,$value->RequestID);
		}

	// 	$RequestSend =DB::table('tblUsers')
	// 	->whereIn('tblUsers.UserID',$FriendRequestsIDS)
	// 	->select('tblUsers.UserID','tblUsers.FirstName','tblUsers.Email','tblUsers.ProfilePhotoURL','tblUsers.LastName','tblUsers.MobileNumber')
	//    ->orderBy('tblUsers.UserID', 'DSC')
	//    ->get();


		$FriendRequests =DB::table('tblUsers')
		->join('tblFriendRequests', 'tblUsers.UserID', '=', 'tblFriendRequests.FriendUserID')
		->WhereIn('tblFriendRequests.RequestID', $FriendRequestsIDS)
		->select('tblFriendRequests.UserID','tblFriendRequests.FriendUserID','tblFriendRequests.RequestID','tblFriendRequests.status','tblUsers.FirstName','tblUsers.Email','tblUsers.ProfilePhotoURL','tblUsers.LastName','tblUsers.MobileNumber')
		->get();



		// DB::table('tblUsers')
		// ->whereIn('UserID', function($query) use ( $id ){
		// 	$query->select('FriendUserID')
		// 	->from(with(new FriendRequests)->getTable())
		// 	->where('UserID',$id );
		// })->get();


		// (SQL: select * from `tblFriendRequests` where 
		// `UserID` in (select * from `tblUsers` where `UserID` = 50)) in f

		
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $FriendRequests;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}



	public function getAllFriendRequests( $id) {
		$responseData = new ResponseData;
		//$FriendRequests =FriendRequests::where('UserID',$id )->get();



		$FriendRequests =DB::table('tblFriendRequests')
		->Where('tblFriendRequests.FriendUserID',$id)
		->select('tblFriendRequests.RequestID')
		->get();


		$FriendRequestsIDS=array();
		
		foreach ($FriendRequests as $value) {
			array_push($FriendRequestsIDS,$value->RequestID);
		}



		$FriendRequests =DB::table('tblUsers')
		->join('tblFriendRequests', 'tblUsers.UserID', '=', 'tblFriendRequests.UserID')
		->WhereIn('tblFriendRequests.RequestID', $FriendRequestsIDS)
		->select('tblFriendRequests.UserID','tblFriendRequests.FriendUserID','tblFriendRequests.RequestID','tblFriendRequests.status','tblUsers.FirstName','tblUsers.Email','tblUsers.ProfilePhotoURL','tblUsers.LastName','tblUsers.MobileNumber')
		->get();

		// DB::table('tblUsers')
		// ->whereIn('UserID', function($query) use ( $id ){
		// 	$query->select('FriendUserID')
		// 	->from(with(new FriendRequests)->getTable())
		// 	->where('UserID',$id );
		// })->get();


		// (SQL: select * from `tblFriendRequests` where 
		// `UserID` in (select * from `tblUsers` where `UserID` = 50)) in f

		
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $FriendRequests;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	public function saveAcceptRequest(Request $request,$id) {
		$requestData = $request->all();
		$FriendRequests = new FriendRequests;
		$Friends = new Friends;
		$Friends2 = new Friends;
        $user = new User;
		$responseData = new ResponseData;
		


        $flag = FriendRequests::where('RequestID', $id)->exists();
		if(!$flag){
			$responseData->message = 'Request is not Valid';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

			try {
			
				$Friends->UserID=$requestData['UserID'];
				$Friends->FriendUserID=$requestData['FriendUserID'];
				$Friends->flag=$requestData['UserID']."".$requestData['FriendUserID'];
				$Friends->status=$requestData['status'];
               
				$response = $Friends->save();

				$Friends2->UserID=$requestData['FriendUserID'];
				$Friends2->FriendUserID=$requestData['UserID'];
				$Friends2->flag=$requestData['UserID']."".$requestData['FriendUserID'];
				$Friends2->status=$requestData['status'];
               
				$response = $Friends2->save();

				
				FriendRequests::where('RequestID', $id)->delete($id);

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				echo"erroorrrr".$e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
	
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}




	public function FriendRequestDelete($id) {
		
		$FriendRequests = new FriendRequests;
		
		$responseData = new ResponseData;
		


        $flag = FriendRequests::where('RequestID', $id)->exists();
		if(!$flag){
			$responseData->message = 'Request id is not Valid';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

			try {
			
				FriendRequests::where('RequestID', $id)->delete($id);

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				echo"erroorrrr".$e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
	
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}



	public function getAllFriendList($id) {
		$responseData = new ResponseData;
    
		$user=array();
        $Friends=array();
		$FriendRequests=array();

        $Friends =DB::table('tblFriends')
	   ->Where('tblFriends.UserID',$id)
	   ->select('tblFriends.RelationID')
	   ->get();



		$FriendsIDS=array();
		
		foreach ($Friends as $value) {
			array_push($FriendsIDS,$value->RelationID);
		}

		$user =DB::table('tblUsers')
		->join('tblFriends', 'tblUsers.UserID', '=','tblFriends.FriendUserID')
		->whereIn('tblFriends.RelationID',$FriendsIDS)
	   ->select('tblFriends.*','tblUsers.FirstName','tblUsers.Email','tblUsers.ProfilePhotoURL','tblUsers.LastName','tblUsers.MobileNumber')
	   ->get();

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $user;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}



	public function unFriend($id) {
		
		$Friends = new Friends;
		
		$responseData = new ResponseData;
		


        $flag = Friends::where('RelationID', $id)->exists();
		if(!$flag){
			$responseData->message = 'Request id is not Valid';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}

			try {


				$Friends=array();
				$FriendRequests=array();
		
				$FriendsFlag =DB::table('tblFriends')
			   ->Where('tblFriends.RelationID',$id)
			   ->select('tblFriends.flag')
			   ->first();
		
            //    print_r($FriendsFlag);
			     
			
				if($FriendsFlag!=null )
			{
				Friends::where('flag', $FriendsFlag->flag)->delete();
			}

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				echo"erroorrrr".$e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
	
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

}
