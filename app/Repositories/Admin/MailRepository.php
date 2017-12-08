<?php
namespace App\Repositories\Admin;

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

use Response, Auth, Validator, DB, Excepiton;

class MailRepository {

    public function __construct()
    {
    }

    // 发送管理员激活邮件
    public function send_admin_activation_email($post_data)
    {
//        Mail::to('longyun-cui@163.com')->send(new OrderShipped());

        $variate['admin_id'] = $post_data['admin_id'];
        $variate['code'] = $post_data['code'];

        // 第一个参数填写模板的路径，第二个参数填写传到模板的变量
        Mail::send('email.admin.activation', $variate, function ($message) use ($post_data) {

            $message->from('admin@softorg.cn', 'Softorg管理员'); // 发件人（你自己的邮箱和名称）
            $message->to($post_data['target']); // 收件人的邮箱地址
            $message->subject('管理员激活'); // 邮件主题
        });
//        dd(count(Mail::failures()));
        return Mail::failures();
    }


}