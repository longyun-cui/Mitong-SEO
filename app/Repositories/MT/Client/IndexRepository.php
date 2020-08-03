<?php
namespace App\Repositories\MT\Client;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOCart;

use App\Repositories\Common\CommonRepository;
use Response, Auth, Validator, DB, Exception;
use QrCode;

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
        $client = Auth::guard("client")->user();
        $client_id = $client->id;
        $expense_funds = ExpenseRecord::where('ownuserid',$client_id)->sum('price');
        return view('mt.client.index')
            ->with(['expense_funds'=>$expense_funds]);
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
            ->where('createuserid',$mine->id)
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

    // 返回【关键词列表】数据
    public function get_business_my_keyword_list_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();
//        $query = SEOKeyword::select('id','createuserid','createusername','keywordstatus','website','sitename','keyword','searchengine','price','createtime','initialrangking','latestranking')
        $query = SEOKeyword::select('*')
            ->with('site')
            ->where('createuserid',$mine->id)
            ->orderby("id","desc");

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
//        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['keywordstatus'])) $query->where('keywordstatus', $post_data['keywordstatus']);

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
    public function get_business_my_keyword_undo_list_datatable($post_data)
    {
        $mine = Auth::guard("client")->user();
//        $query = SEOKeyword::select('id','createuserid','createusername','createtime','cartstatus','keyword','searchengine','price')
        $query = SEOCart::select('*')
            ->where(['createuserid'=>$mine->id,'cartstatus'=>'未购买'])
            ->orderby("id","desc");

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

        $list = $this -> combKeywordSearchResults( $arr );

        $view_blade = 'mt.client.entrance.business.keyword-search-result';
        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list])->__toString();
