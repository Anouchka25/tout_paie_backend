<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;

class UploadController extends Controller {
	
	//Upload File
	public function uploadFile($file) {
		
		$input['imagename'] = 'img'.time() . '.' . $file->getClientOriginalExtension();
		$destinationPath = public_path('images');
		try{
			$file->move($destinationPath, $input['imagename']);
		} catch (Exception $e) {
			$responseData->status = 'Error';
		}
		$fileName = $input['imagename'];
		return $fileName;
	}
}
