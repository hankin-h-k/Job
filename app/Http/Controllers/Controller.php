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
        $result = \UploadService::uploadFile($file);
        return $this->success('ok', $result);
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
	    // $file = $request->file('file');
     //    dd($file);
        $file = $_FILES['fileData'];
        // dd($request->input('file'));
	    $fileName = \UploadService::uploadToLocal($file);
        if (is_array($fileName)){
            if (isset($fileName['is_valid']) && empty($fileName['is_valid'])) {
                return $this->failure('图片无效！');
            }elseif (isset($fileName['extension']) && empty($fileName['extension'])) {
                return $this->failure('图片扩展名有误！');
            }elseif (isset($fileName['size']) && empty($fileName['size'])) {
                return $this->failure('图片尺寸大于4M！');
            }elseif (isset($fileName['request']) && empty($fileName['request'])) {
                return $this->failure('图片上传有误！');
            }
        }
        $path = config('app.url').$fileName;
        return $this->success('ok', $path);
    }
}
