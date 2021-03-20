<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\ProductBasket;
use Auth;
use App\ProductCalaloguePrice;
use Exception;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use Validator;

class BasketController extends Controller {
    
    //Save Product Basket
	public function saveProductBasket(Request $request) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;

		$flag = ProductBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->exists();
			if ($flag) {
			try {
				$basketData['UserID'] = $basketReqData['UserID'];
				$basketData['ProductCatalogueID'] = $basketReqData['ProductCatalogueID'];
				$basketData['StoreID'] = $basketReqData['StoreID'];
				$basketData['Quantity'] = $basketReqData['Quantity'];
				$basketData['Amount'] = $basketReqData['Amount'];

				$productBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->update($basketData);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}

			}else {
				try {
					$productBasket->UserID = $basketReqData['UserID'];
					$productBasket->ProductCatalogueID = $basketReqData['ProductCatalogueID'];
					$productBasket->StoreID = $basketReqData['StoreID'];
					$productBasket->Quantity = $basketReqData['Quantity'];
					$productBasket->Amount = $basketReqData['Amount'];
					$response = $productBasket->save();
					$responseData->message = 'Success';
					$responseData->status = 'Success';

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

	//Save Product Basket for Product Types
	public function saveProductBasketForProductType(Request $request) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		// ProductType

		$flag = ProductBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->where('ProductType', $request->ProductType)->exists();
			if ($flag) {
				$qty = DB::table('tblProductBasket')
					->select('tblProductBasket.Quantity')
					->where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->where('ProductType', $request->ProductType)->get();
					if(empty($qty[0])){
						$Quantity = 0;
					}else{
						$Quantity = $qty[0]->Quantity;
					}
			try {
				$basketData['UserID'] = $basketReqData['UserID'];
				$basketData['ProductCatalogueID'] = $basketReqData['ProductCatalogueID'];
				$basketData['StoreID'] = $basketReqData['StoreID'];
				$basketData['Quantity'] = $basketReqData['Quantity'] + $Quantity;
				$basketData['Amount'] = $basketReqData['Amount'];

				$productBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->where('ProductType', $request->ProductType)->update($basketData);

				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}

			}else {
				try {
					$productBasket->UserID = $basketReqData['UserID'];
					$productBasket->ProductCatalogueID = $basketReqData['ProductCatalogueID'];
					$productBasket->StoreID = $basketReqData['StoreID'];
					$productBasket->Quantity = $basketReqData['Quantity'];
					$productBasket->Amount = $basketReqData['Amount'];
					$productBasket->ProductType = $basketReqData['ProductType'];
					$response = $productBasket->save();
					$responseData->message = 'Success';
					$responseData->status = 'Success';
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


	//get Product Basket Info
	public function getBasketProductInfo(Request $request) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		try {

			$data =DB::table('tblProductBasket')
		   ->leftjoin('tblProductCatalogue', 'tblProductBasket.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->leftjoin('tblStore', 'tblProductBasket.StoreID', '=', 'tblStore.StoreID')
           ->Where('tblProductBasket.ProductCatalogueID', $request->ProductCatalogueID)
           ->Where('tblProductBasket.UserID', $request->UserID)
           ->Where('tblProductBasket.StoreID', $request->StoreID)
           ->select('tblProductBasket.*','tblProductCatalogue.*','tblStore.*')
           ->get();

			$responseData->message = 'Success';
			$responseData->status = 'Success';
			$responseData->data = $data;
		} catch (Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}
	

	//Update Product Basket
	public function updateProductBasket(Request $request, $id) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		try {
			$basketData['UserID'] = $basketReqData['UserID'];
			$basketData['ProductCatalogueID'] = $basketReqData['ProductCatalogueID'];
			$basketData['StoreID'] = $basketReqData['StoreID'];
			$basketData['Quantity'] = $basketReqData['Quantity'];
			$basketData['Amount'] = $basketReqData['Amount'];
			$productBasket::where('ProductBasketID', $id)->update($basketData);
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


    //Delete Basket Product
	public function deleteBasketProduct($id) {
		$responseData = new ResponseData;
		$flag = ProductBasket::where('ProductBasketID', $id)->exists();
		if ($flag) {

			$qty = DB::table('tblProductBasket')
			->select('tblProductBasket.Quantity')
			->where('ProductBasketID', $id)->get();
			
			if(empty($qty[0])){
				$Quantity = 0;
			}else{
				$Quantity = $qty[0]->Quantity;
			}

			ProductBasket::where('ProductBasketID', $id)->delete();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			$responseData->data = ['Quantity' => $Quantity];
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
    }
    
    //Get Product Basket By User Id
	public function getProductBasketByUserId(Request $request, $id) {
		$responseData = new ResponseData;
        $store =DB::table('tblProductBasket')

		   ->leftjoin('tblProductCatalogue', 'tblProductBasket.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')

		   // ->leftjoin('tblProductCalaloguePrice', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblProductCalaloguePrice.ProductCatalogueID')

		   ->join('tblStore', 'tblProductBasket.StoreID', '=', 'tblStore.StoreID')
           ->Where('tblProductBasket.UserID', $id)
           ->select('tblProductBasket.*','tblProductCatalogue.*','tblStore.*')
           ->orderBy('tblProductBasket.ProductBasketID', 'DSC')
           ->get();

        foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$price =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			
			->get();
			$store[$key]->ProductCalaloguePrice = $price;


		}	
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get Product Basket By Basket Id
	public function getProductBasketByBasketId(Request $request, $id) {
		$responseData = new ResponseData;
        $store =DB::table('tblProductBasket')
		   ->leftjoin('tblProductCatalogue', 'tblProductBasket.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID')
		   ->leftjoin('tblStore', 'tblProductBasket.StoreID', '=', 'tblStore.StoreID')
           ->Where('tblProductBasket.ProductBasketID', $id)
           ->select('tblProductBasket.*','tblProductCatalogue.*','tblStore.*')
           ->get();

        foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;
		}	
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Reduce Product Basket Quantity
	public function reduceProductBasketQuantity(Request $request, $id) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		$qty = DB::table('tblProductBasket')
			->select('tblProductBasket.Quantity')
			->where('ProductBasketID', $id)->get();
			
		if(empty($qty[0])){
			$Quantity = 0;
		}else{
			$Quantity = $qty[0]->Quantity;
		}

		if($Quantity > 1){
			try {
				$basketData['Quantity'] = $Quantity - 1;
				$productBasket::where('ProductBasketID', $id)->update($basketData);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		}else{
			$responseData->message = 'quantity_is_not_enough_to_remove';
			$responseData->status = 'Error';
		}

		$response = array(
			$responseData,
		);
		return json_encode($response);
	}


	//Save Product Basket
	public function saveProductBasketPluse(Request $request) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		$flag = ProductBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->exists();

			if ($flag) {
				$qty = DB::table('tblProductBasket')
					->select('tblProductBasket.Quantity')
					->where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->get();
					if(empty($qty[0])){
						$Quantity = 0;
					}else{
						$Quantity = $qty[0]->Quantity;
					}

			try {
				
				$basketData['UserID'] = $basketReqData['UserID'];
				$basketData['ProductCatalogueID'] = $basketReqData['ProductCatalogueID'];
				$basketData['StoreID'] = $basketReqData['StoreID'];
				$basketData['Quantity'] = $basketReqData['Quantity'] + $Quantity;
				$basketData['Amount'] = $basketReqData['Amount'];
				//$basketData['DeliveryMode'] = $basketReqData['DeliveryMode'];

				$productBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->update($basketData);

				$responseData->message = 'Success';
				$responseData->status = 'Success';
				$responseData->data = ['Quantity' => $basketReqData['Quantity'] + $Quantity];
			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
			}else {
				try {
					$productBasket->UserID = $basketReqData['UserID'];
					$productBasket->ProductCatalogueID = $basketReqData['ProductCatalogueID'];
					$productBasket->StoreID = $basketReqData['StoreID'];
					$productBasket->Quantity = $basketReqData['Quantity'];
					$productBasket->Amount = $basketReqData['Amount'];
					$response = $productBasket->save();
					$responseData->message = 'Success';
					$responseData->status = 'Success';
					$responseData->data = ['Quantity' => $basketReqData['Quantity']];

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

	//Reduce Product Basket Quantity By ProductCatalogueID, UserID, StoreID
	public function reduceProductBasketQuantityForSingleItem(Request $request) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;

		$qty = DB::table('tblProductBasket')
				->select('tblProductBasket.Quantity')
				->where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->get();
		
			if(empty($qty[0])){
				$Quantity = 0;
			}else{
				$Quantity = $qty[0]->Quantity;
			}

			if($Quantity > 1){
			try {

				$basketData['Quantity'] = $Quantity - 1;
				$productBasket::where('ProductCatalogueID', $request->ProductCatalogueID)->where('UserID', $request->UserID)->where('StoreID', $request->StoreID)->update($basketData);
				$responseData->message = 'Success';
				$responseData->status = 'Success';
				$responseData->data = ['Quantity' => $basketData['Quantity']];

			} catch (Exception $e) {
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		}else{
			$responseData->message = 'quantity_is_not_enough_to_remove';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	public function setAddressTypeForBasket(Request $request, $user_id) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'AddressType.required'=>'please_select_delivery_address_type',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'AddressType' => 'required',
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
			$basketData['AddressType'] = $basketReqData['AddressType'];
			$productBasket::where('UserID', $user_id)->update($basketData);

		//Check available quantity
		$flag_qty = 0;
		$basketDataToOrderItems = ProductBasket::where('UserID', $user_id)
			->get();

		$basketDataToOrderItemsGroups = ProductBasket::where('UserID', $user_id)
			->groupBy('ProductCatalogueID')->get();


		foreach ($basketDataToOrderItemsGroups as $key => $value) {
				$balance = DB::table('tblProductBasket')->where('ProductCatalogueID', $value->ProductCatalogueID)->where('UserID', $user_id)->sum('Quantity');
				
				$basketDataToOrderItemsGroups[$key]->Quantity = $balance;
		}

		foreach ($basketDataToOrderItemsGroups as $key => $value) {

				$productCatalogue = ProductCalaloguePrice::where('ProductCatalogueID', $value->ProductCatalogueID)->first();
				if($value->Quantity > $productCatalogue['AvailableQuantity'] && $productCatalogue['AvailableQuantity']){
					$flag_qty = 1;
				}

		}

		if($flag_qty == 1){
			$responseData->message = 'product_selected_quantity_more_than_available_quantity';
			$responseData->status = 'Error';
			$response = array(
				$responseData
			);
			return json_encode($response);
		}

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

	public function setDeliveryModeForBasket(Request $request, $user_id, $store_id) {
		$basketReqData = $request->all();
		$productBasket = new ProductBasket;
		$responseData = new ResponseData;
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'DeliveryMode.required'=>'please_select_delivery_mode',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'DeliveryMode' => 'required',
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
			$basketData['DeliveryMode'] = $basketReqData['DeliveryMode'];
			$productBasket::where('UserID', $user_id)->where('StoreID', $store_id)->update($basketData);
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
