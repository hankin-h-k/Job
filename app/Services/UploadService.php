<?php namespace App\Services;

use OSS\OssClient;
use Illuminate\Support\Facades\Storage;


class UploadService
{

    //上传文件　
    public function uploadFile($file)
    {
        //生成新二维码云端全URI
        $object = date('Y').date('m')."/".date('d')."/".$file['name'];
        $file_url = 'https://'.config('alioss.picture_domain').'/'.$object;
        require_once base_path('vendor/aliyuncs/oss-sdk-php').'/autoload.php';
        //连接aliyun oss server
        try {
            $ossClient = new \OSS\OssClient(config('alioss.id'), config('alioss.secret'), config('alioss.host'));
        } catch(\OSS\Core\OssException $e) {
            return $e->getMessage();
        }


        //上传图片到aliyun oss
        try {
            $result = $ossClient->uploadFile(config('alioss.buckets.picture'), $object, $file['tmp_name']);
        } catch(\OSS\Core\OssException $e) {
            return $e->getMessage();
        }
        return $file_url;

    }

    //获取Web真传签名
    public function aliyunSignature($request)
    {

		$id= config('alioss.id');
		$key= config('alioss.secret');
		$host = 'https://'.config('alioss.picture_domain');
		$now = time();
		$expire = 60*30; //设置该policy超时时间是60s. 即这个policy过了这个有效时间，将不能访问
		$end = $now + $expire;
		$expiration = $this->gmt_iso8601($end);

		$dir = date('Y').date('m')."/".date('d')."/";

		//最大文件大小.用户可以自己设置
		$condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
		$conditions[] = $condition;

		//表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
		$start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
		$conditions[] = $start;


		//这里默认设置是２０２０年.注意了,可以根据自己的逻辑,设定expire 时间.达到让前端定时到后面取signature的逻辑
		$arr = array('expiration'=>$expiration,'conditions'=>$conditions);

		$policy = json_encode($arr);
		$base64_policy = base64_encode($policy);
		$string_to_sign = $base64_policy;
		$signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));
        $callback_url = $request->root().'/api/upload';
        $callback_param = array('callbackUrl'=>$callback_url, 
            'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType'=>"application/x-www-form-urlencoded"); 
        $callback_string = json_encode($callback_param);
        $base64_callback_body = base64_encode($callback_string);


		$response = array();
		$response['accessid'] = $id;
		$response['host'] = $host;
		$response['policy'] = $base64_policy;
		$response['signature'] = $signature;
		$response['expire'] = $end;
		//这个参数是设置用户上传指定的前缀
		$response['dir'] = $dir;
		$response['picture_domain'] = config('alioss.picture_domain');
        //$response['callback'] = $base64_callback_body;

		return $response;
    }

    //aliyun get signature using
	private function gmt_iso8601($time) 
    {
		$dtStr = date("c", $time);
		$mydatetime = new \DateTime($dtStr);
		$expiration = $mydatetime->format(\DateTime::ISO8601);
		$pos = strpos($expiration, '+');
		$expiration = substr($expiration, 0, $pos);
		return $expiration."Z";
	}

    public function uploadToLocal($file, $disk = 'public')
    {

    	// $data = [];
         // 1.是否上传成功
        // if (! $file->isValid()) {
        //    return $data['is_valid'] = false;
        // }

        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        // $fileExtension = $file->getClientOriginalExtension();
        // if(! in_array($fileExtension, ['png', 'jpg', 'JPG', 'PNG'])) {
        //     return $data['extension'] = false;
        // }

        // // 3.判断大小是否符合 2M
        // $tmpFile = $file->getRealPath();
        // if (filesize($tmpFile) >= 4096000) {
        //     return $data['size'] = false;
        // }

        // // 4.是否是通过http请求表单提交的文件
        // if (! is_uploaded_file($tmpFile)) {
        //     return $data['request'] = false;
        // }

        // 5.每天一个文件夹,分开存储, 生成一个随机文件名
        // $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileExtension;
        // if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
        //     return Storage::url($fileName);
        // }
        $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,99).'.'. $file['name'];
        $tmpFile = $file['tmp_name'];
        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
            return Storage::url($fileName);
        }
    }
}