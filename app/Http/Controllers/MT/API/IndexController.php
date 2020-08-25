<?php
namespace App\Http\Controllers\MT\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\SEOKeywordDetectRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOKeyword;

use App\Models\TEST\Temp;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new APIRepository;
    }



    /*
     *
     */
    // 关键词检测请求
    public function morning_send()
    {
        $date = date('Y-m-d');
        $query = SEOKeyword::select('id','keyword','website','searchengine')
            ->where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate','<',$date)
            ->orderby('id','desc');

        $limit = request("limit",0);
        if($limit) $query->limit($limit);
        $data = $query->get()->toArray();
//        dd($data);

        foreach ($data as $value)
        {
            // 组合url
            $url = str_ireplace(array('http://','https://'),'',$value['website']);
            // 需要替换的前缀
            $prefixs =  array('www.','m.','wap.', '3g.');
            // 判断是否已以下的几个开开头
//            foreach ($prefixs as $vo_pf){
//                if( stripos($url,$vo_pf) === 0 ){
//                    $url = substr($url,strlen($vo_pf));
//                }
//            }

            switch($value['searchengine'])
            {
                case 'baidu' :
                    $baidu_sType = '1010';
                    $baidu_keyword[] = $value['keyword'];
                    $baidu_url[] = $url;
                    $baidu_id[] = $value['id'];
                    break;
                case 'baidu_mobile' :
                    $baidu_mobile_sType = '7010';
                    $baidu_mobile_keyword[] = $value['keyword'];
                    $baidu_mobile_url[] = $url;
                    $baidu_mobile_id[] = $value['id'];
                    break;
                case '360':
                    $abc_sType = '1015';
                    $abc_keyword[] = $value['keyword'];
                    $abc_url[] = $value['website'];
                    $abc_id[] = $value['id'];
                    break;
                case '360_mobile':
                    $abc_mobile_sType = '7015';
                    $abc_mobile_keyword[] = $value['keyword'];
                    $abc_mobile_url[] = $url;
                    $abc_mobile_id[] = $value['id'];
                    break;
                case 'sougou':
                    $sougou_sType = '1030';
                    $sougou_keyword[] = $value['keyword'];
                    $sougou_url[] = $value['website'];
                    $sougou_id[] = $value['id'];
                    break;
                case 'sougou_mobile':
                    $sougou_mobile_sType = '7030';
                    $sougou_mobile_keyword[] = $value['keyword'];
                    $sougou_mobile_url[] = $url;
                    $sougou_mobile_id[] = $value['id'];
                    break;
                case 'shenma':
                    $shenma_sType = '7070';
                    $shenma_keyword[] = $value['keyword'];
                    $shenma_url[] = $url;
                    $shenma_id[] = $value['id'];
                    break;
                default:
                    $baidu_sType = '1010';
                    $baidu_keyword[] = $value['keyword'];
                    $baidu_url[] = $url;
                    $baidu_id[] = $value['id'];
            }
        }


        $count = 0;
        echo '<pre>';
        if(isset($baidu_keyword))
        {
            echo 'baidu => '.count($baidu_keyword).'<br>';
            $count += count($baidu_keyword);
        }
        if(isset($baidu_mobile_keyword))
        {
            echo 'baidu_mobile => '.count($baidu_mobile_keyword).'<br>';
            $count += count($baidu_mobile_keyword);
        }
        if(isset($abc_keyword))
        {
            echo 'abc => '.count($abc_keyword).'<br>';
            $count += count($abc_keyword);
        }
        if(isset($abc_mobile_keyword))
        {
            echo 'abc_mobile => '.count($abc_mobile_keyword).'<br>';
            $count += count($abc_mobile_keyword);
        }
        if(isset($sougou_keyword))
        {
            echo 'sougou => '.count($sougou_keyword).'<br>';
            $count += count($sougou_keyword);
        }
        if(isset($sougou_mobile_keyword))
        {
            echo 'sougou_mobile => '.count($sougou_mobile_keyword).'<br>';
            $count += count($sougou_mobile_keyword);
        }
        if(isset($shenma_keyword))
        {
            echo 'shenma => '.count($shenma_keyword).'<br>';
            $count += count($shenma_keyword);
        }
        echo 'count => '.$count.'<br>';
        echo '</pre>';
//        die();

        $time = time();
        // baidu
        if(isset($baidu_keyword))
        {
            $baidu_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$baidu_keyword,
                    "url"=>$baidu_url,
                    "searchType"=>$baidu_sType
                )
            );
        }

        // baidu_mobile
        if(isset($baidu_mobile_keyword))
        {
            $baidu_mobile_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$baidu_mobile_keyword,
                    "url"=>$baidu_mobile_url,
                    "searchType"=>$baidu_mobile_sType
                )
            );
        }

        // 360
        if(isset($abc_keyword))
        {
            $abc_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$abc_keyword,
                    "url"=>$abc_url,
                    "searchType"=> $abc_sType
                )
            );
        }
        // 360_mobile
        if(isset($abc_mobile_keyword))
        {
            $abc_mobile_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$abc_mobile_keyword,
                    "url"=>$abc_mobile_url,
                    "searchType"=> $abc_mobile_sType
                )
            );
        }

        // sougou
        if(isset($sougou_keyword))
        {
            $sougou_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$sougou_keyword,
                    "url"=>$sougou_url,
                    "searchType"=>$sougou_sType
                )
            );
        }
        // sougou_mobile
        if(isset($sougou_mobile_keyword))
        {
            $sougou_mobile_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$sougou_mobile_keyword,
                    "url"=>$sougou_mobile_url,
                    "searchType"=>$sougou_mobile_sType
                )
            );
        }

        // shenma
        if(isset($shenma_keyword))
        {
            $shenma_param=json_encode(
                array(
                    "userId"=>111311,
                    "time"=>$time,
                    "apiExtend"=>1,
                    "businessType"=>1006,
                    "keyword"=>$shenma_keyword,
                    "url"=>$shenma_url,
                    "searchType"=>$shenma_sType
                )
            );
        }

        if(isset($baidu_keyword)) $this->request_youbangyun($baidu_param,$baidu_id);

        if(isset($baidu_mobile_keyword)) $this->request_youbangyun($baidu_mobile_param,$baidu_mobile_id);

        if(isset($abc_keyword)) $this->request_youbangyun($abc_param,$abc_id);

        if(isset($abc_mobile_keyword)) $this->request_youbangyun($abc_mobile_param,$abc_mobile_id);

        if(isset($sougou_keyword)) $this->request_youbangyun($sougou_param,$sougou_id);

        if(isset($sougou_mobile_keyword)) $this->request_youbangyun($sougou_mobile_param,$sougou_mobile_id);

        if(isset($shenma_keyword)) $this->request_youbangyun($shenma_param,$shenma_id);


    }

    public function request_youbangyun($wParam,$id){

        $url = "http://api.youbangyun.com/api/customerapi.aspx";
        $wAction='AddSearchTask';
        $api = 'BA046609BA6EFFB023B27B6488C3ADC6';
        $wSign =md5($wAction.$api.$wParam);
        $post_data = array ("wAction" =>$wAction,"wParam" => urlEncode($wParam),"wSign" =>$wSign );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); // post数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // post的变量
        $output = json_decode(curl_exec($ch),true);
        curl_close($ch);
