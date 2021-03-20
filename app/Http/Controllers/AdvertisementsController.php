<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\Advertisements;
use App\AdvertisementsImages;
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

class AdvertisementsController extends Controller {
    
    //Save Advertisements
	public function saveAdvertisements(Request $request) {
		$advertisementsReqData = $request->all();
		$advertisements = new Advertisements;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'AdsType.required'=>'select_ads_type',
			'ArticleName.required'=>'enter_ads_article_name',
			'ArticleDescription.required'=>'enter_ads_article_description',
			'ItemPrice.required'=>'enter_ads_item_price',
			'AdvImageURL.required'=>'select_ads_image',
            'AdvImageURL.image'=>'only_image_allowed',
            'City.required'=>'enter_ads_city',
            'ItemPrice.numeric'=>'numeric_value_allowed',
			'AdvImageURL.uploaded'=>'only_image_allowed',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'AdsType' => 'required',
			'ArticleName' => 'required',
			'ArticleDescription' => 'required',
            'ItemPrice' => 'required|numeric',
            'AdvImageURL' => 'required',
            'AdvImageURL.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'City' => 'required',
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
			\DB::beginTransaction();
			$advertisements->AdsType = $advertisementsReqData['AdsType'];
			$advertisements->ArticleName = isset($advertisementsReqData['ArticleName'])
				?$advertisementsReqData['ArticleName']:'';
			$advertisements->ArticleDescription = $advertisementsReqData['ArticleDescription'];
            $advertisements->ItemPrice = $advertisementsReqData['ItemPrice'];

            if (input::file('PhotoURL') != '') {
				$file = input::file('PhotoURL');
				$uploadController = new UploadController;
				$advertisements->PhotoURL = $uploadController->uploadFile($file);
			}else{
				$advertisements->PhotoURL = '';
            }

			$advertisements->StreetName = $advertisementsReqData['StreetName'];
            $advertisements->PostalCode = $advertisementsReqData['PostalCode'];
            $advertisements->City = $advertisementsReqData['City'];
            $advertisements->Status = isset($advertisementsReqData['Status']) ? $advertisementsReqData['Status']:'Open';
            $advertisements->PublicationDate = date('Y-m-d');
			$advertisements->UserID = $advertisementsReqData['UserID'];

			$response = $advertisements->save();
			//Save store multiple address
			try {
                $images = input::file('AdvImageURL');
                $AdvImageURL = $advertisementsReqData['AdvImageURL'];

                $adsImages=[];
					for($i=0;$i<sizeof($AdvImageURL);$i++) {
    
                        $image = $images[$i];
                        $input[$i] = time() .$i. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('images');
                        $image->move($destinationPath, $input[$i]);
                        $advertisementsImages = new AdvertisementsImages;
                        $adsImages[] = array(
							'AdvImageURL' =>$input[$i],
							'AdvertisementID' => $advertisements->AdvertisementID,
						);
                    }
					//Batch insert to reduce to many DB callls
					AdvertisementsImages::insert($adsImages);
					$responseData->message = 'Success';
					$responseData->status = 'Success';

			}catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
				\DB::rollback();
			}
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
			\DB::rollback();
		}
		$response = array(
			$responseData,
		);\DB::commit();
		return json_encode($response);
	}
	

	
	
	 //Save mobile Advertisements
	 public function saveMobileAdvertisements(Request $request) {
		$advertisementsReqData = $request->all();
		$advertisements = new Advertisements;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'AdsType.required'=>'select_ads_type',
			'ArticleName.required'=>'enter_ads_article_name',
			'ArticleDescription.required'=>'enter_ads_article_description',
			'ItemPrice.required'=>'enter_ads_item_price',
			'AdvImageURL.required'=>'select_ads_image',
            'AdvImageURL.image'=>'only_image_allowed',
            'City.required'=>'enter_ads_city',
            'ItemPrice.numeric'=>'numeric_value_allowed',
			'AdvImageURL.uploaded'=>'only_image_allowed',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'AdsType' => 'required',
			'ArticleName' => 'required',
			'ArticleDescription' => 'required',
            'ItemPrice' => 'required|numeric',
            'AdvImageURL' => 'required',
            'AdvImageURL.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'City' => 'required',
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
			\DB::beginTransaction();
			$advertisements->AdsType = $advertisementsReqData['AdsType'];
			$advertisements->ArticleName = isset($advertisementsReqData['ArticleName'])
				?$advertisementsReqData['ArticleName']:'';
			$advertisements->ArticleDescription = $advertisementsReqData['ArticleDescription'];
            $advertisements->ItemPrice = $advertisementsReqData['ItemPrice'];
			$Advertisement_ID = $advertisementsReqData['AdvertisementID'];

            if (input::file('PhotoURL') != '') {
				$file = input::file('PhotoURL');
				$uploadController = new UploadController;
				$advertisements->PhotoURL = $uploadController->uploadFile($file);
			}else{
				$advertisements->PhotoURL = '';
            }

			$advertisements->StreetName = $advertisementsReqData['StreetName'];
			$advertisements->PostalCode = $advertisementsReqData['PostalCode'];
			$advertisements->DeptID = isset($advertisementsReqData['DeptID']) ? $advertisementsReqData['DeptID']:0;
            $advertisements->City = $advertisementsReqData['City'];
            $advertisements->Status = isset($advertisementsReqData['Status']) ? $advertisementsReqData['Status']:'Open';
            $advertisements->PublicationDate = date('Y-m-d');
			$advertisements->UserID = $advertisementsReqData['UserID'];

			  if($Advertisement_ID==null || $Advertisement_ID==""){
				$response = $advertisements->save();
			  }else{
				$advertisements->AdvertisementID=$Advertisement_ID;
			  }

			
			//Save store multiple address
			try {
                $images = input::file('AdvImageURL');
                $AdvImageURL = $advertisementsReqData['AdvImageURL'];

                $adsImages=[];
					for($i=0;$i<sizeof($AdvImageURL);$i++) {
    
                        $image = $images[$i];
                        $input[$i] = time() .$i. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('images');
                        $image->move($destinationPath, $input[$i]);
                        $advertisementsImages = new AdvertisementsImages;
                        $adsImages[] = array(
							'AdvImageURL' =>$input[$i],
							'AdvertisementID' => $advertisements->AdvertisementID,
						);
                    }
					//Batch insert to reduce to many DB callls
					AdvertisementsImages::insert($adsImages);
					$responseData->message = 'Success';
					$responseData->status = 'Success';
					$responseData->AdvertisementID=$advertisements->AdvertisementID;

			}catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
				$responseData->AdvertisementID=$advertisements->AdvertisementID;
				\DB::rollback();
			}
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
			$responseData->AdvertisementID=$advertisements->AdvertisementID;
			\DB::rollback();
		}
		$response = array(
			$responseData,
		);\DB::commit();
		return json_encode($response);
    }




    
    //Update Advertisements
	public function updateAdvertisements(Request $request, $id) {
		$advertisementsReqData = $request->all();
		$advertisements = new Advertisements;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'AdsType.required'=>'select_ads_type',
			'ArticleName.required'=>'enter_ads_article_name',
			'ArticleDescription.required'=>'enter_ads_article_description',
			'ItemPrice.required'=>'enter_ads_item_price',
            //'AdvImageURL.image'=>'only_image_allowed',
            'City.required'=>'enter_ads_city',
            'ItemPrice.numeric'=>'numeric_value_allowed',
			//'AdvImageURL.uploaded'=>'only_image_allowed',
			// 'PhotoURL.image'=>'only_image_allowed',
			// 'PhotoURL.uploaded'=>'only_image_allowed',	
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'AdsType' => 'required',
			'ArticleName' => 'required',
			'ArticleDescription' => 'required',
            'ItemPrice' => 'required|numeric',
			'City' => 'required',
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
			\DB::beginTransaction();
			$adsData['AdsType'] = $advertisementsReqData['AdsType'];
			$adsData['ArticleName'] = isset($advertisementsReqData['ArticleName'])
				?$advertisementsReqData['ArticleName']:'';
			$adsData['ArticleDescription'] = $advertisementsReqData['ArticleDescription'];
            $adsData['ItemPrice'] = $advertisementsReqData['ItemPrice'];

            if (input::file('PhotoURL') != '') {
				$file = input::file('PhotoURL');
				$uploadController = new UploadController;
				$adsData['PhotoURL'] = $uploadController->uploadFile($file);
			}

			$adsData['StreetName'] = $advertisementsReqData['StreetName'];
            $adsData['PostalCode'] = $advertisementsReqData['PostalCode'];
            $adsData['City'] = $advertisementsReqData['City'];
			$adsData['Status'] = isset($advertisementsReqData['Status']) ? $advertisementsReqData['Status']:'Open';
			$adsData['UserID'] = $advertisementsReqData['UserID'];

			$advertisements::where('AdvertisementID', $id)->update($adsData);
            //AdvertisementsImages::where('AdvertisementID', $id)->delete();
            
			//Save store multiple address
			try {
                $images = input::file('AdvImageURL');
                if(input::file('AdvImageURL') !=''){
                $adsImages=[];
					for($i=0;$i<count($advertisementsReqData['AdvImageURL']);$i++) {
    
                        $image = $images[$i];
                        $input[$i] = time() .$i. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('images');
                        $image->move($destinationPath, $input[$i]);
                        $advertisementsImages = new AdvertisementsImages;
                        $adsImages[] = array(
							'AdvImageURL' =>$input[$i],
							'AdvertisementID' => $id,
						);
                    }
					//Batch insert to reduce to many DB callls
					AdvertisementsImages::insert($adsImages);
				}
					$responseData->message = 'Success';
					$responseData->status = 'Success';
			}catch (Exception $e) {
				echo $e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
				\DB::rollback();
			}
			
		} catch (Exception $e) {
			echo $e;
			$responseData->message = 'Error';
			$responseData->status = 'Error';
			\DB::rollback();
		}
		$response = array(
			$responseData,
		);
		\DB::commit();
		return json_encode($response);
    }


    //Change Advertisements Status
	public function updateAdvertisementsStatus(Request $request, $id) {
		$advertisementsReqData = $request->all();
		$advertisements = new Advertisements;
        $responseData = new ResponseData;
		try {
            $adsData['Status'] = $advertisementsReqData['Status'];
			$advertisements::where('AdvertisementID', $id)->update($adsData);
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

    //Delete Advertisements
	public function deleteAdvertisements($id) {
		$responseData = new ResponseData;
		$flag = Advertisements::where('AdvertisementID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			Advertisements::where('AdvertisementID', $id)->delete();
			AdvertisementsImages::where('AdvertisementID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
    }
    
    //Get All Advertisements For Sell 
	public function getAdvertisementsForSell(Request $request) {
		$responseData = new ResponseData;
        $adv =DB::table('tblAdvertisements')
        	->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
           ->Where('tblAdvertisements.Status', 'Open')
           ->Where('tblAdvertisements.AdsType', 'Sell')
           ->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
           ->get();

        foreach ($adv as $key => $value) {  
			$data = DB::table('tblAdvertisementsImages')
			->where('AdvertisementID', $value->AdvertisementID)
	        ->orderBy('AdvertisementID', 'DSC')
	        ->get();
	        $adv[$key]->advertisementsImages = $data;
		}
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $adv;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }
    
    //Get All Advertisements For Buy
	public function getAdvertisementsForBuy(Request $request) {
		$responseData = new ResponseData;
        $adv =DB::table('tblAdvertisements')
        	->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
           ->Where('tblAdvertisements.Status', 'Open')
           ->Where('tblAdvertisements.AdsType', 'Search')
           ->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
           ->get();

        foreach ($adv as $key => $value) {  
			$data = DB::table('tblAdvertisementsImages')
			->where('AdvertisementID', $value->AdvertisementID)
	        ->orderBy('AdvertisementID', 'DSC')
	        ->get();
	        $adv[$key]->advertisementsImages = $data;
		}
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $adv;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }

    //Get All Advertisements By User Id
	public function getAllAdvertisementsByUserId(Request $request, $id) {
		$responseData = new ResponseData;
		$adv =DB::table('tblAdvertisements')
		->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
           ->Where('tblAdvertisements.UserID', $id)
           ->select('tblAdvertisements.*','tblUsers.ProfilePhotoURL')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
           ->get();

        foreach ($adv as $key => $value) {  
			$data = DB::table('tblAdvertisementsImages')
			->where('AdvertisementID', $value->AdvertisementID)
	        ->orderBy('AdvertisementID', 'DSC')
	        ->get();
	        $adv[$key]->advertisementsImages = $data;
		}
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $adv;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }

    //Get Advertisements Images By Id
	public function getAdvertisementsImagesById(Request $request, $id) {
		$responseData = new ResponseData;
        $store =DB::table('tblAdvertisementsImages')
           ->Where('tblAdvertisementsImages.AdvertisementID', $id)
           ->select('tblAdvertisementsImages.*')
           ->orderBy('tblAdvertisementsImages.AdvertisementID', 'DSC')
           ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }

    //Get Advertisements By Id
	public function getAdvertisementsById(Request $request, $id) {
		$responseData = new ResponseData;
        $ads =DB::table('tblAdvertisements')
           ->Where('tblAdvertisements.AdvertisementID', $id)
           ->select('tblAdvertisements.*')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
		   ->get();
		   
		$adsImg =DB::table('tblAdvertisementsImages')
           ->Where('tblAdvertisementsImages.AdvertisementID', $id)
           ->select('tblAdvertisementsImages.*')
           ->orderBy('tblAdvertisementsImages.AdvertisementID', 'DSC')
           ->get();
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = ['advertisements'=>$ads,'advertisementsImages'=>$adsImg];
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get Advertisements By User Id
	public function getAdvertisementsByUserId(Request $request, $id) {
		$responseData = new ResponseData;
        $ads =DB::table('tblAdvertisements')
		   ->Where('tblAdvertisements.UserID', $id)
		   ->Where('tblAdvertisements.AdsType', 'Sell')
           ->select('tblAdvertisements.*')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $ads;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }

    //Delete Advertisements Image
	public function deleteAdvertisementsImage($id) {
		$responseData = new ResponseData;
		$flag = AdvertisementsImages::where('AdvertisementsPhotoID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			AdvertisementsImages::where('AdvertisementsPhotoID', $id)->delete();
		} else {
			$responseData->message = 'advertisements_image_not_found';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get All Advertisements For Sell 
	public function getAdvertisementsForSellByDept($dept_id) {
		$responseData = new ResponseData;
		if($dept_id == 0){
			$adv =DB::table('tblAdvertisements')
        	->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
           ->Where('tblAdvertisements.Status', 'Open')
		   ->Where('tblAdvertisements.AdsType', 'Sell')
           ->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
           ->get();
		}else{
			$adv =DB::table('tblAdvertisements')
        	->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
           ->Where('tblAdvertisements.Status', 'Open')
		   ->Where('tblAdvertisements.AdsType', 'Sell')
		   ->Where('tblAdvertisements.DeptID', $dept_id)
           ->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL')
           ->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
           ->get();
		}

        foreach ($adv as $key => $value) {  
			$data = DB::table('tblAdvertisementsImages')
			->where('AdvertisementID', $value->AdvertisementID)
	        ->orderBy('AdvertisementID', 'DSC')
	        ->get();
	        $adv[$key]->advertisementsImages = $data;
		}
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $adv;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }
    
    //Get All Advertisements For Buy
	public function getAdvertisementsForBuyByDept($dept_id) {
		$responseData = new ResponseData;

		if($dept_id == 0){
			$adv =DB::table('tblAdvertisements')
			->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
			->Where('tblAdvertisements.Status', 'Open')
			->Where('tblAdvertisements.AdsType', 'Search')
			->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL')
			->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
			->get();

		}else{
			$adv =DB::table('tblAdvertisements')
				->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblAdvertisements.UserID')
			->Where('tblAdvertisements.Status', 'Open')
			->Where('tblAdvertisements.AdsType', 'Search')
			->Where('tblAdvertisements.DeptID', $dept_id)
			->select('tblAdvertisements.*','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL')
			->orderBy('tblAdvertisements.AdvertisementID', 'DSC')
			->get();
		}
		
        foreach ($adv as $key => $value) {  
			$data = DB::table('tblAdvertisementsImages')
			->where('AdvertisementID', $value->AdvertisementID)
	        ->orderBy('AdvertisementID', 'DSC')
	        ->get();
	        $adv[$key]->advertisementsImages = $data;
		}
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $adv;
		$response = array(
			$responseData
		);
		return json_encode($response);
    }


}
