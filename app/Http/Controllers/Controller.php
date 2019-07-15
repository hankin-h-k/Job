<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($msg, $data=[], $cookie = null, $jsonp = false){
		$result = [
			'code'=> 0,
			'message'=> $msg,
			'data'=> $data,
		];
		if($jsonp){
		    return Response()->jsonp('callback', $result);
        }else{
            return Response()->json($result);
        }
	}

	//接口返回失败
	public function failure($msg, $data=[], $jsonp=false){
		$result = [
			'code'=> 1,
			'message'=> $msg,
			'data'=> $data,
		];
		if($jsonp){
		    return Response()->jsonp('callback', $result);
        }else{
		    return Response()->json($result);
        }
	}

	public function test()
	{
		dd(bcrypt('15872844805'));
	}


	public function upload(Request $request)
	{
		$file = $_FILES['fileData'];
        \UploadService::uploadFile($file);
	}

	public function aliyunSignature(Request $request)
	{
		$response = \UploadService::aliyunSignature($request);
		return $this->success('ok', $response);
	}

    /**
     * 时间段
     * @param  [type] $start_time [description]
     * @param  [type] $end_time   [description]
     * @return [type]             [description]
     */
    public function daliy($start_time ,$end_time)
    {
        $strtime1 = strtotime($start_time);
        $strtime2 = strtotime($end_time);  
           
        $day_arr[] = date('Y-m-d', $strtime1); // 当前月;  
        while( ($strtime1 = strtotime('+1 day', $strtime1)) <= $strtime2){  
            $day_arr[] = date('Y-m-d',$strtime1); // 取得递增月;   
        } 
        return $day_arr; 
    }

    public function uploadToLocal(Request $request)
    {
	    $file = $request->file('img');
	    $fileName = \UploadService::uploadToLocal($file);
        if ($fileName){
            return $fileName;
        }
        return '上传失败';
        // $file = $_FILES['file'];
        // $fileName = $request->get('file_name');
        // $fileName = $fileName ?: $file['name'];
        // $path = str_finish($request->get('folder'), '/') . $fileName;
        // $content = File::get($file['tmp_name']);

        // $result = $this->manager->saveFile($path, $content);

    }
}