//        header("Content-Type:text/html; charset=utf-8");

//打印获得的数据

        foreach ($output['xValue'] as $val){
            $outputId[] = $val[0];
        }
        $acombine = array_combine($id,$outputId);
        $display_order = $acombine;
        $ids = implode(',', array_keys($display_order));
        $sql = "UPDATE mt_seo_keyword SET taskId = CASE id ";
        foreach ($display_order as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE id IN ($ids)";

//        echo '<pre>';
//        print_r($output);
//        echo 'count : ',count($display_order).'<br>';
//        print_r($display_order);
//        echo '<br>';
//        print_r($sql);
//        echo '<br>';
//        echo '</pre>';

        if(count($display_order))
        {
            $resule = DB::update($sql);
        }
    }




    /*
     *
     */
    //接受通知 更新关键词排行
    public function receive_from_youbangyun()
    {
        header("Content-Type:text/html;charset=UTF-8");
//        iconv("GB2312","UTF-8");

        $xAction = request("xAction",'');
        $xParam = request("xParam",'');
        $xSign = request("xSign",'');


        $xParam_decode = json_decode($xParam,true);

        $dataTaskId = $xParam_decode["Value"]["TaskId"];
        $dataRankFirst = $xParam_decode["Value"]["RankFirst"];
        $dataRankLast = $xParam_decode["Value"]["RankLast"];
        $dataRankLastChange = $xParam_decode["Value"]["RankLastChange"];
        $dataUpdateTime = $xParam_decode["Value"]["UpdateTime"];

        $rank = $dataRankLast;
//        dd($rank);
//        dd($dataTaskId);

        $temp = new Temp;
        $temp_data['title'] = $dataTaskId;
        $temp_data['content'] = $xParam;
        $bool_0 = $temp->fill($temp_data)->save();
//        echo 1;
        return 1;

        $time = date('Y-m-d H:i:s');
        $current_time = date('Y-m-d H:i:s');
        $current_date = date('Y-m-d');

        $keyword = SEOKeyword::where('taskId',$dataTaskId)->first();
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");
        $price = $keyword->price;


        // 判断是否重复记录
        if(date("Y-m-d",strtotime($keyword->detectiondate)) == $current_date)
        {
            if($keyword->latestranking > 0 and $keyword->latestranking <= $rank)
            {
//                echo 1;
                return 1;
            }
        }


        DB::beginTransaction();
        try
        {
            // 【STEP 1】添加【检测表】
            $DetectRecord = SEOKeywordDetectRecord::where(['keywordid'=>$keyword->id])->whereDate('detect_time',$current_date)->first();
            if(!$DetectRecord)
            {
                $DetectRecord = new SEOKeywordDetectRecord;

                $DetectRecord_data['owner_id'] = $keyword->createuserid;
                $DetectRecord_data['ownuserid'] = $keyword->createuserid;
                $DetectRecord_data['createuserid'] = $keyword->createuserid;
                $DetectRecord_data['createusername'] = $keyword->createusername;
                $DetectRecord_data['createtime'] = $current_time;
                $DetectRecord_data['keywordid'] = $keyword->id;
                $DetectRecord_data['keyword'] = $keyword->keyword;
                $DetectRecord_data['website'] = $keyword->website;
                $DetectRecord_data['searchengine'] = $keyword->searchengine;
                $DetectRecord_data['detect_time'] = $current_time;
                $DetectRecord_data['rank'] = $rank;
                $DetectRecord_data['rank_original'] = $rank;
                $DetectRecord_data['rank_real'] = $rank;
                $DetectRecord_data['detectdata'] = $xParam;

                $bool_1 = $DetectRecord->fill($DetectRecord_data)->save();
                if(!$bool_1) throw new Exception("insert--detect-record--fail");
            }
            else
            {
                $DetectRecord_rank = $DetectRecord->rank;
                if($rank > 0 and $rank < $DetectRecord_rank)
                {
                    $DetectRecord->rank = $rank;
                    $DetectRecord->save();
                }
            }


            // 【STEP 2】更新【关键词表】
            // [Condition A] 已检测
            if(date("Y-m-d",strtotime($keyword->detectiondate)) == $current_date)
            {
                // [odd=1-10]
                if($keyword->latestranking > 0 and $keyword->latestranking <= 10)
                {
                    // [odd=1-10][new=1-10]
                    if($rank > 0 or $rank <= 10)
                    {
                        // [old=1-10][new=1-10][new < old]
                        if($rank < $keyword->latestranking)
                        {
                            $keyword->latestranking = $rank;
                            $bool = $keyword->save();
                            if(!$bool) throw new Exception("update--keyword--fail");
//                            echo 1;
                            return 1;
                        }
                        else // [odd=1-10][new=1-10][new > old]
                        {
//                            echo 1;
                            return 1;
                        }
                    }
                    else // [odd=1-10][new=10+]
                    {
//                        echo 1;
                        return 1;
                    }
                }
                else // [old=10+]
                {
                    // [old=10+][new=1-10]
                    if($rank > 0 or $rank <= 10)
                    {
                        $keyword->latestranking = $rank;
                        $keyword->detectiondate = $current_time; // 检测时间
                        $keyword->standarddate = $current_time;// 达标时间
                        $keyword->standardstatus = '已达标';// 达标状态
                        $keyword->latestconsumption = $keyword->price; // 最新消费

//                    // [method A]
//                    $keyword->standarddays = $keyword->standarddays + 1;// 达标天数+1
//                    $keyword->totalconsumption = $keyword->totalconsumption + $keyword->price; // 累计消费+price
//                    $keyword->standard_days_1 = $keyword->standard_days_1 + 1;// 达标天数+1
//                    $keyword->standard_days_2 = $keyword->standard_days_2 + 1;// 达标天数+1
//                    $keyword->consumption_total = $keyword->consumption_total + $keyword->price; // 累计消费+price

                        // [method B]
                        $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                        $detect_standard_count = $query_detect->count('*');
                        $detect_standard_consumption_sum = $detect_standard_count * $keyword->price;

                        $keyword->standarddays = $keyword->standarddays + 1;// 达标天数+1
                        $keyword->totalconsumption = $keyword->totalconsumption + $keyword->price; // 累计消费+price

                        $keyword->standard_days_1 = $detect_standard_count;// 统计达标天数
                        $keyword->standard_days_2 = $$detect_standard_count;// 统计达标天数
                        $keyword->consumption_total = $detect_standard_consumption_sum; // 统计累计消费

                        if(!$keyword->firststandarddate)
                        {
                            // 如果关键词是首次达标，则需要冻结该关键词90天，90天内不能解冻，并且冻结30天的费用
                            $keyword->firststandarddate = $current_time;// 首次达标时间

                            // 冻结费用
                            $freeze_funds = $keyword->price * 30;
                            $keyword->freezefunds = $freeze_funds;

                            // 冻结关键词90天，90天之后的日期:允许解冻日期
                            $unfreeze_date = date("Y-m-d H:i:s",strtotime("+90 day"));
                            $keyword->unfreezedate = $unfreeze_date;
                        }

                        $bool = $keyword->save();
                        if(!$bool) throw new Exception("update--keyword--fail");
                    }
                    else // [old=10+][new=10+]
                    {
                        // [old=10+][new=10+][new < old]
                        if($rank > 0 and ($rank < $keyword->latestranking))
                        {
                            $keyword->latestranking = $rank;
                            $bool = $keyword->save();
                            if(!$bool) throw new Exception("update--keyword--fail");
//                            echo 1;
                            return 1;
                        }
                        else // [old=10+][new=10+][new > old]
                        {
//                            echo 1;
                            return 1;
                        }
                    }
                }
            }

            // [Condition B] 未检测
            if(date("Y-m-d",strtotime($keyword->detectiondate)) != $current_date)
            {
                // 第一次检测，初始排名+随机10-15
                if(!$keyword->detectiondate)
                {
                    $keyword->initialranking = $rank + rand(10,15);
                }

                if($rank > 0 and $rank <= 10)
                {
                    $keyword->latestranking = $rank;
                    $keyword->detectiondate = $current_time; // 检测时间
                    $keyword->standarddate = $current_time;// 达标时间
                    $keyword->standardstatus = '已达标';// 达标状态
                    $keyword->latestconsumption = $keyword->price; // 最新消费

                    // [method A]
                    $keyword->standarddays = $keyword->standarddays + 1;// 达标天数+1
                    $keyword->standard_days_1 = $keyword->standard_days_1 + 1;// 达标天数+1
                    $keyword->standard_days_2 = $keyword->standard_days_2 + 1;// 达标天数+1
                    $keyword->totalconsumption = $keyword->totalconsumption + $keyword->price; // 累计消费+price
                    $keyword->consumption_total = $keyword->consumption_total + $keyword->price; // 累计消费+price

//                    // [method B]
//                    $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
//                    $detect_standard_count = $query_detect->count('*');
//                    $detect_standard_consumption_sum = $detect_standard_count * $keyword->price;
//
//                    $keyword->standarddays = $keyword->standarddays + 1;// 达标天数+1
//                    $keyword->totalconsumption = $keyword->totalconsumption + $keyword->price; // 累计消费+price
//
//                    $keyword->standard_days_1 = $detect_standard_count;// 统计达标天数
//                    $keyword->standard_days_2 = $$detect_standard_count;// 统计达标天数
//                    $keyword->consumption_total = $detect_standard_consumption_sum; // 统计累计消费

                    if(!$keyword->firststandarddate)
                    {
                        // 如果关键词是首次达标，则需要冻结该关键词90天，90天内不能解冻，并且冻结30天的费用
                        $keyword->firststandarddate = $current_time;// 首次达标时间

                        // 冻结费用
                        $freeze_funds = $keyword->price * 30;
                        $keyword->freezefunds = $freeze_funds;

                        // 冻结关键词90天，90天之后的日期:允许解冻日期
                        $unfreeze_date = date("Y-m-d H:i:s",strtotime("+90 day"));
                        $keyword->unfreezedate = $unfreeze_date;
                    }
                }
                else
                {
                    $keyword->latestranking = $rank;
                    $keyword->detectiondate = $current_time; // 检测时间
                    $keyword->standardstatus = '未达标';// 达标状态
                    $keyword->latestconsumption = 0; // 最新消费
                }

                $bool = $keyword->save();
                if(!$bool) throw new Exception("update--keyword--fail");
            }


            // 【STEP 3】添加【消费记录表】 & 更新【用户-资产表】
            if($rank > 0 and $rank <= 10)
            {
                $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$current_date)->first();
                if(!$ExpenseRecord)
                {
                    $ExpenseRecord = new ExpenseRecord;
                    $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                    $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                    $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                    $ExpenseRecord_data['standarddate'] = $current_time;
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

                        $keyword_owner = User::find($keyword->createuserid)->lockForUpdate();

                        $keyword_owner->fund_expense = $keyword_owner->fund_expense + $price;
                        $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 + $price;
                        $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 + $price;
                        $keyword_owner->fund_balance = $keyword_owner->fund_balance - $price;
                        if($keyword_owner->fund_frozen >= $price)
                        {
                            $keyword_owner->fund_frozen = $keyword_owner->fund_frozen - $price;
                        }
                        else
                        {
                            $keyword_owner->fund_frozen = 0;
                            $keyword_owner->fund_available = $keyword_owner->fund_available - ($price - $keyword_owner->fund_frozen);
                        }

                        $keyword_owner->save();
                    }
                    else throw new Exception("update--expense-record--fail");
                }
                else
                {
                    $ExpenseRecord->detect_id = $DetectRecord->id;
                    $ExpenseRecord->save();
                }
            }

            DB::commit();
