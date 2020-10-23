<?php
namespace App\Repositories\MT\Client;

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
//        $this->model = new Item;
    }

    // 返回【主页】视图
    public function view_client_index()
    {
        $me = Auth::guard("client")->user();
        $me_id = $me->id;
        $index_data = $me;

        $this_month = date('Y-m');
        $this_month_year = date('Y');
        $this_month_month = date('m');
        $last_month = date('Y-m',strtotime('last month'));
        $last_month_year = date('Y',strtotime('last month'));
        $last_month_month = date('m',strtotime('last month'));

        $query1 = ExpenseRecord::select('id','price','standarddate','createtime')
            ->whereYear('standarddate',$this_month_year)
            ->whereMonth('standarddate',$this_month_month)
            ->where('ownuserid',$me->id);
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
            ->whereYear('standarddate',$last_month_year)
            ->whereMonth('standarddate',$last_month_month)
            ->where('ownuserid',$me->id);
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

        /*
         * 关键词
         */
        // 今日优化关键词
        $keyword_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])->where('createuserid',$me_id)->count();
        $index_data->keyword_count = number_format((int)$keyword_count);

        // 今日检测关键词
        $keyword_detect_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$me_id)
            ->count();
        $index_data->keyword_detect_count = number_format((int)$keyword_detect_count);

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$me_id)
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            );
        $index_data->keyword_standard_count = number_format((int)$keyword_standard_data->keyword_standard_count);
        $index_data->keyword_standard_cost_sum = number_format((int)$keyword_standard_data->keyword_standard_cost_sum);

        if($keyword_count > 0)
        {
            $index_data->keyword_standard_rate = round($index_data->keyword_standard_count/$keyword_count*100)."％";
        }
        else $index_data->keyword_standard_rate = "--";


        //
        $recently_notices = Item::select('*')->with(['creator'])->where('category',9)->where('active',1)
            ->where( function($query) use($me) {
                $query
                    ->where( function($query1) {
                        $query1->whereHas('creator', function($query0) { $query0->where('usergroup','Manage'); })->whereIn('type',[1,11]);
                    })
                    ->orWhere( function($query2) use($me) {
                        $query2->where('creator_id',$me->pid)->whereIn('type',[1,11]);
                    });
            })->orderBy("updated_at", "desc")->limit(5)->get();


        return view('mt.client.index')
            ->with([
                'index_data'=>$index_data,
                'consumption_data'=>$consumption_data,
                'recently_notices'=>$recently_notices
            ]);
    }




    /*
     * 用户基本信息
     */

    // 返回【基本信息】视图
    public function view_info_index()
    {
        $me = Auth::guard('client')->user();
        return view('mt.client.entrance.info.index')->with(['data'=>$me]);
    }

    // 返回【编辑基本信息】视图
    public function view_info_edit()
    {
        $me = Auth::guard('client')->user();
        return view('mt.client.entrance.info.edit')->with(['data'=>$me]);
    }
    // 保存【基本信息】
    public function operate_info_save($post_data)
    {
        $me = Auth::guard('client')->user();

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
        $me = Auth::guard('client')->user();
        return view('mt.client.entrance.info.password-reset')->with(['data'=>$me]);
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
            $me = Auth::guard('client')->user();
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




    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $org_id = Auth::guard("admin")->user()->org_id;
        $query = User::select("*")->where('org_id',$org_id)->with(['admin','menu']);
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




    /*
     * 业务系统
     */
    // 返回【站点列表】数据
    public function get_business_my_site_list_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();
        $query = SEOSite::select('id','createuserid','createusername','sitestatus','sitename','website','ftp','createtime')
            ->withCount([
                    'keywords'=>function($query){ $query->where(["keywordstatus"=>"优化中","status"=>1]); },
                    'work_orders as work_order_count'=>function ($query) { $query->where('category',1)->where('active','>',0); },
                ])
            ->where('createuserid',$mine->id);

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


    // 返回【我的关键词】视图
    public function show_my_business_keyword_list()
    {
        $me = Auth::guard("client")->user();
        $me_id = $me->id;
        $data = $me;

        // 今日优化关键词
        $keyword_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])->where('createuserid',$me_id)->count();
        $data->keyword_count = number_format((int)$keyword_count);

        // 今日检测关键词
        $keyword_detect_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$me_id)
            ->count();
        $data->keyword_detect_count = number_format((int)$keyword_detect_count);

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$me_id)
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            );
        $data->keyword_standard_count = number_format((int)$keyword_standard_data->keyword_standard_count);
        $data->keyword_standard_cost_sum = number_format((int)$keyword_standard_data->keyword_standard_cost_sum);

        if($keyword_count > 0)
        {
            $data->keyword_standard_rate = round($data->keyword_standard_count/$keyword_count*100)."％";
        }
        else $data->keyword_standard_rate = "--";


        return view('mt.client.entrance.business.my-keyword-list')
            ->with([
                'data'=>$data,
                'sidebar_business_active'=>'active',
                'sidebar_business_my_keyword_list_active'=>'active'
            ]);
    }
    // 返回【我的关键词列表】数据
    public function get_business_my_keyword_list_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();
