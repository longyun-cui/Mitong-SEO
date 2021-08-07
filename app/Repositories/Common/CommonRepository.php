<?php
namespace App\Repositories\Common;

use Response, Auth, Validator, DB, Exception;
use QrCode, Excel, Image;

/**
 * Description of UploadRepository
 */
class CommonRepository {

    public function create($file, $namespace, $size='', $allowed_extensions=["png", "jpg", "gif", "jpeg", "PNG", "JPG", "GIF", "JPEG"])
    {
        if(empty($file)) return ["status" => false, "info" => "请上传图片!", "data" => ""];

        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ["status" => false, "info" => $file->getClientOriginalExtension() . "上传图片格式不正确!", "data" => ""];
        }
        $namespace = str_replace("-", "/", $namespace);
        $destinationPath = $namespace . "/";
        $extension = $file->getClientOriginalExtension();
        $fileName = md5(date("ymdhis")) . $size . '.' . $extension;

        if (!is_dir(storage_path("resource/" . $destinationPath))) {
            mkdir(storage_path("resource/" . $destinationPath), 0777, true);
        }
        $file->move(storage_path("resource/" . $destinationPath), $fileName);

        return ["status" => true, "info" => "上传成功", "data" => $destinationPath . $fileName, "fileName"=>$fileName];
    }


    // 上传图片
    public function upload($file, $namespace, $filename, $size='', $allowed_extensions=["png", "jpg", "gif", "jpeg", "PNG", "JPG", "GIF", "JPEG"])
    {
        if(empty($file)) return ["status" => false, "info" => "请上传图片!", "data" => ""];

        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ["status" => false, "info" => $file->getClientOriginalExtension() . "上传图片格式不正确!", "data" => ""];
        }
        $namespace = str_replace("-", "/", $namespace);
        $destinationPath = $namespace . "/";
        $extension = $file->getClientOriginalExtension();
//        $fileName = md5(date("ymdhis")) . $size . '.' . $extension;
        $fileName = $filename . '.' . $extension;

        if (!is_dir(storage_path("resource/" . $destinationPath))) {
            mkdir(storage_path("resource/" . $destinationPath), 0777, true);
        }
        $file->move(storage_path("resource/" . $destinationPath), $fileName);

        return ["status" => true, "info" => "上传成功", "data" => $destinationPath . $fileName, "fileName"=>$fileName];
    }





    /**
     * 搜索关键词:根据用户的关键词搜索推荐的关键词
     *
     * 通过第三方接口搜索关键词:由于第三方的接口一下只能提交10个关键词，需要将关键词进行等
     *
     */
    public function combKeywordSearchResults( $list )
    {
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


        ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)");

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
//            dd($url_index);
//            echo file_get_contents("http://www.91cha.com");


            $context = stream_context_create(array('http'=>array('ignore_errors'=>true)));
            $data_index = file_get_contents($url_index, FALSE, $context);
            $data_index = json_decode($data_index, true);
//            dd($data_index);

//            $data_index = json_decode( file_get_contents($url_index), true);

