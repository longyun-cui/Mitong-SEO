<?php
namespace App\Repositories\MT\Admin;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class IndexRepository {

    private $model;
    private $repo;
    public function __construct()
    {
        $this->model = new User;
    }

    // 返回（后台）主页视图
    public function view_admin_index()
    {
        $me = Auth::guard("admin")->user();


        $this_month = date('Y-m');
        $this_month_year = date('Y');
        $this_month_month = date('m');
        $last_month = date('Y-m',strtotime('last month'));
        $last_month_year = date('Y',strtotime('last month'));
        $last_month_month = date('m',strtotime('last month'));

        $query1 = ExpenseRecord::select('id','price','standarddate','createtime')
            ->whereYear('standarddate',$this_month_year)->whereMonth('standarddate',$this_month_month);
        $count1 = $query1->count("*");
        $sum1 = $query1->sum("price");
        $data1 = $query1->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m-%d')"))
            ->select(DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%e') as day,
                    sum(price) as sum,
                    count(*) as count
                "))->get();

        $query2 = ExpenseRecord::select('id','price','standarddate','createtime')
            ->whereYear('standarddate',$last_month_year)->whereMonth('standarddate',$last_month_month);
        $count2 = $query2->count("*");
        $sum2 = $query2->sum("price");
        $data2 = $query2->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m-%d')"))
            ->select(DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%e') as day,
                    sum(price) as sum,
                    count(*) as count
                "))->get();

        $consumption_data[0]['month'] = $this_month;
        $consumption_data[0]['data'] = $data1->keyBy('day');
        $consumption_data[1]['month'] = $last_month;
        $consumption_data[1]['data'] = $data2->keyBy('day');


//        $agent_num = User::where(['usergroup'=>'Agent'])->count();
//        $agent_fund_total_sum = User::where(['usergroup'=>'Agent'])->sum('fund_total');
//        $agent_fund_balance_sum = User::where(['usergroup'=>'Agent'])->sum('fund_balance');

        $agent_data = User::where(['userstatus'=>'正常','status'=>1])->whereIn('usergroup',['Agent','Agent2'])
            ->first(
                array(
                    \DB::raw('COUNT(*) as agent_count'),
                    \DB::raw('SUM(fund_total) as agent_fund_total_sum'),
                    \DB::raw('SUM(fund_balance) as agent_fund_balance_sum')
                )
            )
            ->toArray();
        $index_data['agent_count'] = $agent_data['agent_count'];
        $index_data['agent_fund_total_sum'] = number_format($agent_data['agent_fund_total_sum']);
        $index_data['agent_fund_balance_sum'] = number_format($agent_data['agent_fund_balance_sum'],0);

        $agent2_count = User::where(['usergroup'=>'Agent2'])->count();
        $index_data['agent2_count'] = $agent2_count;
        $index_data['agent1_count'] = $agent_data['agent_count'] - $agent2_count;

        $client_data = User::where(['usergroup'=>'Service'])
            ->first(
                array(
                    \DB::raw('COUNT(*) as client_count'),
                    \DB::raw('SUM(fund_total) as client_fund_total_sum'),
                    \DB::raw('SUM(fund_expense) as client_fund_balance_sum')
                )
            )
            ->toArray();

        $client_count = $client_data['client_count'];
        $client_fund_total_sum = $client_data['client_fund_total_sum'];
        $client_fund_expense_sum = $client_data['client_fund_balance_sum'];

        $index_data['client_count'] = number_format($client_count);
        $index_data['client_fund_total_sum'] = number_format($client_fund_total_sum);
        $index_data['client_fund_expense_sum'] = number_format($client_fund_expense_sum,0);
        $index_data['client_fund_balance_sum'] = number_format((int)$client_fund_total_sum - (int)$client_fund_expense_sum);


        /*
         * 关键词
         */
        // 今日优化关键词
        $keyword_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])->count();
        $index_data['keyword_count'] = $keyword_count;

        // 今日检测关键词
        $keyword_detect_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->count();
        $index_data['keyword_detect_count'] = $keyword_detect_count;

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            )->toArray();
        $index_data['keyword_standard_count'] = $keyword_standard_data["keyword_standard_count"];
        $index_data['keyword_standard_cost_sum'] = $keyword_standard_data["keyword_standard_cost_sum"];



        return view('mt.admin.index')
            ->with([
                'index_data'=>$index_data,
                'consumption_data'=>$consumption_data
            ]);
    }




    /*
     * 用户系统
     */
    // 返回【代理商列表】数据
    public function get_user_agent_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
//        $query = User::select('id','pid','epid','username','usergroup','createtime','userstatus')
        $query = User::select('*')
//            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
            ->with('ep','parent','fund')
            ->withCount([
                'agents'=>function ($query) { $query->where('usergroup','Agent2'); },
                'clients'
            ])
            ->where(['userstatus'=>'正常','status'=>1])->whereIn('usergroup',['Agent','Agent2']);

        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 40;

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
        $admin_id = Auth::guard("admin")->user()->id;
        $query = User::select('*')
