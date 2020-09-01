<?php
namespace App\Http\Controllers\MT\Root\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\Administrator;

use Response, Auth, Validator, DB, Exception;


class AuthController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new AuthRepository;
    }

    // 登陆
    public function login()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.root.auth.login');
        }
        else if(request()->isMethod('post'))
        {
//            $where['password'] = request()->get('password');
//            $where['email'] = request()->get('email');
//            $where['mobile'] = request()->get('mobile');
//            $admin = Administrator::where($where)->first();

            // 邮箱验证
//            $email = request()->get('email');
//            $admin = Administrator::whereEmail($email)->first();

            // 手机验证
            $username = request()->get('username');
            $user = User::where('username',$username)->first();

            if($user)
            {
//                if($admin->active == 1)
//                {
                    $password = request()->get('password');
                    if(password_check($password,$user->password))
                    {
                        if($user->usergroup == "Manage")
                        {
                            Auth::guard('admin')->login($user,true);
                        }
                        else if(in_array($user->usergroup, ["Agent","Agent2"]))
                        {
                            Auth::guard('agent')->login($user,true);
                        }
                        else if($user->usergroup == "Service")
                        {
                            Auth::guard('client')->login($user,true);
                        }
                        return response_success(['usergroup'=>$user->usergroup]);
                    }
                    else return response_error([],'账户or密码不正确');
//                }
//                else return response_error([],'账户尚未激活，请先激活账户。');
            }
            else return response_error([],'账户不存在');
        }
    }

    // 退出
    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect('/login');
    }






}
