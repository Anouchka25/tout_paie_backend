<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\ResponseData;
use App\Posts;
use App\PostComments;
use App\PostLikes;
use App\PostDocument;
use Mail;
use Auth;
use Exception;
use Hash;
use App\Friends;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Session;
use App\Http\Controllers\UploadController;
use Validator;
use App\Http\Controllers\SendNotification;
use Stripe\Stripe as Stripe;
use Stripe\Charge as Charge;

class PostController extends Controller {

	//Save User Post
	public function saveUserPost(Request $request) {
		$posts = new Posts;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'PostDescription.required'=>'post_description_not_empty',
			//'PostDocument.required'=>'select_file',
			'UserID.required'=>'user_id_not_empty',
			//'PostDocument.video'=>'only_image_or_video_allowed',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'PostDescription' => 'required',
			'UserID' => 'required',
			'PostDocument.*' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,avi,mpeg,doc,docx,pdf,xls,xlsx',
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

		if(input::file('PostDocument')){
			$PostDocument = input::file('PostDocument');
		}else{
			$PostDocument=[];
		}

	
		
		
		// if(sizeof($PostDocument) <=0){
		// 	$responseData->message = 'select_file';
		// 	$responseData->status = 'Error';
		// 	$response = array(
		// 		$responseData,
		// 	);
		// 	return json_encode($response);
		// }

		if(sizeof($PostDocument)>5){
			$responseData->message = 'not_allow_to_select_more_than_5_files';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}
		//Laravel validation end

		try {
			\DB::beginTransaction();
			$posts->PostDescription = $request->PostDescription;
			$posts->UserID = $request->UserID;
			$posts->save();
			//Add Post Documents
			$postDoc=[];
			// echo 'size of ---'.sizeof(input::file('PostDocument'));
			// echo 'size of ---'.sizeof($PostDocument);

			for($i=0;$i<sizeof($PostDocument);$i++) {
				$file = input::file('PostDocument')[$i];
				//echo $file.'!!'.$i.'&&&&&';
				$input[$i] = time() .$i. '.' . $file->getClientOriginalExtension();
				$destinationPath = public_path('images');
				$file->move($destinationPath, $input[$i]);
				//$data->PostDocument = $input[$i];
				$Post_images = new PostDocument;
				$Post_images->PostDocument = $input[$i];
				$Post_images->PostID = $posts->PostID;
				$Post_images->save();

				// $uploadController = new UploadController;
				// $postDoc[] = array(
				// 	'PostDocument' => $uploadController->uploadFile($file),
				// 	'PostID' => $posts->PostID,
				// );
			}

			// for($i=0;$i<sizeof($PostDocument);$i++) {
			// 	echo "loop chya aat ala";
			// 	echo $PostDocument[$i];
			// 	// $image = $PostDocument[$i];
			// 	// $input[$i] = time() .$i. '.' . $image->getClientOriginalExtension();
			// 	// $destinationPath = public_path('images');
			// 	// $image->move($destinationPath, $input[$i]);
			// 	// //$data->PostDocument = $input[$i];
			// 	// $Post_images = new PostDocument;
			// 	// $Post_images->PostDocument = $input[$i];
			// 	// $Post_images->PostID = $posts->PostID;
			// 	// $Post_images->save();
			// 	}

			//Batch insert to reduce to many DB callls
			// PostDocument::insert($postDoc);
			
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		}catch (Exception $e) {
			\DB::rollback();
			//echo "errorrrrrrr :".$e;
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		\DB::commit();
		$response = array(
			$responseData,
		);

		return json_encode($response);
	}


	//Save User Post
	public function saveUserMobilePost(Request $request) {
		$posts = new Posts;
		$responseData = new ResponseData;
        
		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'PostDescription.required'=>'post_description_not_empty',
			//'PostDocument.required'=>'select_file',
			'UserID.required'=>'user_id_not_empty',
			//'PostDocument.video'=>'only_image_or_video_allowed',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'PostDescription' => 'required',
			'UserID' => 'required',
			'PostDocument.*' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,avi,mpeg,doc,docx,pdf,xls,xlsx',
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

		if(input::file('PostDocument')){
			$PostDocument = input::file('PostDocument');
		}else{
			$PostDocument=[];
		}

	
		if(sizeof($PostDocument)>5){
			$responseData->message = 'not_allow_to_select_more_than_5_files';
			$responseData->status = 'Error';
			$response = array(
				$responseData,
			);
			return json_encode($response);
		}
		//Laravel validation end

		try {
			\DB::beginTransaction();
			$posts->PostDescription = $request->PostDescription;
			$posts->UserID = $request->UserID;
			$post_ID=$request->PostID;

			if($post_ID==null){
                $posts->save();
			}else{
				$posts->PostID=$post_ID;
			}
			
			//Add Post Documents
			$postDoc=[];
		
			for($i=0;$i<sizeof($PostDocument);$i++) {
				$file = input::file('PostDocument')[$i];
				//echo $file.'!!'.$i.'&&&&&';
				$input[$i] = time() .$i. '.' . $file->getClientOriginalExtension();
				$destinationPath = public_path('images');
				$file->move($destinationPath, $input[$i]);
				//$data->PostDocument = $input[$i];
				$Post_images = new PostDocument;
				$Post_images->PostDocument = $input[$i];
				$Post_images->PostID = $posts->PostID;
				$post_ID=$posts->PostID;
				$Post_images->save();

			
			}

			
			$responseData->PostID=$post_ID;
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		}catch (Exception $e) {
			\DB::rollback();
			//echo "errorrrrrrr :".$e;
			$responseData->message = 'Error';
			$responseData->status = 'Error';
		}
		\DB::commit();
		$response = array(
			$responseData,
		);

		return json_encode($response);
	}

	//Update Post
	public function updateUserPost(Request $request, $id) {
		$posts = new Posts;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'PostDescription.required'=>'post_description',
			// 'PostDocument.mimetypes'=>'select_file',	
			// 'PostDocument.uploaded'=>'select_file',
			// 'PostDocument.image'=>'select_file',
			'UserID.required'=>'user_id_not_empty',
		);

		//Validation rule
		$validator = Validator::make($request->all(), [
			'PostDescription' => 'required',
			'UserID' => 'required',
			'PostDocument.*' => 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,doc,docx,pdf,xls,xlsx',
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
				$postsData['PostDescription'] = $request->PostDescription;
				$postsData['UserID'] = $request->UserID;
				$posts::where('PostID', $id)->update($postsData);

				//Add Post Documents
				
				if(input::file('PostDocument')){
					$PostDocument = input::file('PostDocument');
				}else{
					$PostDocument=[];
				}

				if(sizeof($PostDocument) >=1){
					//PostDocument::where('PostID', $id)->delete();
					$postDoc=[];
					for($j=0;$j<sizeof($PostDocument);$j++) {
						$file = input::file('PostDocument')[$j];
						$uploadController = new UploadController;
						$postDoc[] = array(
							'PostDocument' => $uploadController->uploadFile($file),
							'PostID' => $id,
						);
					}
					//Batch insert to reduce to many DB callls
					PostDocument::insert($postDoc);
				}
				$responseData->message = 'Success';
				$responseData->status = 'Success';
			} catch (Exception $e) {
				\DB::rollback();
				$responseData->message = 'Error';
				$responseData->status = 'Error';
			}
		\DB::commit();
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Delete post by id
	public function deletePost($id) {
		$responseData = new ResponseData;
		$flag = Posts::where('PostID', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			Posts::where('PostID', $id)->delete();
			PostDocument::where('PostID', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
	
	//Get Post By Post Id
	public function getPostByPostId($id) {
		$responseData = new ResponseData;

		$post =DB::table('tblPosts')
		->select('tblPosts.*')
		->where('PostID', $id)
        ->orderBy('tblPosts.PostID', 'DSC')
		->get();

		$postComments =DB::table('tblPostComments')
		->select('tblPostComments.*')
		->where('PostID', $id)
        ->orderBy('tblPostComments.PostCommentID', 'DSC')
		->get();

		$postDoc =DB::table('tblPostDocuments')
		->select('tblPostDocuments.*')
		->where('PostID', $id)
        ->orderBy('tblPostDocuments.PostDocumentId', 'DSC')
		->get();
		
		foreach ($post as $key => $value) {  
			$count = PostLikes::where('PostID', $value->PostID)->get();
			$commentCount = $count->count();
            $post[$key]->Likes = floatval($commentCount);
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = ['post'=>$post, 'postComments'=>$postComments, 'PostDocuments'=>$postDoc];
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Post By Store Id
	public function getPostByStoreId($id) {
		$responseData = new ResponseData;

		$store =DB::table('tblPosts')->select('tblPosts.*')->where('PostID', $id)
        ->orderBy('tblPosts.PostID', 'DSC')->get();
		
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $store;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Save Post Comments
	public function savePostComments(Request $request) {
		$postComments = new PostComments;
		$responseData = new ResponseData;

		//Laravel Validation start
		//Messages set in laravel validation
		$messsages = array(
			'Comment.required'=>'enter_comment',
		);
		//Validation rule
		$validator = Validator::make($request->all(), [
			'Comment' => 'required',
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
			$postComments->UserID = $request->UserID;
			$postComments->PostID = $request->PostID;
			$postComments->Comment = $request->Comment;
			$postComments->save();
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

	//Get All Post
	public function getAllPost(Request $request) {
		$responseData = new ResponseData;
		$posts =DB::table('tblPosts')
		   ->select('tblPosts.*')
           ->orderBy('tblPosts.PostID', 'DSC')
		   ->get();

		foreach ($posts as $key => $value) {  
		    $count = PostLikes::
			where('PostID', $value->PostID)->get();
			$commentCount = $count->count();
            $posts[$key]->Likes = floatval($commentCount);

			$postComments =DB::table('tblPostComments')
			->join('tblUsers', 'tblUsers.UserID', '=', 'tblPostComments.UserID')
			->select('tblPostComments.*','tblUsers.FirstName','tblUsers.FirstName','tblUsers.LastName','tblUsers.Email')
			->where('PostID', $value->PostID)
			->get();
        	$posts[$key]->PostComments = $postComments;

        	$data = DB::table('tblPostDocuments')
			->select('tblPostDocuments.*')
			->where('tblPostDocuments.PostID', $value->PostID)
	        ->orderBy('tblPostDocuments.PostID', 'DSC')
	        ->get();
            $posts[$key]->postDocuments = $data;

            $user = DB::table('tblUsers')
			->select('tblUsers.FirstName','tblUsers.LastName','tblUsers.ProfilePhotoURL')
            ->where('UserID', $value->UserID)->get();
            $posts[$key]->UserData = $user;
		}

        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $posts;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get Last 15 Posts Comments
	public function getPostComment(Request $request, $id) {
		$responseData = new ResponseData;
		$posts =DB::table('tblPostComments')
		   ->select('tblPostComments.*')
		   ->Where('PostID', $id)
		   ->Limit(15)
           ->orderBy('tblPostComments.PostCommentID', 'DSC')
		   ->get();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $posts;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Get All Post Comments Count
	public function getPostCommentCount($id) {
		$responseData = new ResponseData;
		$count = PostComments::
		where('PostID', $id)
		->get();
		$commentCount = $count->count();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $commentCount;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Save Post Likes
	public function savePostLikes(Request $request) {
		$postLikes = new PostLikes;
		$responseData = new ResponseData;
		$flag = PostLikes::where('PostID', $request->PostID)->
		where('UserID', $request->UserID)->exists();

		if ($flag) {
			$responseData->message = 'like_already_given_to_post';
			$responseData->status = 'Error';
		} else {
			$postLikes->UserID = $request->UserID;
			$postLikes->PostID = $request->PostID;
			$postLikes->save();
			$responseData->message = 'Success';
			$responseData->status = 'Success';
		}
		$response = array(
			$responseData,
		);
		return json_encode($response);
	}

	//Get All Post Comments Count
	public function getPostLikeCount($id) {
		$responseData = new ResponseData;
		$count = PostLikes::where('PostID', $id)->get();
		$commentCount = $count->count();
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $commentCount;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Active or Inactive post
	public function updatePostStatus(Request $request, $id) {
		$posts = new Posts;
		$responseData = new ResponseData;
			try {
				$postsData['PostStatus'] = $request->PostStatus;
				$posts::where('PostID', $id)->update($postsData);
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

	//Get All Post By User Id
	public function getAllPostByUserId($id) {
		$responseData = new ResponseData;
		
		$post = DB::table('tblPosts')
		->select('tblPosts.*')
		->where('tblPosts.PostStatus', 'Y')
		->where('tblPosts.UserID', $id)
        ->orderBy('tblPosts.PostID', 'DSC')
        ->get();

		foreach ($post as $key => $value) {  
			//Post likes
			$count = PostLikes::
			where('PostID', $value->PostID)->get();
			$commentCount = $count->count();
            $post[$key]->Likes = floatval($commentCount);

            //Post documents
			$data = DB::table('tblPostDocuments')
			->select('tblPostDocuments.*')
			->where('tblPostDocuments.PostID', $value->PostID)
	        ->orderBy('tblPostDocuments.PostID', 'DSC')
	        ->get();
			$post[$key]->postDocuments = $data;
			

			$user = DB::table('tblUsers')
			->select('tblUsers.FirstName','tblUsers.LastName','tblUsers.ProfilePhotoURL','tblUsers.UserStatus')
            ->where('UserID', $value->UserID)->get();
            $post[$key]->UserData = $user;

	        //Post comments
	        $postComments =DB::table('tblPostComments')
			->select('tblPostComments.*','tblUsers.UserID','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL','tblUsers.UserStatus')
			->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblPostComments.UserID')
			->where('PostID', $value->PostID)
	        ->orderBy('tblPostComments.PostCommentID', 'DSC')
			->get();
			$post[$key]->PostComments = $postComments;
		}
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $post;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}

	//Delete Post Images
	public function deletePostImages(Request $request, $id) {
		$responseData = new ResponseData;
		$flag = PostDocument::where('PostDocumentId', $id)->exists();
		if ($flag) {
			$responseData->message = 'Success';
			$responseData->status = 'Success';
			PostDocument::where('PostDocumentId', $id)->delete();
		} else {
			$responseData->message = 'id_not_exits';
			$responseData->status = 'Error';
		}
		$response = array(
			$responseData
		);
		return json_encode($response);
	}


	//Send Mail 
	public function sendMailToAdmin(Request $request, $id) {
		try {
			$responseData = new ResponseData;
			$user = new User;

			
						$AdminDetails =DB::table('tblUsers')
						->select('tblUsers.Email','tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.City')
						->Where('tblUsers.Role', 'SA')
						//->whereIn('tblUsers.UserID',array('50','53'))
						->Where('tblUsers.isActive','Y')
						->get();

						
						
						foreach ($AdminDetails as $key => $value) {  
							
							$AdminDetails[$key]->postSubject=$request->postSubject;
							$AdminDetails[$key]->postmessage=$request->postmessage;

						}
			
			
			$user_data['userName'] = $request->userName;
			$user_data['postSubject']=$request->postSubject;
			$user_data['postmessage'] = $request->postmessage;

				$data = array("userName" => $user_data['userName'],"postmessage"=>$user_data['postmessage']);
				$message = 'Post feedback';

				foreach ($AdminDetails as $value) { 

				Mail::send('post', $data, function($message)  use ($value) {				
					$message->to($value->Email,$value->FirstName)
					->subject($value->postSubject);
					$message->from('mindnervesdemo@gmail.com','Tout Paie');
				});
			}
               $responseData->message = 'Success';
				$responseData->status = 'Success';	
				$responseData->adminDtails =$AdminDetails;
			}catch (Exception $e) {
				echo"errorrrrr :".$e;
				$responseData->message = 'Error';
				$responseData->status = 'Error';
				$responseData->adminDtails =$AdminDetails;
			}

			$response = array(
				$responseData,
			);
			return json_encode($response);
	}

	//Get All Post By User Id
	public function getMyAndFreindsPosts($id) {
		$responseData = new ResponseData;
		$getMyFriends = DB::table('tblFriends')
		->select('tblFriends.*')
		->where('tblFriends.status', 'Accepted')
		->where('tblFriends.UserID', $id)
        ->orderBy('tblFriends.RelationID', 'DSC')
        ->get();
        $friendsIDS = array();
        foreach ($getMyFriends as $key => $value) {
        	array_push($friendsIDS, $value->FriendUserID);
        }
       	array_push($friendsIDS, $id);
      
		$post = DB::table('tblPosts')
		->select('tblPosts.*')
		->where('tblPosts.PostStatus', 'Y')
		->whereIn('tblPosts.UserID', $friendsIDS)
        ->orderBy('tblPosts.PostID', 'DSC')
        ->get();

		foreach ($post as $key => $value) {  
			//Post likes
			$count = PostLikes::
			where('PostID', $value->PostID)->get();
			$commentCount = $count->count();
            $post[$key]->Likes = floatval($commentCount);

            //Post documents
			$data = DB::table('tblPostDocuments')
			->select('tblPostDocuments.*')
			->where('tblPostDocuments.PostID', $value->PostID)
	        ->orderBy('tblPostDocuments.PostID', 'DSC')
	        ->get();
			$post[$key]->postDocuments = $data;
			

			$user = DB::table('tblUsers')
			->select('tblUsers.FirstName','tblUsers.LastName','tblUsers.ProfilePhotoURL','tblUsers.UserStatus')
            ->where('UserID', $value->UserID)->get();
            $post[$key]->UserData = $user;

	        //Post comments
	        $postComments =DB::table('tblPostComments')
			->select('tblUsers.FirstName','tblUsers.LastName','tblUsers.MobileNumber','tblUsers.ProfilePhotoURL','tblUsers.UserStatus','tblPostComments.*')
			->leftjoin('tblUsers', 'tblUsers.UserID', '=','tblPostComments.UserID')
			->where('PostID', $value->PostID)
	        ->orderBy('tblPostComments.PostCommentID', 'DSC')
			->get();
			$post[$key]->PostComments = $postComments;
		}
        $responseData->message = 'Success';
		$responseData->status = 'Success';
		$responseData->data = $post;
		$response = array(
			$responseData
		);
		return json_encode($response);
	}
}
