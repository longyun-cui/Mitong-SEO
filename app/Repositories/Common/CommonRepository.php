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




    // 生成详细页面二维码
    public function create_qrcode($qrcode_path, $logo_path, $name)
    {

        // 创建画布
        $img = Image::canvas(400, 400, '#fafafa');


        if(file_exists(storage_path($qrcode_path)))
        {
            $qrcode = Image::make(storage_path($qrcode_path));
            $img->insert($qrcode, 'bottom-right',20, 20);
        }

//        if(file_exists(storage_path($logo_path)))
//        {
//            $logo = Image::make(storage_path($logo_path));
//            $logo->resize(40, 40);
//
//            // define polygon points
//            $points = array(
//                1,  1,  // Point 1 (x, y)
//                39,  1, // Point 2 (x, y)
//                39,  39,  // Point 3 (x, y)
//                1, 39,  // Point 4 (x, y)
//            );
//            $logo->polygon($points, function ($draw) {
////            $draw->background('#0000ff');
//                $draw->border(1, '#ffffff');
//            });
//
//            $img->insert($logo, 'bottom-right',180, 180);
//        }

        return $img->save(storage_path($name));
    }

    // 生成机构根二维码
    public function create_root_qrcode($name, $org_name, $qrcode_path, $logo_path)
    {
        $font_file = public_path().'/fonts/huawenkaiti.ttf';
        $font_2 = public_path().'/fonts/huawenkaiti.ttf';

//        $org_name = "上海(中国)如哉网络科技有限公司";
        $org_name = !empty($org_name) ? $org_name : '名称暂无';
        $result = $this->autowrap(20, 0, $font_file, $org_name, 360); // 自动换行处理
        $title_row = $result['row'];
        $title = $result['content'];

        // 创建画布
        $img = Image::canvas(400, 400 + (($title_row - 1) * 20), '#fafafa');

        // 标题
        $img->text($title, 200, 40, function($font) use ($font_file) {
            $font->file($font_file);
            $font->size(20);
            $font->color('#000');
            $font->align('center');
            $font->valign('top');
        });

        $line_v = 80 + (($title_row - 1) * 20);
        // 分割线
        $points1 = array(
            30,  $line_v,  // Point 1 (x, y)
            370,  $line_v, // Point 2 (x, y)
            30,  $line_v,  // Point 3 (x, y)
        );
        $img->polygon($points1, function ($draw) {
//            $draw->background('#0000ff');
            $draw->border(1, '#000');
        });

        if(file_exists(storage_path($qrcode_path)))
        {
            $qrcode = Image::make(storage_path($qrcode_path));
            $qrcode->resize(240, 240);
            $img->insert($qrcode, 'bottom-right',80, 40);
        }

        if(file_exists(storage_path($logo_path)))
        {
            $logo = Image::make(storage_path($logo_path));
            $logo->resize(60, 60);

            // define polygon points
            $points = array(
                1,  1,  // Point 1 (x, y)
                59,  1, // Point 2 (x, y)
                59,  59,  // Point 3 (x, y)
                1, 59,  // Point 4 (x, y)
            );
            $logo->polygon($points, function ($draw) {
//            $draw->background('#0000ff');
                $draw->border(1, '#ffffff');
            });

            $img->insert($logo, 'bottom-right',170, 130);
        }

        return $img->save(storage_path($name));

    }

    // 生成详细页面二维码
    public function create_qrcode_image($org_name, $type_string, $title, $qrcode_path, $logo_path, $name)
    {
        $font_file = public_path().'/fonts/huawenkaiti.ttf';
        $font_2 = public_path().'/fonts/huawenkaiti.ttf';

//        $title = "2017天津How I Treat和淋巴瘤转化医学国际研讨会";
        $result = $this->autowrap(24, 0, $font_file, $title, 320); // 自动换行处理
        $title_row = $result['row'];
        $title = $result['content'];

        // 创建画布
        $img = Image::canvas(400, 340 + ($title_row * 24), '#fafafa');

//        $type_string = '活动';
        $img->text($type_string, 200, 50, function($font) use ($font_file) {
            $font->file($font_file);
            $font->size(16);
            $font->color('#000');
            $font->align('center');
            $font->valign('bottom');
        });

        // 分割线
        $points1 = array(
            30,  60,  // Point 1 (x, y)
            370,  60, // Point 2 (x, y)
            30,  60,  // Point 3 (x, y)
        );
        $img->polygon($points1, function ($draw) {
//            $draw->background('#0000ff');
            $draw->border(1, '#000');
        });

        // 标题
        $img->text($title, 200, 90, function($font) use ($font_file) {
            $font->file($font_file);
            $font->size(24);
            $font->color('#000');
            $font->align('center');
            $font->valign('top');
        });

        if(file_exists(storage_path($qrcode_path)))
        {
            $qrcode = Image::make(storage_path($qrcode_path));
            $img->insert($qrcode, 'bottom-right',120, 60);
        }

        if(file_exists(storage_path($logo_path)))
        {
            $logo = Image::make(storage_path($logo_path));
            $logo->resize(40, 40);

            // define polygon points
            $points = array(
                1,  1,  // Point 1 (x, y)
                39,  1, // Point 2 (x, y)
                39,  39,  // Point 3 (x, y)
                1, 39,  // Point 4 (x, y)
            );
            $logo->polygon($points, function ($draw) {
//            $draw->background('#0000ff');
                $draw->border(1, '#ffffff');
            });

            $img->insert($logo, 'bottom-right',180, 120);
        }


//        $org_name = "上海如哉网络科技有限公司";
        $img->text($org_name, 200, 290 + ($title_row * 24), function($font) use ($font_file) {
            $font->file($font_file);
            $font->size(16);
            $font->color('#000');
            $font->align('center');
            $font->valign('top');
        });

        return $img->save(storage_path($name));
    }


    public function autowrap($font_size, $angle, $font_face, $string, $width)
    {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        $row = 1;

        // 将字符串拆分成一个个单字 保存到数组 letter 中
        for ($i=0;$i<mb_strlen($string);$i++)
        {
            $letter[] = mb_substr($string, $i, 1);
        }

        foreach ($letter as $l)
        {
            $test_str = $content." ".$l;
            $test_box = imagettfbbox($font_size, $angle, $font_face, $test_str);

            // 判断拼接后的字符串是否超过预设的宽度
            if (($test_box[2] > $width) && ($content !== ""))
            {
                $content .= "\n";
                $row = $row + 1;
            }
            $content .= $l;
        }

        $result['content'] = $content;
        $result['row'] = $row;

//        return $content;
        return $result;
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