//        $query = SEOKeyword::select('id','createuserid','createusername','keywordstatus','website','sitename','keyword','searchengine','price','createtime','initialrangking','latestranking')
        $query = SEOKeyword::select('*')
            ->with('site')
            ->where('createuserid',$mine->id);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
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


    // 返回【关键词购物车列表】数据
    public function get_business_my_keyword_cart_list_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();
//        $query = SEOKeyword::select('id','createuserid','createusername','createtime','cartstatus','keyword','searchengine','price')
        $query = SEOCart::select('*')
            ->where(['createuserid'=>$mine->id,'cartstatus'=>'未购买']);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['cartstatus'])) $query->where('cartstatus', $post_data['cartstatus']);

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
        $mine = Auth::guard("client")->user();
        $id  = $post_data["id"];
        $keyword_data = SEOKeyword::select('*')->with('creator')->where('id',$id)->first();
        return view('mt.client.entrance.business.keyword-detect-record')
            ->with(['data'=>$keyword_data]);
    }
    // 返回【关键词检测】列表
    public function get_business_keyword_detect_record_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();

        $id  = $post_data["id"];
        $query = SEOKeywordDetectRecord::select('*')->where('keywordid',$id);

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




    // 返回【添加站点】视图
    public function view_business_site_create()
    {
        $mine = Auth::guard('client')->user();
        $view_blade = 'mt.client.entrance.business.site-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }

    // 返回【编辑站点】视图
    public function view_business_site_edit()
    {
        $mine = Auth::guard('client')->user();
        $id = request("id",0);
        $view_blade = 'mt.client.entrance.business.site-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $site = SEOSite::find($id);
            if($site)
            {
                if($site->createuserid != $mine->id) return response("该站点不是你的，你无权编辑！", 404);
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$site]);
            }
            else return response("该用户不存在！", 404);
        }
    }

    // 保存【站点】
    public function operate_business_site_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'sitename.required' => '请输入站点名',
            'website.required' => '请输入站点',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'sitename' => 'required',
            'website' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $mine = Auth::guard('client')->user();
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $site = new SEOSite;
            $current_time = date('Y-m-d H:i:s');
            $post_data["owner_id"] = $mine->id;
            $post_data["createuserid"] = $mine->id;
            $post_data["createusername"] = $mine->username;
            $post_data["createtime"] = $current_time;
            $post_data["sitestatus"] = "待审核";
            $post_data["status"] = 1;
        }
        else if($operate == 'edit') // 编辑
        {
            $site = SEOSite::find($operate_id);
            if(!$site) return response_error([],"该站点不存在，刷新页面重试！");
            if($site->createuserid != $mine->id) return response_error([],"该站点不是你的，你无权编辑！");
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

            $site_data = $post_data;
            unset($site_data['operate']);
            unset($site_data['operate_id']);
            $bool = $site->fill($site_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $site_cover_pic = $site->cover_pic;
                    if(!empty($site_cover_pic) && file_exists(storage_path("resource/" . $site_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $site_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $site->cover_pic = $result["local"];
                        $site->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

            }
            else throw new Exception("insert--site--fail");

            DB::commit();
            return response_success(['id'=>$site->id]);
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

    // 删除【站点】
    public function operate_business_site_delete($post_data)
    {
        $mine = Auth::guard('client')->user();
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $site = SEOSite::with('keywords')->find($id);
        if($site->createuserid != $mine->id) return response_error([],"该站点不是你的，你无权删除！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $site->content;
            $cover_pic = $site->cover_pic;

            // 删除名下客户
            $keywords = SEOKeyword::where(['siteid'=>$id])->get();
            foreach ($keywords as $k)
            {
                $keyword_id = $k->id;

                // 删除【关键词检测记录】
                $deletedRows_3 = SEOKeywordDetectRecord::where('owner_id', $keyword_id)->delete();

                // 删除【扣费记录】
                $deletedRows_4 = ExpenseRecord::where('owner_id', $keyword_id)->delete();

                // 删除【用户】
//                $keyword->pivot_menus()->detach(); // 删除相关目录
                $bool = $k->delete();
                if(!$bool) throw new Exception("delete--keyword--fail");
            }

            // 删除【用户】
//            $site->pivot_menus()->detach(); // 删除相关目录
            $bool = $site->delete();
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




    // 返回【关键词查询】视图
    public function view_business_keyword_search()
    {
        $mine = Auth::guard('client')->user();
        $view_blade = 'mt.client.entrance.business.keyword-search';
        return view($view_blade)->with([
            'operate'=>'search', 'operate_id'=>0,
            'sidebar_business_active'=>'active','sidebar_business_keyword_search_active'=>'active'
        ]);
    }
    // 查询【关键词】
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

        $list = $CommonRepository -> combKeywordSearchResults( $arr );


        $mine = Auth::guard('client')->user();
        $mine_id = $mine->id;
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");

        foreach ($list as $k => $v)
        {
            $keyword = $v["keyword"];
            $keywords = SEOKeyword::select('id','keyword','searchengine','price')->where(['createuserid'=>$mine_id,'keyword'=>$keyword])->get();
            $keywords_data = $keywords->toArray();
            if($keywords_data)
            {
                foreach ($keywords_data as $data_k => $data_v)
                {
                    $searchengine = $data_v["searchengine"];
                    $price = (int)$data_v["price"];
                    $difference = 0;
                    if($searchengine == "baidu")
                    {
                        if($v["baidu"] < $price) $difference = $price - $v["baidu"];
                        $list[$k]["baidu"] = $price;

                        $list[$k]["baidu_mobile"] += $difference;
                        $list[$k]["sougou"] += $difference;
                        $list[$k]["360"] += $difference;
                        $list[$k]["shenma"] += $difference;
                    }
                    else if($searchengine == "baidu_mobile")
                    {
                        if($v["baidu_mobile"] < $price) $difference = $price - $v["baidu_mobile"];
                        $list[$k]["baidu_mobile"] = $price;

                        $list[$k]["baidu"] += $difference;
                        $list[$k]["sougou"] += $difference;
                        $list[$k]["360"] += $difference;
                        $list[$k]["shenma"] += $difference;
                    }
                    else if($searchengine == "sougou")
                    {
                        if($v["sougou"] < $price) $difference = $price - $v["sougou"];
                        $list[$k]["sougou"] = $price;

                        $list[$k]["baidu"] += $difference;
                        $list[$k]["baidu_mobile"] += $difference;
                        $list[$k]["360"] += $difference;
                        $list[$k]["shenma"] += $difference;
                    }
                    else if($searchengine == "360")
                    {
                        if($v["360"] < $price) $difference = $price - $v["360"];
                        $list[$k]["360"] = $price;

                        $list[$k]["baidu"] += $difference;
                        $list[$k]["baidu_mobile"] += $difference;
                        $list[$k]["sougou"] += $difference;
                        $list[$k]["shenma"] += $difference;
                    }
                    else if($searchengine == "shenma")
                    {
                        if($v["shenma"] < $price) $difference = $price - $v["shenma"];
                        $list[$k]["shenma"] = $price;

                        $list[$k]["baidu"] += $difference;
                        $list[$k]["baidu_mobile"] += $difference;
                        $list[$k]["sougou"] += $difference;
                        $list[$k]["360"] += $difference;
                    }
                }
            }
        }

        $view_blade = 'mt.client.entrance.business.keyword-search-result';
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
        $me = Auth::guard('client')->user();
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




    // 添加【购物车】【关键词】
    public function operate_keyword_cart_add($post_data)
    {
        $mine = Auth::guard('client')->user();
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");

        $keywords = $post_data['keywords'];

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            foreach($keywords as $key => $val)
            {
                $keyword = $val['keyword'];
                $data = $val['data'];
                foreach($data as $k => $v)
                {
                    if($v > 0)
                    {
                        $item = new SEOCart;
                        $current_time = date('Y-m-d H:i:s');
                        $item_data["owner_id"] = $mine->id;
                        $item_data["createuserid"] = $mine->id;
                        $item_data["createusername"] = $mine->username;
                        $item_data["createtime"] = $current_time;
                        $item_data["regtime"] = time();
                        $item_data["keyword"] = $keyword;
                        $item_data["searchengine"] = $k;
                        $item_data["price"] = $v;
                        $item_data["initial_price"] = $v;
                        $item_data["cartstatus"] = "未购买";
                        $item_data["status"] = 1;
                        $bool = $item->fill($item_data)->save();
                        if($bool)
                        {
                            // 封面图片
                            if(!empty($post_data["cover"]))
                            {
                                // 删除原封面图片
                                $item_cover_pic = $item->cover_pic;
                                if(!empty($item_cover_pic) && file_exists(storage_path("resource/" . $item_cover_pic)))
                                {
                                    unlink(storage_path("resource/" . $item_cover_pic));
                                }

                                $result = upload_storage($post_data["cover"]);
                                if($result["result"])
                                {
                                    $item->cover_pic = $result["local"];
                                    $item->save();
                                }
                                else throw new Exception("upload-cover-fail");
                            }

                        }
                        else throw new Exception("insert--cart--fail");
                    }
                }
            }

            DB::commit();
            return response_success(['id'=>$item->id]);
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


    // 删除【购物车】【关键词】
    public function operate_keyword_cart_delete($post_data)
    {
        $mine = Auth::guard('client')->user();
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该用户不存在，刷新页面试试！");

        $item = SEOCart::find($id);
        if($item->createuserid != $mine->id) return response_error([],"该站点不是你的，你无权删除！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $item->content;
            $cover_pic = $item->cover_pic;

            // 删除【待选关键词】
//            $item->pivot_menus()->detach(); // 删除相关目录
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--cart--fail");

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
    // 批量删除【购物车】【关键词】
    public function operate_keyword_cart_delete_bulk($post_data)
    {
        $messages = [
            'bulk_cart_id.required' => '请选择关键词！',
        ];
        $v = Validator::make($post_data, [
            'bulk_cart_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard('client')->user();
        if($me->usergroup != "Service") return response_error([],"你没有操作权限！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $cart_ids = $post_data["bulk_cart_id"];
            foreach($cart_ids as $key => $cart_id)
            {
                if(intval($cart_id) !== 0 && !$cart_id) return response_error([],"该关键词不存在，刷新页面试试！");

                $item = SEOCart::find($cart_id);
                if($item->createuserid != $me->id) return response_error([],"该关键词不是你的，你无权删除！");

                $content = $item->content;
                $cover_pic = $item->cover_pic;

                // 删除【待选关键词】
//                $item->pivot_menus()->detach(); // 删除相关目录
                $bool = $item->delete();
                if(!$bool) throw new Exception("delete--cart--fail");

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




    // 购买【关键词】
    public function operate_keyword_buy($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'website.required' => '请选择站点',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'website' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $cart_id = $post_data["id"];
        if(intval($cart_id) !== 0 && !$cart_id) return response_error([],"该待选关键词不存在，刷新页面试试！");

        $me = Auth::guard('client')->user();
        if($me->usergroup != "Service") return response_error([],"你没有操作权限！");

        $cart = SEOCart::find($cart_id);
        if($cart->createuserid != $me->id) return response_error([],"该待选关键词不是你的，你无权操作！");

        $site_id = $post_data["website"];
        $site = SEOSite::find($site_id);
        if(!$site) return response_error([],"该站点不存在，刷新页面试试！");
        if($site->createuserid != $me->id) return response_error([],"该站点不是你的，你无权操作！");

        $my_to_review_keyword_price_sum = SEOKeyword::where('createuserid',$me->id)->where('keywordstatus',"待审核")->sum('price');
        $frozen_amount = ($my_to_review_keyword_price_sum + $cart->price) * 30;
        if($frozen_amount > $me->fund_available) return response_error([],"可用余额小于待审核关键词冻结资金，请先充值！");


        $current_time = date('Y-m-d H:i:s');
        $keyword_data["owner_id"] = $me->id;
        $keyword_data["createuserid"] = $me->id;
        $keyword_data["createusername"] = $me->username;
        $keyword_data["createtime"] = $current_time;
        $keyword_data["keywordstatus"] = "待审核";
        $keyword_data["status"] = 1;
        $keyword_data["price"] = $cart->price;
        $keyword_data["keyword"] = $cart->keyword;
        $keyword_data["searchengine"] = $cart->searchengine;
        $keyword_data["siteid"] = $site_id;
        $keyword_data["sitename"] = $site->sitename;
        $keyword_data["website"] = $site->website;
        $keyword_data["cartid"] = $cart_id;

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $keyword = new SEOKeyword;
            $bool = $keyword->fill($keyword_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $site_cover_pic = $site->cover_pic;
                    if(!empty($site_cover_pic) && file_exists(storage_path("resource/" . $site_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $site_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $site->cover_pic = $result["local"];
                        $site->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

            }
            else throw new Exception("insert--keyword--fail");

            $cart->cartstatus = "已购买";
            $bool1 = $cart->save();
            if(!$bool1) throw new Exception("update--cart--fail");

            DB::commit();
            return response_success(['id'=>$site->id]);
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
    // 批量购买【关键词】
    public function operate_keyword_buy_bulk($post_data)
    {
        $messages = [
            'bulk_cart_id.required' => '请选择关键词！',
            'bulk_site_id.required' => '请输入站点！',
        ];
        $v = Validator::make($post_data, [
          'bulk_cart_id' => 'required',
            'bulk_site_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard('client')->user();
        if($me->usergroup != "Service") return response_error([],"你没有操作权限！");

        $site_id = $post_data["bulk_site_id"];
        $site = SEOSite::find($site_id);
        if(!$site) return response_error([],"该站点不存在，刷新页面试试！");
        if($site->createuserid != $me->id) return response_error([],"该站点不是你的，你无权操作！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $cart_ids = $post_data["bulk_cart_id"];
            foreach($cart_ids as $key => $cart_id)
            {
                if(intval($cart_id) !== 0 && !$cart_id) return response_error([],"该待选关键词不存在，刷新页面试试！");

                $cart = SEOCart::find($cart_id);
                if($cart->createuserid != $me->id) return response_error([],"该待选关键词不是你的，你无权操作！");


                $my_to_review_keyword_price_sum = SEOKeyword::where('createuserid',$me->id)->where('keywordstatus',"待审核")->sum('price');
                $frozen_amount = ($my_to_review_keyword_price_sum + $cart->price) * 30;
                if($frozen_amount > $me->fund_available) return response_error([],"可用余额小于待审核关键词冻结资金，请先充值！");


                $current_time = date('Y-m-d H:i:s');
                $keyword_data["owner_id"] = $me->id;
                $keyword_data["createuserid"] = $me->id;
                $keyword_data["createusername"] = $me->username;
                $keyword_data["createtime"] = $current_time;
                $keyword_data["keywordstatus"] = "待审核";
                $keyword_data["status"] = 1;
                $keyword_data["price"] = $cart->price;
                $keyword_data["keyword"] = $cart->keyword;
                $keyword_data["searchengine"] = $cart->searchengine;
                $keyword_data["siteid"] = $site_id;
                $keyword_data["sitename"] = $site->sitename;
                $keyword_data["website"] = $site->website;
                $keyword_data["cartid"] = $cart_id;

                if(!empty($post_data['custom']))
                {
                    $post_data['custom'] = json_encode($post_data['custom']);
                }

                $keyword = new SEOKeyword;
                $bool = $keyword->fill($keyword_data)->save();
                if($bool)
                {
                    // 封面图片
                    if(!empty($post_data["cover"]))
                    {
                        // 删除原封面图片
                        $site_cover_pic = $site->cover_pic;
                        if(!empty($site_cover_pic) && file_exists(storage_path("resource/" . $site_cover_pic)))
                        {
                            unlink(storage_path("resource/" . $site_cover_pic));
                        }

                        $result = upload_storage($post_data["cover"]);
                        if($result["result"])
                        {
                            $site->cover_pic = $result["local"];
                            $site->save();
                        }
                        else throw new Exception("upload-cover-fail");
                    }

                }
                else throw new Exception("insert--keyword--fail");

                $cart->cartstatus = "已购买";
                $bool1 = $cart->save();
                if(!$bool1) throw new Exception("update--cart--fail");

            }

            DB::commit();
            return response_success(['id'=>$site->id]);
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




    // 【select2】
    public function operate_business_select2_sites($post_data)
    {
        $mine = Auth::guard('client')->user();
        if(empty($post_data['keyword']))
        {
            $list =SEOSite::select(['id','website as text'])->where(['createuserid'=>$mine->id])->orderBy('id','desc')->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =SEOSite::select(['id','website as text'])->where(['createuserid'=>$mine->id])->where('sitename','like',"%$keyword%")
                ->orderBy('id','desc')->get()->toArray();
        }
        return $list;
    }




    /*
     * 工单管理
     */
    // 返回【工单】视图
    public function show_business_my_work_order_list()
    {
        return view('mt.client.entrance.business.my-work-order-list')
            ->with(['sidebar_work_order_list_active'=>'active menu-open']);
    }
    // 返回【工单】列表
    public function get_business_my_work_order_datatable($post_data)
    {
        $me = Auth::guard("client")->user();

        $query = Item::select('*')->with(['user','site'])->where('category',1)->where('active','>',0)->where('user_id',$me->id);

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


    // 返回【站点】
    public function operate_business_my_work_order_get($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入工单ID',
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
        if($operate != 'work-order-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该站点不存在，刷新页面试试！");

        $me = Auth::guard('client')->user();
        if($me->usertype != "sub") return response_error([],"你没有操作权限");


        $work_order = Item::find($id);
        if($work_order->is_read == 0)
        {
            $work_order->is_read = 1;
            $work_order->save();
        }
        return response_success($work_order,"");

    }
    // 删除【站点】
    public function operate_business_my_work_order_complete($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入工单ID',
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
        if($operate != 'work-order-complete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"ID有误！");

        $me = Auth::guard('client')->user();
        if($me->usertype != "sub") return response_error([],"你没有操作权限!");

        $work_order = Item::find($id);
        if(!$work_order) return response_error([],"该工单不存在，刷新页面试试!");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $work_order->active = 9;
            $bool = $work_order->save();
            if(!$bool) throw new Exception("update--item--fail");

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




    // 下载
    public function operate_download_keyword_today()
    {
        $me = Auth::guard('client')->user();
        if($me->usertype != "sub") return response_error([],"你没有操作权限!");

        dd($me->id);

        $cellData = SEOKeyword::select('keyword','searchengine','price','detectiondate','latestranking')
            ->where('createuserid',$me->id)
            ->whereDate('detectiondate',date("Y-m-d"))
            ->orderby('id','desc')
            ->get()
            ->toArray();
        array_unshift($cellData,['关键词','搜索引擎','价格','检测时间','排名']);

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
        $me = Auth::guard('client')->user();
        if($me->usertype != "sub") return response_error([],"你没有操作权限!");

        $keyword_id = $post_data["id"];
        $keyword = SEOKeyword::find($keyword_id);
        if(!$keyword) return response_fail([],'关键词不存在，请重试！');
        if($keyword->createuserid != $me->id) return response_fail([],'关键词不是你的，你无权操作！');

        $cellData = SEOKeywordDetectRecord::select('detect_time','rank')
            ->where('keywordid',$keyword_id)->orderby('detect_time','desc')
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
    // 返回【财务概览】视图
    public function show_finance_overview()
    {
        $me = Auth::guard("client")->user();

        return view('mt.client.entrance.finance.overview')
            ->with([
                'sidebar_finance_active'=>'active',
                'sidebar_finance_overview_active'=>'active'
            ]);
    }
    // 返回【财务概览】数据
    public function get_finance_overview_datatable($post_data)
    {
        $me = Auth::guard("client")->user();

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
            ->where("ownuserid",$me->id)
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
        return view('mt.client.entrance.finance.overview-month')
            ->with([
                'data'=>$data,
                'sidebar_finance_active'=>'active'
            ]);
    }
    // 返回【月份财务】数据
    public function get_finance_overview_month_datatable($post_data)
    {
        $me = Auth::guard("client")->user();

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
            ->where("ownuserid",$me->id)
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
        $client_id = Auth::guard("client")->user()->id;
        $query = FundRechargeRecord::select('id','userid','puserid','createuserid','amount','createtime')
            ->with('user','parent','creator')
            ->where('userid',$client_id)
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

    // 返回【消费记录】数据
    public function get_finance_expense_record_datatable($post_data)
    {
        $client_id = Auth::guard("client")->user()->id;
        $query = ExpenseRecord::select('id','siteid','keywordid','ownuserid','price','createtime')
            ->with('user','site','keyword')
            ->where('ownuserid',$client_id);

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
    // 返回【公告列表】视图
    public function show_notice_notice_list()
    {
        return view('mt.client.entrance.notice.notice-list')
            ->with(['sidebar_notice_notice_list_active'=>'active']);
    }
    // 返回【公告列表】数据
    public function get_notice_notice_list_datatable($post_data)
    {
        $me = Auth::guard("client")->user();

        $query = Item::select('*')->with(['creator'])->where('category',9)->where('active',1)
            ->where( function($query) use($me) {
                $query
                    ->where( function($query1) {
                        $query1->whereHas('creator', function($query0) { $query0->where('usergroup','Manage'); })->whereIn('type',[1,11]);
                    })
                    ->orWhere( function($query2) use($me) {
                        $query2->where('creator_id',$me->pid)->whereIn('type',[1,11]);
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

        $me = Auth::guard('client')->user();
        if(!in_array($me->usergroup,['Service'])) return response_error([],"你没有操作权限！");

        $item = Item::find($id);
        return response_success($item,"");

    }




    /*
     * 公告
     */
    // 获取【内容详情】
    public function view_item_item_detail($post_data)
    {
        $me = Auth::guard("client")->user();
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response("该内容不存在！", 404);

        $item = Item::find($id);
        return view('mt.client.entrance.item.item-detail')->with(['data'=>$item]);
    }


}