<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\ProductCategory;
use App\ProductCategoryType;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use Validator;

class ProductCategoryController extends Controller {

	//save Product Category
	public function saveProductCategory(Request $request) {
		$data = new ProductCategory;
		$responseData = new ResponseData;
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'ProductCategoryName.required'=>'select_product_category_name',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'ProductCategoryName' => 'required',
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
			$data->ProductCategoryName = $request->ProductCategoryName;
			$data->save();
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

	//Update Product Category
	public function updateProductCategory(Request $request, $id) {
		$productCategory = new ProductCategory;
		$responseData = new ResponseData;

		//Laravel Validation start
		$messsages = array(
			'ProductCategoryName.required'=>'select_product_category_name',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'ProductCategoryName' => 'required',
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
			$data['ProductCategoryName'] = $request->ProductCategoryName;
			$productCategory::where('ProductCategoryID', $id)->update($data);
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

	//Delete Product Catalogue
	public function deleteProductCategory($id) {
		$responseData = new ResponseData;
		$flag = ProductCategory::where('ProductCategoryID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			ProductCategory::where('ProductCategoryID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Product Category 
	public function getAllProductCategory(Request $request) {
		$responseData = new ResponseData;
		$ProductCategory = DB::table('tblProductCategory')
		->orderBy('tblProductCategory.ProductCategoryID', 'DSC')
		->get();
		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $ProductCategory;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
}
