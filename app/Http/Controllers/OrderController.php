<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\ProductBasket;
use App\Orders;
use App\User;
use App\Store;
use App\OrderItems;
use App\PaymentTransaction;
use App\OrderDeliveryDetails;
use App\ProductCalaloguePrice;
use App\OrderStatus;
use Auth;
use Mail;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\SendNotification;
use Validator;
use Stripe\Stripe as Stripe;
use Stripe\Charge as Charge;

// testNotifications($title, $body, $device_id, $device_type)

class OrderController extends Controller {
    
    //Save Order
	public function saveOrder(Request $request) {


		$orderReqData = $request->all();
		$orders = new Orders;
		$user = new User;
		$storeData = new Store;
		$responseData = new ResponseData;
		$paymentTransaction = new PaymentTransaction;
		$orderDeliveryDetails = new OrderDeliveryDetails;
		$productBasket = new ProductBasket;
		$message = 'Error';
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'UserID.required'=>'id_not_exits',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'UserID' => 'required',
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

		$flag_qty = 0;
		$basketDataToOrderItems = ProductBasket::where('UserID', $request->UserID)
			->get();

		$basketDataToOrderItemsGroups = ProductBasket::where('UserID', $request->UserID)
			->groupBy('ProductCatalogueID')->get();

		foreach ($basketDataToOrderItemsGroups as $key => $value) {

				$balance = DB::table('tblProductBasket')->where('ProductCatalogueID', $value->ProductCatalogueID)->where('UserID', $request->UserID)->sum('Quantity');

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

		//Stripe Token
		if (!$request->has('stripeToken')) {
			$responseData->message = 'stripe_token_issue';
			$responseData->status = 'Error';
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
      	$token = $request->get('stripeToken');

		$flag = User::where('UserID', $orderReqData['UserID'])->exists();
		if (!$flag) {
			$responseData->message = 'user_id_not_exit';
			$responseData->status = 'Error';
			$response = array(
				$responseData
			);
			return json_encode($response);
		}

		$flag = ProductBasket::where('UserID', $orderReqData['UserID'])->exists();
		if (!$flag) {
			$responseData->message = 'basket_is_empty_for_login_user';
			$responseData->status = 'Error';
			$response = array(
				$responseData
			);
			return json_encode($response);
		}
		try{

			//Calculate total amount
			$totalAmount = 0;
			foreach ($basketDataToOrderItems as $key => $value) {
				$totalAmount += floatval($value->Amount) * floatval($value->Quantity);
			}

			//Calculate delivery price
			$totalDeliveryAmount = 0;
			$getProductToDeliver = ProductBasket::where('UserID', $request->UserID)
			->where('DeliveryMode', 'H')
			->get();


			foreach ($getProductToDeliver as $key => $value) {
				$store =DB::table('tblStore')
				   ->select('tblStore.DeliveryPrice')
				   ->where('tblStore.StoreID', $value->StoreID)
				   ->get();    

				   $deliveryp = isset($store[0]->DeliveryPrice) ? $store[0]->DeliveryPrice:0;
				   $totalDeliveryAmount = floatval($totalDeliveryAmount) + floatval($deliveryp);
			}

			$store_copy=$store;

			//Final Amount
			$totalTransactionAmount = floatval($totalAmount) + floatval($totalDeliveryAmount);
			//sk_test_49lJgLAdftnvpZr6jCWLGI7V00Kvv4Zlxa
			//
			//live key-
			\Stripe\Stripe::setApiKey("sk_live_el4UuDLrh8V0sjToYKDUFbQ700wuqEOxgS");
			
			// \Stripe\Stripe::setApiKey("sk_test_49lJgLAdftnvpZr6jCWLGI7V00Kvv4Zlxa");

			$charge =  \Stripe\Charge::create ([
                "amount" => $totalTransactionAmount * 100,
                "currency" => "eur",
                'source'  => $token,
                // 'source'  => 'tok_amex',
                "description" => "Tout Paie App Payment."
	        ]);

			

	        $responseData->message = 'Error';
			$responseData->status = 'Error';

	        if($charge->status == 'succeeded'){

		        $getAddressType =DB::table('tblProductBasket')
				->select('tblProductBasket.AddressType')
				->Where('tblProductBasket.UserID', $orderReqData['UserID'])
				->first();

				$userAddress = DB::table('tblUsers')
				->select('tblUsers.BillStreetName','tblUsers.BillPostalCode','tblUsers.BillCity','tblUsers.ResidenceStreetName','tblUsers.ResidencePostalCode','tblUsers.ResidenceCity')
				->Where('tblUsers.UserID', $orderReqData['UserID'])
				->first();

				if($getAddressType->AddressType == 'B'){
					$StreetName = $userAddress->BillStreetName;
					$PostalCode = $userAddress->BillPostalCode;
					$City = $userAddress->BillCity;
					$address = $StreetName .', '.$PostalCode.', '.$City;
				}else{
					$StreetName = $userAddress->ResidenceStreetName;
					$PostalCode = $userAddress->ResidencePostalCode;
					$City = $userAddress->ResidenceCity;
					$address = $StreetName .', '.$PostalCode.', '.$City;
				}
				//If else end

				$currentDate = date('Y-m-d H:i:s');
				try {
					\DB::beginTransaction();

					foreach ($basketDataToOrderItemsGroups as $key => $value) {

						$productCatalogue = ProductCalaloguePrice::where('ProductCatalogueID', $value->ProductCatalogueID)->first();

						$availableQuantity['AvailableQuantity'] = $productCatalogue['AvailableQuantity'] - $value->Quantity;
						ProductCalaloguePrice::where('ProductCatalogueID', $value->ProductCatalogueID)->update($availableQuantity);
					}

		
					$orders->UserID = $orderReqData['UserID'];
					$orders->TotalAmount = $totalAmount;
					$orders->OrderDeliveryPrice = $totalDeliveryAmount; 
					$orders->PaymentStatus = 'Success';
					$orders->Status = 1;
					$orders->OrderDeliveryAddress = $address;
					$response = $orders->save();

					$id = $orders->OrderID;
					$orderDate = $orders->OrderDate;
					$orderNumber = 'ORD- '.$id.' - '.date('Y/m/d',strtotime($orderDate));

					$orderNo['OrderNo'] = $orderNumber;
					Orders::where('OrderID', $id)->update($orderNo);

					try {
						//Save multiple order product	
						foreach ($basketDataToOrderItems as $key => $value) {
							$orderItems = new OrderItems;
							$orderItems->OrderID = $id;
							$orderItems->StoreID = $value->StoreID;
							$orderItems->Quantity = $value->Quantity;
							$orderItems->Amount = $value->Amount;
							$orderItems->ProductCataloguePriceID = isset($value->ProductType)?$value->ProductType:'';
							$orderItems->ProductCatalogueID = $value->ProductCatalogueID;
							$orderItems->DeliveryMode = $value->DeliveryMode;
							$orderItems->save();

						}

						//Get Data from basket group by store and save into order delivery details
						$basketData = ProductBasket::where('UserID', $request->UserID)
							->groupBy('StoreID')
							->get();
						foreach ($basketData as $key => $value) {
							$orderDeliveryDetails = new OrderDeliveryDetails;
							$orderDeliveryDetails->OrderID = $id;
							$orderDeliveryDetails->StoreID = $value->StoreID;
							$orderDeliveryDetails->DeliveryMode = $value->DeliveryMode;
							$orderDeliveryDetails->AddressType = $value->AddressType;
							$orderDeliveryDetails->Status = 1;
							$orderDeliveryDetails->UpdatedOn = $currentDate;
							$orderDeliveryDetails->save();


						}
						//Write Mail functionality here to send order confirmation with bill
						$user_data = $user->select('Email','FirstName','LastName','MobileNumber','City','DeviceID','DeviceType')->where('UserID', $orderReqData['UserID'])->first();

							$device_id = $user_data['DeviceID'];
							if($device_id !=''){
								$device_type = $user_data['DeviceType'];
								$title = 'Order Placed';
								$body = 'Your order is placed successfully.Order number is '.$orderNumber;

								$notification = new SendNotification;
								$notification->testNotifications($title, $body, $device_id, $device_type);
							}
							
						 
						//$store_copy=$store;

						// foreach ($store_copy as $key => $value) {

								  
						// 	$StoreManageruser =DB::table('tblUsers')
						// 	->leftjoin('tblStore', 'tblStore.StoreID', '=','tblUsers.StoreID')
						// 	->select('tblUsers.*','tblStore.StoreName')
						// 	->Where('tblUsers.StoreID','=', $value->StoreID)
							 
						// 	->get();



						// 	$storeManagerEmail=$value->Email;

						// 	Mail::send('order', $data, function($message)  use ($user_data) {	
						// 		$message->to($storeManagerEmail, 'Order')
						// 		->subject('Your order is placed');
						// 		$message->from('mindnervesdemo@gmail.com','ToutPaie');
						// 	});



						// }



						//Transaction Details
						$paymentTransaction->BalanceTransactionID = $charge->balance_transaction;
			        	$paymentTransaction->Status = $charge->status;
			        	$paymentTransaction->Amount = $totalTransactionAmount;
			        	$paymentTransaction->OrderID = $id;
			        	$paymentTransaction->TransactionDate = $currentDate;
			        	$paymentTransaction->save();
			        	//Clear User Basket
						ProductBasket::where('UserID', $request->UserID)->delete(); 

						//-------------------------------------------------------------//

						//User Email
						$orderProducts =DB::table('tblOrderItems')
							->join('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
							->leftjoin('tblProductCalaloguePrice',function($join){
								$join->on('tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID');
								$join->on('tblProductCalaloguePrice.ProductCataloguePriceID', '=', 'tblOrderItems.ProductCataloguePriceID');
							})
							->select('tblOrderItems.*','tblProductCatalogue.ProductName','tblProductCalaloguePrice.ProductType','tblProductCatalogue.ShortDescription','tblProductCatalogue.PhotoURL')
							->where('tblOrderItems.OrderID', $id)
							->get();


						$data = array("FirstName" => $user_data['FirstName'],"LastName" => $user_data['LastName'],"MobileNumber" => $user_data['MobileNumber'],
						 "Email" => $user_data['Email'], 'OrderID' =>$orderNumber,'OrderDate' =>$orderDate,"City" => $user_data['City'],
						 "ResidenceStreetName" => $StreetName,"ResidencePostalCode" => $PostalCode,"ResidenceCity" => $City,
						 'OrderItems' => $orderProducts , "TotalAmount" => $totalAmount , "TotalDeliveryAmount" => $totalDeliveryAmount);

						$message = 'Order Placed';
						Mail::send('order', $data, function($message)  use ($user_data) {	
							$message->to($user_data['Email'], 'Order')
							->subject('Your order is placed.');
							$message->from('contact@toutpaie.fr','ToutPaie');
						});
					

						//Admin Email SA ...
						$Adminuser = User::Where('tblUsers.Role','=', 'SA')
						->orderBy('tblUsers.UserID', 'DSC')
						->get();

 						$adminEmail = '';
						foreach ($Adminuser as $value) {

							$adminEmail = (string)$value->Email;
							\Log::info($adminEmail);
							\Log::info('Admin Email');
							Mail::send('trader_email', $data, function($message)  use ($adminEmail) {	
								$message->to($adminEmail, 'Order')
								->subject('This order is placed ...');
								$message->from('contact@toutpaie.fr','ToutPaie');
							});
						 }

						$orderProductsData = DB::table('tblOrderItems')
							->join('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
							->leftjoin('tblProductCalaloguePrice',function($join){
								$join->on('tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID');
								$join->on('tblProductCalaloguePrice.ProductCataloguePriceID', '=', 'tblOrderItems.ProductCataloguePriceID');
							})
							->select('tblOrderItems.*','tblProductCatalogue.ProductName','tblProductCalaloguePrice.ProductType','tblProductCatalogue.ShortDescription','tblProductCatalogue.PhotoURL')
							->where('tblOrderItems.OrderID', $id)
							->groupBy('tblOrderItems.StoreID')
							->get();

		
						foreach ($orderProductsData as $key => $value) {

							$user= $user->select('Email','FirstName','LastName','MobileNumber','City','DeviceID','DeviceType','StoreID')->where('StoreID', $value->StoreID)
								->where('isActive','Y')
								->first();

							$storeDeliveryPrice = $storeData->select('DeliveryPrice','StoreID')->where('StoreID', $value->StoreID)->first();

							$trader = DB::table('tblOrderItems')
							->join('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
								->leftjoin('tblProductCalaloguePrice',function($join){
									$join->on('tblProductCalaloguePrice.ProductCatalogueID', '=', 'tblProductCatalogue.ProductCatalogueID');
									$join->on('tblProductCalaloguePrice.ProductCataloguePriceID', '=', 'tblOrderItems.ProductCataloguePriceID');
								})
							->select('tblOrderItems.*','tblProductCatalogue.ProductName','tblProductCatalogue.ShortDescription','tblProductCatalogue.PhotoURL')
							->where('tblOrderItems.OrderID', $id)
							->where('tblOrderItems.StoreID', $value->StoreID)
							->get();

							//$result = $trader->toArray();

							$FirstName = (string)$user['FirstName'];
				      		$userEmail = (string)$user['Email'];
				      		$deliveryPrice = (int)$storeDeliveryPrice['DeliveryPrice'];

				      		$data = array("FirstName" => $user_data['FirstName'],"LastName" => $user_data['LastName'],"MobileNumber" => $user_data['MobileNumber'], "Email" => $user_data['Email'], 'OrderID' =>$orderNumber,'OrderDate' =>$orderDate,"City" => $user_data['City'],"ResidenceStreetName" => $StreetName,"ResidencePostalCode" => $PostalCode,"ResidenceCity" => $City,'OrderItems' => $trader , "TotalDeliveryAmount" => $deliveryPrice);

				   
							$message = 'Order Placed';

							if($userEmail !=''){
								\Log::info('Trader Email -----');

								\Log::info($userEmail);
								\Log::info('Trader Email -----');

								//Email to trader
					      		Mail::send('trader_email', $data, function($message)  use ($userEmail)
					      		{			
						        	$message->to($userEmail,"Users")
						        	->subject('Order Email !!!');
						        	$message->from('contact@toutpaie.fr','Tout Paie Team');
							    });
							}
				      		
						}

						//-------------------------------------------------------------//
			        	\DB::commit();


			        	$responseData->message = 'Success';
						$responseData->status = 'Success';
					}
					catch(\Stripe\Exception\CardException $e) {
					 $responseData->message=$e->getError()->message;
					 $responseData->status = 'Error';
					  \DB::rollback();
					} catch (\Stripe\Exception\RateLimitException $e) {
					  $responseData->message='Too many requests made to the API too quickly';
					  $responseData->status = 'Error';
					  \DB::rollback();
					} catch (\Stripe\Exception\InvalidRequestException $e) {
					  $responseData->message='Invalid parameters were supplied to Stripe\'s API';
					  $responseData->status = 'Error';
					  \DB::rollback();
					} catch (\Stripe\Exception\AuthenticationException $e) {
					  $responseData->message='Authentication with Stripe\'s API failed';
					  $responseData->status = 'Error';
					  \DB::rollback();
					} catch (\Stripe\Exception\ApiConnectionException $e) {
					  $responseData->message = 'Network communication with Stripe failed';
					  $responseData->status = 'Error';
					  \DB::rollback();
					}
					catch (Exception $e) {
						\Log::info('1....charge-----', [$e]);
						$responseData->message = $e->getMessage();
						$responseData->status = 'Error';
						\DB::rollback();
					}
					
				}
				catch(\Stripe\Exception\CardException $e) {
				\Log::info('2....charge-----', [$e]);
				 $responseData->message=$e->getError()->message;
				 $responseData->status = 'Error';
				  \DB::rollback();
				} catch (\Stripe\Exception\RateLimitException $e) {
					\Log::info('3....charge-----', [$e]);
				  $responseData->message='Too many requests made to the API too quickly';
				  $responseData->status = 'Error';
				  \DB::rollback();
				} catch (\Stripe\Exception\InvalidRequestException $e) {
				  $responseData->message='Invalid parameters were supplied to Stripe\'s API';
				  $responseData->status = 'Error';
				  \DB::rollback();
				} catch (\Stripe\Exception\AuthenticationException $e) {
				  $responseData->message='Authentication with Stripe\'s API failed';
				  $responseData->status = 'Error';
				  \DB::rollback();
				} catch (\Stripe\Exception\ApiConnectionException $e) {
				  $responseData->message = 'Network communication with Stripe failed';
				  $responseData->status = 'Error';
				  \DB::rollback();
				}

				 catch (Exception $e) {
				 	\Log::info('4...charge-----', [$e]);
					$responseData->message = $e->getMessage();
					$responseData->status = 'Error';
					\DB::rollback();
				}
	        }

	        //Sucess if statement check end
		}
		catch(\Stripe\Exception\CardException $e) {
		\Log::info('5...charge-----', [$e]);
		 $responseData->message=$e->getError()->message;
		 $responseData->status = 'Error';
		  \DB::rollback();
		} catch (\Stripe\Exception\RateLimitException $e) {
		  $responseData->message='Too many requests made to the API too quickly';
		  $responseData->status = 'Error';
		  \DB::rollback();
		} catch (\Stripe\Exception\InvalidRequestException $e) {
		  $responseData->message='Invalid parameters were supplied to Stripe\'s API';
		  $responseData->status = 'Error';
		  \DB::rollback();
		} catch (\Stripe\Exception\AuthenticationException $e) {
		  $responseData->message='Authentication with Stripe\'s API failed';
		  $responseData->status = 'Error';
		  \DB::rollback();
		} catch (\Stripe\Exception\ApiConnectionException $e) {
		  $responseData->message = 'Network communication with Stripe failed';
		  $responseData->status = 'Error';
		  \DB::rollback();
		}catch (Exception $e) {
			\Log::info('6...charge-----', [$e]);
			$responseData->message = $e->getMessage();
			$responseData->status = 'Error';
			\DB::rollback();
		}
		$response = array(
				$responseData
		);
		return json_encode($response);
	}

	//Update Order
	public function updateOrder(Request $request, $id) {
		$orderReqData = $request->all();
		$responseData = new ResponseData;
		$user = new User;
		try {
			\DB::beginTransaction();
			$orders['UserID'] = $orderReqData['UserID'];
			$orders['TotalAmount'] = $orderReqData['TotalAmount'];
			$orders['PaymentStatus'] = $orderReqData['PaymentStatus'];
			$orders['Status'] = $orderReqData['Status'];
			Orders::where('OrderID', $id)->update($orders);
			OrderItems::where('OrderID', $id)->delete();

			//Save Multiple Order Product
			try {
				$ProductCatalogueID = $orderReqData['ProductCatalogueID'];
				$orderItems=[];
				for($i=0;$i<sizeof($ProductCatalogueID);$i++) {
					$orderItems[] = array(
						'ProductCatalogueID' =>$orderReqData['ProductCatalogueID'][$i],
						'StoreID' =>$orderReqData['StoreID'][$i],
						'Quantity' =>$orderReqData['Quantity'][$i],
						'Amount' =>$orderReqData['Amount'][$i],
						'OrderID' => $id,
					);
				}
				//Batch insert to reduce to many DB calls
				OrderItems::insert($orderItems);

				//Write Mail functionality here to send ordr confirmation
				$user_data = $user->select('Email','FirstName','LastName','MobileNumber','City')
						->where('UserID', $orderReqData['UserID'])->first();

				$data = array("Name" => $user_data['FirstName']);
				$message = 'Order Updated';

				Mail::send('order', $data, function($message)  use ($user_data) {				
					$message->to($user_data['Email'], 'Order')
					->subject('Your order is updated');
					$message->from('contact@toutpaie.fr','Tout Paie');
				});

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

	

	//Get Order By Order Id
	public function getOrderByOrderId(Request $request, $id) {
		$responseData = new ResponseData;
		$order =DB::table('tblOrders')
		    ->leftjoin('tblUsers', 'tblOrders.UserID', '=', 'tblUsers.UserID')
		    ->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrders.Status')
			->Where('tblOrders.OrderID', $id)
            ->select('tblOrders.*','tblUsers.*','tblOrderStatus.StatusValue')
		    ->get();
		   
		$orderItems = DB::table('tblOrderItems')
			->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblOrderItems.StoreID')
			->leftjoin('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
            ->Where('tblOrderItems.OrderID', $id)
            ->select('tblOrderItems.*','tblStore.*','tblProductCatalogue.*')
     	    ->get();

     	foreach ($orderItems as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$orderItems[$key]->ProductCatalogueImages = $storeComments;
		}	
 
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = ['order'=>$order,'orderItems'=>$orderItems];
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get User Orders By User Id
	public function getUserOrders(Request $request, $id) {
		$responseData = new ResponseData;

		$order =DB::table('tblOrders')
		    ->leftjoin('tblUsers', 'tblOrders.UserID', '=', 'tblUsers.UserID')
		    ->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrders.Status')
			->Where('tblOrders.UserID', $id)
            ->select('tblOrders.*','tblUsers.*','tblOrderStatus.StatusValue')
		    ->get();

		foreach ($order as $key => $value) {  

	        $data = DB::table('tblOrderItems')
			->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblOrderItems.StoreID')
			->leftjoin('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
			->where('OrderID', $value->OrderID)
			->select('tblOrderItems.*','tblStore.StoreName','tblProductCatalogue.*')
	        ->orderBy('OrderID', 'DSC')
	        ->get();
	        $order[$key]->orderItems = $data;


		    foreach ($order[$key]->orderItems as $key1 => $value1) { 
		         $img =DB::table('tblProductCatalogueImages')
				->select('tblProductCatalogueImages.*')
				->Where('tblProductCatalogueImages.ProductCatalogueID', $value1->ProductCatalogueID)
				->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
				->get();

				$order[$key]->orderItems[$key1]->ProductCatalogueImages = $img;		
			}	
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $order;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get all order by status
	public function getOrdersByStatus(Request $request, $status) {
		$responseData = new ResponseData;
		$order =DB::table('tblOrders')
			->Where('tblOrders.Status', $status)
            ->select('tblOrders.*')
		    ->get();

		foreach ($order as $key => $value) {  
			$data = DB::table('tblOrderItems')
			->where('OrderID', $value->OrderID)
	        ->orderBy('OrderID', 'DSC')
	        ->get();
	        $order[$key]->orderItems = $data;
		}
		
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $order;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Orders
	public function getAllOrders() {
		$responseData = new ResponseData;
		//$order = Orders::get();
		$order = DB::table('tblOrders')
		    ->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrders.Status')
            ->select('tblOrders.*','tblOrderStatus.StatusValue')
		    ->get();

		foreach ($order as $key => $value) {  
			$data = DB::table('tblOrderItems')
			->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblOrderItems.StoreID')
			->leftjoin('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
			->where('OrderID', $value->OrderID)
			->select('tblOrderItems.*','tblStore.StoreName','tblProductCatalogue.ProductName')
	        ->orderBy('OrderID', 'DSC')
	        ->get();
	        $order[$key]->orderItems = $data;
		}

		foreach ($order as $key => $value) {  
			foreach ($value->orderItems as $k => $store) {
				$getDeliveryMode = DB::table('tblOrderDeliveryDetails')
				->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrderDeliveryDetails.Status')
			    ->where('StoreID', $store->StoreID)
			    ->where('OrderID', $value->OrderID)
	            ->select('tblOrderDeliveryDetails.DeliveryMode','tblOrderStatus.StatusValue')
			    ->first();

			    if(!empty($getDeliveryMode)){
			    	 $order[$key]->orderItems[$k]->DeliveryMode = $getDeliveryMode->DeliveryMode;
			    	 $order[$key]->orderItems[$k]->StatusValue = $getDeliveryMode->StatusValue;
			    }else{
			    	$order[$key]->orderItems[$k]->DeliveryMode = 'NA';
			    	$order[$key]->orderItems[$k]->StatusValue = 'NA';
			    }
			}
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $order;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	public function getAllOrdersByStoreId(Request $request, $id) {
		$responseData = new ResponseData;
		//$order = Orders::get();
		$order = DB::table('tblOrders')
	    	->join('tblOrderItems', 'tblOrderItems.OrderID', '=','tblOrders.OrderID' )
			->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrders.Status')
			->Where('tblOrderItems.StoreID', $id)
            ->select('tblOrders.*','tblOrderStatus.StatusValue')
		    ->get();

		foreach ($order as $key => $value) {  
			$data = DB::table('tblOrderItems')
			->leftjoin('tblStore', 'tblStore.StoreID', '=', 'tblOrderItems.StoreID')
			->leftjoin('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
			->where('OrderID', $value->OrderID)
			->Where('tblOrderItems.StoreID', $id)
			->select('tblOrderItems.*','tblStore.StoreName','tblProductCatalogue.ProductName')
	        ->orderBy('OrderID', 'DSC')
	        ->get();
	        $order[$key]->orderItems = $data;
		}

		foreach ($order as $key => $value) {  
			foreach ($value->orderItems as $k => $store) {
				$getDeliveryMode = DB::table('tblOrderDeliveryDetails')
				->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrderDeliveryDetails.Status')
			    ->where('StoreID', $id)
			    ->where('OrderID', $value->OrderID)
	            ->select('tblOrderDeliveryDetails.DeliveryMode','tblOrderStatus.StatusValue')
			    ->first();

			    if(!empty($getDeliveryMode)){
			    	 $order[$key]->orderItems[$k]->DeliveryMode = $getDeliveryMode->DeliveryMode;
			    	 $order[$key]->orderItems[$k]->StatusValue = $getDeliveryMode->StatusValue;
			    }else{
			    	$order[$key]->orderItems[$k]->DeliveryMode = 'NA';
			    	$order[$key]->orderItems[$k]->StatusValue = 'NA';
			    }
			}
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $order;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Order By Stores
	public function getOrdersByStoreId(Request $request, $id) {
		$responseData = new ResponseData;
		$order =DB::table('tblOrderItems')
			->leftjoin('tblOrders', 'tblOrders.OrderID', '=', 'tblOrderItems.OrderID')
			->leftjoin('tblOrderStatus', 'tblOrderStatus.StatusId', '=', 'tblOrders.Status')
			->leftjoin('tblProductCatalogue', 'tblProductCatalogue.ProductCatalogueID', '=', 'tblOrderItems.ProductCatalogueID')
			->Where('tblOrderItems.StoreID', $id)
            ->select('tblOrders.*','tblOrderItems.*','tblProductCatalogue.*','tblOrderStatus.StatusValue')
		    ->get();

		foreach ($order as $key => $value) {  
	        $storeComments =DB::table('tblProductCatalogueImages')
			->select('tblProductCatalogueImages.*')
			->Where('tblProductCatalogueImages.ProductCatalogueID', $value->ProductCatalogueID)
			->orderBy('tblProductCatalogueImages.ProductCatalogueImageID', 'DSC')
			->get();
			$order[$key]->ProductCatalogueImages = $storeComments;
		}	

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $order;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get order master status
	public function getOrderMasterStatus() {
		$responseData = new ResponseData;
		$orderStatus = OrderStatus::get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $orderStatus;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Update Order Status
	public function updateOrderStatus(Request $request, $id) {
		$orderReqData = $request->all();
		$responseData = new ResponseData;
		$user = new User;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'UserID.required'=>'user_id_empty',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
            'UserID' => 'required|numeric',
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
			$orders['Status'] = $orderReqData['Status'];
			Orders::where('OrderID', $id)->update($orders);

			//Write Mail functionality here to send ordr confirmation
			// $user_data = $user->select('Email','FirstName','LastName','MobileNumber','City')
			// ->where('UserID', $orderReqData['UserID'])->first();

			// $data = array("Name" => $user_data['FirstName'], "Status" => $orderReqData['Status']);
			// $message = 'Your order is '.$orderReqData['Status'];

			// Mail::send('order_cancel', $data, function($message)  use ($user_data) {		
			// 	$message->to($user_data['Email'], 'Order')
			// 	->subject('Order status change');
			// 	$message->from('mindnervesdemo@gmail.com','Tout Paie');
			// });

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

	//Update Order Status for store level
	public function updateOrderStatusForStoreLevel(Request $request, $store_id,$order_id) {
		$orderReqData = $request->all();
		$responseData = new ResponseData;
		$user = new User;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'UserID.required'=>'user_id_empty',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
            'UserID' => 'required|numeric',
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
			$orders['Status'] = $orderReqData['Status'];
			OrderDeliveryDetails::where('OrderID', $order_id)->where('StoreID', $store_id)
				->update($orders);

			$getStatus = DB::table('tblOrderDeliveryDetails')
			    ->where('DeliveryMode', 'H')
			    ->where('OrderID', $order_id)
	            ->select('tblOrderDeliveryDetails.DeliveryMode','tblOrderDeliveryDetails.Status')
			    ->get();
			

			$flag = 0;
			$flag_check = 0;
			foreach ($getStatus as $key => $value) {  
	        	if($value->Status == $orderReqData['Status']){
	        		$flag = 1;	
	        	}
	        	else{
	        		$flag_check = 1;
	        	}
			}	

			if($flag==1 && $flag_check!=1){
				Orders::where('OrderID', $order_id)->update($orders);
			}

			//Send notification here code
			$user = new User;
			$user_data = $user->select('DeviceID','DeviceType')->where('UserID', $orderReqData['UserID'])->first();

			$device_id = $user_data['DeviceID'];
			if($device_id !=''){

				$status = DB::table('tblOrderStatus')
			    ->where('StatusId', $orders['Status'])
	            ->select('tblOrderStatus.StatusValue')
			    ->first();
				
			$order_no = DB::table('tblOrders')
			    ->where('OrderID', $order_id)
	            ->select('tblOrders.OrderNo')
			    ->first();

			$storeName = DB::table('tblStore')
			    ->where('StoreID', $store_id)
	            ->select('tblStore.StoreName')
			    ->first();
			   	
				$device_type = $user_data['DeviceType'];
				$title = 'Your Order is'.$status->StatusValue;
				$body = $title.'.Your Order number is #'.$order_no->OrderNo.' for store '.$storeName->StoreName;
				$notification = new SendNotification;
				$notification->testNotifications($title, $body, $device_id, $device_type);
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

}