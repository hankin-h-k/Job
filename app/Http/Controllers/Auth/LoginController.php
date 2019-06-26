<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
            

            /**
             * 邀请人记录
             */
            $from_openid = $request->input('from_openid');
            $marriage = '';
            $from_avatar = '';
            if ($from_openid) {
                // $this->addInviteHistory($openid, $from_openid);
                $other_wechat = Wechat::with('user')->where('openid', $from_openid)->first();
                if ($other_wechat) {
                    //邀请人头像
                    $from_avatar = $other_wechat->avatar2?$other_wechat->avatar2:$other_wechat->avatar;
                    //邀请人姓名
                    $marriage = $other_wechat->nickname;
                    if ($other_wechat->user && $other_wechat->user->name) {
                        $marriage = $other_wechat->user->name;
                    }
                }
            }

            if($wechat->user_id){
                $user = User::withTrashed()->findOrFail($wechat->user_id);
                if ($user) {
                    $user = $user->restore();
                    $registered = true;
                    $user = $this->guard()->loginUsingId($wechat->user_id, true);
                    if (!empty($user)) {
                        $token = $user->createToken($user->mobile)->accessToken; 
                    }
                }
                
            }
            
            if ($user) {
                $avatar = Wechat::where('user_id', $user->id)->value('avatar2');
                $user->avatar = $avatar;
                //动态
                $content = '登录【福恋】成功';
                $data = [
                    'user_id'=>$user->id,
                    'content'=>$content,
                ];
                $this->dynamic->addDynamic($data);
                $this->addAccountDynamic($user->id, $content);
                //未读消息
                $news_count = $this->userCon->newsCount($user->id);
                $user->news_count = $news_count;
            }
            return $this->success('login_success', compact('user', 'token', 'openid', 'marriage', 'from_avatar'));
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
}
