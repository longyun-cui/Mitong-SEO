<?php
namespace App\Http\Controllers\MT\TEST;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;

use App\Repositories\MT\TEST\TestRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class TestController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new TestRepository;
    }


    // 返回【主页】视图
    public function index()
    {

//        dd(date("Y-m-d"));
//        dd(date("Y-m-d",strtotime("-26 day")));

        $sql ="select a.keywordid, a.rank - b.rank as diff,a.keyword,a.rank as rank_yesterday, b.rank as rank_today,a.website,a.searchengine from (select keywordid,rank,keyword,website,searchengine from mt_seo_keyword_detect_record WHERE detect_time like '".date("Y-m-d",strtotime("-1 day"))."%' and rank > -1 ) a, (select `keywordid`,`rank` from mt_seo_keyword_detect_record WHERE detect_time like '".date('Y-m-d')."%' and rank > -1 ) b where a.keywordid = b.keywordid and (a.rank - b.rank != 0) and a.rank > 0 AND a.rank <= 10";


        $data_0 = DB::select($sql);

//        $query = SEOKeyword::select('*')
//            ->with([
//                'creator',
//                'detects'=>function($query) {
//                    $query->whereDate('detect_time','>',date("Y-m-d",strtotime("-8 day")));
//                }
//                ])
//            ->where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'未达标']);
//            ->whereDate('detectiondate',date("Y-m-d"));

//        $data = $query->orderby('id','desc')->get()->toArray();
//        dd($data);

//        $keyword_data = SEOKeyword::first(
//            array(
//                \DB::raw('COUNT(*) as keyword_count'),
//                \DB::raw('SUM(standarddays) as standarddays_sum'),
//                \DB::raw('SUM(standard_days_1) as standard_days_sum'),
//                \DB::raw('SUM(totalconsumption) as totalconsumption_sum'),
//                \DB::raw('SUM(standard_days * price) as standard_days_consumption_sum')
//            )
//        )->toArray();
//
//        $test_data["keyword_count"] = number_format($keyword_data["keyword_count"]);
//        $test_data["standarddays_sum"] = number_format($keyword_data["standarddays_sum"]);
//        $test_data["standard_days_sum"] = number_format($keyword_data["standard_days_sum"]);
//        $test_data["totalconsumption_sum"] = number_format($keyword_data["totalconsumption_sum"]);
//        $test_data["standard_days_consumption_sum"] = number_format($keyword_data["standard_days_consumption_sum"]);
//
//
//        $keyword_data = User::first(
//            array(
//                \DB::raw('COUNT(*) as keyword_count'),
//                \DB::raw('SUM(fund_expense) as fund_expense_sum'),
//                \DB::raw('SUM(fund_expense_2) as fund_expense_2_sum'),
//                \DB::raw('SUM(fund_balance) as fund_balance'),
//                \DB::raw('SUM(standard_days * price) as standard_days_consumption_sum')
//            )
//        )->toArray();

        return view('mt.admin.test')->with('test_data',$data_0);
    }



    // 返回【主页】视图
    public function repeat()
    {
        $date_today = date("Y-m-d");
        echo "【Today】".$date_today;
        echo "<br>";

        $a = "2019-07-33 02:58:45";
        $date_a = date("Y-m-d",strtotime($a));
        echo "【A】".$a."--".$date_a;
        echo "<br>";

        $b = "2020-01-01 12:30:22";
        $date_b = date("Y-m-d",strtotime($b));
        echo "【B】".$b."--".$date_b;
        echo "<br>";

        if($date_a > $date_b) echo "date_a > date_b";
        else if($date_a < $date_b) echo 'date_a < date_b';
        else echo "a=b";
        echo "<br>";

//        $expenses = ExpenseRecord::with('detects')->has('detects', '>=', 1)->get();
//        dd($expenses->toArray());
    }


    // 返回主页视图
    public function statistics()
    {
        $query1 = ExpenseRecord::select('id','price','createtime')->whereYear('createtime','2020')->whereMonth('createtime','01');
        $query = ExpenseRecord::select('id','price','createtime');
        $count = $query->count("*");
        $sum = $query->sum("price");
        $data = $query->groupBy(DB::raw("STR_TO_DATE(createtime,'%Y-%m')"))
            ->select(
                DB::raw("
                    STR_TO_DATE(createtime,'%Y-%m-%d') as date,
                    DATE_FORMAT(createtime,'%Y-%m') as month,
                    DATE_FORMAT(createtime,'%d') as day,
                    sum(price) as sum,
                    count(*) as count
                "))
            ->get();
        dd($data->keyBy('month')->sortByDesc('month')->toArray());
        $data = $query->get();

        $grouped = $data->groupBy(function ($item, $key) {
            return date("Y-m-d",strtotime($item['createtime']));
        });
        dd($grouped->toArray());


        $index_data = [];
//        return view('mt.admin.index')->with('index_data',$index_data);

        dd($count."--".$sum);
        dd($data->toArray());
    }


    // 返回主页视图
    public function temp()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $userpass = $user->userpass;
            $pass_decrypt = basic_decrypt($user->userpass);
            $user->password = password_encode($pass_decrypt);
            $user->save();
        }
    }


    // 修改密码
    public function update_password()
    {
        $users = User::all();
        foreach ($users as $user)
        {
//            $userpass = $user->userpass;
//            $pass_decrypt = basic_decrypt($userpass);
//            $user->password_1 = trim($pass_decrypt);
//            $user->password = password_encode($pass_decrypt);

            if($user->password_1)
            {
                $user->password_1 = str_replace( chr(1), '',$user->password_1 );
                $user->password_1 = str_replace( chr(2), '',$user->password_1 );
                $user->password_1 = str_replace( chr(3), '',$user->password_1 );
                $user->password_1 = str_replace( chr(4), '',$user->password_1 );
                $user->password_1 = str_replace( chr(5), '',$user->password_1 );
                $user->password_1 = str_replace( chr(6), '',$user->password_1 );
                $user->password_1 = str_replace( chr(7), '',$user->password_1 );
                $user->password_1 = str_replace( chr(8), '',$user->password_1 );
                $user->password_1 = str_replace( chr(9), '',$user->password_1 );
                $user->password_1 = trim($user->password_1);
                $user->password = password_encode(trim($user->password_1));
                $user->save();
                echo $user->id.'--'.$user->password_1.'--'.$user->password."<br>";
            }
            else
            {
                echo $user->id.'--null'."<br>";
            }
        }
    }




    // 补加【消费记录表】数据
    public function fill_expense()
    {
        $detect_list = SEOKeywordDetectRecord::select('id','owner_id','ownuserid','expense_id','keywordid','rank','createuserid','detect_time','createtime')
            ->where("expense_id",0)->where("rank",">",0)->where("rank","<=",10)
            ->limit(1000)->orderby("id","asc")->get();
        echo $detect_list->count()."</br>";

        $count = 1;
        foreach($detect_list as $k => $detect)
        {
            $keyword_id = $detect->keywordid;
            $keyword = SEOKeyword::find($keyword_id);
            $detect_time = $detect->detect_time;
            $detect_date = explode(" ",trim($detect_time))[0];

            DB::beginTransaction();
            try
            {
                $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword_id])->whereDate('standarddate',$detect_date)->first();
                if(!$ExpenseRecord)
                {
                    $ExpenseRecord = new ExpenseRecord;
                    $ExpenseRecord_data['detect_id'] = $detect->id;
                    $ExpenseRecord_data['owner_id'] = $detect->ownuserid;
                    $ExpenseRecord_data['ownuserid'] = $detect->ownuserid;
                    $ExpenseRecord_data['standarddate'] = $detect_time;
                    $ExpenseRecord_data['createtime'] = $detect_time;
                    $ExpenseRecord_data['siteid'] = $keyword->siteid;
                    $ExpenseRecord_data['keywordid'] = $keyword->id;
                    $ExpenseRecord_data['keyword'] = $keyword->keyword;
                    $ExpenseRecord_data['price'] = (int)$keyword->price;
                    $bool_1 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                    if(!$bool_1) throw new Exception("update--expense-record--fail");
                }
                else
                {
                    $ExpenseRecord->detect_id = $detect->id;
                    $ExpenseRecord->save();
                }

                $detect->expense_id = $ExpenseRecord->id;
                $detect->save();

                DB::commit();
                $count += 1;
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([],$msg);
            }
        }
        echo $count."</br>";
        return response_success([]);
    }

    // 补加【检测表】数据
    public function fill_detect()
    {
        $expense_list = ExpenseRecord::where("detect_id",0)->orderby("id","asc")->get();
        echo $expense_list->count()."</br>";
        $count = 0;
        foreach($expense_list as $k => $expense)
        {
            $keyword_id = $expense->keywordid;
            $standard_time = $expense->standarddate;
            $standard_date = explode(" ",trim($standard_time))[0];

            DB::beginTransaction();
            try
            {

                // 添加检测记录
                $DetectRecord = SEOKeywordDetectRecord::where(['keywordid'=>$keyword_id])->whereDate('detect_time',$standard_date)->first();
                if(!$DetectRecord)
                {
                    $keyword = SEOKeyword::find($keyword_id);

                    $DetectRecord = new SEOKeywordDetectRecord;

                    $DetectRecord_data['owner_id'] = $keyword->createuserid;
                    $DetectRecord_data['ownuserid'] = $keyword->createuserid;
                    $DetectRecord_data['createuserid'] = $keyword->createuserid;
                    $DetectRecord_data['createusername'] = $keyword->createusername;
                    $DetectRecord_data['createtime'] = $standard_time;
                    $DetectRecord_data['keywordid'] = $keyword->id;
                    $DetectRecord_data['keyword'] = $keyword->keyword;
                    $DetectRecord_data['website'] = $keyword->website;
                    $DetectRecord_data['searchengine'] = $keyword->searchengine;
                    $DetectRecord_data['detect_time'] = $standard_time;
                    $DetectRecord_data['rank'] = 1;
                    $DetectRecord_data['rank_original'] = 1;
                    $DetectRecord_data['rank_real'] = 1;
                    $DetectRecord_data['detectdata'] = "";

                    $bool_1 = $DetectRecord->fill($DetectRecord_data)->save();
                    if(!$bool_1) throw new Exception("update--detect-record--fail");
                    echo $count."INSERT"."<br>";
                }
                else
                {
                    echo $count."--EXIST--rank(".$DetectRecord->rank.")--real(".$DetectRecord->rank_real.")"."<br>";

                    if($DetectRecord->rank_real <= 10)
                    {
                        $DetectRecord->rank = $DetectRecord->rank_real;
                    }
                    else $DetectRecord->rank = 1;

                    $DetectRecord->expense_id = $expense->id;
                    $DetectRecord->save();
                }

                $expense->detect_id = $expense->id;
                $expense->save();

                DB::commit();
                $count += 1;
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([],$msg);
            }
        }
        echo $count."</br>";
        return response_success([]);
    }




    // 返回主页视图
    public function add_user_id_to_table_keyword()
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
     *
     */
    // 关键词检测请求
    public function morning_send()
    {

        $type = empty($_GET['type']) ? 'default' : $_GET['type'];

        $date = date('Y-m-d');

//        if($type == "all") {
//
//
//            $Dao = M('keyword');
//            $data = $Dao->query("SELECT id,keyword,website,searchengine FROM `ts_keyword` WHERE keywordstatus = '优化中' and status = 1 and detectiondate < '{$date}' order by id desc");
//        }
//        else if($type == "test") {
//            $Dao = M('testkeyword');
//            $data = $Dao->query("SELECT id,keyword,website,searchengine FROM `ts_keyword` WHERE keywordstatus = '优化中' and status = 1 and detectiondate < '{$date}' order by id desc limit 2");
//        }
//        else {
//            $Dao = M('keyword');
//            $data = $Dao->query("SELECT id,keyword,website,searchengine FROM `ts_keyword` WHERE keywordstatus = '优化中' and status = 1 and detectiondate < '{$date}' order by id desc");
//        }


        $data = SEOKeyword::select('id','keyword','website','searchengine')
            ->where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate','<',$date)
            ->orderby('id','desc')->limit(2)
            ->get()->toArray();

//        dd($data);

        foreach ($data as $value) {

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


        echo '<pre>';
        var_dump($baidu_url);
        if(isset($baidu_keyword)) echo 'baidu => '.count($baidu_keyword).'<br>';
        if(isset($baidu_mobile_keyword)) echo 'baidu_mobile => '.count($baidu_mobile_keyword).'<br>';
        if(isset($abc_keyword)) echo 'abc => '.count($abc_keyword).'<br>';
        if(isset($abc_mobile_keyword)) echo 'abc_mobile => '.count($abc_mobile_keyword).'<br>';
        if(isset($sougou_keyword)) echo 'sougou => '.count($sougou_keyword).'<br>';
        if(isset($sougou_mobile_keyword)) echo 'sougou_mobile => '.count($sougou_mobile_keyword).'<br>';
        if(isset($shenma_keyword)) echo 'shenma => '.count($shenma_keyword).'<br>';
        echo '</pre>';
//        die();

        $time = time();
        // baidu
        $baidu_param=json_encode(
            array(
                "userId"=>111311,
                "time"=>$time,"apiExtend"=>1,
                "businessType"=>1006,
                "keyword"=>$baidu_keyword,
                "url"=>$baidu_url,
                "searchType"=>$baidu_sType
            )
        );

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

        echo '<pre>';
        print_r($output);
        echo 'count : ',count($display_order).'<br>';
//        print_r($display_order);
//        echo '<br>';
        print_r($sql);
        echo '<br>';
        echo '</pre>';

        if(count($display_order))
        {

            $resule = DB::update($sql);
//            $Dao = M('keyword');
//            $Dao->query("$sql");
        }
    }



    //接受通知 更新关键词排行
    public function receive_from_youbangyun()
    {
        header("Content-Type:text/html;charset=UTF-8");
        iconv("GB2312","UTF-8");

        $xAction = $_POST["xAction"];
        $xParam = $_POST["xParam"];
        $xSign = $_POST["xSign"];


        $keyword = SEOKeyword::where('id',1)->find();
        $keyword->reviewopinion = $xParam;
        $keyword->save;
/*
//        $Dao = M('keyword');
//        $ra = $Dao->execute("UPDATE `ts_keyword` set reviewopinion = '{$xParam}' WHERE id = 1");
//        $xParam_from_SQL = $Dao->query("SELECT id,updateTime,reviewopinion FROM `ts_keyword` where id = 1");

        $model_keyword = D('Biz/Keyword');
        $data['reviewopinion'] = $xParam;
        $model_keyword->where('id=1')->save($data);
        $xParam_from_SQL = $model_keyword->where('id=1')->find();

//        $xParam_decode = json_decode($xParam);
        $xParam_decode = json_decode(stripslashes($xParam_from_SQL['reviewopinion']),true);
        $dataTaskId = $xParam_decode["Value"]["TaskId"];
        $dataRankFirst = $xParam_decode["Value"]["RankFirst"];
        $dataRankLast = $xParam_decode["Value"]["RankLast"];
        $dataRankLastChange = $xParam_decode["Value"]["RankLastChange"];
        $dataUpdateTime = $xParam_decode["Value"]["UpdateTime"];


//        $nowTimeA = $Dao->execute("SELECT id FROM `ts_keyword` where taskId = {$dataTaskId}");
//        $data = $Dao->query("SELECT * FROM `ts_keyword` where taskId = '{$dataTaskId}'");
//        $keyword_id = $data[0]['id'];
//        $keyword_type = $data[0]['type'];
//        $keyword_keyword = $data[0]['keyword'];
//        $keyword_website = $data[0]['website'];
//        $keyword_searchengine = $data[0]['searchengine'];


        $keyword_sql = $model_keyword->where("taskId={$dataTaskId}")->find();
        $keyword_id = $keyword_sql['id'];
        $keyword_type = $keyword_sql['type'];
        $keyword_keyword = $keyword_sql['keyword'];
        $keyword_website = $keyword_sql['website'];
        $keyword_searchengine = $keyword_sql['searchengine'];


        $return = $this->receive_keywords_rank($keyword_id,$keyword_keyword,$keyword_type,$dataRankLast,$keyword_website);
        $return = json_decode($return,true);
        if($return['ret'] == 1)
        {
            $data['reviewopinion'] = "";
            $model_keyword->where('id=1')->save($data);
            echo 1;
        }
        else echo 2;

//        if(count($data))
//        {
//            $nowTimeA = $data[0]['updateTime'];
//            $nowTimeB = time();
//            $ts = $nowTimeB-strtotime($nowTimeA);
//            $param = $ts/3600;
//
//            if($param<0.2){
//                echo 11 ;
//            }else{
//                $ra = $Dao->execute("UPDATE `ts_keyword` set initialranking = '{$dataRankFirst}', latestranking = '{$dataRankLast}', detectiondate = '{$dataUpdateTime}', updateTime = '{$dataUpdateTime}' WHERE taskId = '{$dataTaskId}'");
//
//                if($ra)
//                {
//                    echo 1;
//                }
//                else echo 2;
//            }
//        }
//        else echo 3;
*/
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
//            $data_index = json_decode( file_get_contents($url_index), true);
            //$data_index = json_decode( file_get_contents($url_index));

            //$data_index = file_get_contents($url_index);
            $context = stream_context_create(array('http'=>array('ignore_errors'=>true)));
            $data_index = file_get_contents($url_index, FALSE, $context);
            $data_index = json_decode($data_index, true);


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
        dd($return);
        return $return;
    }





}
