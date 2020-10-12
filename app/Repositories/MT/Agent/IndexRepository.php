<?php
namespace App\Repositories\MT\Agent;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\SEOKeywordDetectRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOKeyword;

use App\Repositories\Common\CommonRepository;
use Response, Auth, Validator, DB, Exception;
use QrCode;

class IndexRepository {

    private $model;
    private $repo;
    public function __construct()
    {
        // $this->model = new User;
    }

    // 返回（后台）主页视图
    public function view_agent_index()
    {
        $me = Auth::guard("agent")->user();
        $agent = User::select('*')
            ->with([
                'clients'=>function($query) { $query->where('usergroup','Service'); }
            ])
            ->withCount([
                'clients'=>function($query) { $query->where('usergroup','Service'); },
                'children_keywords'
            ])
            ->find($me->id);
//        dd($agent->toArray());

        $agent->fund_total = number_format($agent->fund_total);
        $agent->fund_balance = number_format($agent->fund_balance);


        // 客户ID
        $client_ids = User::select('id') ->where(['pid'=>$me->id,'usergroup'=>'Service'])->get()->pluck('id')->toArray();
//        dd($client_ids);

        // 今日优化关键词
        $keywords_count = SEOKeyword::where(['status'=>1,'keywordstatus'=>'优化中'])->whereIn("createuserid",$client_ids)->count();
        $agent->keywords_count = $keywords_count;

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->whereIn("createuserid",$client_ids)
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            );
//        dd($keyword_standard_data);
        $agent->keyword_standard_count = $keyword_standard_data->keyword_standard_count;
        $agent->keyword_standard_cost_sum = $keyword_standard_data->keyword_standard_cost_sum;