//        $query = User::select('id','pid','epid','username','usergroup','createtime')
//            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
            ->with('parent','ep','fund')
            ->withCount(['sites','keywords'])
            ->where(['userstatus'=>'正常','status'=>1])->whereIn('usergroup',['Service']);

        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");

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
//            $v->fund_total = number_format($v->fund_total);
//            $v->fund_expense = number_format($v->fund_expense);
//            $v->fund_balance = number_format($v->fund_balance);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




    // 返回【添加代理商】视图
    public function view_user_agent_create()
    {
        $admin = Auth::guard('admin')->user();
        $view_blade = 'mt.admin.entrance.user.agent-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }

    // 返回【编辑代理商】视图
    public function view_user_agent_edit()
    {
        $id = request("id",0);
        $view_blade = 'mt.admin.entrance.user.agent-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $mine = User::with(['parent'])->find($id);
            if($mine)
            {
                if(!in_array($mine->usergroup,['Agent','Agent2'])) return response("该用户不是代理商！", 404);
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$mine]);
            }
            else return response("该用户不存在！", 404);
        }
    }

    // 保存【代理商】
    public function operate_user_agent_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'username.required' => '请输入用户名',
            'mobileno.required' => '请输入电话',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'username' => 'required',
            'mobileno' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $admin = Auth::guard('admin')->user();
        if($admin->usergroup != "Manage") return response_error([],"你没有操作权限");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $mine = new User;
            $current_time = date('Y-m-d H:i:s');
            $post_data["usergroup"] = "Agent";
            $post_data["pid"] = $admin->id;
            $post_data["createuserid"] = $admin->id;
            $post_data["createtime"] = $current_time;
            $post_data["userstatus"] = "正常";
            $post_data["status"] = 1;
        }
        else if($operate == 'edit') // 编辑
        {
            $mine = User::find($operate_id);
            if(!$mine) return response_error([],"该用户不存在，刷新页面重试");
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
            else throw new Exception("insert--user--fail");

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




    // 【代理商】充值
    public function operate_user_agent_recharge($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
            'recharge-amount.required' => '请输入用户名',
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
        if($operate != 'recharge') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $amount = $post_data['recharge-amount'];
        // 充值金额不能为0
        if($amount == 0) return response_error([],"充值金额不能为0！");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent'])) return response_error([],"该用户不是1级代理商，你不能操作！");

        // 退款金额应该小于资金余额
        if($amount < 0)
        {
            if(($agent->fund_balance + $amount) < 0) return response_error([],"退款金额不能超过该账户余额");
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
            $FundRechargeRecord_data['usertype'] = 'admin';
            $FundRechargeRecord_data['status'] = 1;
            $FundRechargeRecord_data['amount'] = $amount;

            $bool = $FundRechargeRecord->fill($FundRechargeRecord_data)->save();
            if($bool)
            {
                $agent->increment('fund_total',$amount);
                $agent->increment('fund_balance',$amount);
            }
            else throw new Exception("insert--fund-record--fail");

            DB::commit();
            return response_success(['id'=>$agent->id]);
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

    // 关闭【代理商】充值限制
    public function operate_user_agent_recharge_limit_close($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
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
        if($operate != 'recharge-limit-close') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["is_recharge_limit"] = 0;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
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
    // 开启【代理商】充值限制
    public function operate_user_agent_recharge_limit_open($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
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
        if($operate != 'recharge-limit-open') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["is_recharge_limit"] = 1;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
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




    // 关闭【二级代理商】
    public function operate_user_agent_sub_agent_close($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
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
        if($operate != 'sub-agent-close') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["isopen_subagent"] = 0;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
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
    // 开启【二级代理商】
    public function operate_user_agent_sub_agent_open($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
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
        if($operate != 'sub-agent-open') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["isopen_subagent"] = 1;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
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
    public function operate_user_agent_delete($post_data)
    {
        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $agent = User::find($id);
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商");
        if($agent->fund_balance > 0) return response_error([],"该用户还有余额");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $agent->content;
            $cover_pic = $agent->cover_pic;

            // 删除名下客户
            $clients = User::where(['pid'=>$id,'usergroup'=>'Service'])->get();
            foreach ($clients as $c)
            {
                $client_id =  $c->id;
                $client  = User::find($client_id);

                // 删除【站点】
//                $deletedRows_1 = SEOSite::where('owner_id', $client_id)->delete();
                $deletedRows_1 = SEOSite::where('createuserid', $client_id)->delete();

                // 删除【关键词】
//                $deletedRows_2 = SEOKeyword::where('owner_id', $client_id)->delete();
                $deletedRows_2 = SEOKeyword::where('createuserid', $client_id)->delete();

                // 删除【待选关键词】
//                $deletedRows_3 = SEOCart::where('owner_id', $client_id)->delete();
                $deletedRows_3 = SEOCart::where('createuserid', $client_id)->delete();

                // 删除【关键词检测记录】
//                $deletedRows_4 = SEOKeywordDetectRecord::where('owner_id', $client_id)->delete();
                $deletedRows_4 = SEOKeywordDetectRecord::where('ownuserid', $client_id)->delete();

                // 删除【扣费记录】
//                $deletedRows_5 = ExpenseRecord::where('owner_id', $client_id)->delete();
                $deletedRows_5 = ExpenseRecord::where('ownuserid', $client_id)->delete();

                // 删除【用户】
//                $client->pivot_menus()->detach(); // 删除相关目录
                $bool = $client->delete();
                if(!$bool) throw new Exception("delete--user-client--fail");
            }

            // 删除名下子代理
            $sub_agents = User::where(['pid'=>$id,'usergroup'=>'Agent2'])->get();
            foreach ($sub_agents as $sub_a)
            {
                $sub_agent_id = $sub_a->id;
                $sub_agent_clients = User::where(['pid'=>$sub_agent_id,'usergroup'=>'Service'])->get();

                foreach ($sub_agent_clients as $sub_agent_c)
                {
                    $sub_agent_client_id =  $sub_agent_c->id;
                    $sub_agent_client = User::find($sub_agent_client_id);

                    // 删除【站点】
//                    $deletedRows_1 = SEOSite::where('owner_id', $sub_agent_client_id)->delete();
                    $deletedRows_1 = SEOSite::where('createuserid', $sub_agent_client_id)->delete();

                    // 删除【关键词】
//                    $deletedRows_2 = SEOKeyword::where('owner_id', $sub_agent_client_id)->delete();
                    $deletedRows_2 = SEOKeyword::where('createuserid', $sub_agent_client_id)->delete();

                    // 删除【待选关键词】
//                    $deletedRows_3 = SEOCart::where('owner_id', $sub_agent_client_id)->delete();
                    $deletedRows_3 = SEOCart::where('createuserid', $sub_agent_client_id)->delete();

                    // 删除【关键词检测记录】
//                    $deletedRows_4 = SEOKeywordDetectRecord::where('owner_id', $sub_agent_client_id)->delete();
                    $deletedRows_4 = SEOKeywordDetectRecord::where('ownuserid', $sub_agent_client_id)->delete();

                    // 删除【扣费记录】
//                    $deletedRows_5 = ExpenseRecord::where('owner_id', $sub_agent_client_id)->delete();
                    $deletedRows_5 = ExpenseRecord::where('ownuserid', $sub_agent_client_id)->delete();

                    // 删除【用户】
//                    $sub_agent_c->pivot_menus()->detach(); // 删除相关目录
                    $bool = $sub_agent_c->delete();
                    if(!$bool) throw new Exception("delete--user-sub-client--fail");
                }

                // 删除【用户】
//                $sub_a->pivot_menus()->detach(); // 删除相关目录
                $bool = $sub_a->delete();
                if(!$bool) throw new Exception("delete--user-sub--fail");
            }

            // 删除【用户】
//            $agent->pivot_menus()->detach(); // 删除相关目录
            $bool = $agent->delete();
            if(!$bool) throw new Exception("delete--user-agent--fail");

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 删除【客户】
    public function operate_user_client_delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        if($admin->usergroup != "Manage") return response_error([],"你没有操作权限");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $mine = User::find($id);
        if($mine)
        {
            if(!in_array($mine->usergroup,['Service'])) return response_error([],"该用户不是客户");

//        if($mine->fund_balance > 0) return response_error([],"该用户还有余额");

            // 启动数据库事务
            DB::beginTransaction();
            try
            {
                $content = $mine->content;
                $cover_pic = $mine->cover_pic;


                // 删除【站点】
                $deletedRows_1 = SEOSite::where('createuserid', $id)->delete();

                // 删除【关键词】
                $deletedRows_2 = SEOKeyword::where('createuserid', $id)->delete();

                // 删除【关键词检测记录】
                $deletedRows_3 = SEOKeywordDetectRecord::where('ownuserid', $id)->delete();

                // 删除【扣费记录】
                $deletedRows_4 = ExpenseRecord::where('ownuserid', $id)->delete();

                // 删除【用户】
//            $mine->pivot_menus()->detach(); // 删除相关目录
                $bool = $mine->delete();
                if(!$bool) throw new Exception("delete--user--fail");

                DB::commit();

                return response_success([]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '删除失败，请重试';
//                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([],$msg);
            }

        }
        else return response_error([],'账户不存在，刷新页面试试');
    }



    /*
     * 业务系统
     */
    // 返回【站点】列表
    public function get_business_site_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOSite::select('*')->with('creator')
            ->withCount([
                'keywords',
                'keywords as keywords_standard_count'=>function ($query) { $query->where('standardstatus','已达标'); },
                'keywords as consumption_sum'=>function ($query) {
                    $query->select(DB::raw("sum(price) as consumption_sum"))->where('standardstatus','已达标');
                }
            ]);

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

    // 返回【待审核站点】列表
    public function get_business_site_todo_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOSite::select('*')->with('creator')
            ->where('sitestatus','待审核');

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


    // 返回【关键词】视图
    public function show_business_keyword_list()
    {
        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1]);

        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"))->where('latestranking','>',0)->where('latestranking','<=',10);

        $keyword_standard_count = $query_1->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        $keyword_standard_fund_sum = $query_1->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
        $keyword_standard_fund_sum_1 = $query_detect->count('*');
        $data['keyword_standard_fund_sum_1'] = $keyword_standard_fund_sum_1;


        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
        $keyword_standard_fund_sum_2 = $query_detect->count('*');
        $data['keyword_standard_fund_sum_2'] = $keyword_standard_fund_sum_2;

        return view('mt.admin.entrance.business.keyword-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_list_active'=>'active'
            ]);
    }
    // 返回【关键词】列表
    public function get_business_keyword_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator');

        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['keywordstatus'])) $query->where('keywordstatus', $post_data['keywordstatus'])->where('status', 1);

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


    // 返回【今日关键词】视图
    public function show_business_keyword_today_list()
    {
        $data = [];

        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1]);

        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"));
        $keyword_detect_count = $query_1->count("*");
        $data['keyword_detect_count'] = $keyword_detect_count;

        $query_2 = $query->whereDate('standarddate',date("Y-m-d"))->where('standardstatus','已达标');
        $keyword_standard_count = $query_1->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        $keyword_standard_fund_sum = $query_1->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


//        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
//        $keyword_standard_count_by_detect = $query_detect->count('*');
//        $data['keyword_standard_count_by_detect'] = $keyword_standard_count_by_detect;
//
//
//        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
//        $keyword_standard_count_by_expense = $query_expense->count('*');
//        $data['keyword_standard_count_by_expense'] = $keyword_standard_count_by_expense;
//
//        $keyword_standard_fund_sum_by_expense = $query_expense->sum('price');
//        $data['keyword_standard_fund_sum_by_expense'] = $keyword_standard_fund_sum_by_expense;

//        dd($data);

        return view('mt.admin.entrance.business.keyword-today-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_today_active'=>'active'
            ]);
    }
    // 返回【今日关键词】列表
    public function get_business_keyword_today_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator')
            ->where(['keywordstatus'=>'优化中','status'=>1]);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
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


    // 返回【待审核关键词】列表
    public function get_business_keyword_todo_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOKeyword::select('*')->with('creator')
            ->where(['status'=>1,'keywordstatus'=>'待审核']);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
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




    // 返回【关键词检测】视图
    public function show_business_keyword_detect_record($post_data)
    {
        $id = $post_data["id"];
        $keyword_data = SEOKeyword::select('*')->with('creator')->where('id',$id)->first();
        return view('mt.admin.entrance.business.keyword-detect-record')
            ->with(['data'=>$keyword_data]);
    }
    // 返回【关键词检测】列表
    public function get_business_keyword_detect_record_datatable($post_data)
    {
        $mine = Auth::guard("admin")->user();

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


    // 添加【关键词排名】
    public function operate_business_keyword_detect_create_rank($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'keyword_id.required' => '参数有误',
            'detect_date.required' => '请输入指定日期',
            'detect_rank.required' => '请输入指定排名',
            'detect_rank.numeric' => '指定排名必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'keyword_id' => 'required',
            'detect_date' => 'required',
            'detect_rank' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard("admin")->user();

        $operate = $post_data["operate"];

        if($operate != 'detect-create-rank') return response_error([],"参数有误！");
        $keyword_id = $post_data["keyword_id"];
        if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"该关键词不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $detect_rank = $post_data['detect_rank'];
        if($detect_rank <= 0 or $detect_rank > 101) return response_error([],"指定排名必须在1-100之间！");

        $detect_date = $post_data['detect_date'];
        if(strtotime($detect_date) > time('Y-m-d')) return response_error([],"指定日期不能大于今天！");

        $keyword = SEOKeyword::find($keyword_id);
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");


        $DetectRecord = SEOKeywordDetectRecord::where(['keywordid'=>$keyword->id])->whereDate('detect_time',$detect_date)->first();
        if($DetectRecord) return response_error([],"该日期已存在，不能重复添加！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $DetectRecord = New SEOKeywordDetectRecord;
            $DetectRecord_data['owner_id'] = $keyword->createuserid;
            $DetectRecord_data['ownuserid'] = $keyword->createuserid;
            $DetectRecord_data['createuserid'] = $me->id;
            $DetectRecord_data['createusername'] = $me->username;
            $DetectRecord_data['createtime'] = $time;
            $DetectRecord_data['keywordid'] = $keyword->id;
            $DetectRecord_data['keyword'] = $keyword->keyword;
            $DetectRecord_data['website'] = $keyword->website;
            $DetectRecord_data['searchengine'] = $keyword->searchengine;
            $DetectRecord_data['detect_time'] = $detect_date;
            $DetectRecord_data['rank'] = $detect_rank;
            $DetectRecord_data['rank_original'] = $detect_rank;
            $DetectRecord_data['rank_real'] = $detect_rank;

            $bool = $DetectRecord->fill($DetectRecord_data)->save();
            if($bool)
            {
                $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                $detect_standard_count = $query_detect->count('*');
                $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                if(strtotime($detect_date) >= strtotime($keyword->detectiondate))
                {
                    $keyword->detectiondate = $detect_date;
                    $keyword->latestranking = $detect_rank;

                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->standardstatus = "已达标";
                        $keyword->standarddate = $detect_date;
                        $keyword->latestconsumption = (int)$keyword->price;
                    }
                    else
                    {
                        $keyword->standardstatus = "未达标";
                        $keyword->latestconsumption = 0;
                    }
                }

                if(strtotime($detect_date) <= strtotime($keyword->firststandarddate))
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->firststandarddate = $detect_date;
                    }
                }

                $keyword->standarddays = $detect_standard_count;
                $keyword->standard_days = $detect_standard_count;
                $keyword->totalconsumption = $detect_standard_price_sum;
                $keyword->consumption_total = $detect_standard_price_sum;

                $bool_1 = $keyword->save();
                if($bool_1)
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$detect_date)->first();
                        if(!$ExpenseRecord)
                        {
                            $ExpenseRecord = new ExpenseRecord;
                            $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                            $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                            $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                            $ExpenseRecord_data['standarddate'] = $detect_date;
                            $ExpenseRecord_data['createtime'] = $time;
                            $ExpenseRecord_data['siteid'] = $keyword->siteid;
                            $ExpenseRecord_data['keywordid'] = $keyword->id;
                            $ExpenseRecord_data['keyword'] = $keyword->keyword;
                            $ExpenseRecord_data['price'] = (int)$keyword->price;
                            $bool_2 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                            if($bool_2)
                            {
                                $DetectRecord->expense_id = $ExpenseRecord->id;
                                $DetectRecord->save();

                                $keyword_owner = User::find($keyword->createuserid);
                                $keyword_owner->fund_expense = $keyword_owner->fund_expense + $keyword->price;
                                $keyword_owner->fund_balance = $keyword_owner->fund_balance - $keyword->price;
                                if($keyword_owner->fund_frozen >= $keyword->price)
                                {
                                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen - $keyword->price;
                                }
                                else
                                {
                                    $keyword_owner->fund_frozen = 0;
                                    $keyword_owner->fund_available = $keyword_owner->fund_available - $keyword->price;
                                }
                                $keyword_owner->save();
                            }
                            else throw new Exception("update--expense-record--fail");
                        }
                    }
                }
                else throw new Exception("update--detect-record--fail");
            }
            else throw new Exception("insert--detect-record--fail");

            DB::commit();
            return response_success(['id'=>$keyword->id]);
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
    // 修改【关键词排名】
    public function operate_business_keyword_detect_set_rank($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'detect_id.required' => '参数有误',
            'detect_date.required' => '请输入指定日期',
            'detect_rank.required' => '请输入指定排名',
            'detect_rank.numeric' => '指定排名必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'detect_id' => 'required',
            'detect_date' => 'required',
            'detect_rank' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard("admin")->user();

        $operate = $post_data["operate"];

        if($operate != 'detect-set-rank') return response_error([],"参数有误！");
        $detect_id = $post_data["detect_id"];
        if(intval($detect_id) !== 0 && !$detect_id) return response_error([],"该检测不存在，刷新页面试试！");

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $detect_rank = $post_data['detect_rank'];
        if($detect_rank <= 0 or $detect_rank > 101) return response_error([],"指定排名必须在1-100之间！");

        $detect_date = $post_data['detect_date'];
        if(strtotime($detect_date) > time('Y-m-d')) return response_error([],"指定日期不能大于今天！");

        $DetectRecord = SEOKeywordDetectRecord::find($detect_id);
        if(!$DetectRecord) return response_error([],"该检测不存在，刷新页面重试！");

        $keyword = SEOKeyword::find($DetectRecord->keywordid);
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $DetectRecord->createuserid = $me->id;
            $DetectRecord->createusername = $me->username;
            $DetectRecord->detect_time = $detect_date;
            $DetectRecord->rank = $detect_rank;

            $bool = $DetectRecord->save();
            if($bool)
            {
                $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                $detect_standard_count = $query_detect->count('*');
                $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                if(strtotime($detect_date) >= strtotime($keyword->detectiondate))
                {
                    $keyword->detectiondate = $detect_date;
                    $keyword->latestranking = $detect_rank;

                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->standardstatus = "已达标";
                        $keyword->standarddate = $detect_date;
                        $keyword->latestconsumption = (int)$keyword->price;
                    }
                    else
                    {
                        $keyword->standardstatus = "未达标";
                        $keyword->latestconsumption = 0;
                    }
                }

                if(strtotime($detect_date) <= strtotime($keyword->firststandarddate))
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->firststandarddate = $detect_date;
                    }
                }

                $keyword->standarddays = $detect_standard_count;
                $keyword->standard_days = $detect_standard_count;
                $keyword->totalconsumption = $detect_standard_price_sum;
                $keyword->consumption_total = $detect_standard_price_sum;

                $bool_1 = $keyword->save();
                if($bool_1)
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$detect_date)->first();
                        if(!$ExpenseRecord)
                        {
                            $ExpenseRecord = new ExpenseRecord;
                            $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                            $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                            $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                            $ExpenseRecord_data['standarddate'] = $detect_date;
                            $ExpenseRecord_data['createtime'] = $time;
                            $ExpenseRecord_data['siteid'] = $keyword->siteid;
                            $ExpenseRecord_data['keywordid'] = $keyword->id;
                            $ExpenseRecord_data['keyword'] = $keyword->keyword;
                            $ExpenseRecord_data['price'] = (int)$keyword->price;
                            $bool_2 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                            if($bool_2)
                            {
                                $DetectRecord->expense_id = $ExpenseRecord->id;
                                $DetectRecord->save();
                            }
                            else throw new Exception("update--expense-record--fail");
                        }
                        else
                        {
                            $ExpenseRecord->detect_id = $DetectRecord->id;
                            $ExpenseRecord->save();
                        }
                    }
                }
                else throw new Exception("update--keyword--fail");
            }
            else throw new Exception("insert--detect-record--fail");

            DB::commit();
            return response_success(['id'=>$keyword->id]);
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




    // 审核【站点】
    public function operate_business_site_review($post_data)
    {
        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $current_time = date('Y-m-d H:i:s');
        $site_status = $post_data["sitestatus"];
        if(!in_array($site_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");


        $site = SEOSite::find($id);
        if($site)
        {
        }
        else return response_error([],'账户不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $site_data["reviewuserid"] = $mine->id;
            $site_data["reviewusername"] = $mine->username;
            $site_data["reviewdate"] = $current_time;
            $site_data["sitestatus"] = $site_status;

            $bool = $site->fill($site_data)->save();
            if(!$bool) throw new Exception("update--site--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 批量审核【站点】
    public function operate_business_site_review_bulk($post_data)
    {
        $messages = [
            'bulk_site_id.required' => '请选择站点！',
            'bulk_site_status.required' => '请选择状态！',
        ];
        $v = Validator::make($post_data, [
            'bulk_site_id' => 'required',
            'bulk_site_status' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $site_status = $post_data["bulk_site_status"];
        if(!in_array($site_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $current_time = date('Y-m-d H:i:s');

            $site_ids = $post_data["bulk_site_id"];
            foreach($site_ids as $key => $site_id)
            {
                if(intval($site_id) !== 0 && !$site_id) return response_error([],"id有误，刷新页面试试！");

                $site = SEOSite::where(["id"=>$site_id,"sitestatus"=>"待审核"])->first();
                if(!$site) return response_error([],'账户不存在，刷新页面试试！');

                $site_data["reviewuserid"] = $me->id;
                $site_data["reviewusername"] = $me->username;
                $site_data["reviewdate"] = $current_time;
                $site_data["sitestatus"] = $site_status;

                $bool = $site->fill($site_data)->save();
                if(!$bool) throw new Exception("update--site--fail");
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 审核【关键词】
    public function operate_business_keyword_review($post_data)
    {
        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $current_time = date('Y-m-d H:i:s');
        $keyword_status = $post_data["keywordstatus"];
        $keyword_price = $post_data["review-price"];
        if(!in_array($keyword_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        $keyword = SEOKeyword::find($id);
        if($keyword)
        {
            $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();
            if($keyword_owner)
            {
                if($keyword_owner->fund_available < ($keyword_price * 30))
                {
                    return response_error([],'用户可用余额不足！');
                }
            }
            else return response_error([],'用户不存在，刷新页面试试！');
        }
        else return response_error([],'关键词不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $keyword_data["reviewuserid"] = $me->id;
            $keyword_data["reviewusername"] = $me->username;
            $keyword_data["reviewdate"] = $current_time;
            $keyword_data["keywordstatus"] = $keyword_status;
            $keyword_data["price"] = $keyword_price;

            $bool = $keyword->fill($keyword_data)->save();
            if($bool)
            {
            }
            else throw new Exception("update--keyword--fail");

            $keyword_owner->fund_available = $keyword_owner->fund_available - ($keyword_price * 30);
            $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + ($keyword_price * 30);
            $keyword_owner->fund_frozen_init = $keyword_owner->fund_frozen_init + ($keyword_price * 30);
            $keyword_owner->save();

            $cart = SEOCart::find($keyword->cartid);
            $cart->price = $keyword_price;
            $cart->save();

            $freeze = new FundFreezeRecord;
            $freeze_date["owner_id"] = $keyword->createuserid;
            $freeze_date["siteid"] = $keyword->siteid;
            $freeze_date["keywordid"] = $keyword->id;
            $freeze_date["freezefunds"] = $keyword_price * 30;
            $freeze_date["createuserid"] = $me->id;
            $freeze_date["createusername"] = $me->username;
            $freeze_date["reguser"] = $me->username;
            $freeze_date["regtime"] = time();
            $bool_1 = $freeze->fill($freeze_date)->save();
            if($bool_1)
            {
            }
            else throw new Exception("insert--freeze--fail");

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 批量审核【关键词】
    public function operate_business_keyword_review_bulk($post_data)
    {
        $messages = [
            'bulk_keyword_id.required' => '请选择站点！',
            'bulk_keyword_status.required' => '请选择状态！',
        ];
        $v = Validator::make($post_data, [
            'bulk_keyword_id' => 'required',
            'bulk_keyword_status' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $keyword_status = $post_data["bulk_keyword_status"];
        if(!in_array($keyword_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $current_time = date('Y-m-d H:i:s');

            $keyword_ids = $post_data["bulk_keyword_id"];
            foreach($keyword_ids as $key => $keyword_id)
            {
                if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"id有误，刷新页面试试！");

                $keyword = SEOKeyword::find($keyword_id);
                if($keyword)
                {
                    $keyword_price = $keyword->price;
                    $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();
                    if($keyword_owner)
                    {
                        if($keyword_status == "优化中")
                        {
                            if($keyword_owner->fund_available < ($keyword_price * 30))
                            {
                                return response_error([],'用户可用余额不足！');
                            }
                        }
                    }
                    else return response_error([],'用户不存在，刷新页面试试！');
                }
                else return response_error([],'关键词不存在，刷新页面试试！');

                $keyword_data["reviewuserid"] = $me->id;
                $keyword_data["reviewusername"] = $me->username;
                $keyword_data["reviewdate"] = $current_time;
                $keyword_data["keywordstatus"] = $keyword_status;

                $bool = $keyword->fill($keyword_data)->save();
                if(!$bool) throw new Exception("update--keyword--fail");

                $cart = SEOCart::find($keyword->cartid);
                $cart->price = $keyword_price;
                $cart->save();

                if($keyword_status == "优化中")
                {
                    $keyword_owner->fund_available = $keyword_owner->fund_available - ($keyword_price * 30);
                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + ($keyword_price * 30);
                    $keyword_owner->fund_frozen_init = $keyword_owner->fund_frozen_init + ($keyword_price * 30);
                    $keyword_owner->save();

                    $freeze = new FundFreezeRecord;
                    $freeze_date["owner_id"] = $keyword->createuserid;
                    $freeze_date["siteid"] = $keyword->siteid;
                    $freeze_date["keywordid"] = $keyword->id;
                    $freeze_date["freezefunds"] = $keyword_price * 30;
                    $freeze_date["createuserid"] = $me->id;
                    $freeze_date["createusername"] = $me->username;
                    $freeze_date["reguser"] = $me->username;
                    $freeze_date["regtime"] = time();
                    $bool_1 = $freeze->fill($freeze_date)->save();
                    if(!$bool_1) throw new Exception("insert--freeze--fail");
                }
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }




    // 删除【待选站点】
    public function operate_business_site_todo_delete($post_data)
    {
        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该站点不存在，刷新页面试试！");

        $item = SEOSite::find($id);
        if(!$item) return response_error([],'站点不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $item->content;
            $cover_pic = $item->cover_pic;

            // 删除【该条目】
//            $item->pivot_menus()->detach(); // 删除相关目录
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--site--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 批量删除【待选站点】
    public function operate_business_site_todo_delete_bulk($post_data)
    {
        $messages = [
            'bulk_site_id.required' => '请选择站点！',
        ];
        $v = Validator::make($post_data, [
            'bulk_site_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $site_ids = $post_data["bulk_site_id"];
            foreach($site_ids as $key => $site_id)
            {
                if(intval($site_id) !== 0 && !$site_id) return response_error([],"该站点不存在，刷新页面试试！");

                $item = SEOSite::where(["id"=>$site_id,"sitestatus"=>"待审核"])->first();
                if(!$item) return response_error([],'站点不存在，刷新页面试试！');

                $content = $item->content;
                $cover_pic = $item->cover_pic;

                // 删除【该条目】
//                $item->pivot_menus()->detach(); // 删除相关目录
                $bool = $item->delete();
                if(!$bool) throw new Exception("delete--site--fail");
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 删除【待选关键词】
    public function operate_business_keyword_todo_delete($post_data)
    {
        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $item = SEOKeyword::where(["id"=>$id,"keywordstatus"=>"待审核"])->first();
        if(!$item) return response_error([],'关键词不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $item->content;
            $cover_pic = $item->cover_pic;

            // 删除【Cart】
//            $deletedRows_1 = SEOCart::find($item->id)->delete();

            // 删除【该条目】
//            $item->pivot_menus()->detach(); // 删除相关目录
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--keyword--fail");

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 批量删除【待选关键词】
    public function operate_business_keyword_todo_delete_bulk($post_data)
    {
        $messages = [
            'bulk_keyword_id.required' => '请选择关键词！',
        ];
        $v = Validator::make($post_data, [
            'bulk_keyword_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $keyword_ids = $post_data["bulk_keyword_id"];
            foreach($keyword_ids as $key => $keyword_id)
            {
                if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"ID有误，刷新页面试试！");

                $item = SEOKeyword::where(["id"=>$keyword_id,"keywordstatus"=>"待审核"])->first();
                if(!$item) return response_error([],'关键词不存在，刷新页面试试！');

                $content = $item->content;
                $cover_pic = $item->cover_pic;

                // 删除【Cart】
//                $deletedRows_1 = SEOCart::find($item->id)->delete();

                // 删除【该条目】
//                $item->pivot_menus()->detach(); // 删除相关目录
                $bool = $item->delete();
                if(!$bool) throw new Exception("delete--keyword--fail");
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }




    /*
     * 财务系统
     */
    // 返回【财务概览】视图
    public function show_finance_overview()
    {
//        $this_month = date('Y-m');
//        $this_month_year = date('Y');
//        $this_month_month = date('m');
//        $last_month = date('Y-m',strtotime('last month'));
//        $last_month_year = date('Y',strtotime('last month'));
//        $last_month_month = date('m',strtotime('last month'));
////        dd($this_month_year."--".$this_month_month."--".$last_month_year."--".$last_month_month);
//
//        $query1 = ExpenseRecord::select('id','price','createtime')
//            ->whereYear('createtime',$this_month_year)->whereMonth('createtime',$this_month_month);
//        $count1 = $query1->count("*");
//        $sum1 = $query1->sum("price");
//        $data1 = $query1->groupBy(DB::raw("STR_TO_DATE(createtime,'%Y-%m-%d')"))
//            ->select(DB::raw("
//                    STR_TO_DATE(createtime,'%Y-%m-%d') as date,
//                    DATE_FORMAT(createtime,'%e') as day,
//                    sum(price) as sum,
//                    count(*) as count
//                "))->get();
//
//        $query2 = ExpenseRecord::select('id','price','createtime')
//            ->whereYear('createtime',$last_month_year)->whereMonth('createtime',$last_month_month);
//        $count2 = $query2->count("*");
//        $sum2 = $query2->sum("price");
//        $data2 = $query2->groupBy(DB::raw("STR_TO_DATE(createtime,'%Y-%m-%d')"))
//            ->select(DB::raw("
//                    STR_TO_DATE(createtime,'%Y-%m-%d') as date,
//                    DATE_FORMAT(createtime,'%e') as day,
//                    sum(price) as sum,
//                    count(*) as count
//                "))->get();
//
//        $data[0]['month'] = $this_month;
//        $data[0]['data'] = $data1->keyBy('day');
//        $data[1]['month'] = $last_month;
//        $data[1]['data'] = $data2->keyBy('day');
////        dd($data[0]['data']);

        $data = [];

        return view('mt.admin.entrance.finance.overview')
            ->with([
                'data'=>$data,
                'sidebar_finance_active'=>'active',
                'sidebar_finance_overview_active'=>'active'
            ]);
    }
    // 返回【消费记录】数据
    public function get_finance_overview_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $query = ExpenseRecord::select('id','price','standarddate','createtime');
        $data = $query->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m')"))
            ->select(
                DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%Y-%m') as month,
                    DATE_FORMAT(standarddate,'%d') as day,
                    sum(price) as sum,
                    count(*) as count
                "))
            ->orderby("month","desc")
            ->get();

        $list = $data->keyBy('month')->sortByDesc('month');
        $total = $list->count();
        $list = collect(array_values($list->toArray()));


        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : -1;

//        if(isset($post_data['order']))
//        {
//            $columns = $post_data['columns'];
//            $order = $post_data['order'][0];
//            $order_column = $order['column'];
//            $order_dir = $order['dir'];
//
//            $field = $columns[$order_column]["data"];
//            $query->orderBy($field, $order_dir);
//        }
//        else $query->orderBy("id", "desc");
//
//        if($limit == -1) $list = $query->get();
//        else $list = $query->skip($skip)->take($limit)->get();

//        foreach ($list as $k => $v)
//        {
//            $list[$k]->encode_id = encode($v->id);
//        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 返回【月份财务】视图
    public function show_finance_overview_month($post_data)
    {
        $month = $post_data['month'];
        $month_arr = explode("-",$month);
        $_year = $month_arr[0];
        $_month = $month_arr[1];

        $data = [];
        return view('mt.admin.entrance.finance.overview-month')
            ->with([
                'data'=>$data,
                'sidebar_finance_active'=>'active',
                'sidebar_finance_overview_active'=>'active'
            ]);
    }
    // 返回【月份财务】数据
    public function get_finance_overview_month_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();

        $month = $post_data['month'];
        $month_arr = explode("-",$month);
        $_year = $month_arr[0];
        $_month = $month_arr[1];

        $query = ExpenseRecord::select('id','price','standarddate')->whereYear('standarddate',$_year)->whereMonth('standarddate',$_month);
        $list = $query->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m-%d')"))
            ->select(
                DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%Y-%m') as month,
                    DATE_FORMAT(standarddate,'%d') as day,
                    DATE_FORMAT(standarddate,'%e') as day_0,
                    sum(price) as sum,
                    count(*) as count
                "))
            ->orderby("day","desc")
            ->get();


//        $list = $data->groupBy(function ($item, $key) {
//            return date("Y-m-d",strtotime($item['createtime']));
//        });

//        $list = $data->keyBy('month')->sortByDesc('month');
        $total = $list->count();
        $list = collect(array_values($list->toArray()));


        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : -1;

//        if(isset($post_data['order']))
//        {
//            $columns = $post_data['columns'];
//            $order = $post_data['order'][0];
//            $order_column = $order['column'];
//            $order_dir = $order['dir'];
//
//            $field = $columns[$order_column]["data"];
//            $query->orderBy($field, $order_dir);
//        }
//        else $query->orderBy("id", "desc");
//
//        if($limit == -1) $list = $query->get();
//        else $list = $query->skip($skip)->take($limit)->get();

//        foreach ($list as $k => $v)
//        {
//            $list[$k]->encode_id = encode($v->id);
//        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 返回【充值记录】数据
    public function get_finance_recharge_record_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = FundRechargeRecord::select('*')
//        $query = FundRechargeRecord::select('id','userid','puserid','createuserid','amount','createtime')
            ->with('user','parent','creator')
            ->orderby("id","desc");

        if(!empty($post_data['creator'])) $query->where('createusername', 'like', "%{$post_data['creator']}%");
//        {
//
//
//            $query->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
//        }

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


    // 返回【消费记录】数据
    public function get_finance_expense_record_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = ExpenseRecord::select('*')
//        $query = ExpenseRecord::select('id','siteid','keywordid','ownuserid','price','createtime')
            ->with('user','site','keyword')
            ->orderby("id","desc");

        if(!empty($post_data['createtime']))
        {
            $query->whereDate('createtime', $post_data['createtime']);
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
    // 返回【每日消费记录】数据
    public function get_finance_expense_record_daily_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = ExpenseRecord::select('*')
//        $query = ExpenseRecord::select('id','siteid','keywordid','ownuserid','price','createtime')
            ->with('user','site','keyword')
            ->orderby("id","desc");

        if(!empty($post_data['createtime']))
        {
            $query->whereDate('createtime', $post_data['createtime']);
        }
        else
        {
            $query->whereDate('createtime', date("Y-m-d") );
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
//        $list["fund_total"] = $fund_total;
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 返回【关键词检测】视图
    public function show_finance_freeze_record()
    {
        $freeze_data = [];
        return view('mt.admin.entrance.finance.freeze-record')
            ->with([
                'freeze_data'=>$freeze_data,
                'sidebar_finance_active'=>'active',
                'sidebar_finance_freeze_active'=>'active'
            ]);
    }
    // 返回【冻结资金】数据
    public function get_finance_freeze_record_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $query = FundFreezeRecord::select('*')
            ->with('creator','site','keyword')
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








    // 删除
    public function delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试！");

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
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试！");

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
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试！");

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