//        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list]);
//        $html = response($html)->getContent();

        return response_success(['html'=>$html]);

    }

    /**
     * 搜索关键词:根据用户的关键词搜索推荐的关键词
     *
     * 通过第三方接口搜索关键词:由于第三方的接口一下只能提交10个关键词，需要将关键词进行等
     *
     */
    public function combKeywordSearchResults( $list ){
        // 关键词长度价格指数代码集
        $KeywordLengthPriceIndexOptions 			= config('seo.KeywordLengthPriceIndexOptions');
        // 百度指数价格指数代码集
        $BaiduIndexPriceIndexOptions 				= config('seo.BaiduIndexPriceIndexOptions');
        // 关键词长度难度指数代码集
        $KeywordDifficultyIndexOptions 				= config('seo.KeywordDifficultyIndexOptions');
        // 关键词长度优化周期代码集
        $KeywordOptimizationCycleOptions 			= config('seo.KeywordOptimizationCycleOptions');
        // 关键词百度指数难度指数代码集
        $KeywordDifficultyIndex4BaiduIndexOptions 	= config('seo.KeywordDifficultyIndex4BaiduIndexOptions');
        // 关键词百度指数化周期代码集
        $KeywordOptimizationCycle4BaiduIndexOptions = config('seo.KeywordOptimizationCycle4BaiduIndexOptions');



        // 将关键词组成一个字符串
        /* foreach ( $list as $vo1){
            $keyword_arr[] = $vo1['keyword'];
            } */

        // 将 关键词进行等分
        $list_new = array_chunk($list,10);

        foreach ($list_new as &$vo_list)
        {
            unset($keyword_arr);
            foreach ($vo_list as $vo_temp )
            {
                $keyword_arr[] = trim($vo_temp['keyword']);
            }

            //将获取的关键词数组组成字符串
            $keywords= implode(',',$keyword_arr);



            // 百度指数查询
            $url_index = 'http://api.91cha.com/index?key=456a38a7a22f41a0ae3829ec1ccb7fc1&kws='.urlencode($keywords);
            //echo file_get_contents("http://www.91cha.com");

            //$data_index = json_decode( file_get_contents($url_index));

            try {
                $data_index = file_get_contents($url_index);
                $data_index = json_decode($data_index, true);
            }
            catch (Exception $e) {
                echo $e->getMessage();
                return $e->getMessage();
            }



            /*
            $baiduindex_data =0;
            $baiduindex = 0;
            $mobileindex = 0;
            $so360index = 0;
            if($data_index['state'] == 1 )
            {
                $baiduindex_data = $data_index['data'];
                $baiduindex = $data_index['data'][0]['allindex'];
                $mobileindex = $data_index['data'][0]['mobileindex'];
                $so360index = $data_index['data'][0]['so360index'];
            }
            */

            foreach ( $vo_list as $key => &$vo )
            {
                $baiduindex = 0;
                $mobileindex = 0;
                $so360index = 0;

                if($data_index['state'] == 1 )
                {
                    foreach ($data_index['data'] as $vo_bi)
                    {
                        if(  $vo['keyword'] == $vo_bi['keyword'])
                        {
                            $baiduindex 	= $vo_bi['allindex'];
                            $mobileindex 	= $vo_bi['mobileindex'];
                            $so360index 	= $vo_bi['so360index'];
                        }
                    }
                }

                // 判断字符的长度
                $len = floor((strlen( $vo['keyword']) + mb_strlen( $vo['keyword'],'UTF8')) / 2);

                /*
                $baiduindex 	= $baiduindex_data[$key]['allindex'];
                $mobileindex 	= $baiduindex_data[$key]['mobileindex'];
                $so360index 	= $baiduindex_data[$key]['so360index'];
                */

                foreach ($vo as $key_vo => &$vo_vo )
                {
                    $price = 0;
                    $price1 = 0;
                    $price2 = 0;

                    // 关键词长度指数
                    $keywordOption = isset($KeywordLengthPriceIndexOptions[$key_vo]) ? $KeywordLengthPriceIndexOptions[$key_vo] : null;
                    if( $keywordOption )
                    {
                        $keywordOption = $KeywordLengthPriceIndexOptions[$key_vo];
                        foreach ($keywordOption as $vo_ko )
                        {
                            if( $vo_ko['vmin'] <= $len && $len <=$vo_ko['vmax'] )
                            {
                                $price1 = $vo_ko['quotavalue'];
//                                echo("-----");
//                                echo(" vmin=".$vo_ko['vmin']);
//                                echo(" vmax=".$vo_ko['vmax']);
//                                echo(" price1=".$price1);
                            }
                        }
                    }

                    // 关键词百度指数
                    $BaiduIndexPriceIndexOption = isset($KeywordLengthPriceIndexOptions[$key_vo]) ? $BaiduIndexPriceIndexOptions[$key_vo] : null;
                    if( $BaiduIndexPriceIndexOption )
                    {
                        foreach ($BaiduIndexPriceIndexOption as $vo_bo )
                        {
                            if( $vo_bo['vmin'] <= $baiduindex && $baiduindex <=$vo_bo['vmax'] )
                            {
                                $price2 = $vo_bo['quotavalue'];
//                                echo(" price2=".$price2);
                            }
                        }
                    }

                    $price = $price1 + $price2;

                    if( $price )
                    {
                        $vo_vo = round($price*0.95,0);
                    }
//                    echo("-----");
//                    echo "<br>";
                }



                // 计算难度指数difficulty_index 和 优化周期 optimization_cycle
                // 如果有百度指数，则只通过百度指数来进行计算
                if( $baiduindex )
                {
                    foreach ($KeywordDifficultyIndex4BaiduIndexOptions as $vo_kd )
                    {
                        if( $vo_kd['vmin'] <= $baiduindex && $baiduindex <=$vo_kd['vmax'] )
                        {
                            $difficulty_index = $vo_kd['quotavalue'];
                        }
                    }

                    foreach ($KeywordOptimizationCycle4BaiduIndexOptions as $vo_ko )
                    {
                        if( $vo_ko['vmin'] <= $baiduindex && $baiduindex <=$vo_ko['vmax'] )
                        {
                            $optimization_cycle = $vo_ko['quotavalue'];
                        }
                    }
                }
                else
                {
                    foreach ($KeywordDifficultyIndexOptions as $vo_kd )
                    {
                        if( $vo_kd['vmin'] <= $len && $len <=$vo_kd['vmax'] )
                        {
                            $difficulty_index = $vo_kd['quotavalue'];
                        }
                    }

                    foreach ($KeywordOptimizationCycleOptions as $vo_ko )
                    {
                        if( $vo_ko['vmin'] <= $len && $len <=$vo_ko['vmax'] )
                        {
                            $optimization_cycle = $vo_ko['quotavalue'];
                        }
                    }
                }
                $vo['difficulty_index'] = $difficulty_index;


                // 计算显示的样式
                if($difficulty_index > 0 )
                {
                    $rate = '';
                    for($i =1 ;$i<=$difficulty_index;$i++)
                    {
                        $rate .='★';
                    }
                    $rate .= '';
                }

                $rate_diff = 5 - $difficulty_index;
                if($rate_diff > 0 )
                {
                    $rate .= '';
                    for($i=1;$i<= $rate_diff;$i++)
                    {
                        $rate .= '☆';
                    }
                    $rate .= '';
                }
                $vo['rate'] = $rate;

                $vo['optimization_cycle'] = $optimization_cycle;
            }
        }

        foreach ($list_new as $vo2 )
        {
            if( !isset($return) )
            {
                $return = $vo2;
            }
            else
            {
                $return = array_merge ( $return,$vo2);
            }
        }
        return $return;
    }




    // 添加【关键词】
    public function operate_keyword_add_undo($post_data)
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

    // 删除【站点】
    public function operate_keyword_delete_undo($post_data)
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

    // 保存【站点】
    public function operate_keyword_buy($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'website.required' => '请输入站点',
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

        $cart_id = $post_data["id"];
        if(intval($cart_id) !== 0 && !$cart_id) return response_error([],"该待选关键词不存在，刷新页面试试！");

        $mine = Auth::guard('client')->user();
        if($mine->usergroup != "Service") return response_error([],"你没有操作权限！");

        $cart = SEOCart::find($cart_id);
        if($cart->createuserid != $mine->id) return response_error([],"该待选关键词不是你的，你无权操作！");

        $site_id = $post_data["website"];
        $site = SEOSite::find($site_id);
        if(!$site) return response_error([],"该站点不存在，刷新页面试试！");
        if($site->createuserid != $mine->id) return response_error([],"该站点不是你的，你无权操作！");


        $current_time = date('Y-m-d H:i:s');
        $keyword_data["owner_id"] = $mine->id;
        $keyword_data["createuserid"] = $mine->id;
        $keyword_data["createusername"] = $mine->username;
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