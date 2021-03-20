<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\PaymentInformation;
use App\User;
use App\PaymentTransaction;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use App\Http\Controllers\UploadController;
use App\Http\Requests;
use Validator;
use Stripe\Stripe as Stripe;
use Stripe\Charge as Charge;

class PaymentInfoController extends Controller {

	//save Payment information
	public function savePaymentInformation(Request $request) {
		$paymentInformation = new PaymentInformation;
		$responseData = new ResponseData;
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'IBAN.required'=>'enter_IBAN',
			'BIC.required'=>'enter_BIC',
			'AccountOwner.required'=>'enter_account_owner_info',
			'AccountAddress.required'=>'enter_account_address_info',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'IBAN' => 'required',
			'BIC' => 'required',
			'AccountOwner' => 'required',
			'AccountAddress' => 'required',
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
			$paymentInformation->StoreID = $request->StoreID;
			$paymentInformation->IBAN = $request->IBAN;
			$paymentInformation->BIC = $request->BIC;
			$paymentInformation->AccountOwner = $request->AccountOwner;
			$paymentInformation->AccountAddress = $request->AccountAddress;
			$paymentInformation->AddedBy = $request->AddedBy;
			$paymentInformation->save();
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

	//update Product Catalogue
	public function updatePaymentInformation(Request $request, $id) {
		$responseData = new ResponseData;
		//Laravel Validation start
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'IBAN.required'=>'enter_IBAN',
			'BIC.required'=>'enter_BIC',
			'AccountOwner.required'=>'enter_account_owner_info',
			'AccountAddress.required'=>'enter_account_address_info',
		);
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'IBAN' => 'required',
			'BIC' => 'required',
			'AccountOwner' => 'required',
			'AccountAddress' => 'required',
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
			$paymentInformation['StoreID'] = $request->StoreID;
			$paymentInformation['IBAN'] = $request->IBAN;
			$paymentInformation['BIC'] = $request->BIC;
			$paymentInformation['AccountOwner'] = $request->AccountOwner;
			$paymentInformation['AccountAddress'] = $request->AccountAddress;
			$paymentInformation['AddedBy'] = $request->AddedBy;
			PaymentInformation::where('PaymentInformationId', $id)->update($paymentInformation);
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

	//Delete payment inforamtion
	public function deletePaymentInformation($id) {
		$responseData = new ResponseData;
		try{
		PaymentInformation::where('PaymentInformationId', $id)->delete();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		}catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Payment information
	public function getPaymentInformation() {
		$responseData = new ResponseData;
		$store =DB::table('tblPaymentInformation')
		->join('tblStore', 'tblStore.StoreID', '=', 'tblPaymentInformation.StoreID')
		->select('tblPaymentInformation.*','tblStore.StoreName')
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Payment information By ID
	public function getPaymentInformationByID($id) {
		$responseData = new ResponseData;
		$store =DB::table('tblPaymentInformation')
		->join('tblStore', 'tblStore.StoreID', '=', 'tblPaymentInformation.StoreID')
		->select('tblPaymentInformation.*','tblStore.StoreName')
		->where('PaymentInformationId', $id)
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


		//Get All Payment information By User ID
	public function getPaymentInformationByUserID($id) {
		$responseData = new ResponseData;
		$store =DB::table('tblPaymentInformation')
		->join('tblStore', 'tblStore.StoreID', '=', 'tblPaymentInformation.StoreID')
		->join('tblUsers', 'tblUsers.UserID', '=', 'tblPaymentInformation.AddedBy')
		->select('tblPaymentInformation.*','tblStore.StoreName','tblUsers.*')
		->where('tblUsers.UserID', $id)
		->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	public function stripePost(Request $request)
    {
       	$responseData = new ResponseData;
       	$paymentTransaction = new PaymentTransaction;

       	if (!$request->has('stripeToken')) {
			$responseData->message = 'stripe_token_issue';
			$responseData->status = 'Error';
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
      	$token = $request->get('stripeToken');
      	
    	try{

			\Stripe\Stripe::setApiKey("sk_test_49lJgLAdftnvpZr6jCWLGI7V00Kvv4Zlxa");
			
			// $customer = \Stripe\Customer::create(array(
   //                      'email' => 'maheshab555@gmail.com',
   //                      'source'  => 'tok_amex'
   //                  ));

			//'source'  => 'tok_amex',

			$charge =  \Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                'source'  => $token,
                "description" => "Test payment"
	        ]);
	        $responseData->message = 'Success';
			$responseData->status = 'Success';

		}catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
    }

}
