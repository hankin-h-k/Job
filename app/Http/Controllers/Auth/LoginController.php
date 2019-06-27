<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use EasyWechat;
use App\Models\User;
use App\Models\Wechat;
use WechatService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    /**
     * 微信登录接口
     * 创建微信账号信息
     */
    public function loginWechat(Request $request)
    {   
        $failure = null;
        $user = null;
        $token = null; 
        $session =  $this->getWechatSession($request->code);
        if($session){
            if(!isset($session['session_key'])){
                return $this->failure('failure', $session);
            }
            session(['session_key'=>$session['session_key']]);
            $openid = $session['openid'];
            $wechat =  Wechat::where('openid', $openid)->first();
            if (empty($wechat)) {
                return $this->success('login_success', compact('user', 'token'));
            }

            if($wechat->user_id){
                $user = User::findOrFail($wechat->user_id);
                if ($user) {
                    $token = $user->createToken($user->mobile)->accessToken; 
                }
            }
            return $this->success('login_success', compact('user', 'token'));
        }else{
            return $this->failure('wechat login failed');
        }
    }

    public function getWechatSession($code)
    {
        $session = null;
        $mp = EasyWechat::miniProgram();

        try{
            if(config('app.debug') && $code == 'the code is a mock one'){
                //simulate
                $session = [
                    'openid' => 'oyBj70MRExrrzYH7K8F_VE75XeoE',
                    'session_key' => 'oyBj70MRExrrzYH7K8F_VE75XeoE',
                    'unionid' => 'oVMWoswKQA2ToVHyLzcc6t19N4zE',
                ];
            }else{
                $session = $mp->auth->session($code);
            }
        }catch(\Exeception $e){
            $failure = $e->getMessage;
        }
        return $session;
    
    }

    /*
     * 微信资料更新
     */
    public function wechatRegister(Request $request)
    {   
        $mobile = $request->input('mobile');
        if (empty($mobile)) {
            return $this->failure('请输入手机号');
        }
        $user = User::where('mobile', $request->mobile)->first();
        if (empty($user)) {
            $user = new User();
            $user->mobile = $mobile;
            $user->email = $mobile.'@test.com';
            $user->name = '';
            $user->save();
        }
        $code = $request->input('code');
        if (empty($code)) {
            return $this->failure('信息无效，请重启小程序');
        }
        $session = $this->getWechatSession($code);
        if ($session && isset($session['openid'])) {
            $wechat = Wechat::where('openid', $session['openid'])->first();
            if (empty($wechat)) {
                $wechat = new Wechat;
                $wechat->openid = $session['openid'];
            }
            $wechat->user_id = $user->id;
            $wechat->save();
            $session_key = $session['session_key'];
            // $result = $this->wechatUpdate($request, $session_key, $session['openid']);
            $user = $this->guard()->loginUsingId($user->id, true);
            $token = $user->createToken($user->mobile)->accessToken; 
            return $this->success('register user info', compact('user', 'token'));
        }else{
            return $this->failure($session);
        }
    }

    /*
     * 微信资料更新
     */
    public function wechatUpdate($request,$session_key, $openid)
    {   
        if (!$request->iv || !$request->encryptedData) {
            return ;
        }
        $mp = WechatService::app();
        $user_info = $mp->encryptor->decryptData($session_key, $request->iv, $request->encryptedData);
        $wechat = Wechat::where('openid', $openid)->firstOrFail();
        // return false;
        $wechat->nickname = $user_info['nickName'];
        $wechat->sex = $user_info['gender'];
        // $wechat->city = $user_info['city'];
        // $wechat->province = $user_info['province'];
        // $wechat->country = isset($user_info['country'])?$user_info['country']:'中国';
        if (empty($user_info['avatarUrl'])) {
            if ($wechat->gender == 1) {
                $avatar = 'http://images.ufutx.com/201811/12/0e8b72aae6fa640d9e73ed312edeebf3.png';
            }else{
                $avatar = 'http://images.ufutx.com/201811/12/dddd79aa2c2fc6a6f35e641f6b8fb8f5.png';
            }   
        }else{
            $avatar = $user_info['avatarUrl']?$user_info['avatarUrl']:null;
        }
        $wechat->avatar = $avatar;
        // $wechat->unionid = $user_info['unionId'];
        $wechat->save();

        return;
    }

    /**
     * 获取微信手机号
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getPhone(Request $request){
        $mp = EasyWechat::miniProgram();
        $session = $this->getWechatSession($request->code);
        if(!isset($session['session_key'])){
            return $this->failure('failure', $session);
        }
        $session_key = $session['session_key'];
        $raw_data = $mp->encryptor->decryptData($session_key, $request->iv, $request->encryptedData);
        return $this->success('ok',$raw_data);
    }
}
