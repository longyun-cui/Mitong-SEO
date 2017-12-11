<?php
namespace App\Repositories\Admin;

use App\Models\Softorg;
use App\Administrator;
use App\Models\Verification;
use App\Repositories\Common\CommonRepository;
use Response, Auth, Validator, DB, Excepiton;
use QrCode;

class AuthRepository {

    private $model;
    public function __construct()
    {
    }

    // 注册
    public function register_org($post_data)
    {
        $messages = [
            'captcha.required' => '请输入验证码',
            'captcha.captcha' => '验证码有误',
            'website_name.unique' => '企业域名已经存在，请更换一个名字',
            'website_name.alpha' => '企业域名必须是英文字符',
            'email.unique' => '管理员邮箱已存在，请更换邮箱',
        ];
        $v = Validator::make($post_data, [
            'captcha' => 'required|captcha',
            'website_name' => 'required|alpha|unique:softorg',
            'email' => 'required|email|unique:administrator',
            'password' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        //return response_success([],'YANZ');

        $website_name = $post_data['website_name'];
        $email = $post_data['email'];
        $password = $post_data['password'];
        $password_confirm = $post_data['password_confirm'];
        if($password == $password_confirm)
        {
            $org = new Softorg;
            $org_create['website_name'] = $website_name;
            $bool1 = $org->fill($org_create)->save();
            if($bool1)
            {
                $admin = new Administrator;
                $admin_create['org_id'] = $org->id;
                $admin_create['email'] = $email;
                $admin_create['password'] = password_encode($password);
                $bool2 = $admin->fill($admin_create)->save();
                if($bool2)
                {
                    $string = 'org_id='.$org->id.'&admin_id='.$admin->id.'&time='.time();
                    $code = hash("sha512", $string);

                    $verification_create['org_id'] = $org->id;
                    $verification_create['admin_id'] = $admin->id;
                    $verification_create['email'] = $email;
                    $verification_create['code'] = $code;

                    $verification = new Verification;
                    $bool3 = $verification->fill($verification_create)->save();
                    if($bool3)
                    {
                        $send = new MailRepository();
                        $post_data['type'] = 1;
                        $post_data['admin_id'] = encode($admin->id);
                        $post_data['code'] = $code;
                        $post_data['target'] = $email;
                        $flag = $send->send_admin_activation_email($post_data);
                        if(count($flag) < 1) return response_success([],'注册成功,请前往邮箱激活管理员');
                        else
                        {
                            $flag = $send->send_admin_activation_email($post_data);
                            if(count($flag) < 1) return response_success([],'注册成功,请前往邮箱激活管理员');
                            else response_fail([],'邮件发送失败！');
                        }
                    }
                }
                else response_fail([],'注册失败！');
            }
            else return response_fail([],'注册失败！');
        }
        else return response_error([],'密码不一致！');
    }

    // 管理员激活
    public function activation($post_data)
    {
        $admin_id = decode($post_data['admin']);
        $where['admin_id'] = $admin_id;
        $where['type'] = $post_data['type'];
        $where['code'] = $post_data['code'];
        $verification = Verification::where($where)->orderBy('id','desc')->first();
        if($verification)
        {
            if($verification->active == 0)
            {
                $admin = Administrator::where('id',$admin_id)->first();
                if($admin)
                {
                    $admin->active = 1;
                    $bool1 = $admin->save();
                    if($bool1)
                    {
                        $verification->active = 1;
                        $bool2 = $verification->save();
                        header("Refresh:5;url=/admin");
                        if($bool2) echo('验证成功，5秒后跳转后台页面！');
                        else echo('验证成功2，5秒后跳转后台页面！');
                    }
                    else dd('验证失败');
                }
            }
            else
            {
                header("Refresh:5;url=/admin");
                echo('已经验证过了，5秒后跳转后台页面！');
            }
        }
        else dd('参数有误');
    }


}