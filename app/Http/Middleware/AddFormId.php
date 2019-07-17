<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\FormId;
class AddFormId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (empty($user)) {
            return $next($request);
        }
        $openid = null;
        if ($user->wechat && $user->wechat->openid) {
            $openid = $user->wechat->openid;
        }
        if ($user && $request->input('formId')) {
            $form_ids = $request->formId;
            if (!is_array($form_ids)) {
                $form_ids = explode(',', $form_ids);
            }
            $user_id = $user->id;
            foreach ($form_ids as $form_id) {
                if ($form_id == 'the formId is a mock one') {
                    continue;
                }
                if (empty($form_id)) {
                    continue;
                }
                FormId::create([
                    'user_id'=>$user_id,
                    'openid'=>$openid,
                    'form_id'=>$form_id,
                ]);
            }
        }
        return $next($request);
    }
}
