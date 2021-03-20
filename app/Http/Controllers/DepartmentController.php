<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\ResponseData;
use App\Department;
use Mail;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;

class DepartmentController extends Controller {
	
	//Get All Departments 
	public function getAllDepartments(Request $request) {
		$responseData = new ResponseData;
		$department = Department::get();
		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $department;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get All Departments 
	public function updateDepartment(Request $request, $dept) {
		Session::put('DepartmentID', $dept);
		$department  = Department::where('DepartmentID', $dept)->get();
		$responseData = new ResponseData;
		$responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $department;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
}