        $agent_data = $agent;
        return view('mt.agent.index')->with(['agent_data'=>$agent_data]);
    }




    /*
     * 用户基本信息
     */

    // 返回【基本信息】视图
    public function view_info_index()
    {
        $me = Auth::guard('agent')->user();
        return view('mt.agent.entrance.info.index')->with(['data'=>$me]);
    }

    // 返回【编辑基本信息】视图
    public function view_info_edit()
    {
        $me = Auth::guard('agent')->user();
        return view('mt.agent.entrance.info.edit')->with(['data'=>$me]);
    }
    // 保存【基本信息】
    public function operate_info_save($post_data)
    {
        $me = Auth::guard('agent')->user();

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            $bool = $me->fill($mine_data)->save();
            if($bool)
            {
                // 头像
                if(!empty($post_data["portrait_img"]))
                {
                    // 删除原文件
                    $mine_original_file = $me->portrait_img;
                    if(!empty($mine_original_file) && file_exists(storage_path("resource/" . $mine_original_file)))
                    {
                        unlink(storage_path("resource/" . $mine_original_file));
                    }

                    $result = upload_file_storage($post_data["attachment"]);
                    if($result["result"])
                    {
                        $me->portrait_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload-portrait-img-file-fail");
                }

            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 返回【修改密码】视图
    public function view_info_password_reset()
    {
        $me = Auth::guard('agent')->user();
        return view('mt.agent.entrance.info.password-reset')->with(['data'=>$me]);
    }
    // 保存【密码】
    public function operate_info_password_reset_save($post_data)
    {
        $messages = [
            'password_pre.required' => '请输入旧密码',
            'password_new.required' => '请输入新密码',
            'password_confirm.required' => '请输入确认密码',
        ];
        $v = Validator::make($post_data, [
            'password_pre' => 'required',
            'password_new' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $password_pre = request()->get('password_pre');
        $password_new = request()->get('password_new');
        $password_confirm = request()->get('password_confirm');

        if($password_new == $password_confirm)
        {
            $me = Auth::guard('agent')->user();
            if(password_check($password_pre,$me->password))
            {
                $me->password = password_encode($password_new);
                $bool = $me->save();
                if($bool) return response_success([], '密码修改成功！');
                else return response_fail([], '密码修改失败！');
            }
            else
            {
                return response_fail([], '原密码有误！');
            }
        }
        else return response_error([],'两次密码输入不一致！');
    }




    /*
     * 用户系统
     */
    // 【修改密码】
    public function operate_user_change_password($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户ID',
            'user-password.required' => '请输入密码',
            'user-password-confirm.required' => '请输入确认密码',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
            'user-password' => 'required',
            'user-password-confirm' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'change-password') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限!");

        $password = $post_data["user-password"];
        $confirm = $post_data["user-password-confirm"];
        if($password != $confirm) return response_error([],"两次密码不一致！");

//        if(!password_is_legal($password)) ;
        $pattern = '/^[a-zA-Z0-9]{1}[a-zA-Z0-9]{5,19}$/i';
        if(!preg_match($pattern,$password)) return response_error([],"密码格式不正确！");


        $user = User::find($id);
        if(!$user) return response_error([],"该用户不存在，刷新页面重试!");
        if(!in_array($user->usergroup,['Agent','Agent2','Service'])) return response_error([],"该用户参数有误，你不能操作！");
        if($user->pid != $me->id) return response_error([],"该用户不是你的客户或子代理，你不能操作！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $user->password_1 = $password;
            $user->password = password_encode($password);
            $user->userpass = basic_encrypt($password);
            $user->save();

            $bool = $user->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success(['id'=>$user->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }


    // 返回【子代理商列表】数据
    public function get_user_sub_agent_list_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();

        $query = User::select('*')
//        $query = User::select('id','pid','epid','username','usergroup','createtime')
//            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
            ->with('ep','parent','fund')
            ->withCount(['clients'])
            ->where('userstatus','正常')->where('status',1)->where('pid',$me->id)->whereIn('usergroup',['Agent2']);

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 返回【客户列表】数据
    public function get_user_client_list_datatable($post_data)
    {
        $agent_id = Auth::guard("agent")->user()->id;
        $query = User::select('*')
//        $query = User::select('id','pid','epid','username','usergroup','createtime')
            ->with('parent','ep','fund')
            ->withCount([
                'sites'=>function ($query) { $query->where('status',1)->whereIn('sitestatus',['优化中','待审核']); },
                'keywords'=>function ($query) { $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']); }
            ])
            ->where('userstatus','正常')->where('status',1)->where('pid',$agent_id)->whereIn('usergroup',['Service'])
            ->orderby("id","desc");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




    // 返回【客户详情】视图
    public function view_user_client($post_data)
    {
        $me = Auth::guard("agent")->user();

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $user = User::find($id);
        if($user)
        {
            if($user->usergroup != 'Service') return response_error([],"该用户不存在，刷新页面试试！");
        }

        if($user->pid != $me->id) return response_error([],"该用户不是你的客户！");

        $user_data = $user;


        /*
         * 关键词
         */
        // 今日优化关键词
        $keyword_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])->where('createuserid',$id)->count();
        $user_data->keyword_count = $keyword_count;

        // 今日检测关键词
        $keyword_detect_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$id)
            ->count();
        $user_data->keyword_detect_count = $keyword_detect_count;

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$id)
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            );
        $user_data->keyword_standard_count = $keyword_standard_data->keyword_standard_count;
        $user_data->keyword_standard_cost_sum = $keyword_standard_data->keyword_standard_cost_sum;


        return view('mt.agent.entrance.user.client')
            ->with([
                'user_data'=>$user_data
            ]);
    }

    // 返回【客户-关键词】列表
    public function get_user_client_keyword_list_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();
        $id = $post_data["id"];
        $query = SEOKeyword::select('*')->with('creator')->where('createuserid',$id);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
//        if(!empty($post_data['keywordstatus'])) $query->where('keywordstatus', $post_data['keywordstatus'])->where('status', 1);
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "默认")
            {
                $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']);
            }
            else if($post_data['keywordstatus'] == "全部")
            {
            }
            else if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }
        else
        {
            $query->where(['status'=>1,'keywordstatus'=>['优化中','待审核']]);
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




    // 返回【添加二级代理商】视图
    public function view_user_sub_agent_create()
    {
        $mine = Auth::guard('agent')->user();
        $view_blade = 'mt.agent.entrance.user.sub-agent-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }

    // 返回【编辑二级代理商】视图
    public function view_user_sub_agent_edit()
    {
        $mine = Auth::guard('agent')->user();
        $id = request("id",0);
        $view_blade = 'mt.agent.entrance.user.sub-agent-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $sub_agent = User::with(['parent'])->find($id);
            if($sub_agent)
            {
                if(!in_array($sub_agent->usergroup,['Agent2'])) return response("该用户不是二级代理商！", 404);
                if($sub_agent->pid != $mine->id) return response("该客户不是你的二级代理，你无权操作！！", 404);
                $sub_agent->custom = json_decode($sub_agent->custom);
                $sub_agent->custom2 = json_decode($sub_agent->custom2);
                $sub_agent->custom3 = json_decode($sub_agent->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$sub_agent]);
            }
            else return response("该用户不存在！", 404);
        }
    }

    // 保存【二级代理商】
    public function operate_user_sub_agent_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'username.required' => '请输入用户名',
            'mobileno.required' => '请输入电话',
            'epname.required' => '请输入企业全称',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'username' => 'required',
            'mobileno' => 'required',
            'epname' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $mine = Auth::guard('agent')->user();
        if($mine->usergroup != "Agent") return response_error([],"你没有操作权限");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $sub_agent = new User;
            $current_time = date('Y-m-d H:i:s');
            $post_data["usergroup"] = "Agent2";
            $post_data["pid"] = $mine->id;
            $post_data["createuserid"] = $mine->id;
            $post_data["createtime"] = $current_time;
            $post_data["userstatus"] = "正常";
            $post_data["status"] = 1;
        }
        else if($operate == 'edit') // 编辑
        {
            $sub_agent = User::find($operate_id);
            if(!$sub_agent) return response_error([],"该用户不存在，刷新页面重试");
        }
        else return response_error([],"参数有误");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $sub_agent_data = $post_data;
            unset($sub_agent_data['operate']);
            unset($sub_agent_data['operate_id']);
            $bool = $sub_agent->fill($sub_agent_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $sub_agent_cover_pic = $sub_agent->cover_pic;
                    if(!empty($sub_agent_cover_pic) && file_exists(storage_path("resource/" . $sub_agent_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $sub_agent_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $sub_agent->cover_pic = $result["local"];
                        $sub_agent->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

            }
            else throw new Exception("insert--user--fail");

            DB::commit();
            return response_success(['id'=>$mine->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
//            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }




    // 返回【添加客户】视图
    public function view_user_client_create()
    {
        $mine = Auth::guard('agent')->user();
        $view_blade = 'mt.agent.entrance.user.client-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }

    // 返回【编辑客户】视图
    public function view_user_client_edit()
    {
        $mine = Auth::guard('agent')->user();
        $id = request("id",0);
        $view_blade = 'mt.agent.entrance.user.client-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $client = User::with(['parent'])->find($id);
            if($client)
            {
                if(!in_array($client->usergroup,['Service'])) return response("该用户不是客户！", 404);
                if($client->pid != $mine->id) return response("该客户不是你的客户，你无权操作！！", 404);
                $client->custom = json_decode($client->custom);
                $client->custom2 = json_decode($client->custom2);
                $client->custom3 = json_decode($client->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$client]);
            }
            else return response("该用户不存在！", 404);
        }
    }

    // 保存【客户】
    public function operate_user_client_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'username.required' => '请输入用户名',
            'mobileno.required' => '请输入电话',
            'epname.required' => '请输入企业全称',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'username' => 'required',
            'mobileno' => 'required',
            'epname' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $mine = Auth::guard('agent')->user();
        if($mine->usergroup != "Agent") return response_error([],"你没有操作权限");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $client = new User;
            $current_time = date('Y-m-d H:i:s');
            $post_data["usergroup"] = "Service";
            $post_data["pid"] = $mine->id;
            $post_data["createuserid"] = $mine->id;
            $post_data["createtime"] = $current_time;
            $post_data["userstatus"] = "正常";
            $post_data["status"] = 1;
        }
        else if($operate == 'edit') // 编辑
        {
            $client = User::find($operate_id);
            if(!$client) return response_error([],"该用户不存在，刷新页面重试");
        }
        else return response_error([],"参数有误");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $client_data = $post_data;
            unset($client_data['operate']);
            unset($client_data['operate_id']);
            $bool = $client->fill($client_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $client_cover_pic = $client->cover_pic;
                    if(!empty($mine_cover_pic) && file_exists(storage_path("resource/" . $client_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $client_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $client->cover_pic = $result["local"];
                        $client->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

            }
            else throw new Exception("insert--user--fail");

            DB::commit();
            return response_success(['id'=>$client->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }




    // 【二级代理商】充值
    public function operate_user_sub_agent_recharge($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
            'recharge-amount.required' => '请输入金额',
            'recharge-amount.numeric' => '金额必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
            'recharge-amount' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'recharge') return response_error([],"参数有误");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试");

        $mine = Auth::guard('agent')->user();
        if($mine->usergroup != "Agent") return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $amount = $post_data['recharge-amount'];
        // 充值金额不能为0
        if($amount == 0) return response_error([],"充值金额不能为0！");
        // 充值金额应该大于资金余额
        if($amount > 0)
        {
            if(($mine->fund_balance - $amount) < 0) return response_error([],"您的余额不足！");
        }

        // 充值限制
        if($mine->is_recharge_limit == 1)
        {
            if($amount < 5000) return response_error([],"充值金额需要大于5000！");
        }

        $sub_agent = User::find($id);
        if(!$sub_agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($sub_agent->usergroup,['Agent2'])) return response_error([],"该用户不是1级代理商，你不能操作！");
        if($sub_agent->pid != $mine->id) return response_error([],"该客户不是你的二级代理商，你无权充值/退款操作！");

        // 退款金额应该小于资金余额
        if($amount < 0)
        {
            if(($sub_agent->fund_balance + $amount) < 0) return response_error([],"退款金额不能超过该账户余额");
        }

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $FundRechargeRecord = New FundRechargeRecord;
            $FundRechargeRecord_data['owner_id'] = $mine->id;
            $FundRechargeRecord_data['createuserid'] = $mine->id;
            $FundRechargeRecord_data['createusername'] = $mine->username;
            $FundRechargeRecord_data['createtime'] = $time;
            $FundRechargeRecord_data['userid'] = $id;
            $FundRechargeRecord_data['usertype'] = 'sub';
            $FundRechargeRecord_data['status'] = 1;
            $FundRechargeRecord_data['amount'] = $amount;

            $bool = $FundRechargeRecord->fill($FundRechargeRecord_data)->save();
            if($bool)
            {
                $sub_agent->increment('fund_total',$amount);
                $sub_agent->increment('fund_balance',$amount);
                $mine->decrement('fund_total',$amount);
                $mine->decrement('fund_balance',$amount);
            }
            else throw new Exception("insert--fund-record--fail");

            DB::commit();
            return response_success(['id'=>$sub_agent->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }

    // 【客户】充值
    public function operate_user_client_recharge($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
            'recharge-amount.required' => '请输入金额',
            'recharge-amount.numeric' => '金额必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
            'recharge-amount' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'recharge') return response_error([],"参数有误");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试");


        $mine = Auth::guard('agent')->user();
        if(!in_array($mine->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $amount = $post_data['recharge-amount'];
        // 充值金额不能为0
        if($amount == 0) return response_error([],"充值金额不能为0！");
        // 充值金额应该大于资金余额
        if($amount > 0)
        {
            if(($mine->fund_balance - $amount) < 0) return response_error([],"您的余额不足");
        }

        // 充值限制
        if($mine->is_recharge_limit == 1)
        {
            if($amount < 5000) return response_error([],"充值金额需要大于5000！");
        }

        $client = User::find($id);
        if(!$client) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($client->usergroup,['Service'])) return response_error([],"该用户不是客户，你不能操作");
        if($client->pid != $mine->id) return response_error([],"该客户不是你的客户，你无权充值/退款操作！");

        // 退款金额应该小于资金余额
        if($amount < 0)
        {
            if(($client->fund_balance + $amount) < 0) return response_error([],"退款金额不能超过该账户余额");
        }

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $FundRechargeRecord = New FundRechargeRecord;
            $FundRechargeRecord_data['owner_id'] = $mine->id;
            $FundRechargeRecord_data['createuserid'] = $mine->id;
            $FundRechargeRecord_data['createusername'] = $mine->username;
            $FundRechargeRecord_data['createtime'] = $time;
            $FundRechargeRecord_data['userid'] = $id;
            $FundRechargeRecord_data['usertype'] = 'client';
            $FundRechargeRecord_data['status'] = 1;
            $FundRechargeRecord_data['amount'] = $amount;

            $bool = $FundRechargeRecord->fill($FundRechargeRecord_data)->save();
            if($bool)
            {
                $client->increment('fund_total',$amount);
                $client->increment('fund_balance',$amount);
                $client->increment('fund_available',$amount);
                $mine->decrement('fund_total',$amount);
                $mine->decrement('fund_balance',$amount);
                $mine->decrement('fund_available',$amount);
            }
            else throw new Exception("insert--fund-record--fail");

            DB::commit();
            return response_success(['id'=>$client->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }




    // 删除【代理商】
    public function operate_user_sub_agent_delete($post_data)
    {
        $mine = Auth::guard('agent')->user();
        if($mine->usergroup != "Agent") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $sub_agent = User::find($id);
        if($sub_agent)
        {
            if(!in_array($sub_agent->usergroup,['Agent2'])) return response_error([],"该用户不是二级代理商！");
            if($sub_agent->pid != $mine->id) return response_error([],"该客户不是你的二级代理商，你无权删除！");
            if($sub_agent->fund_balance > 0) return response_error([],"该用户还有余额！");
        }
        else return response_error([],'账户不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $mine->content;
            $cover_pic = $mine->cover_pic;

            // 删除名下客户
            $clients = User::where(['pid'=>$id,'usergroup'=>'Service'])->get();
            foreach ($clients as $c)
            {
                $client_id =  $c->id;
                $client  = User::find($client_id);

                // 删除【站点】
                $deletedRows_1 = SEOSite::where('owner_id', $client_id)->delete();

                // 删除【关键词】
                $deletedRows_2 = SEOKeyword::where('owner_id', $client_id)->delete();

                // 删除【关键词检测记录】
                $deletedRows_3 = SEOKeywordDetectRecord::where('owner_id', $client_id)->delete();

                // 删除【扣费记录】
                $deletedRows_4 = ExpenseRecord::where('owner_id', $client_id)->delete();

                // 删除【用户】
//                $mine->pivot_menus()->detach(); // 删除相关目录
                $bool = $client->delete();
                if(!$bool) throw new Exception("delete--user--fail");
            }

            // 删除【用户】
//            $mine->pivot_menus()->detach(); // 删除相关目录
            $bool = $sub_agent->delete();
            if(!$bool) throw new Exception("delete--user--fail");

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
//            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 删除【客户】
    public function operate_user_client_delete($post_data)
    {
        $mine = Auth::guard('agent')->user();
        if(!in_array($mine->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $client = User::find($id);
        if($client)
        {
            if(!in_array($client->usergroup,['Service'])) return response_error([],"该用户不是客户！");
            if($client->pid != $mine->id) return response_error([],"该客户不是你的客户，你无权删除！");
            if($client->fund_balance > 0) return response_error([],"该用户还有余额");
        }
        else return response_error([],'账户不存在，刷新页面试试');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $client->content;
            $cover_pic = $client->cover_pic;


            // 删除【站点】
            $deletedRows_1 = SEOSite::where('createuserid', $id)->delete();

            // 删除【关键词】
            $deletedRows_2 = SEOKeyword::where('createuserid', $id)->delete();

            // 删除【关键词检测记录】
            $deletedRows_3 = SEOKeywordDetectRecord::where('ownuserid', $id)->delete();

            // 删除【扣费记录】
            $deletedRows_4 = ExpenseRecord::where('ownuserid', $id)->delete();

            // 删除【用户】
//            $client->pivot_menus()->detach(); // 删除相关目录
            $bool = $client->delete();
            if(!$bool) throw new Exception("delete--user--fail");

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
//            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }




    /*
     * 财务系统
     */
    // 返回【充值记录】数据
    public function get_finance_recharge_record_datatable($post_data)
    {
        $agent_id = Auth::guard("agent")->user()->id;
        $query = FundRechargeRecord::select('id','userid','puserid','createuserid','amount','createtime')
            ->with('user','parent','creator')
            ->where('userid',$agent_id)
            ->orderby("id","desc");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 返回【支出记录】数据
    public function get_finance_expense_record_datatable($post_data)
    {
        $agent_id = Auth::guard("agent")->user()->id;
        $query = FundRechargeRecord::select('id','userid','puserid','createuserid','amount','createtime')
            ->with('user','parent','creator')
            ->where('createuserid',$agent_id)
            ->orderby("id","desc");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $org_id = Auth::guard("admin")->user()->org_id;
        $query = Item::select("*")->where('org_id',$org_id)->with(['admin','menu']);
        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 返回添加视图
    public function view_create()
    {
        $admin = Auth::guard('admin')->user();
        $org_id = $admin->org_id;
        $org = Softorg::with(['menus'=>function ($query1) {$query1->orderBy('order','asc');}])->find($org_id);
        return view('admin.activity.edit')->with(['org'=>$org]);
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id) return response("参数有误", 404);

        if($decode_id == 0)
        {
            $org = Softorg::with(['menus'=>function ($query1) {$query1->orderBy('order','asc');}])->find($decode_id);
            return view('admin.activity.edit')->with(['operate'=>'create', 'encode_id'=>$id, 'org'=>$org]);
        }
        else
        {
            $activity = Activity::with([
                'menu',
                'org' => function ($query) { $query->with([
                    'menus'=>function ($query1) {$query1->orderBy('order','asc');}
                ]); },
            ])->find($decode_id);
            if($activity)
            {
                unset($activity->id);
                return view('admin.activity.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$activity]);
            }
            else return response("活动不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'name.required' => '请输入名称',
            'title.required' => '请输入标题',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'name' => 'required',
            'title' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $start = $post_data["start"];
        $end = $post_data["end"];
        if(!empty($start) && !empty($end))
        {
            $start_time = strtotime($post_data["start"]);
            $end_time = strtotime($post_data["end"]);
            if($start_time >= $end_time)
            {
                return response_error([],"时间有误，开始时间大于结束时间！");
            }
            else
            {
                $post_data["start_time"] = $start_time;
                $post_data["end_time"] = $end_time;
            }
        }
        else return response_error([],"时间有误！");

//        是否报名功能
        $is_apply = $post_data["is_apply"];
        if($is_apply == 1)
        {
            $apply_start = $post_data["apply_start"];
            $apply_end = $post_data["apply_end"];
            if(!empty($apply_start) && !empty($apply_end))
            {
                $apply_start_time = strtotime($post_data["apply_start"]);
                $apply_end_time = strtotime($post_data["apply_end"]);
                if($apply_start_time >= $apply_end_time)
                {
                    return response_error([],"报名时间有误，开始时间大于结束时间！");
                }
                else
                {
                    $post_data["apply_start_time"] = $apply_start_time;
                    $post_data["apply_end_time"] = $apply_end_time;
                }
            }
            else return response_error([],"报名时间有误！");
        }

//        是否签到功能
        $is_sign = $post_data["is_sign"];
        if($is_sign == 1)
        {
            $sign_start = $post_data["sign_start"];
            $sign_end = $post_data["sign_end"];
            if(!empty($sign_start) && !empty($sign_end))
            {
                $sign_start_time = strtotime($post_data["sign_start"]);
                $sign_end_time = strtotime($post_data["sign_end"]);
                if($sign_start_time >= $sign_end_time)
                {
                    return response_error([],"签到时间有误，开始时间大于结束时间！");
                }
                else
                {
                    $post_data["sign_start_time"] = $sign_start_time;
                    $post_data["sign_end_time"] = $sign_end_time;
                }
            }
            else return response_error([],"签到时间有误！");
        }
        else unset($post_data["sign_type"]);


        $admin = Auth::guard('admin')->user();

        $id = decode($post_data["id"]);
        $operate = decode($post_data["operate"]);
        if(intval($id) !== 0 && !$id) return response_error();

        DB::beginTransaction();
        try
        {
            if($id == 0) // $id==0，添加一个新的活动
            {
                $activity = new Activity;
                $post_data["admin_id"] = $admin->id;
                $post_data["org_id"] = $admin->org_id;
            }
            else // 修改活动
            {
                $activity = Activity::find($id);
                if(!$activity) return response_error([],"该活动不存在，刷新页面重试");
                if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");
            }

            $bool = $activity->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($activity->id);
                // 目标URL
                $url = 'http://www.softorg.cn/activity?id='.$encode_id;
                // 保存位置
                $qrcode_path = 'resource/org/'.$admin->id.'/unique/activities';
                if(!file_exists(storage_path($qrcode_path)))
                    mkdir(storage_path($qrcode_path), 0777, true);
                // qrcode图片文件
                $qrcode = $qrcode_path.'/qrcode_activity_'.$encode_id.'.png';
                QrCode::errorCorrection('H')->format('png')->size(160)->margin(0)->encoding('UTF-8')->generate($url,storage_path($qrcode));


                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'org-'. $admin->id.'-unique-activities' , 'cover_activity_'.$encode_id);
                    if($result["status"])
                    {
                        $activity->cover_pic = $result["data"];
                        $activity->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

                $softorg = Softorg::find($admin->org_id);
                $create = new CommonRepository();
                $org_name = $softorg->name;
                $logo_path = '/resource/'.$softorg->logo;
                $title = $activity->title;
                $name = $qrcode_path.'/qrcode__activity_'.$encode_id.'.png';
                $create->create_qrcode_image($org_name, '活动', $title, $qrcode, $logo_path, $name);
            }
            else throw new Exception("insert-activity-fail");

            $item = Item::where(['org_id'=>$admin->org_id,'sort'=>3,'itemable_id'=>$activity->id])->first();
            if($item)
            {
                $item->menu_id = $post_data["menu_id"];
                $item->updated_at = time();
                $bool1 = $item->save();
                if(!$bool1) throw new Exception("update-item-fail");
            }
            else
            {
                $item = new Item;
                $item_data["sort"] = 3;
                $item_data["org_id"] = $admin->org_id;
                $item_data["admin_id"] = $admin->id;
                $item_data["menu_id"] = $post_data["menu_id"];
                $item_data["itemable_id"] = $activity->id;
                $item_data["itemable_type"] = 'App\Models\Activity';
                $bool1 = $item->fill($item_data)->save();
                if($bool1)
                {
                    $activity->item_id = $item->id;
                    $bool2 = $activity->save();
                    if(!$bool2) throw new Exception("update-activity-item_id-fail");
                }
                else throw new Exception("insert-item-fail");
            }

            DB::commit();
            return response_success(['id'=>$encode_id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],'操作失败，请重试！');
        }
    }

    // 删除
    public function delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $activity->delete();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->delete();
                    if(!$bool1) throw new Exception("delete-item--fail");
                }
            }
            else throw new Exception("delete-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }

    // 启用
    public function enable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $activity->fill($update)->save();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->fill($update)->save();
                    if(!$bool1) throw new Exception("update-item--fail");
                }
            }
            else throw new Exception("update-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }

    // 禁用
    public function disable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $activity->fill($update)->save();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->fill($update)->save();
                    if(!$bool1) throw new Exception("update-item--fail");
                }
            }
            else throw new Exception("update-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }


}