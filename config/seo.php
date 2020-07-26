<?php

    return [



        // 关键词长度价格指数代码集
        'KeywordLengthPriceIndexOptions' => array(

            'baidu' => array(
                array('vmin'=>1,'vmax'=> 2,'quotavalue' => 35),
                array('vmin'=>3 ,'vmax'=> 4,'quotavalue' => 26),
                array('vmin'=>5 ,'vmax'=> 6,'quotavalue' => 19),
                array('vmin'=>7 ,'vmax'=> 8,'quotavalue' => 16),
                array('vmin'=>9 ,'vmax'=> 10,'quotavalue' => 15),
                array('vmin'=>11 ,'vmax'=> 12,'quotavalue' => 11),
                array('vmin'=>13,'vmax'=> 14,'quotavalue' => 9),
                array('vmin'=>15,'vmax'=> 16,'quotavalue' => 8),
                array('vmin'=>17,'vmax'=> 18,'quotavalue' => 5),
                array('vmin'=>19,'vmax'=> 20,'quotavalue' => 3),
                array('vmin'=>21,'vmax'=> 22,'quotavalue' => 3),
                array('vmin'=>23,'vmax'=> 24,'quotavalue' => 3),
                array('vmin'=>25,'vmax'=> 26,'quotavalue' => 3),
                array('vmin'=>27,'vmax'=> 999999999,'quotavalue' => 3),
            ),

            'baidu_mobile' => array(
                array('vmin'=>1,'vmax'=> 2,'quotavalue' => 39),
                array('vmin'=>3 ,'vmax'=> 4,'quotavalue' => 29),
                array('vmin'=>5 ,'vmax'=> 6,'quotavalue' => 22),
                array('vmin'=>7 ,'vmax'=> 8,'quotavalue' => 19),
                array('vmin'=>9 ,'vmax'=> 10,'quotavalue' => 18),
                array('vmin'=>11 ,'vmax'=> 12,'quotavalue' => 14),
                array('vmin'=>13,'vmax'=> 14,'quotavalue' => 13),
                array('vmin'=>15,'vmax'=> 16,'quotavalue' => 12),
                array('vmin'=>17,'vmax'=> 18,'quotavalue' => 8),
                array('vmin'=>19,'vmax'=> 20,'quotavalue' => 6),
                array('vmin'=>21,'vmax'=> 22,'quotavalue' => 6),
                array('vmin'=>23,'vmax'=> 24,'quotavalue' => 6),
                array('vmin'=>25,'vmax'=> 26,'quotavalue' => 6),
                array('vmin'=>27,'vmax'=> 999999999,'quotavalue' => 6),
            ),

            'sougou' => array(
                array('vmin'=>1,'vmax'=> 4,'quotavalue' => 8),
                array('vmin'=>5 ,'vmax'=> 5,'quotavalue' => 7),
                array('vmin'=>6 ,'vmax'=> 6,'quotavalue' => 7),
                array('vmin'=>7 ,'vmax'=> 7,'quotavalue' => 6),
                array('vmin'=>8 ,'vmax'=> 8,'quotavalue' => 6),
                array('vmin'=>9 ,'vmax'=> 9,'quotavalue' => 5),
                array('vmin'=>10,'vmax'=> 10,'quotavalue' => 5),
                array('vmin'=>11,'vmax'=> 11,'quotavalue' => 4),
                array('vmin'=>12,'vmax'=> 12,'quotavalue' => 4),
                array('vmin'=>13,'vmax'=> 13,'quotavalue' => 3),
                array('vmin'=>14,'vmax'=> 14,'quotavalue' => 3),
                array('vmin'=>15,'vmax'=> 15,'quotavalue' => 2),
                array('vmin'=>16,'vmax'=> 16,'quotavalue' => 2),
                array('vmin'=>17,'vmax'=> 999999999,'quotavalue' => 2),
            ),

            '360' => array(
                array('vmin'=>1 ,'vmax'=> 4,'quotavalue' => 9),
                array('vmin'=>5 ,'vmax'=> 5,'quotavalue' => 8),
                array('vmin'=>6 ,'vmax'=> 6,'quotavalue' => 8),
                array('vmin'=>7 ,'vmax'=> 7,'quotavalue' => 7),
                array('vmin'=>8 ,'vmax'=> 8,'quotavalue' => 7),
                array('vmin'=>9 ,'vmax'=> 9,'quotavalue' => 6),
                array('vmin'=>10,'vmax'=> 10,'quotavalue' => 6),
                array('vmin'=>11,'vmax'=> 11,'quotavalue' => 5),
                array('vmin'=>12,'vmax'=> 12,'quotavalue' => 5),
                array('vmin'=>13,'vmax'=> 13,'quotavalue' => 4),
                array('vmin'=>14,'vmax'=> 14,'quotavalue' => 4),
                array('vmin'=>15,'vmax'=> 15,'quotavalue' => 3),
                array('vmin'=>16,'vmax'=> 16,'quotavalue' => 3),
                array('vmin'=>17,'vmax'=> 999999999,'quotavalue' => 3),
            ),

            'shenma' => array(
                array('vmin'=>1 ,'vmax'=> 4,'quotavalue' => 6),
                array('vmin'=>5 ,'vmax'=> 5,'quotavalue' => 6),
                array('vmin'=>6 ,'vmax'=> 6,'quotavalue' => 6),
                array('vmin'=>7 ,'vmax'=> 7,'quotavalue' => 5),
                array('vmin'=>8 ,'vmax'=> 8,'quotavalue' => 5),
                array('vmin'=>9 ,'vmax'=> 9,'quotavalue' => 4),
                array('vmin'=>10,'vmax'=> 10,'quotavalue' => 4),
                array('vmin'=>11,'vmax'=> 11,'quotavalue' => 3),
                array('vmin'=>12,'vmax'=> 12,'quotavalue' => 3),
                array('vmin'=>13,'vmax'=> 13,'quotavalue' => 2),
                array('vmin'=>14,'vmax'=> 14,'quotavalue' => 2),
                array('vmin'=>15,'vmax'=> 15,'quotavalue' => 2),
                array('vmin'=>16,'vmax'=> 16,'quotavalue' => 2),
                array('vmin'=>17,'vmax'=> 999999999,'quotavalue' => 2),
            ),

        ),

        // 百度指数价格指数代码集
        'BaiduIndexPriceIndexOptions' =>array(

            'baidu' => array(
                array('quotavalue'=>5,'vmin'=>1,'vmax'=>100,'choose'=>0),
                array('quotavalue'=>8,'vmin'=>101,'vmax'=>500,'choose'=>0),
                array('quotavalue'=>12,'vmin'=>501,'vmax'=>1000,'choose'=>0),
                array('quotavalue'=>15,'vmin'=>1001,'vmax'=> 1500,'choose'=>0),
                array('quotavalue'=>18,'vmin'=>1501,'vmax'=> 999999999,'choose'=>0),
            ),

            'baidu_mobile' => array(
                array('quotavalue'=>5,'vmin'=>1,'vmax'=>100,'choose'=>0),
                array('quotavalue'=>8,'vmin'=>101,'vmax'=>500,'choose'=>0),
                array('quotavalue'=>12,'vmin'=>501,'vmax'=>1000,'choose'=>0),
                array('quotavalue'=>15,'vmin'=>1001,'vmax'=> 1500,'choose'=>0),
                array('quotavalue'=>18,'vmin'=>1501,'vmax'=> 999999999,'choose'=>0),
            ),

            'sougou' => array(
                array('quotavalue'=>2,'vmin'=>1,'vmax'=>100,'choose'=>0),
                array('quotavalue'=>3,'vmin'=>101,'vmax'=>500,'choose'=>0),
                array('quotavalue'=>4,'vmin'=>501,'vmax'=>1000,'choose'=>0),
                array('quotavalue'=>5,'vmin'=>1001,'vmax'=> 1500,'choose'=>0),
                array('quotavalue'=>6,'vmin'=>1501,'vmax'=> 999999999,'choose'=>0),
            ),

            '360' => array(
                array('quotavalue'=>2,'vmin'=>1,'vmax'=>100,'choose'=>0),
                array('quotavalue'=>3,'vmin'=>101,'vmax'=>500,'choose'=>0),
                array('quotavalue'=>4,'vmin'=>501,'vmax'=>1000,'choose'=>0),
                array('quotavalue'=>5,'vmin'=>1001,'vmax'=> 1500,'choose'=>0),
                array('quotavalue'=>6,'vmin'=>1501,'vmax'=> 999999999,'choose'=>0),
            ),

            'shenma' => array(
                array('quotavalue'=>2,'vmin'=>1,'vmax'=>100,'choose'=>0),
                array('quotavalue'=>2,'vmin'=>101,'vmax'=>500,'choose'=>0),
                array('quotavalue'=>3,'vmin'=>501,'vmax'=>1000,'choose'=>0),
                array('quotavalue'=>4,'vmin'=>1001,'vmax'=> 1500,'choose'=>0),
                array('quotavalue'=>5,'vmin'=>1501,'vmax'=> 999999999,'choose'=>0),
            ),

        ),

        // 关键词长度难度指数代码集
        'KeywordDifficultyIndexOptions' => array(
            array('quotavalue'=>5,'vmin'=>1,'vmax'=>4,'choose'=>0),
            array('quotavalue'=>4,'vmin'=>5,'vmax'=>8,'choose'=>0),
            array('quotavalue'=>3,'vmin'=>9,'vmax'=>13,'choose'=>0),
            array('quotavalue'=>2,'vmin'=>14,'vmax'=> 999999999,'choose'=>0),
        ),

        // 关键词长度优化周期代码集
        'KeywordOptimizationCycleOptions' => array(
            array('quotavalue'=>'3-6/月','vmin'=>1,'vmax'=>4,'choose'=>0),
            array('quotavalue'=>'2-5/月','vmin'=>5,'vmax'=>8,'choose'=>0),
            array('quotavalue'=>'7-90/天','vmin'=>9,'vmax'=>13,'choose'=>0),
            array('quotavalue'=>'3-60/天','vmin'=>14,'vmax'=> 999999999,'choose'=>0),
        ),


        // 关键词百度指数难度指数代码集
        'KeywordDifficultyIndex4BaiduIndexOptions'=>array(
            array('quotavalue'=>4,'vmin'=>1,'vmax'=>499,'choose'=>0),
            array('quotavalue'=>5,'vmin'=>500,'vmax'=> 999999999,'choose'=>0),
        ),

        // 关键词百度指数化周期代码集
        'KeywordOptimizationCycle4BaiduIndexOptions'=>array(
            array('quotavalue'=>'2-5/月','vmin'=>1,'vmax'=>499,'choose'=>0),
            array('quotavalue'=>'3-6/月','vmin'=>500,'vmax'=> 999999999,'choose'=>0),
        ),


        // 长尾词挖掘代码集
        'KeywordDigOptions' => array(
            'url' => 'http://www.5118.com/seo/words/',

        ),

        // 关键词排名解耦
        'KeywordRankOptions' => array(
            'url' => 'https://api.zzmofang.com/v1/coop/recieve_words',

        ),

        'SearchengineOptions' 	=> array(
            'baidu' 		=> '百度pc',
            'baidu_mobile' 	=> '百度手机',
            'sougou' 		=> '搜狗',
            '360' 			=> '360',
            'shenma' 		=> '神马',
        ),
        /**
         * 搜索引擎地址
         */
        'SearchengineSiteOptions' 	=> array(
            'baidu' 		=> 'http://www.baidu.com/#ie=UTF-8&amp;wd={keyword}',
            'baidu_mobile' 	=> 'https://m.baidu.com/ssid=fd5379616e677a696c676c8223/from=1012971h/s?&ie=utf-8&word={keyword}',
            'sougou' 		=> 'https://www.sogou.com/web?ie=utf8&query={keyword}',
            '360' 			=> 'https://www.so.com/s?ie=utf-8&q={keyword}',
            'shenma' 		=> 'http://www.baidu.com/#ie=UTF-8&amp;wd={keyword}',// 暂时用百度的
        ),


    ];
