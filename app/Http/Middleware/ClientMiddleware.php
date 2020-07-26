<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Models\MT\User;

use Auth, Response;

class ClientMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('client')->check()) // 未登录
        {
            return redirect('/client/login');

//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $user = Auth::guard('client')->user();
            $user_id = $user->id;
            $user_data = User::with('ep','parent','fund')
                ->withCount([
                    'sites'=>function ($query) { $query->where('sitestatus','优化中'); },
                    'keywords'=>function ($query) { $query->where('keywordstatus','优化中'); }
                ])
                ->find($user_id);
            view()->share('user_data', $user_data);
        }

        return $next($request);

    }
}
