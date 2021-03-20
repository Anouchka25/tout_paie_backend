<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\Store;
use App\ResponseData;
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

class SendNotification extends Controller {

	//public function testNotifications()

	public function testNotifications($title, $body, $device_id, $device_type)
	{	

		// $title = 'Test';
		// $body = 'Body';
		// $device_type = 'android';
		
		// $device_id ='e8TBTk2no9c:APA91bGPRtLE5JnH5M9MAAUrXBKGtaCSeuwzWkMul6dDT0_3F-BHB5xvNgqkBnVsvkzgMVqdlBgyj09Gkrnrk40nna6UNJbw9RnvM1AuDrydd2kfhcl5HhK9lRfua77mK_gPI0EQEYtk';

		// $device_id='ek9ddDFqi9I:APA91bHoa_vA3S4iRnXc64z4w0Cci5eoekpeZuYicqRO2YBRYEJJZ9h5wQ287fL7URthfCWWwa5_JRMrc57o4vSBlNzmsSDTbRQbVZBkO3eLnKmRmlLYT8HWHVu7g2t31RXK12T4oamI';

		//Check device is is null or not
		if($device_id == ''){
			return;
		}

	    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	    
	    $notification = [
	        'sound' => true,
	        'title' => $title,
            'body' => $body,
	    ];
	    
	    $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
	    $fcmNotification = [
	        'to'        => $device_id, //single token
	        'notification' => $notification,
	        'data' => $extraNotificationData,
	    ];

	    // 'Authorization: key=AAAAr2t1sog:APA91bEJnho1u7siyzOL4bJST7gBCytUpQwGi8olW7MQ5YnPov3TtuNbcRL5Yx2zxiVWgXSUNrRurCp9FqYwzQBaKAjyEFFIPwqCvvFRFHT7rLyqyBmeEkePLV84UFEmsLd6uNok8NwC'

	    
	    $headers = [
	        'Authorization: key=AAAA9Tfv_0U:APA91bFXnPZN5oxiehsH0URoIX597ONmh5sjIrAsJ-HNs1OhVIxZL7sXwjYhIbHtgfhImCy7NzLPDpipg2zUoRTxmyaUNpe2qmP40qrqhevDP8oQsp93HjdisJzKMBpq8B5bj-gmLK8N',
	        'Content-Type: application/json',
	    ];

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$fcmUrl);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

	    $result = curl_exec($ch);
	    curl_close($ch);
	 //    print_r($result);
		// exit;
	    return;
	}
}
