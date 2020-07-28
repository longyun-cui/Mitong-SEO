<?php
namespace App\Repositories\MT\Admin;

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
        $this->model = new User;
    }

    // 返回（后台）主页视图
    public function view_admin_index()
    {
        $admin = Auth::guard("admin")->user();

        $agent_num = User::where(['usergroup'=>'Agent'])->count();
        $index_data['agent_num'] = $agent_num;

        $agent2_num = User::where(['usergroup'=>'Agent2'])->count();
        $index_data['agent2_num'] = $agent2_num;

        $service_num = User::where(['usergroup'=>'Service'])->count();
        $index_data['service_num'] = $service_num;

        $keyword_num = SEOKeyword::where(['keywordstatus'=>'优化中'])->count();
        $index_data['keyword_num'] = $keyword_num;

        $keyword_standard_num = SEOKeyword::where(['keywordstatus'=>'优化中','standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->count();
        $index_data['keyword_standard_num'] = $keyword_standard_num;

        $keyword_standard_cost = SEOKeyword::where(['keywordstatus'=>'优化中','standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->sum('price');
        $index_data['keyword_standard_cost'] = $keyword_standard_cost;

        return view('mt.admin.index')->with('index_data',$index_data);
    }


    // 返回（后台）主页视图
    public function test()
    {
        $user = User::where("id",616)->first();

        $userpass = $user->userpass;
        $pass_encrypt = basic_encrypt("df123456");
        $pass_decrypt = basic_decrypt($user->userpass);

        $user->password = $pass_decrypt;
        $user->save();
        $password = $user->password;

        echo $userpass."<br>";
        echo $pass_encrypt."<br>";
        echo $pass_decrypt."<br>";
        echo $password."<br>";
        dd($pass_decrypt);
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
            ->where('userstatus','正常')->where('status',1)->whereIn('usergroup',['Agent','Agent2'])
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

    // 返回【客户列表】数据
    public function get_user_client_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = User::select('*')
//        $query = User::select('id','pid','epid','username','usergroup','createtime')
            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
            ->with('parent','ep','fund')
            ->withCount(['sites','keywords'])
            ->where('userstatus','正常')->where('status',1)->whereIn('usergroup',['Service'])
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


    // 删除【代理商】
    public function delete_user_agent($post_data)
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

    // 删除【客户】
    public function delete_user_client($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = $post_data["id"];

        $user = User::find($id);
        if($user->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $user->delete();
            if($bool)
            {
                $item = Item::find($user->item_id);
                if($item)
                {
                    $bool1 = $item->delete();
                    if(!$bool1) throw new Exception("delete-item--fail");
                }
            }
            else throw new Exception("delete-user--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }
    }



    /*
     * 业务系统
     */
    // 返回【站点列表】数据
    public function get_business_site_list_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
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

    // 返回【关键词列表】数据
    public function get_business_keyword_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator');

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");

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

    // 返回【关键词列表】数据
    public function get_business_keyword_today_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator')
            ->where('keywordstatus','优化中');

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
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




    /*
     * 财务系统
     */
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