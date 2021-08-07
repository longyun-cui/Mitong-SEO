<?php
namespace App\Repositories\MT\Agent;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;
use App\Models\MT\Item;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode, Excel;

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


        $insufficient_clients = User::where(['userstatus'=>'正常','status'=>1,'usergroup'=>'Service'])
            ->where('pid',$me->id)
            ->where('fund_expense_daily','>',0)
            ->whereRaw("fund_balance < (fund_expense_daily * 7)")
            ->get();


        //
        $recently_notices = Item::select('*')
            ->with(['creator'])
            ->where('category',9)
            ->where( function($query) use($me) {
                $query->where('creator_id',$me->id)->orWhere( function($query1) {
                    $query1->where('active',1)->whereIn('type',[1,9]);
                });
            })
            ->orderBy("updated_at", "desc")->limit(5)->get();


        $agent_data = $agent;
        return view('mt.agent.index')
            ->with([
                'agent_data'=>$agent_data,
                'insufficient_clients'=>$insufficient_clients,
                'recently_notices'=>$recently_notices
            ]);
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
            $post_data["usertype"] = "agent2";
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
        if(!in_array($mine->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $client = new User;
            $current_time = date('Y-m-d H:i:s');
            $post_data["usergroup"] = "Service";
            $post_data["usertype"] = "sub";
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

                $mine->decrement('fund_balance',$amount);
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




    // 【关键词】返回-查询-视图
    public function view_business_keyword_search()
    {
        $me = Auth::guard('agent')->user();
        $view_blade = 'mt.agent.entrance.business.keyword-search';
        return view($view_blade)->with([
            'operate'=>'search',
            'operate_id'=>0,
            'sidebar_business_active'=>'active',
            'sidebar_business_keyword_search_active'=>'active'
        ]);
    }
    // 【关键词】返回-查询-结果
    public function operate_business_keyword_search($post_data)
    {
        $messages = [
            'keywords.required' => '关键词不能为空',
        ];
        $v = Validator::make($post_data, [
            'keywords' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $mine = Auth::guard('agent')->user();
        $mine_id = $mine->id;
        if(!in_array($mine->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");

        $CommonRepository = new CommonRepository();


        $keywords = $post_data['keywords'];

        // 将回车换行替换成逗号
        $keywords = str_replace(array("\r\n", "\r", "\n"), ",", $keywords);

//        //将回车换行替换成逗号
//        $kws = str_replace(",","\r\n", $keywords);
//        $kws = str_replace(",","\r", $keywords);
//        $kws = str_replace(",","\n", $keywords);

        $keyword_array = explode(',' , $keywords );
        // 去重空值
        $keyword_array = array_filter( $keyword_array );
        // 去重操作
        $keyword_array = array_values(array_unique( $keyword_array ));

        $KeywordLengthPriceIndexOptions = config('seo.KeywordLengthPriceIndexOptions');

        $search_engine_keys = array_keys($KeywordLengthPriceIndexOptions);

        //组成字符
        $keywords = implode(',' , $keyword_array);

        //
        foreach ( $keyword_array as $key => $vo ){

            // 去掉关键词前后的空额
            //$vo = strtolower(trim($vo));
            $vo = trim($vo);
            $replace = array(" ","　","\n","\r","\t");
            $vo = str_replace($replace, "", $vo);
            $temp['keyword'] = $vo;
            foreach ( $search_engine_keys as $vo2 )
            {
                $temp[$vo2] = 0;
            }
            $arr[] = $temp;
        }

        $list = $CommonRepository -> combKeywordSearchResults_new( $arr );
        $view_blade = 'mt.admin.entrance.business.keyword-search-result';
        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list])->__toString();
//        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list]);
//        $html = response($html)->getContent();


        $recommend_list = $CommonRepository->get_keyword_recommend($post_data);
        $recommend_html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$recommend_list])->__toString();

        return response_success([
            'list'=>json_encode($list),
            'html'=>$html,
            'recommend_list'=>json_encode($recommend_list),
            'recommend_html'=>$recommend_html
        ]);

    }
    // 【关键词】导出-查询-结果
    public function operate_business_keyword_search_export($post_data)
    {
        $me = Auth::guard('agent')->user();
        $list_decode = json_decode($post_data['list'],true);
        $recommend_list_decode = json_decode($post_data['recommend_list'],true);

        $cellData = array_merge($list_decode,$recommend_list_decode);
        array_unshift($cellData,['关键词','百度PC(元/天)','百度移动(元/天)','搜狗(元/天)','360(元/天)','神马(元/天)','难度指数','难度指数','优化周期']);

//        dd($cellData);

        $title = '【关键词价格查询】 - '.date('YmdHis');
        Excel::create($title,function($excel) use ($cellData){
            $excel->sheet('all', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');

        return false;
    }




    // 【关键词】返回-列表-视图
    public function show_business_keyword_list()
    {
        $me = Auth::guard("agent")->user();

        $data = [];

        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereHas('creator',function($query) use($me) { $query->where('pid',$me->id); });

        // 优化关键词总数
        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        // 检测关键词总数
        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"));
        $keyword_detect_count = $query_1->count("*");
        $data['keyword_detect_count'] = $keyword_detect_count;

        // 已达标关键词总数
        $query_2 = $query->whereDate('standarddate',date("Y-m-d"))->where('standardstatus','已达标');
        $keyword_standard_count = $query_2->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        // 已达标关键词消费
        $keyword_standard_fund_sum = $query_2->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


        if($keyword_count > 0)
        {
            $data['keyword_standard_rate'] = round($data['keyword_standard_count']/$keyword_count*100)."％";
        }
        else $data['keyword_standard_rate'] = "--";


//        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
//        $keyword_standard_fund_sum_1 = $query_detect->count('*');
//        $data['keyword_standard_sum_by_detect'] = $keyword_standard_fund_sum_1;
//
//
//        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
//        $keyword_standard_fund_sum_2 = $query_expense->count('*');
//        $data['keyword_standard_sum_by_expense'] = $keyword_standard_fund_sum_2;

        return view('mt.agent.entrance.business.keyword-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_list_active'=>'active'
            ]);
    }
    // 【关键词】返回-列表-数据
    public function get_business_keyword_list_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();
        $query = SEOKeyword::select('*')->with('creator')
            ->whereHas('creator',function($query) use($me) { $query->where('pid',$me->id); });

        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }
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

    // 【关键词排名】返回-列表-视图
    public function show_business_keyword_detect_record($post_data)
    {
        $id = $post_data["id"];
        $keyword_data = SEOKeyword::select('*')->with('creator')->where('id',$id)->first();
        return view('mt.agent.entrance.business.keyword-detect-record')
            ->with(['data'=>$keyword_data]);
    }
    // 【关键词排名】返回-列表-数据
    public function get_business_keyword_detect_record_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();

        $id  = $post_data["id"];
        $query = SEOKeywordDetectRecord::select('*')->where('keywordid',$id);

        if(!empty($post_data['rank']))
        {
            if($post_data['rank'] = 1)
            {
                $query->where('rank', '>', 0)->where('rank', '<=', 10);
            }
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
        else $query->orderBy("createtime", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




    // 下载
    public function operate_download_keyword_today()
    {
        $me = Auth::guard("agent")->user();
//        $cellData = SEOKeyword::select('keyword','createusername','website','searchengine','price','detectiondate','latestranking')
        $cellData = SEOKeyword::select('keyword','createusername','website','searchengine','price','createtime','initialranking','latestranking','detectiondate','latestconsumption','standarddays','totalconsumption')
            ->whereDate('detectiondate',date("Y-m-d"))
            ->whereHas('creator',function($query) use($me) { $query->where('pid',$me->id); })
            ->orderby('id','desc')
            ->get()
            ->toArray();
        foreach($cellData as $k => $v)
        {
            if($v['searchengine'] == "baidu") $cellData[$k]['searchengine'] = '百度PC';
            else if($v['searchengine'] == "baidu_mobile") $cellData[$k]['searchengine'] = '百度移动';
            else if($v['searchengine'] == "sougou") $cellData[$k]['searchengine'] = '搜狗';
            else if($v['searchengine'] == "360") $cellData[$k]['searchengine'] = '360';
            else if($v['searchengine'] == "shenma") $cellData[$k]['searchengine'] = '神马';
        }
//        array_unshift($cellData,['关键词','客户','站点','搜索引擎','价格','检测时间','排名']);
        array_unshift($cellData,['关键词','客户','站点','搜索引擎','价格','创建时间','初始排名','最新排名','检测时间','最新消费','达标天数','累计消费']);

        $title = '【今日关键词】 - '.date('YmdHis');
        Excel::create($title,function($excel) use ($cellData){
            $excel->sheet('all', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    // 下载
    public function operate_download_keyword_detect($post_data)
    {
//        echo 0;
        $keyword_id = $post_data["id"];
        $keyword = SEOKeyword::find($keyword_id);
        if(!$keyword) return response_fail([],'关键词不存在，请重试！');

        $cellData = SEOKeywordDetectRecord::select('detect_time','rank')
            ->where('keywordid',$keyword_id)
            ->orderby('detect_time','desc')
            ->get()
            ->toArray();
        array_unshift($cellData,['检测时间','排名']);

        $title = "【关键词】{$keyword->keyword}-{$keyword->searchengine}-{$keyword->price}元 - ".date('YmdHis');
        $engine = $keyword->searchengine;
        Excel::create($title,function($excel) use ($cellData,$keyword,$engine){
            $excel->sheet($engine, function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
        return response_success([]);
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




    /*
     * 公告&通知
     */
    // 返回【添加公告】视图
    public function view_notice_notice_create()
    {
        $me = Auth::guard('agent')->user();
        $view_blade = 'mt.agent.entrance.notice.notice-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }
    // 返回【编辑公告】视图
    public function view_notice_notice_edit($post_data)
    {
        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response("你没有权限操作！", 404);

        $id = $post_data["id"];
        $mine = Item::with(['user'])->find($id);
        if(!$mine) return response_error([],"该公告不存在，刷新页面试试！");
        if($mine->creator_id != $me->id) return response_error([],"该公告不是你的，你没有权限操作！");

        $view_blade = 'mt.agent.entrance.notice.notice-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $mine = Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$mine]);
            }
            else return response("该公告不存在！", 404);
        }
    }
    // 保存【公告】
    public function operate_notice_notice_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'title.required' => '请输入标题',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'title' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $mine = new Item;
            $post_data["category"] = 9;
            $post_data["sort"] = 9;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate == 'edit') // 编辑
        {
            $mine = Item::find($operate_id);
            if(!$mine) return response_error([],"该公告不存在，刷新页面重试！");
            if($mine->creator_id != $me->id) return response_error([],"该公告不是你的，你没有权限操作！");
        }
        else return response_error([],"参数有误！");

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
            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $mine_cover_pic = $mine->cover_pic;
                    if(!empty($mine_cover_pic) && file_exists(storage_path("resource/" . $mine_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $mine_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $mine->cover_pic = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }
            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$mine->id]);
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


    // 返回【公告列表】视图
    public function show_notice_notice_list()
    {
        return view('mt.agent.entrance.notice.notice-list')
            ->with(['sidebar_notice_notice_list_active'=>'active']);
    }
    // 返回【公告列表】数据
    public function get_notice_notice_list_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();

        $query = Item::select('*')->with(['creator'])->where('category',9)
            ->where( function($query) use($me) {
                $query->where('creator_id',$me->id)->orWhere( function($query1) {
                    $query1->where('active',1)->whereIn('type',[1,9]);
                });
            });

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['creator']))
        {
            $creator = $post_data['creator'];
            $query->whereHas('creator',function ($query1) use ($creator)  { $query1->where('username', 'like', "%{$creator}%"); });
        }
        if(!empty($post_data['sort'])) $query->where('sort',$post_data['sort']);
        if(!empty($post_data['type'])) $query->where('type',$post_data['type']);

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


    // 返回【公告列表】视图
    public function show_notice_my_notice_list()
    {
        return view('mt.agent.entrance.notice.my-notice-list')
            ->with(['sidebar_notice_my_notice_list_active'=>'active']);
    }
    // 返回【公告列表】数据
    public function get_notice_my_notice_list_datatable($post_data)
    {
        $me = Auth::guard("agent")->user();

        $query = Item::select('*')->with(['creator'])->where('category',9)->where('creator_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['creator']))
        {
            $creator = $post_data['creator'];
            $query->whereHas('creator',function ($query1) use ($creator)  { $query1->where('username', 'like', "%{$creator}%"); });
        }
        if(!empty($post_data['sort'])) $query->where('sort',$post_data['sort']);
        if(!empty($post_data['type'])) $query->where('type',$post_data['type']);

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


    // 获取【公告详情】
    public function operate_notice_notice_get($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'notice-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该公告不存在，刷新页面试试！");

        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");

        $work_order = Item::find($id);
        return response_success($work_order,"");

    }
    // 推送【公告】
    public function operate_notice_notice_push($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'notice-push') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该公告不存在，刷新页面试试！");

        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");

        $notice = Item::find($id);
        if(!$notice) return response_error([],"该公告不存在，刷新页面重试");
        if($notice->creator_id != $me->id) return response_error([],"该公告不是你的，你没有权限操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $notice->active = 1;
            $bool = $notice->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();
            return response_success([],"操作成功！");
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
    // 删除【公告】
    public function operate_notice_notice_delete($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'notice-delete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该公告不存在，刷新页面试试！");

        $me = Auth::guard('agent')->user();
        if(!in_array($me->usergroup,['Agent','Agent2'])) return response_error([],"你没有操作权限！");

        $notice = Item::find($id);
        if(!$notice) return response_error([],"该公告不存在，刷新页面重试");
        if($notice->creator_id != $me->id) return response_error([],"该公告不是你的，你没有权限操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $notice->delete();
            if(!$bool) throw new Exception("delete--item--fail");

            DB::commit();
            return response_success([],"操作成功！");
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




    /*
     * 公告
     */
    // 获取【内容详情】
    public function view_item_item_detail($post_data)
    {
        $me = Auth::guard("agent")->user();
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response("该内容不存在！", 404);

        $item = Item::find($id);
        return view('mt.agent.entrance.item.item-detail')->with(['data'=>$item]);
    }


}