//            echo 1;
            return 1;
//            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            echo 2;
            return response_fail([],$msg);
        }

    }

    /**
     * 关键词检测回调接口
     * 1、首次达标就冻结30天的费用
     * 2、达标的费用从冻结的资金中进行扣费，并且一个账号的全部冻结资金是公用的，如果冻结资金消耗完了，就从余额总进行扣除
     *
     * @return string 资金申请信息处理结果
     * @author zhangss
     */
    public function receive_keywords_rank($data_id,$keywords,$type,$rank,$url){
        Log::write("============================== 关键词检测接口调试 ==============================");
        // 关键词模型
        $modelKeyword 				= D('Biz/Keyword');
        // 关键词检测模型
        $modelKeyworddetectrecord 	= D('Biz/Keyworddetectrecord');
        // 关键词达标扣费模型
        $modelStandardfee 			= D('Biz/Standardfee');
        // 资金账户模型
        $modelFunds 				= D('Biz/Funds');
        // 资金账户冻结模型
        $modelFundsfreeze 			= D('Biz/Fundsfreeze');

        Log::write("---------------/--------------- 原始数据POST：".json_encode($_POST));
        Log::write("-----------------/------------- 原始数据REQUEST：".json_encode($_REQUEST));
        //Log::write("------------------------------ input：".json_encode(file_get_contents("php://input")));

//        // 1.=========================== 从请求中获取参数 ===========================
//        $token 		= $_POST['token'];
//        $data_id	= $_POST['data_id'];
//        $keywords	= $_POST['keywords'];
//        $type		= $_POST['type'];
//        $rank		= $_POST['rank'];
//        $url		= $_POST['url'];
//
//        $token 		= $_REQUEST['token'];
//        $data_id	= $_REQUEST['data_id'];
//        $keywords	= $_REQUEST['keywords'];
//        $type		= $_REQUEST['type'];
//        $rank		= $_REQUEST['rank'];
//        $url		= $_REQUEST['url'];

        /*测试数据*/
        /*
        $token 		= "eadf855b997f06e7a236c3c7695a8954";
        $data_id	= 4;
        $keywords	= "seo";
        $type		= 1;
        $rank		= 15;
        $url		= "10jrw.com";
        */


        // 没有任务ID
        if( !$data_id ){
            $return['ret'] 		= -1;
            $return['message'] 	= '任务ID为空';
            exit(json_encode( $return ));
        }

        // 2.=========================== 根据data_id来获取关键词的详细信息，并进行相关的校验 ===========================
        // 查询搜索任务：值查询满足条件的任务
        $map_kw['id'] 				= $data_id;
        $map_kw['status'] 			= 1;
        $map_kw['keywordstatus'] 	= '优化中';
        $data_kw = $modelKeyword -> selectOne( $map_kw );
        unset( $map_kw );
        //判断$token是否相同
        /*
        if( $token != $data_kw['detect_token']){
            $return['ret'] 		= -1;
            $return['message'] 	= 'token校验不正确';
            exit(json_encode( $return ));
        }
        //判断关键词是否相同
        if( $keywords != $data_kw['keyword']){
            $return['ret'] 		= -1;
            $return['message'] 	= '关键词校验不正确';
            exit(json_encode( $return ));
        }*/

        //判断url是否相同
// 		if( $url != $data['website']){
// 			$return['ret'] 		= -1;
// 			$return['message'] 	= 'url验证不正确';
// 			exit(json_encode( $return ));
// 		}
        // 当前的时间：达标时间
        $date_cur 		= date('Y-m-d H:i:s');
        // 判断最新达标日期是否是今天，如果是今天表示今日已经达标了，不能再重复进行扣费
        if( substr( $data_kw['standarddate'],0,10 ) == substr( $date_cur,0,10 ) ){
            $return['ret'] 		= 1;
            $return['message'] 	= '成功';
            return json_encode( $return );
        }

        // 不是优化中的不检测
        if( $data_kw['keywordstatus'] != '优化中' ){
            $return['ret'] 		= 1;
            $return['message'] 	= '成功';
            return json_encode( $return );
        }

        //Add by echo 2018-03-29 如果非初次检测,排名不在首页,10点之前 直接跳过

//        if(date('H')<10 && !$data_kw['detectiondate'] && $rank>10){
//            $return['ret'] 		= 1;
//            $return['message'] 	= '成功';
//            return json_encode( $return );
//        }

        // 3.=========================== 验证通过后，进行数据处理 ===========================
        // 真实排名
        $rank_real	= $rank;
        // 判断是否是首次进行关键词的检测
        if( !$data_kw['detectiondate'] ){

            // 如果是首次进行检测，需要更新初始排名,将排加上10
            // update By Richer 于2017年6月9日17:27:46 所有初始排名加10
            // update By Richer 于2017年7月25日10:43:23 所有的初始排名加10到15之间的随机数
            $num = rand(10, 15) ;
            if( $rank > 0 ){
                $rank 	= $rank + $num;//
            }
            // 设置初始排名
            $kw['initialranking'] 	= $rank;// 初始排名
        }



        // 4.=========================== 更新检测记录表中的数据  ===========================
        // 更新检测记录表中的数据
        $model_keyword_detect_record = D('keyworddetectrecord');
        $today = date("Y-m-d",time());
        $sql_record = $model_keyword_detect_record->where("keyword = '{$data_kw['keyword']}' and website = '{$data_kw['website']}' and searchengine = '{$data_kw['searchengine']}' and createtime LIKE '{$today}%'")->find();
        if(!$sql_record)
        {
//            echo "keyword_detect_record not exist";
            $result = $modelKeyworddetectrecord -> updateRecord( $rank,$rank_real,$data_kw['id'], $data_kw['keyword'], $data_kw['website'], $data_kw['searchengine'], $data_kw['createuserid'] );
            //dump($modelKeyworddetectrecord -> _sql());
        }




        // 5.=========================== 组合关键词公共信息  ===========================
        // 组合关键词公共部分
        $kw['id'] 				= $data_id;
        $kw['detectiondate'] 	= $date_cur;// 检测时间
        $kw['latestranking'] 	= $rank;// 最新排名
        $kw['is_detect'] 		= 1;// 已经通过检测接口进行了检测

        // 如果关键词达标
        if( $rank <= 10 && $rank  > 0){

            // 6.=========================== 获取资金账户信息  ===========================
            // 获取资金账户信息
            $data_funds  	= $modelFunds -> selectOne( array('userid' => $data_kw['createuserid'] ));

            // 7.=========================== 组合关键词其他信息  ===========================
            // 剩余冻结资金
            $freezefunds = $data_funds['freezefunds'];
            $kw['standarddate'] 		= $date_cur;// 达标时间
            $kw['standardstatus'] 		= '已达标';// 达标状态
            $kw['latestconsumption'] 	= $data_kw['price']; // 最新消费
            $kw['standarddays'] 		= $data_kw['standarddays'] + 1;// 达标天数
            $kw['totalconsumption'] 	= $data_kw['totalconsumption'] + $data_kw['price']; // 累计消费

            // 判断是否是首次达标
            if ( !$data_kw['firststandarddate'] ){
                // 如果关键词是首次达标，则需要冻结该关键词90天，90天内部能解冻，并且冻结30天的费用
                // 冻结费用
                // $freezefunds = $data_kw['price'] * 30;
                // 90天之后的日期:允许解冻日期
                $unfreezedate = date("Y-m-d H:i:s",strtotime("+90 day"));

                //冻结关键词90天 ====================================>
                $kw['firststandarddate'] 	= $date_cur;// 首次达标时间
                //$kw['standarddays'] 		= 1;// 达标天数
                $kw['unfreezedate'] 		= $unfreezedate;// 解冻日期
            }

            // 8.=========================== 更新关键词信息  ===========================
            // 更新关键词
            $result = $modelKeyword -> update($kw);
            //dump($modelKeyword -> _sql());
            Log::write("------------------------------ 更新关键词：". $modelKeyword -> _sql());


            // 9.=========================== 更新资金账户信息  ===========================

            // 更新资金账户更新消费记录 ================>

            // 更新资金账户 ：需要将冻结的资金全部消耗完毕，然后再从余额中进行消耗================>
            // 如果冻结资金已经小于当前的关键词单价，那么关键词的消耗要从资金余额中进行扣除
            // 判断是否还有冻结资金
            if( $freezefunds > 0 ){
                // 如果还有冻结资金，
                // 判断冻结资金是否已经小于关键词的单价
                if( $freezefunds <= $data_kw['price'] ){

                    // 将冻结资金设置为 0
                    $funds['freezefunds'] 			= 0;
                    // 资金可用余额 :冻结资金消耗完之后从可用余额中扣除
                    $funds['availablefunds'] 		= $data_funds['availablefunds']  - $data_kw['price'] + $freezefunds; //array('exp', "balancefunds - {$data_kw['price']}" );// 充值金额减去消费金额

                }else{
                    // 冻结资金还未消耗完毕，从冻结资金中扣除，此时资金可用余额不变
                    $funds['freezefunds'] 			= $data_funds['freezefunds'] 	- $data_kw['price'];// 关键词达标扣费需要从冻结费用中进行扣除
                    $funds['availablefunds'] 		= $data_funds['availablefunds'] ;
                    // $funds['balancefunds'] 			= $data_funds['balancefunds']   - $data_kw['price']; //array('exp', "balancefunds - {$data_kw['price']}" );// 充值金额减去消费金额
                }

            }else{
                // 资金可用余额 :冻结资金消耗完之后从可用余额中扣除
                $funds['availablefunds'] 		= $data_funds['availablefunds']  - $data_kw['price'] ; //array('exp', "balancefunds - {$data_kw['price']}" );// 充值金额减去消费金额
                $funds['freezefunds'] 			= 0;

            }

            // 资金余额：等于资金可用余额加上资金冻结金额
            $funds['balancefunds'] 			= $funds['availablefunds']  + $funds['freezefunds'];

            // update By Richer 于2017年9月1日16:59:37  解决冻结资金出现负数的问题
            if( $funds['freezefunds'] < 0 ){
                $funds['freezefunds']  = 0;
                $funds['availablefunds'] = $funds['balancefunds'] ;
            }

            $modelFunds -> where( array('id' => $data_funds['id'] )) -> save( $funds );
            //	dump($modelFunds -> _sql());
            Log::write("------------------------------ 更新资金账户：". $modelFunds -> _sql());



            // 往達標消費記錄中增加一條消費記錄 ================>
            $standardfee['siteid'] 			= $data_kw['siteid'];
            $standardfee['keywordid'] 		= $data_id;
            $standardfee['keyword'] 		= $keywords;
            $standardfee['price'] 			= $data_kw['price'];
            $standardfee['ownuserid'] 		= $data_kw['createuserid'];
            $standardfee['standarddate'] 	= $date_cur;


            $model_standardfee = D('standardfee');
            $today = date("Y-m-d",time());
            $sql_standardfee_record = $model_standardfee->where("siteid = '{$data_kw['siteid']}' and keywordid = '{$data_id}' and keyword = '{$keywords}' and standarddate LIKE '{$today}%'")->find();
            if(!$sql_standardfee_record)
            {
//                echo "standard_fee not exist";
                $modelStandardfee -> insert( $standardfee );
                //dump($modelStandardfee -> _sql());
                Log::write("---------------/--------------- 达标消费记录中增加信息：". $modelStandardfee -> _sql());
            }

        }else{
            $kw['standardstatus'] 		= '未达标';// 达标状态
            $kw['latestconsumption'] 	= 0; // 最新消费
            $result = $modelKeyword -> update($kw);
            Log::write($modelKeyword -> _sql());
        }


        $return['ret'] 		= 1;
        $return['message'] 	= '成功';
        return json_encode( $return );
    }








    // 查询关键词
    public function search_keyword()
    {
//        $t0=(int)microtime(true)*1000; // 开始的时间
        $keyword_arr  = ['如未科技','小米科技'];
//        // 将关键词以逗号隔开
//        $keywords= implode(',',$keyword_arr);
//        // 百度指数查询
//        $url_index = 'http://api.91cha.com/index?key=456a38a7a22f41a0ae3829ec1ccb7fc1&kws='.urlencode($keywords);
////        dd($url_index);
//
//        $t1=(int)microtime(true)*1000; // 开始的时间
//
//        $data_index = json_decode( file_get_contents($url_index), true);
//
//        $t2=(int)microtime(true)*1000; // 结束的时间
//
//        echo $t0."<br>";
//        echo $t1."<br>";
//        echo $t2."<br>";
//        echo round(($t1-$t0),4)."<br>";
//        echo round(($t2-$t1),4)."<br>";
//        dd($data_index);

        return $this->search($keyword_arr);
    }


    /**
     * 搜索关键词
     *
     * 通过第三方接口搜索关键词
     *
     * @accesspublic
     */
    public function search1(){

        $model 		= D( $this->modelName);

        $kws = $_GET['kws'];
        $keywords = $_GET['keywords'];

        if( $kws )
        {
            // 将回车换行替换成逗号
            $keywords = str_replace(array("\r\n", "\r", "\n"), ",", $kws);

        }
        else if( $keywords )
        {
            //将回车换行替换成都好
            $kws = str_replace(",","\r\n", $keywords);
            $kws = str_replace(",","\r", $keywords);
            $kws = str_replace(",","\n", $keywords);
        }

        $keyword_arr = explode(',' , $keywords );
        // 去重空值
        $keyword_arr = array_filter( $keyword_arr );
        // 去重操作
        $keyword_arr = array_values(array_unique( $keyword_arr ));

        $this -> assign('kws',$kws);
        $this -> assign('keywords',$keywords);

        // 如果关键词存在才进行查询
        if( $keywords ){
            $list = $model -> search( $keyword_arr );
        }

        $this -> assign('list',$list);

        $this->display();
    }


    public function search( $keyword_arr )
    {
        $KeywordLengthPriceIndexOptions = config('seo.KeywordLengthPriceIndexOptions');

        $searchengine_keys = array_keys($KeywordLengthPriceIndexOptions);

        //组成字符
        $keywords = implode(',' , $keyword_arr);

        //
        foreach ( $keyword_arr as $key => $vo ){

            // 去掉关键词前后的空额
            //$vo = strtolower(trim($vo));
            $vo = trim($vo);
            $replace = array(" ","　","\n","\r","\t");
            $vo = str_replace($replace, "", $vo);
            $temp['keyword'] = $vo;
            foreach ( $searchengine_keys as $vo2 )
            {
                $temp[$vo2] = 0;
            }
            $arr[] = $temp;
        }

        $list = $this -> combKeywordSearchResults( $arr );

        return $list;
    }


    /**
     * 搜索关键词:根据用户的关键词搜索推荐的关键词
     *
     * 通过第三方接口搜索关键词:由于第三方的接口一下只能提交10个关键词，需要将关键词进行等分
     *
     * @accesspublic
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
            $data_index = json_decode( file_get_contents($url_index), true);
            //$data_index = json_decode( file_get_contents($url_index));

            //$data_index = file_get_contents($url_index);


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
                    $rate = '<span style="color:red;font-size:20px;">';
                    for($i =1 ;$i<=$difficulty_index;$i++)
                    {
                        $rate .='★';
                    }
                    $rate .= '</span>';
                }

                $rate_diff = 5 - $difficulty_index;
                if($rate_diff > 0 )
                {
                    $rate .= '<span style="font-size:20px;">';
                    for($i=1;$i<= $rate_diff;$i++)
                    {
                        $rate .= '☆';
                    }
                    $rate .= '</span>';
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





}
