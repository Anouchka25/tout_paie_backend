<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\ProductCatalogue;
use App\ProductCalaloguePrice;
use App\ProductCatalogueImages;
use App\ProductBasket;
use Auth;
use App\User;
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

class ProductCatalogueController extends Controller {

	//save Product Catalogue
	public function saveProductCatalogue(Request $request) {		
		$productCatalogReqData = $request->all();
		$productCatalogue = new ProductCatalogue;
		$productCalaloguePrice = new ProductCalaloguePrice;
		$responseData = new ResponseData;
		
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'StoreProductGroupID.required'=>'select_product_group_name',
			'ProductName.required'=>'enter_product_name',
			'ProductName.unique'=>'product_name_allready_exits',
			'PhotoURL'=>'select_image_for_product_catalogue',
			'ShortDescription.max'=>'short_description_is_not_more_than_150_chars',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'StoreProductGroupID' => 'required',
			'ProductName' => 'required|unique:tblProductCatalogue,ProductName',
			'PhotoURL' => 'required',
			'ShortDescription' => 'max:150',
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
			$productCatalogue->StoreID = $request->StoreID;
			$productCatalogue->StoreProductGroupID = $request->StoreProductGroupID;
			$productCatalogue->ProductName = $request->ProductName;
			$productCatalogue->ShortDescription = $request->ShortDescription;
			$productCatalogue->LongDescription = $request->LongDescription;
			$productCatalogue->AddFlashSell = $request->AddFlashSell;

		 
			$productCatalogue->ProductStatus ="Y";
			$productCatalogue->save();
			//Save product category type and price
			try {
					//$PromotionalPrice = $productCatalogReqData['PromotionalPrice'];
					$PromotionalPrice = $productCatalogReqData['ProductItems'];

					$productCalaloguePrice=[];
					for($i=0;$i<sizeof($PromotionalPrice);$i++) {

						$encode =json_decode($PromotionalPrice[$i]);
						$productCalaloguePrice[] = array(
							'PriceType' =>$encode->PriceType,
							'ProductType' =>$encode->ProductType,
							'PromotionalPrice' =>$encode->PromotionalPrice,
							'SellPrice' =>$encode->SellPrice,
							'Amount' =>$encode->Amount,
							'TotalQuantity' =>$encode->TotalQuantity,
							'AvailableQuantity' =>$encode->AvailableQuantity,
							'ProductCatalogueID' => $productCatalogue->ProductCatalogueID,
						);
					}
					//Batch insert to reduce to many DB callls
					ProductCalaloguePrice::insert($productCalaloguePrice);

					// tblProductCatalogueImages
					$PhotoURL = $productCatalogReqData['PhotoURL'];
					$images = input::file('PhotoURL');

					$productCalalogueImagegs=[];
					for($ij=0;$ij<sizeof($PhotoURL);$ij++) {

                        $image = $images[$ij];
                        $input[$ij] = time() .$ij. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('images');
                        $image->move($destinationPath, $input[$ij]);
                        $productCatalogueImages = new ProductCatalogueImages;
                        
						$productCalalogueImagegs[] = array(
							'ProductCatalogueImage' =>$input[$ij],
							'ProductCatalogueID' => $productCatalogue->ProductCatalogueID,
						);
					}

					//Batch insert to reduce to many DB callls
					ProductCatalogueImages::insert($productCalalogueImagegs);
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
		\DB::commit();
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//update Product Catalogue
	public function updateProductCatalogue(Request $request, $id) {
		$productCatalogueReqData = $request->all();
		$productCatalogue = new ProductCatalogue;
		$productCalaloguePrice = new ProductCalaloguePrice;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'StoreID.required'=>'select_store_name',
			'StoreProductGroupID.required'=>'select_product_group_name',
			'ProductName.required'=>'enter_product_name',
			'ProductName.unique'=>'product_name_allready_exits',
			'ShortDescription.max'=>'short_description_is_not_more_than_150_chars',
			//'PhotoURL.required'=>'select_image_for_product_catalogue',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'StoreID' => 'required',
			'StoreProductGroupID' => 'required',
			'ProductName' => 'required|unique:tblProductCatalogue,ProductName,'.$id.',ProductCatalogueID',
			'ShortDescription' => 'max:150',
			//'PhotoURL' => 'required',
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
			$data['StoreID'] = $request->StoreID;
			$data['StoreProductGroupID'] = $request->StoreProductGroupID;
			$data['ProductName'] = $request->ProductName;
			$data['ShortDescription'] = $request->ShortDescription;
			$data['LongDescription'] = $request->LongDescription;
			$data['AddFlashSell'] = $request->AddFlashSell;
			$data['ProductStatus']  = $request->ProductStatus;

			$productCatalogue::where('ProductCatalogueID', $id)->update($data);
			ProductCalaloguePrice::where('ProductCatalogueID', $id)->delete();

			//Save product calalogue price
			try {
					$PromotionalPrice = $productCatalogueReqData['ProductItems'];	
					$productCalaloguePrice=[];
					for($i=0;$i<sizeof($PromotionalPrice);$i++) {
						
						$encode =json_decode($PromotionalPrice[$i]);
						$productCalaloguePrice[] = array(
							'PriceType' =>$encode->PriceType,
							'ProductType' =>$encode->ProductType,
							'PromotionalPrice' =>$encode->PromotionalPrice,
							'SellPrice' =>$encode->SellPrice,
							'Amount' =>$encode->Amount,
							'TotalQuantity' =>$encode->TotalQuantity,
							'AvailableQuantity' =>$encode->AvailableQuantity,
							'ProductCatalogueID' => $id,
						);
					}

					//Batch insert to reduce to many DB callls
					ProductCalaloguePrice::insert($productCalaloguePrice);
						
					// tblProductCatalogueImages
					if(isset($request->PhotoURL)){
						$PhotoURL = $request->PhotoURL;
						$images = input::file('PhotoURL');
						$productCalalogueImagegs=[];
						for($ij=0;$ij<sizeof($PhotoURL);$ij++) {
	                        $image = $images[$ij];
	                        $input[$ij] = time() .$ij. '.' . $image->getClientOriginalExtension();
	                        $destinationPath = public_path('images');
	                        $image->move($destinationPath, $input[$ij]);
	                        $productCatalogueImages = new ProductCatalogueImages;
	                        
							$productCalalogueImagegs[] = array(
								'ProductCatalogueImage' =>$input[$ij],
								'ProductCatalogueID' => $id,
							);
						}
						//Batch insert to reduce to many DB callls
						ProductCatalogueImages::insert($productCalalogueImagegs);
					}
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
		);
		\DB::commit();
		return json_encode($response);
	}

	//Delete Product Catalogue
	public function deleteProductCatalogue(Request $request, $id) {
		$responseData = new ResponseData;
		$flag = ProductCatalogue::where('ProductCatalogueID', $id)->exists();
		if ($flag) {
			   
			$flag2 = ProductBasket::where('ProductCatalogueID', $id)->exists();
			$responseData->flag2=	$flag2;
		if ($flag2) {

			ProductBasket::where('ProductCatalogueID', $id)->delete();
            $responseData->id='$id';
		}
    
			$responseData->message = 'Success';
			$responseData->status = 'Success';
  





			ProductCatalogue::where('ProductCatalogueID', $id)->delete();
			ProductCalaloguePrice::where('ProductCatalogueID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Product Catalog.
	public function getProductCatalog(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->join('tblStoreProductGroup', 'tblProductCatalogue.StoreProductGroupID', '=', 'tblStoreProductGroup.StoreProductGroupID')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->select('tblStoreProductGroup.*','tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID','tblStore.StoreID','tblStore.StoreName')
		   ->where('tblProductCatalogue.ProductStatus', 'Y')  

		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItems =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItems;
		}	
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


    	//Get All Product Catalog.
	public function getProductCatalogforAdmin(Request $request) {
		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->join('tblStoreProductGroup', 'tblProductCatalogue.StoreProductGroupID', '=', 'tblStoreProductGroup.StoreProductGroupID')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->select('tblStoreProductGroup.*','tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID','tblStore.StoreID','tblStore.StoreName')
		   ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItems =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItems;
		}	
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}




	//Is prodcut available for flash sell or not
	public function statusForFlashSell(Request $request, $id) {
		$productCatalogueReqData = $request->all();
		$productCatalogue = new ProductCatalogue;
		$responseData = new ResponseData;		
		try {
			$data['AddFlashSell'] = $request->AddFlashSell;
			$productCatalogue::where('ProductCatalogueID', $id)->update($data);
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			$response = array(
				$responseData,
			);
		}catch(Exception $e) {
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		return json_encode($response);
	}

	//Get All Product Catalog By ID
	public function getProductCatalogById($id) {
		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->join('tblStoreProductGroup', 'tblProductCatalogue.StoreProductGroupID', '=', 'tblStoreProductGroup.StoreProductGroupID')
		   ->select('tblStoreProductGroup.*','tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID')
		   ->where('tblProductCatalogue.ProductCatalogueID', $id)
		   ->where('tblProductCatalogue.ProductStatus', 'Y')  
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID',  $id)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItems =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItems;

		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Get All Product Catalog By Store Prodcut Group Id
	public function getProductCatalogByStoreProductGroupId($id) {
		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->join('tblStoreProductGroup', 'tblProductCatalogue.StoreProductGroupID', '=', 'tblStoreProductGroup.StoreProductGroupID')

		   ->select('tblStoreProductGroup.*','tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID')
		   ->where('tblProductCatalogue.StoreProductGroupID', $id)
		   ->where('tblProductCatalogue.ProductStatus', 'Y')  
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItem =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItem;			
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Product Catalog By Store Prodcut Group Id
	public function getProductListByStoreIDAndProductGroupId($store_id, $product_group_id) {
		$responseData = new ResponseData;
		$store =DB::table('tblProductCatalogue')
		   ->select('tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID')
		   ->where('tblProductCatalogue.StoreID', $store_id)
		   ->where('tblProductCatalogue.ProductStatus', 'Y')  
		   ->where('tblProductCatalogue.StoreProductGroupID', $product_group_id)
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItems =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItems;
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Delete Product Catalogue Images
	public function deleteProductCatalogueImages(Request $request, $id) {
		$responseData = new ResponseData;
		$flag = ProductCatalogueImages::where('ProductCatalogueImageID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			ProductCatalogueImages::where('ProductCatalogueImageID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Product Catalog for store user.
	public function getProductCatalogByStoreProductGroupIdByStoreUser($user_id) {
		$responseData = new ResponseData;
		$user = new User;
		$user_data = $user->select('StoreID')->where('UserID', $user_id)->first();
		$store_id = $user_data['StoreID'];
		$store =DB::table('tblProductCatalogue')
		   ->join('tblStoreProductGroup', 'tblProductCatalogue.StoreProductGroupID', '=', 'tblStoreProductGroup.StoreProductGroupID')
		   ->join('tblStore', 'tblStore.StoreID', '=', 'tblProductCatalogue.StoreID')
		   ->select('tblStoreProductGroup.*','tblProductCatalogue.*', 'tblProductCatalogue.ProductCatalogueID as ProductCatalogueID','tblStore.StoreID','tblStore.StoreName')
		   ->Where('tblProductCatalogue.StoreID', $store_id)
		   ->where('tblProductCatalogue.ProductStatus', 'Y')  
           ->orderBy('tblProductCatalogue.ProductCatalogueID', 'DSC')
		   ->get();

		foreach ($store as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$store[$key]->ProductCatalogueImages = $storeComments;

			$productItems =DB::table('tblProductCalaloguePrice')
			->select('tblProductCalaloguePrice.*')
			->Where('tblProductCalaloguePrice.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCalaloguePrice.ProductCataloguePriceID', 'DSC')
			->get();
			$store[$key]->ProductItems = $productItems;
		}	
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	
	
	//Disable store
	public function disableProductByProductId(Request $request, $id) {
		$ProductCatalogue = new ProductCatalogue;
		$responseData = new ResponseData;
		$flag = ProductCatalogue::where('ProductCatalogueID', $id)->exists();
		if ($flag) {
			try {
				$data['ProductStatus'] = 'N';
				$ProductCatalogue::where('ProductCatalogueID', $id)->update($data);
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
	public function enableProductByProductId(Request $request, $id) {
		$ProductCatalogue = new ProductCatalogue;
		$responseData = new ResponseData;
		$flag = ProductCatalogue::where('ProductCatalogueID', $id)->exists();
		if ($flag) {
			try {
				$data['ProductStatus'] = 'Y';
				$ProductCatalogue::where('ProductCatalogueID', $id)->update($data);
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





}
