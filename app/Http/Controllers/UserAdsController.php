<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\ResponseData;
use App\Store;
use Mail;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;

class UserAdsController extends Controller {

	public function sportOrders(Request $request)
	{
		$key = "3798c29a804a7a67a041733ffde3f5fc";
		$password = "89a98617f25c9958de180ebdddc4a464";
		// $payload = '{
		//   "order": {
		//     "line_items": [
		//       {
		//         "title":"spade",
		//         "name":"spade",
		//         "quantity": 1,
		//         "price":"200"
		//       }
		//     ]
		//   }
		// }';

		$payload = file_get_contents("php://input");

		$url = 'https://'.$key.':'.$password.'@str8-sports-inc.myshopify.com/admin/api/2019-07/orders.json';

		// Prepare new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		 
		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($payload))
		);

		$result = curl_exec($ch);
		$err     = curl_errno( $ch );
		return array("Result" => $result, "Error" => $err);
		curl_close($ch);

	}
	
}
