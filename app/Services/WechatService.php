<?php namespace App\Services;

use EasyWeChat\Factory;

class WechatService
{
    protected $app;
	public function __construct()
    {
        $mini_config = [
            'app_id' => config('wechat.mini_program.default.app_id'),
            'secret' => config('wechat.mini_program.default.secret'),
        ];
        $app = Factory::miniProgram($mini_config);
        $this->app = $app;
    }

    public function app()
    {
        return $this->app;
    }
 	
 	/**
     * 通知用户
     */
    public function informUser($param=[])
    {
        $template_id = config('wechat.tpls.inform_user');
        $page = 'pages/home/information?id='.$param['user_id'];
        $form_id = $param['form_id'];
        $data = [
            'keyword1'=>$param['user_name'],
            'keyword2'=>$param['visit_time'],
            'keyword3'=>$param['message'],
        ];
        return $this->send($param['openid'], $template_id, $page, $form_id, $data);
    }

    /**
     * 发送模板消息
     */
    public function send($openid, $template_id, $page, $form_id,$data=[])
    {
        Log::info($data);
        $this->app->template_message->send([
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' =>$data,
        ]);
    }

}