//            try {
//                $data_index = file_get_contents($url_index);
//                $data_index = json_decode($data_index, true);
//            }
//            catch (Exception $e) {
////                echo $e->getMessage();
//                return $e->getMessage();
//            }



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



    /**
     * by longyun 2021-08-04
     * 新的第三方接口
     *
     * 搜索关键词:根据用户的关键词搜索推荐的关键词
     *
     * 通过第三方接口搜索关键词:由于第三方的接口一下只能提交10个关键词，需要将关键词进行等
     *
     */
    public function combKeywordSearchResults_new( $list )
    {
        // 关键词长度价格指数代码集
        $KeywordLengthPriceIndexOptions = config('seo.KeywordLengthPriceIndexOptions');
        // 百度指数价格指数代码集
        $BaiduIndexPriceIndexOptions = config('seo.BaiduIndexPriceIndexOptions');
        // 关键词长度难度指数代码集
        $KeywordDifficultyIndexOptions = config('seo.KeywordDifficultyIndexOptions');
        // 关键词长度优化周期代码集
        $KeywordOptimizationCycleOptions = config('seo.KeywordOptimizationCycleOptions');
        // 关键词百度指数难度指数代码集
        $KeywordDifficultyIndex4BaiduIndexOptions = config('seo.KeywordDifficultyIndex4BaiduIndexOptions');
        // 关键词百度指数化周期代码集
        $KeywordOptimizationCycle4BaiduIndexOptions = config('seo.KeywordOptimizationCycle4BaiduIndexOptions');


        ini_set("user_agent", "Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)");

        // 将关键词组成一个字符串
        /* foreach ( $list as $vo1){
            $keyword_arr[] = $vo1['keyword'];
            } */

        // 将 关键词进行等分
        $list_new = array_chunk($list, 10);

        foreach ($list_new as &$vo_list) {
            unset($keyword_arr);
            foreach ($vo_list as $vo_temp) {
                $keyword_arr[] = trim($vo_temp['keyword']);
            }

            //将获取的关键词数组组成字符串
            $keywords = implode(',', $keyword_arr);


            /*
             * 新的百度指数查询接口
             * by longyun 2021-08-04
             */

            $data_all = [];
            foreach ($keyword_arr as $keyword) {

                $url_index = 'https://apidatav2.chinaz.com/single/index/baidu?key=88537cd8a9754fc7b3383f117380723d&keyword=' . urlencode($keyword);

                $context = stream_context_create(array('http' => array('ignore_errors' => true)));
                $data_index = file_get_contents($url_index, FALSE, $context);
                $data_index = json_decode($data_index, true);
                $data_index['keyword'] = $keyword;

                $data_all[] = $data_index;

            }


            foreach ($vo_list as $key => &$vo) {
                $baiduindex = 0;
                $mobileindex = 0;
                $so360index = 0;


                foreach ($data_all as $vo_bi) {
                    if ($vo['keyword'] == $vo_bi['keyword']) {
                        if ($vo_bi['StateCode'] == 1) {
                            $baiduindex = $vo_bi['Result']['BaiduAll'];
                            $mobileindex = $vo_bi['Result']['BaiduMobile'];
                            $so360index = $vo_bi['Result']['BaiduPc'];
                        }
                    }
                }

                // 判断字符的长度
                $len = floor((strlen($vo['keyword']) + mb_strlen($vo['keyword'], 'UTF8')) / 2);

                /*
                $baiduindex 	= $baiduindex_data[$key]['allindex'];
                $mobileindex 	= $baiduindex_data[$key]['mobileindex'];
                $so360index 	= $baiduindex_data[$key]['so360index'];
                */

                foreach ($vo as $key_vo => &$vo_vo) {
                    $price = 0;
                    $price1 = 0;
                    $price2 = 0;

                    // 关键词长度指数
                    $keywordOption = isset($KeywordLengthPriceIndexOptions[$key_vo]) ? $KeywordLengthPriceIndexOptions[$key_vo] : null;
                    if ($keywordOption) {
                        $keywordOption = $KeywordLengthPriceIndexOptions[$key_vo];
                        foreach ($keywordOption as $vo_ko) {
                            if ($vo_ko['vmin'] <= $len && $len <= $vo_ko['vmax']) {
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
                    if ($BaiduIndexPriceIndexOption) {
                        foreach ($BaiduIndexPriceIndexOption as $vo_bo) {
                            if ($vo_bo['vmin'] <= $baiduindex && $baiduindex <= $vo_bo['vmax']) {
                                $price2 = $vo_bo['quotavalue'];
//                                echo(" price2=".$price2);
                            }
                        }
                    }

                    $price = $price1 + $price2;

                    if ($price) {
                        $vo_vo = round($price * 0.95, 0);
                    }
//                    echo("-----");
//                    echo "<br>";
                }


                // 计算难度指数difficulty_index 和 优化周期 optimization_cycle
                // 如果有百度指数，则只通过百度指数来进行计算
                if ($baiduindex) {
                    foreach ($KeywordDifficultyIndex4BaiduIndexOptions as $vo_kd) {
                        if ($vo_kd['vmin'] <= $baiduindex && $baiduindex <= $vo_kd['vmax']) {
                            $difficulty_index = $vo_kd['quotavalue'];
                        }
                    }

                    foreach ($KeywordOptimizationCycle4BaiduIndexOptions as $vo_ko) {
                        if ($vo_ko['vmin'] <= $baiduindex && $baiduindex <= $vo_ko['vmax']) {
                            $optimization_cycle = $vo_ko['quotavalue'];
                        }
                    }
                } else {
                    foreach ($KeywordDifficultyIndexOptions as $vo_kd) {
                        if ($vo_kd['vmin'] <= $len && $len <= $vo_kd['vmax']) {
                            $difficulty_index = $vo_kd['quotavalue'];
                        }
                    }

                    foreach ($KeywordOptimizationCycleOptions as $vo_ko) {
                        if ($vo_ko['vmin'] <= $len && $len <= $vo_ko['vmax']) {
                            $optimization_cycle = $vo_ko['quotavalue'];
                        }
                    }
                }
                $vo['difficulty_index'] = $difficulty_index;


                // 计算显示的样式
                if ($difficulty_index > 0) {
                    $rate = '';
                    for ($i = 1; $i <= $difficulty_index; $i++) {
                        $rate .= '★';
                    }
                    $rate .= '';
                }

                $rate_diff = 5 - $difficulty_index;
                if ($rate_diff > 0) {
                    $rate .= '';
                    for ($i = 1; $i <= $rate_diff; $i++) {
                        $rate .= '☆';
                    }
                    $rate .= '';
                }
                $vo['rate'] = $rate;

                $vo['optimization_cycle'] = $optimization_cycle;
            }


            foreach ($list_new as $vo2) {
                if (!isset($return)) {
                    $return = $vo2;
                } else {
                    $return = array_merge($return, $vo2);
                }
            }
            return $return;
        }

    }


    // 查询【关键词】推荐
    public function get_keyword_recommend($post_data)
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

        $keyword_arr = explode(',' , $keywords );
        // 去重空值
        $keyword_arr = array_filter( $keyword_arr );
        // 去重操作
        $keyword_arr = array_values(array_unique( $keyword_arr ));


        // 长尾词挖掘配置
//        $KeywordDigOptions = C('KeywordDigOptions');
        $KeywordDigOptions = config('seo.KeywordDigOptions');
        // 关键词长度价格指数代码集
//        $KeywordLengthPriceIndexOptions = C('KeywordLengthPriceIndexOptions');
        $KeywordLengthPriceIndexOptions = config('seo.KeywordLengthPriceIndexOptions');
        $searchengine_keys = array_keys($KeywordLengthPriceIndexOptions);

        //组成字符
        $keywords = implode(',' , $keyword_arr);


        ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)");


        //关键词搜索
        //$url_search = $KeywordDigOptions['url']. urlencode($keywords);
        $url_search = "http://www.baidu.com/s?wd=". urlencode($keywords);;
        //从http://www.5118.com/seo/words/%E4%BA%92%E8%81%94%E7%BD%91%E4%BF%9D%E9%99%A9
//        $html = file_get_contents( $url_search );


        $context = stream_context_create(array('http'=>array('ignore_errors'=>true)));
        $html = file_get_contents($url_search, FALSE, $context);

//        $ch = curl_init();
//        $timeout = 10; // set to zero for no timeout
//        curl_setopt ($ch, CURLOPT_URL,$url_search);
//        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//        $html = curl_exec($ch);

//        echo $html;
//        dd($url_search);


        // ...也许这里还有其他代码
        // 进行统计区间
        //echo G('begin','end').'s';
        // 根据特殊的字符进行匹配
        /*
        $pattern_all = '/<span class="hoverToHide"><a.*?>(.+?)<\/a><\/span>/is';
        */
//        $pattern_all = '/<th><a href="(?:.*?)">(.*?)<\/a><\/th>/is';
        $pattern_all = '/<th><a class="c-font-medium" href="(?:.*?)">(.*?)<\/a><\/th>/is';
        preg_match_all($pattern_all, $html, $results);

        $keyword_arr1 = $results[1];
//        dd($results);

        //$keyword_arr2 =  array_slice($keyword_arr1,0,20) ;

        $list = [];

        if( count($keyword_arr1) > 0 ){


            // 截取和去重获取10个关键词
            foreach ( $keyword_arr1 as $vo )
            {
                //dump($vo);
                $keyword_arr3[] = str_replace(array('<em>','</em>'), '',$vo);
                //$keyword_temp  = explode(' ',$vo);
            }


            // 数组去重
            $keyword_arr3 = array_unique( $keyword_arr3 );
            // 在最终的数组去掉关键词
            $keyword_arr3 = array_diff( $keyword_arr3,$keyword_arr );
            // 截取前10个元素
            $keyword_arr3 =  array_slice($keyword_arr3,0,10) ;


            foreach ( $keyword_arr3 as $key => $vo )
            {
                $temp['keyword'] = $vo;
                foreach ( $searchengine_keys as $vo2 )
                {
                    $temp[$vo2] = 0;
                }
                $temp['isrecommend'] = 1;
                $arr[] = $temp;
            }

            $list = $this -> combKeywordSearchResults( $arr );
        }

        return $list;

//        $view_blade = 'mt.admin.entrance.business.keyword-search-result';
//        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list])->__toString();
//
////        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list]);
////        $html = response($html)->getContent();
//
//        return response_success(['html'=>$html]);
    }


}
