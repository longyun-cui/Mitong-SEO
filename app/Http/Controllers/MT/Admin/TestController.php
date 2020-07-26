<?php
namespace App\Http\Controllers\MT\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOKeyword;

use App\Repositories\MT\Admin\TestRepository;

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
        dd('test');
    }


    // 返回主页视图
    public function temp()
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
