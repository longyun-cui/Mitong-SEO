<?php
/*
 * 全局公共方法
 */


// 密码加密
if(!function_exists('password_encode'))
{
    function password_encode($str)
    {
        return password_hash(md5($str),PASSWORD_BCRYPT);
    }
}
// 核对密码
if(!function_exists('password_check'))
{
    function password_check($str,$password)
    {
        return password_verify(md5($str),$password);
    }
}


// 初始化时间戳 global $time_stamp
if(!function_exists('time_init')) {
    function time_init()
    {
        global $today_start_unix;    //今天开始；
        global $today_ended_unix;    //今天结束；
        global $yesterday_start_unix;    //昨天开始；
        global $yesterday_ended_unix;    //昨天结束；
        global $beforeday_start_unix;    //前天开始；
        global $beforeday_ended_unix;    //前天结束；
        global $tomorrow_start_unix;    //明天开始；
        global $tomorrow_ended_unix;    //明天结束；
        global $afterday_start_unix;    //后天开始；
        global $afterday_ended_unix;    //后天结束；
        global $this_year_start_unix;    //今年开始；
        global $this_year_ended_unix;    //今年结束；


        $today_start_unix = strtotime(date("Y-m-d",time()));
        $today_ended_unix = $today_start_unix + (3600 * 24 - 1);
        $yesterday_start_unix = $today_start_unix - 3600 * 24;
        $yesterday_ended_unix = $today_ended_unix - 3600 * 24;
        $beforeday_start_unix = $yesterday_start_unix - 3600 * 24;
        $beforeday_ended_unix = $yesterday_ended_unix - 3600 * 24;
        $tomorrow_start_unix = $today_start_unix + 3600 * 24;
        $tomorrow_ended_unix = $today_ended_unix + 3600 * 24;
        $afterday_start_unix = $tomorrow_start_unix + 3600 * 24;
        $afterday_ended_unix = $tomorrow_ended_unix + 3600 * 24;
        $this_year_start_unix = strtotime(date("Y",time())."-1-1");
        $this_year_ended_unix = strtotime(date("Y",time())."-12-31 23:59:59");
    }
}

// 处理数据 返回 Data Show
if(!function_exists('time_show'))
{
    function time_show($stamp)
    {
        global $today_start_unix;	//今天开始；
        global $today_ended_unix;	//今天结束；

        global $yesterday_start_unix;	//昨天开始；
        global $yesterday_ended_unix;	//昨天结束；

        global $beforeday_start_unix;	//前天开始；
        global $beforeday_ended_unix;	//前天结束；

        global $tomorrow_start_unix;	//明天开始；
        global $tomorrow_ended_unix;	//明天结束；

        global $afterday_start_unix;	//后天开始；
        global $afterday_ended_unix;	//后天结束；

        global $this_year_start_unix;	//今年开始；
        global $this_year_ended_unix;	//今年结束；

        time_init();

        if( ($stamp >= $beforeday_start_unix) && ($stamp < $yesterday_start_unix) ) {
            return "前天".date(" H:i",$stamp);
        }
        elseif( ($stamp >= $yesterday_start_unix) && ($stamp < $today_start_unix) ) {
            return "昨天".date(" H:i",$stamp);
        }
        elseif( ($stamp >= $today_start_unix) && ($stamp <= $today_ended_unix) ) {
            return "今天".date(" H:i",$stamp);
        }
        elseif( ($stamp >= $today_ended_unix) && ($stamp < $tomorrow_ended_unix) ) {
            return "明天".date(" H:i",$stamp);
        }
        elseif( ($stamp >= $tomorrow_ended_unix) && ($stamp < $afterday_ended_unix) ) {
            return "后天".date(" H:i",$stamp);
        }
        else {
            if( ($this_year_start_unix <= $stamp) && ($stamp <= $this_year_ended_unix) ) {
                return date("n月j日 H:i",$stamp);
            } else {
                return date("Y-n-j H:i",$stamp);
            }
        }
    }
}
// 处理数据 返回 Data Show
if(!function_exists('date_show'))
{
    function date_show($stamp)
    {
        return date("Y-m-j",$stamp);
    }
}

if(!function_exists('return_interval_unix'))
{
    function return_interval_unix($sort,$value)
    {
        if($sort == config('display.geter.schedule.type.month'))
        {
            $stamp = strtotime($value."-1");
            $return["start_unix"] = get_month_start_unix($stamp);
            $return["end_unix"] = get_month_ended_unix($stamp);
        }
        else if($sort == config('display.geter.schedule.type.day'))
        {
            $stamp = strtotime($value);
            $return["start_unix"] = get_day_start_unix($stamp);
            $return["end_unix"] = get_day_ended_unix($stamp);
        }
        else
        {
            $return["start_unix"] = 0;
            $return["end_unix"] = 0;
        }
        return $return;
    }
}


if(!function_exists('replace_blank')) {
    function replace_blank($text)
    {
        $patterns = array();
        $patterns[0] = "/ /";
        $patterns[1] = "/\\n/";
        $replacements = array();
        $replacements[0] = "&nbsp;";
        $replacements[1] = "<br>";
        $text = preg_replace($patterns, $replacements, $text);
        return $text;
    }
}
if(!function_exists('replace_content')) {
    function replace_content($text)
    {
        $patterns = array();
        $patterns[0] = "/ /";
        $patterns[2] = "/&amp;/";
        $patterns[3] = "/&quot;/";
        $patterns[4] = "/\</";
        $patterns[5] = "/\>/";
        $patterns[6] = "/\\n/";
        $replacements = array();
        $replacements[0] = "&nbsp;";
        $replacements[2] = "&";
        $replacements[3] = '"';
        $replacements[4] = "&lt;";
        $replacements[5] = "&gt;";
        $replacements[6] = "<br/>";
        $text = preg_replace($patterns, $replacements, $text);

        $pattern = "/((?:https?|http?|ftp?):\/\/(?:\w|\?|\.|\/|\=|\+|\-|\&|\%|\#|\@|\:|\[|\])+)\b/i";
        $text = preg_replace($pattern,'<a class=content_link href=\1 target=_blank title=\1 ><em class="link-icon"></em> 网址链接</a>',$text);
        return $text;
    }
}

if(!function_exists('Get_IP'))
{
    function Get_IP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }
}
if(!function_exists('GetIP'))
{
    function GetIP()
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (isset($_SERVER["HTTP_CLIENT_IP"])) $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (isset($_SERVER["REMOTE_ADDR"])) $ip = $_SERVER["REMOTE_ADDR"];
        else if (getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknown";

        return $ip;
    }
}

//获取浏览器
if(!function_exists('getBrowserInfo'))
{
    function getBrowserInfo()
    {
        global $_SERVER;
        $Agent = $_SERVER['HTTP_USER_AGENT'];
//        dd($Agent);

        $info = [];
        $info['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $info['type'] = 'PC';
        if(stripos($Agent, 'Mobile')) $info['type'] = 'Mobile';

        $info['system'] = 'Unknown';
        if(stripos($Agent, 'Windows')) $info['system'] = 'Windows';
        if(stripos($Agent, 'Windows Phone')) $info['system'] = 'WinPhone';
        if(stripos($Agent, 'Android')) $info['system'] = 'Android';
        if(stripos($Agent, 'Mac')) $info['system'] = 'Mac';
        if(stripos($Agent, 'iPad')) $info['system'] = 'iPad';
        if(stripos($Agent, 'iPhone')) $info['system'] = 'iPhone';

        $info['browser'] = 'Ohters';
        if(stripos($Agent, 'Mozilla') && !stripos($Agent, 'MSIE')) $info['browser'] = 'Netscape';
        if(stripos($Agent, 'Mozilla') && stripos($Agent, 'MSIE')) $info['browser'] = 'IExplorer';
        if(stripos($Agent, 'Safari')) $info['browser'] = 'Safari';
        if(stripos($Agent, 'Chrome')) $info['browser'] = "Chrome";
        if(stripos($Agent, 'Firefox')) $info['browser'] = 'Firefox';
        if(stripos($Agent, 'FxiOS')) $info['browser'] = 'Firefox';
        if(stripos($Agent, 'Opera')) $info['browser'] = 'Opera';
        if(stripos($Agent, 'QQBroser')) $info['browser'] = 'QQBroser';

        $info['app'] = 'default';
        if(stripos($Agent, 'MicroMessenger')) $info['app'] = 'WeChat';
        if(stripos($Agent, 'QQ') && !stripos($Agent, 'MQQBrowser')) $info['app'] = 'QQ';
        if(stripos($Agent, 'QQ/')) $info['app'] = 'QQ';

        return $info;
    }
}


if(!function_exists('encode')) {
    function encode($str, $key = '')
    {
        $str = strval($str);
//        $key = empty($key) ? env('APP_KEY', 'key.by.heroest') : $key;
        $key = empty($key) ? config('env.APP_KEY') : $key;
        $charset = hash_hmac('sha256', $key, 'abcdefgopqrsthigklmnuvwxyz');
        $mac = hash_hmac('sha256', $str, $key);

        $len_str = mb_strlen($str);
        while(mb_strlen($charset) < $len_str)
        {
            $charset .= $charset;
        }

        $encoded = bin2hex($str ^ ($charset . $charset . $charset));
        $head = substr($mac, 0, 2);
        $tail = substr($mac, -2);
        return $head . $encoded . $tail;
    }
}
if(!function_exists('decode')) {
    function decode($input, $key = '')
    {
//        $key = empty($key) ? env('APP_KEY', 'key.by.heroest') : $key;
        $key = empty($key) ? config('env.APP_KEY') : $key;
        $charset = hash_hmac('sha256', $key, 'abcdefgopqrsthigklmnuvwxyz');

        $head = substr($input, 0, 2);
        $tail = substr($input, -2);
        $encoded = substr($input, 2, -2);
        if(strlen($encoded) % 2 !== 0) return false;
        $encoded = hex2bin($encoded);

        $len_str = mb_strlen($encoded);
        while(mb_strlen($charset) < $len_str)
        {
            $charset .= $charset;
        }
        $origin = $encoded ^ $charset;

        $mac = hash_hmac('sha256', $origin, $key);
        if($head == substr($mac, 0, 2) and $tail == substr($mac, -2))
        {
            return $origin;
        }
        else
        {
            return false;
        }
    }
}

if(!function_exists('medsci_encode')){
    function medsci_encode($id,$key){
        $id = trim($id);
//		if(!is_numeric($id)){
//			return FALSE;
//		}
        $iii=5;//左侧位数，必需是数字，避免与其它定义重复，故用此定义
        $kkk=2;//右侧位数，必需是数字
        $mmm=$key;//加的一个常量，必需是数字,这样容纳10亿个数字，加密后的位数依然不变，为16位，与MD5加密一致，会被误认为是md5
        $nnn="7";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $ooo="e";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $ppp="0";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $qqq="c";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $rrr="4";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $sss="a";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $id_plus=$id1=$id2=$id3=$id_str = '';


        $id_plus=$id+$mmm;//加上一个常数进行运算，这是能否解密的关键，同时也可以防止黑客用非数字攻击。
        $id1 = substr(md5($id_plus),8,16);
        $id2 = substr($id1,0,$iii);//只取前5位
        $id3 = substr($id1,-$kkk);//取后2位
        $replace_count = 1;
        $id_str = substr($id_plus, 0, 1).preg_replace("/{$nnn}/", $ooo, substr($id_plus, 1), $replace_count);//替换第一个出现的数字为字符
        $id_str = substr($id_str, 0, 1).preg_replace("/{$ppp}/", $qqq, substr($id_str, 1), $replace_count);//替换第一个出现的数字为字符
        $id_str = substr($id_str, 0, 1).preg_replace("/$rrr/", $sss, substr($id_str, 1), $replace_count);//替换第一个出现的数字为字符

        return $id2.$id_str.$id3;
    }
}
if(!function_exists('medsci_decode')){
    function medsci_decode($key,$module){
        $key = trim($key);
//		if(!ctype_alnum($key) or strlen($key) != 16 ){
//			return $key;
//		}
        $iii=5;//左侧位数，必需是数字，避免与其它定义重复，故用此定义
        $kkk=2;//右侧位数，必需是数字
        $mmm=$module;//加的一个常量，必需是数字,这样容纳10亿个数字，加密后的位数依然不变，为16位，与MD5加密一致，会被误认为是md5
        $nnn="7";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $ooo="e";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $ppp="0";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $qqq="c";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $rrr="4";//只能填0-9之间的数字，将数字替换为下面的o代表的字符,只替换一次。
        $sss="a";//最好是a-f之间的字符,改变上述这五个常量,黑客就无从猜解了.
        $id1=$id2=$id3=$id_left=$id_right=$id_left_1=$id_right_1=$x=$x1=$id3_md5=$id1_str=$id_plus='';
        $id_left_1=substr($key,0,5);//取MD5加密的前5位
        $id_right_1=substr($key,-$kkk);//取加密后2位
        $x=strlen($key);//计算长度
        $x1=$x-$iii-$kkk;//实际ID值的长度
        $replace_count =1;
        $id_plus =substr($key,$iii,$x1);
        $id_plus = substr($id_plus, 0, 1).preg_replace("/{$ooo}/", $nnn, substr($id_plus, 1), $replace_count);//替换第一个出现的数字为字符
        $id_plus = substr($id_plus, 0, 1).preg_replace("/{$qqq}/", $ppp, substr($id_plus, 1), $replace_count);//替换第一个出现的数字为字符
        $id_plus = substr($id_plus, 0, 1).preg_replace("/{$sss}/", $rrr, substr($id_plus, 1), $replace_count);//替换第一个出现的数字为字符
        if(!is_numeric($id_plus)){
            $medsci_Decryption_id=0;
        }
        $id_plus_md5=substr(md5($id_plus),8,16);
        $id_left = substr($id_plus_md5, 0, $iii);
        $id_right=substr($id_plus_md5,-$kkk);

        if($id_left==$id_left_1 and $id_right==$id_right_1){
            $medsci_Decryption_id=$id_plus-$mmm;
        }else{
            $medsci_Decryption_id=0;
        }
        return $medsci_Decryption_id;
    }
}


/*
 * 删除指定标签；删除或者保留标签内的内容
 */
if(!function_exists('strip_html_tags')){
    /**
     * 删除指定的标签和内容
     * @param array $tags 需要删除的标签数组
     * @param string $str 数据源
     * @param string $content 是否删除标签内的内容 默认为0保留内容    1不保留内容
     * @return string
     */
    function strip_html_tags($tags,$str,$content=0){
        if($content){
            $html=array();
            foreach ($tags as $tag) {
                $html[]='/(<'.$tag.'.*?>[\s|\S]*?<\/'.$tag.'>)/';
            }
            $data=preg_replace($html,'',$str);
        }else{
            $html=array();
            foreach ($tags as $tag) {
                $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
            }
            $data=preg_replace($html, '', $str);
        }
        return $data;
    }
}
/*
 * 获取html
 */
if(!function_exists('get_html_img')){

    function get_html_img($html){
        $strPreg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i';
        preg_match_all($strPreg, $html, $matches);
        return $matches;
    }
}



/**
 * 上传文件
 */
if (!function_exists('upload')) {
    function upload($file, $saveFolder, $patch = 'research')
    {
        $allowedExtensions = [
            'txt', 'pdf', 'csv',
            'png', 'jpg', 'jpeg', 'gif', "PNG", "JPG", "JPEG", "GIF",
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'wps', 'dps', 'et',
        ];
        $extension = $file->getClientOriginalExtension();

        /*判断后缀是否合法*/
        if (in_array($extension, $allowedExtensions)) {
            $image = Image::make($file);
            /*保存图片*/
            $date = date('Y-m-d');
            $upload_path = <<<EOF
resource/$patch/$saveFolder/$date/
EOF;

            $mysql_save_path = <<<EOF
$patch/$saveFolder/$date/
EOF;
            $path = public_path($upload_path);
            if (!is_dir($path)) {
                mkdir($path, 0766, true);
            }
            $filename = uniqid() . time() . '.' . $extension;
            $image->save($path . $filename);
            $returnData = [
                'result' => true,
                'msg' => '上传成功',
                'local' => $mysql_save_path . $filename,
                'extension' => $extension,
            ];
        } else {
            $returnData = [
                'result' => false,
                'msg' => '上传文件格式不正确',
            ];
        }
        return $returnData;
    }
}
/**
 * 上传文件
 */
if (!function_exists('upload_storage')) {
    function upload_storage($file, $filename = '', $saveFolder = 'research/common')
    {
        $allowedExtensions = [
            'txt', 'pdf', 'csv',
            'png', 'jpg', 'jpeg', 'gif', "PNG", "JPG", "JPEG", "GIF",
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'wps', 'dps', 'et',
        ];
        $extension = $file->getClientOriginalExtension();

        /*判断后缀是否合法*/
        if (in_array(strtolower($extension), $allowedExtensions)) {
            $image = Image::make($file);
            /*保存图片*/
            $date = date('Y-m-d');
            $upload_path = <<<EOF
resource/$saveFolder/$date/
EOF;

            $mysql_save_path = <<<EOF
$saveFolder/$date/
EOF;
            $path = storage_path($upload_path);
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            if($filename == '') $filename = uniqid() . time() . '.' . $extension;
            else $filename = $filename . '.' . $extension;

            $image->save($path . $filename);
            $returnData = [
                'result' => true,
                'msg' => '上传成功',
                'local' => $mysql_save_path . $filename,
                'extension' => $extension,
            ];
        } else {
            $returnData = [
                'result' => false,
                'msg' => '上传图片格式不正确',
            ];
        }
        return $returnData;
    }
}
/**
 * 上传文件
 */
if (!function_exists('upload_file_storage')) {
    function upload_file_storage($file, $saveFolder = 'common', $patch = 'research', $filename = '')
    {
        $allowedExtensions = [
            'txt', 'pdf', 'csv',
            'png', 'jpg', 'jpeg', 'gif', "PNG", "JPG", "JPEG", "GIF",
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'wps', 'dps', 'et',
        ];
        $extension = $file->getClientOriginalExtension();

        /*判断后缀是否合法*/
        if (in_array(strtolower($extension), $allowedExtensions)) {
            /*保存文件*/
            $date = date('Y-m-d');
            $upload_path = <<<EOF
resource/$patch/$saveFolder/$date/
EOF;

            $mysql_save_path = <<<EOF
$patch/$saveFolder/$date/
EOF;
            $path = storage_path($upload_path);
            if (!is_dir($path)) {
                mkdir($path, 0766, true);
            }
            if($filename == '') $filename = uniqid() . time() . '.' . $extension;
            else $filename = $filename . '.' . $extension;

            $clientName = $file -> getClientOriginalName();

            $file->move($path, $filename);
            $returnData = [
                'result' => true,
                'msg' => '上传成功',
                'local' => $mysql_save_path . $filename,
                'name' => $clientName,
                'extension' => $extension,
            ];
        } else {
            $returnData = [
                'result' => false,
                'msg' => '上传文件格式不正确',
            ];
        }
        return $returnData;
    }
}
/**
 * 上传文件
 */
if (!function_exists('upload_s')) {
    function upload_s($file, $saveFolder = 'common', $patch = 'research', $filename = '')
    {
        $allowedExtensions = [
            'txt', 'pdf', 'csv',
            'png', 'jpg', 'jpeg', 'gif', "PNG", "JPG", "JPEG", "GIF",
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'wps', 'dps', 'et',
        ];
        $extension = $file->getClientOriginalExtension();

        /*判断后缀是否合法*/
        if (in_array(strtolower($extension), $allowedExtensions)) {
            $image = Image::make($file);
            /*保存图片*/
            $date = date('Y-m-d');
            $upload_path = <<<EOF
resource/$patch/$saveFolder/$date/
EOF;

            $mysql_save_path = <<<EOF
$patch/$saveFolder/$date/
EOF;
            $path = storage_path($upload_path);
            if (!is_dir($path)) {
                mkdir($path, 0766, true);
            }
            if($filename == '') $filename = uniqid() . time() . '.' . $extension;
            else $filename = $filename . '.' . $extension;

            $image->save($path . $filename);
            $returnData = [
                'result' => true,
                'msg' => '上传成功',
                'local' => $mysql_save_path . $filename,
                'extension' => $extension,
            ];
        } else {
            $returnData = [
                'result' => false,
                'msg' => '上传图片格式不正确',
            ];
        }
        return $returnData;
    }
}

if (!function_exists('commonUpload'))
{
    function commonUpload($file, $saveFolder)
    {
        $allowedExtensions = [
            'txt', 'pdf', 'csv',
            'png', 'jpg', 'jpeg', 'gif', "PNG", "JPG", "JPEG", "GIF",
            'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'wps', 'dps', 'et',
        ];
        $extension = $file->getClientOriginalExtension();

        /*判断后缀是否合法*/
        if (in_array($extension, $allowedExtensions)) {

            /*保存图片*/
            $upload_path = 'resource/research/' . $saveFolder . '/' . date('Y-m-d') . '/';
            $mysql_save_path = 'research/' . $saveFolder . '/' . date('Y-m-d') . '/';
            $path = public_path($upload_path);
            if (!is_dir($path)) {
                mkdir($path, 0766, true);
            }
            $filename = uniqid() . time() . '.' . $extension;

            $file->move($path, $filename);

            $returnData = [
                'result' => true,
                'msg' => '上传成功',
                'local' => $mysql_save_path . $filename,
                'extension' => $extension,
            ];
        } else {
            $returnData = [
                'result' => false,
                'msg' => '上传文件格式不正确',
            ];
        }
        return $returnData;
    }
}

if (! function_exists('storage_path')) {

    function storage_path($path = '')
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

/*检查是否是手机号码*/
if(! function_exists('isMobile'))
{
    function isMobile($mobile)
    {
        if (!is_numeric($mobile)) return false;
//        return preg_match('#^13[\d]{9}$|^14[\d]{9}}$|^15[\d]{9}$|^17[\d]{9}$|^18[\d]{9}$#', $mobile) ? true : false;
        $rule = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[1,5,6,7,8,9]{1}\d{8}$#';
        return preg_match($rule, $mobile) ? true : false;
    }
}


/*检查是否是移动设备*/
if(!function_exists('isMobileEquipment')){
    function isMobileEquipment()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );

            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }

        return false;
    }
}



// 获得 今天（开始）时间戳
if(!function_exists('get_today_start_unix'))
{
    function get_today_start_unix() {
        return strtotime(date("Y-m-d",time()));
    }
}
// 获得 今天（结束）时间戳
if(!function_exists('get_today_ended_unix'))
{
    function get_today_ended_unix() {
        return strtotime(date("Y-m-d",time())) + (3600*24-1);
    }
}
// 获得 本周（开始）时间戳
if(!function_exists('get_this_week_start_unix'))
{
    function get_this_week_start_unix() {
        return (get_today_start_unix() - ((date("N")-1)*3600*24));
    }
}
// 获得 本周（结束）时间戳
if(!function_exists('get_this_week_ended_unix'))
{
    function get_this_week_ended_unix() {
        return get_this_week_start_unix() + (7*3600*24-1);
    }
}
// 获得 本月（开始）时间戳
if(!function_exists('get_this_month_start_unix'))
{
    function get_this_month_start_unix() {
        return strtotime(date("Y-m",time())."-1");
    }
}
// 获得 本月（结束）时间戳
if(!function_exists('get_this_month_ended_unix'))
{
    function get_this_month_ended_unix() {
        return (strtotime(date("Y",time())."-".(date("m",time()) + 1)."-1") - 1);
    }
}
// 获得 本年（开始）时间戳
if(!function_exists('get_this_year_start_unix'))
{
    function get_this_year_start_unix() {
        return strtotime(date("Y",time())."-1-1");
    }
}
// 获得 本年（结束）时间戳
if(!function_exists('get_this_year_ended_unix'))
{
    function get_this_year_ended_unix() {
        return strtotime(date("Y",time())."-12-31 23:59:59");
    }
}



// 获取 某一天（开始）时间戳
if(!function_exists('get_day_start_unix'))
{
    function get_day_start_unix($stamp) {
        return strtotime(date("Y-m-d",$stamp));
    }
}
// 获取 某一天（结束）时间戳
if(!function_exists('get_day_ended_unix'))
{
    function get_day_ended_unix($stamp) {
        return strtotime(date("Y-m-d",$stamp)) + (3600*24-1);
    }
}
// 获取 某一周（开始）时间戳
if(!function_exists('get_week_start_unix'))
{
    function get_week_start_unix($stamp) {
        return ( get_day_start_unix($stamp) - ((date("N",$stamp)-1)*3600*24) );
    }
}
// 获取 某一周（结束）时间戳
if(!function_exists('get_week_ended_unix'))
{
    function get_week_ended_unix($stamp) {
        return get_week_start_unix($stamp) + (7*3600*24-1);
    }
}
// 获取 某一月（开始）时间戳
if(!function_exists('get_month_start_unix'))
{
    function get_month_start_unix($stamp) {
        return strtotime(date("Y-m",$stamp)."-1");
    }
}
// 获取 某一月（结束）时间戳
if(!function_exists('get_month_ended_unix'))
{
    function get_month_ended_unix($stamp) {
        $yearN = date("Y",$stamp);
        $monthN = date("m",$stamp);
        if($monthN == 12) {
            $yearN = $yearN +1;
            $monthN = 1;
        } else {
            $monthN = $monthN + 1;
        }
        $timestr = $yearN."-".$monthN."-1";
        //return (strtotime(date("Y",$stamp)."-".(date("m",$stamp) + 1)."-1") - 1);
        return strtotime($timestr) - 1;
    }
}
// 获取 某一年（开始）时间戳
if(!function_exists('get_year_start_unix'))
{
    function get_year_start_unix($stamp) {
        return strtotime(date("Y",$stamp)."-1-1");
    }
}
// 获取 某一年（结束）时间戳
if(!function_exists('get_year_ended_unix'))
{
    function get_year_ended_unix($stamp) {
        return strtotime(date("Y",$stamp)."-12-31 23:59:59");
    }
}



// 是否同一天 return 0 || 1
if(!function_exists('is_same_day'))
{
    function is_same_day($stamp_1,$stamp_2) {
        if( get_day_start_unix($stamp_1) == get_day_start_unix($stamp_2) ) return 1;
        else return 0;
    }
}
// 是否同一周 return 0 || 1
if(!function_exists('is_same_week'))
{
    function is_same_week($stamp_1,$stamp_2) {
        if( get_week_start_unix($stamp_1) == get_week_start_unix($stamp_2) ) return 1;
        else return 0;
    }
}
// 是否同一月 return 0 || 1
if(!function_exists('is_same_month'))
{
    function is_same_month($stamp_1,$stamp_2) {
        if( get_month_start_unix($stamp_1) == get_month_start_unix($stamp_2) ) return 1;
        else return 0;
    }
}
// 是否同一年 return 0 || 1
if(!function_exists('is_same_year'))
{
    function is_same_year($stamp_1,$stamp_2) {
        if( get_year_start_unix($stamp_1) == get_year_start_unix($stamp_2) ) return 1;
        else return 0;
    }
}


if(!function_exists('response_success'))
{
    function response_success($data = "",$msg = "操作成功！") {
        return json_encode([
            'success' => true,
            'status' => 200,
            'code' => 200,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
if(!function_exists('response_notice'))
{
    function response_notice($data = "",$msg = "") {
        return json_encode([
            'success' => false,
            'status' => 201,
            'code' => 201,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
if(!function_exists('response_fail'))
{
    function response_fail($data = "",$msg = "程序出错，请重试！") {
        return json_encode([
            'success' => false,
            'status' => 500,
            'code' => 500,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
if(!function_exists('response_error'))
{
    function response_error($data = "",$msg = "参数有误！") {
        return json_encode([
            'success' => false,
            'status' => 422,
            'code' => 422,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}

if(!function_exists('datatable_response'))
{
    function datatable_response($data = array(), $draw = 0, $total = 100, $message = 'success', $status_code = '200')
    {
        if($status_code == 304) exit(header('server-response-code: 304'));

        if(!empty($data)) {
            $result = array(
                'message'			=> $message,
                'data' 				=> $data,
                'draw' 				=> $draw + 1,
                'recordsTotal'		=> $total,
                'recordsFiltered'	=> $total,
            );
        } else {
            $result = array(
                'message'			=> $message,
                'data'				=> $data,
                'draw'				=> 0,
                'recordsTotal'		=> 0,
                'recordsFiltered'	=> 0,
            );
        }
        return Response::json($result);
    }
}









//if(!function_exists('getClientIps'))
//{
//    function getClientIps()
//    {
//        $clientIps = array();
//        $ip = $this->server->get('REMOTE_ADDR');
//
//        if (!$this->isFromTrustedProxy()) {
//            return array($ip);
//        }
//
//        $hasTrustedForwardedHeader = self::$trustedHeaders[self::HEADER_FORWARDED] && $this->headers->has(self::$trustedHeaders[self::HEADER_FORWARDED]);
//        $hasTrustedClientIpHeader = self::$trustedHeaders[self::HEADER_CLIENT_IP] && $this->headers->has(self::$trustedHeaders[self::HEADER_CLIENT_IP]);
//
//        if ($hasTrustedForwardedHeader) {
//            $forwardedHeader = $this->headers->get(self::$trustedHeaders[self::HEADER_FORWARDED]);
//            preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwardedHeader, $matches);
//            $forwardedClientIps = $matches[3];
//
//            $forwardedClientIps = $this->normalizeAndFilterClientIps($forwardedClientIps, $ip);
//            $clientIps = $forwardedClientIps;
//        }
//
//        if ($hasTrustedClientIpHeader) {
//            $xForwardedForClientIps = array_map('trim', explode(',', $this->headers->get(self::$trustedHeaders[self::HEADER_CLIENT_IP])));
//
//            $xForwardedForClientIps = $this->normalizeAndFilterClientIps($xForwardedForClientIps, $ip);
//            $clientIps = $xForwardedForClientIps;
//        }
//
//        if ($hasTrustedForwardedHeader && $hasTrustedClientIpHeader && $forwardedClientIps !== $xForwardedForClientIps) {
//            throw new ConflictingHeadersException('The request has both a trusted Forwarded header and a trusted Client IP header, conflicting with each other with regards to the originating IP addresses of the request. This is the result of a misconfiguration. You should either configure your proxy only to send one of these headers, or configure Symfony to distrust one of them.');
//        }
//
//        if (!$hasTrustedForwardedHeader && !$hasTrustedClientIpHeader) {
//            return $this->normalizeAndFilterClientIps(array(), $ip);
//        }
//
//        return $clientIps;
//    }
//
//}


/**
 * 加密处理
 *
 * @param string $key
 * @param string $message
 * @param string $encrypt
 * @param string $mode
 * @param string $iv
 * @param string $padding
 * @return string
 */
function des ($key, $message, $encrypt, $mode, $iv) {
    $message0 = $message;
    //declaring this locally speeds things up a bit
    $spfunction1 = array (0x1010400,0,0x10000,0x1010404,0x1010004,0x10404,0x4,0x10000,0x400,0x1010400,0x1010404,0x400,0x1000404,0x1010004,0x1000000,0x4,0x404,0x1000400,0x1000400,0x10400,0x10400,0x1010000,0x1010000,0x1000404,0x10004,0x1000004,0x1000004,0x10004,0,0x404,0x10404,0x1000000,0x10000,0x1010404,0x4,0x1010000,0x1010400,0x1000000,0x1000000,0x400,0x1010004,0x10000,0x10400,0x1000004,0x400,0x4,0x1000404,0x10404,0x1010404,0x10004,0x1010000,0x1000404,0x1000004,0x404,0x10404,0x1010400,0x404,0x1000400,0x1000400,0,0x10004,0x10400,0,0x1010004);
    $spfunction2 = array (-0x7fef7fe0,-0x7fff8000,0x8000,0x108020,0x100000,0x20,-0x7fefffe0,-0x7fff7fe0,-0x7fffffe0,-0x7fef7fe0,-0x7fef8000,-0x80000000,-0x7fff8000,0x100000,0x20,-0x7fefffe0,0x108000,0x100020,-0x7fff7fe0,0,-0x80000000,0x8000,0x108020,-0x7ff00000,0x100020,-0x7fffffe0,0,0x108000,0x8020,-0x7fef8000,-0x7ff00000,0x8020,0,0x108020,-0x7fefffe0,0x100000,-0x7fff7fe0,-0x7ff00000,-0x7fef8000,0x8000,-0x7ff00000,-0x7fff8000,0x20,-0x7fef7fe0,0x108020,0x20,0x8000,-0x80000000,0x8020,-0x7fef8000,0x100000,-0x7fffffe0,0x100020,-0x7fff7fe0,-0x7fffffe0,0x100020,0x108000,0,-0x7fff8000,0x8020,-0x80000000,-0x7fefffe0,-0x7fef7fe0,0x108000);
    $spfunction3 = array (0x208,0x8020200,0,0x8020008,0x8000200,0,0x20208,0x8000200,0x20008,0x8000008,0x8000008,0x20000,0x8020208,0x20008,0x8020000,0x208,0x8000000,0x8,0x8020200,0x200,0x20200,0x8020000,0x8020008,0x20208,0x8000208,0x20200,0x20000,0x8000208,0x8,0x8020208,0x200,0x8000000,0x8020200,0x8000000,0x20008,0x208,0x20000,0x8020200,0x8000200,0,0x200,0x20008,0x8020208,0x8000200,0x8000008,0x200,0,0x8020008,0x8000208,0x20000,0x8000000,0x8020208,0x8,0x20208,0x20200,0x8000008,0x8020000,0x8000208,0x208,0x8020000,0x20208,0x8,0x8020008,0x20200);
    $spfunction4 = array (0x802001,0x2081,0x2081,0x80,0x802080,0x800081,0x800001,0x2001,0,0x802000,0x802000,0x802081,0x81,0,0x800080,0x800001,0x1,0x2000,0x800000,0x802001,0x80,0x800000,0x2001,0x2080,0x800081,0x1,0x2080,0x800080,0x2000,0x802080,0x802081,0x81,0x800080,0x800001,0x802000,0x802081,0x81,0,0,0x802000,0x2080,0x800080,0x800081,0x1,0x802001,0x2081,0x2081,0x80,0x802081,0x81,0x1,0x2000,0x800001,0x2001,0x802080,0x800081,0x2001,0x2080,0x800000,0x802001,0x80,0x800000,0x2000,0x802080);
    $spfunction5 = array (0x100,0x2080100,0x2080000,0x42000100,0x80000,0x100,0x40000000,0x2080000,0x40080100,0x80000,0x2000100,0x40080100,0x42000100,0x42080000,0x80100,0x40000000,0x2000000,0x40080000,0x40080000,0,0x40000100,0x42080100,0x42080100,0x2000100,0x42080000,0x40000100,0,0x42000000,0x2080100,0x2000000,0x42000000,0x80100,0x80000,0x42000100,0x100,0x2000000,0x40000000,0x2080000,0x42000100,0x40080100,0x2000100,0x40000000,0x42080000,0x2080100,0x40080100,0x100,0x2000000,0x42080000,0x42080100,0x80100,0x42000000,0x42080100,0x2080000,0,0x40080000,0x42000000,0x80100,0x2000100,0x40000100,0x80000,0,0x40080000,0x2080100,0x40000100);
    $spfunction6 = array (0x20000010,0x20400000,0x4000,0x20404010,0x20400000,0x10,0x20404010,0x400000,0x20004000,0x404010,0x400000,0x20000010,0x400010,0x20004000,0x20000000,0x4010,0,0x400010,0x20004010,0x4000,0x404000,0x20004010,0x10,0x20400010,0x20400010,0,0x404010,0x20404000,0x4010,0x404000,0x20404000,0x20000000,0x20004000,0x10,0x20400010,0x404000,0x20404010,0x400000,0x4010,0x20000010,0x400000,0x20004000,0x20000000,0x4010,0x20000010,0x20404010,0x404000,0x20400000,0x404010,0x20404000,0,0x20400010,0x10,0x4000,0x20400000,0x404010,0x4000,0x400010,0x20004010,0,0x20404000,0x20000000,0x400010,0x20004010);
    $spfunction7 = array (0x200000,0x4200002,0x4000802,0,0x800,0x4000802,0x200802,0x4200800,0x4200802,0x200000,0,0x4000002,0x2,0x4000000,0x4200002,0x802,0x4000800,0x200802,0x200002,0x4000800,0x4000002,0x4200000,0x4200800,0x200002,0x4200000,0x800,0x802,0x4200802,0x200800,0x2,0x4000000,0x200800,0x4000000,0x200800,0x200000,0x4000802,0x4000802,0x4200002,0x4200002,0x2,0x200002,0x4000000,0x4000800,0x200000,0x4200800,0x802,0x200802,0x4200800,0x802,0x4000002,0x4200802,0x4200000,0x200800,0,0x2,0x4200802,0,0x200802,0x4200000,0x800,0x4000002,0x4000800,0x800,0x200002);
    $spfunction8 = array (0x10001040,0x1000,0x40000,0x10041040,0x10000000,0x10001040,0x40,0x10000000,0x40040,0x10040000,0x10041040,0x41000,0x10041000,0x41040,0x1000,0x40,0x10040000,0x10000040,0x10001000,0x1040,0x41000,0x40040,0x10040040,0x10041000,0x1040,0,0,0x10040040,0x10000040,0x10001000,0x41040,0x40000,0x41040,0x40000,0x10041000,0x1000,0x40,0x10040040,0x1000,0x41040,0x10001000,0x40,0x10000040,0x10040000,0x10040040,0x10000000,0x40000,0x10001040,0,0x10041040,0x40040,0x10000040,0x10040000,0x10001000,0x10001040,0,0x10041040,0x41000,0x41000,0x1040,0x1040,0x40040,0x10000000,0x10041000);
    $masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

    //create the 16 or 48 subkeys we will need
    $keys = des_createKeys ($key);
    $m=0;
    $len = strlen($message);
    //如果加密，则需要填充
    if($encrypt==1){
        if($len%8==1){
            for($i=0;$i<7;$i++)
                $message.=chr(7);
        }
        if($len%8==2){
            for($i=0;$i<6;$i++)
                $message.=chr(6);
        }
        if($len%8==3){
            for($i=0;$i<5;$i++)
                $message.=chr(5);
        }

        if($len%8==4){
            for($i=0;$i<4;$i++)
                $message.=chr(4);
        }
        if($len%8==5){
            for($i=0;$i<3;$i++)
                $message.=chr(3);
        }
        if($len%8==6){
            for($i=0;$i<2;$i++)
                $message.=chr(2);
        }
        if($len%8==7){
            for($i=0;$i<1;$i++)
                $message.=chr(1);
        }
        if($len%8==0){
            for($i=0;$i<8;$i++)
                $message.=chr(8);
            $len = $len + 8;
        }
    }
//echo "message:".$message;
//echo "<br>";
    $chunk = 0;
//set up the loops for single and triple des
    $iterations = ((count($keys) == 32) ? 3 : 9); //single or triple des
    if ($iterations == 3) {
        $looping = (($encrypt) ? array (0, 32, 2) : array (30, -2, -2));
    }
    else {$looping = (($encrypt) ? array (0, 32, 2, 62, 30, -2, 64, 96, 2) : array (94, 62, -2, 32, 64, 2, 30, -2, -2));
    }

//store the result here
    $result = "";
    $tempresult = "";

    if ($mode == 1) { //CBC mode
        $cbcleft = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
        $cbcright = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
        $m=0;
    }

//loop through each 64 bit chunk of the message
    while ($m < $len) {
        $left = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});
        $right = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});

        //for Cipher Block Chaining mode, xor the message with the previous result
        if ($mode == 1) {
            if ($encrypt) {
                $left ^= $cbcleft; $right ^= $cbcright;
            } else {$cbcleft2 = $cbcleft; $cbcright2 = $cbcright; $cbcleft = $left; $cbcright = $right;
            }
        }

        //first each 64 but chunk of the message must be permuted according to IP
        $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
        $temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
        $temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
        $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
        $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

        $left = (($left << 1) | ($left >> 31 & $masks[31]));
        $right = (($right << 1) | ($right >> 31 & $masks[31]));

        //do this either 1 or 3 times for each chunk of the message
        for ($j=0; $j<$iterations; $j+=3) {
            $endloop = $looping[$j+1];
            $loopinc = $looping[$j+2];
            //now go through and perform the encryption or decryption
            for ($i=$looping[$j]; $i!=$endloop; $i+=$loopinc) { //for efficiency
                $right1 = $right ^ $keys[$i];
                $right2 = (($right >> 4 & $masks[4]) | ($right << 28 & 0xffffffff)) ^ $keys[$i+1];
                //the result is attained by passing these bytes through the S selection functions
                $temp = $left;
                $left = $right;
                $right = $temp ^ ($spfunction2[($right1 >> 24 & $masks[24]) & 0x3f] | $spfunction4[($right1 >> 16 & $masks[16]) & 0x3f]
                        | $spfunction6[($right1 >>  8 & $masks[8]) & 0x3f] | $spfunction8[$right1 & 0x3f]
                        | $spfunction1[($right2 >> 24 & $masks[24]) & 0x3f] | $spfunction3[($right2 >> 16 & $masks[16]) & 0x3f]
                        | $spfunction5[($right2 >>  8 & $masks[8]) & 0x3f] | $spfunction7[$right2 & 0x3f]);
            }
            $temp = $left; $left = $right; $right = $temp; //unreverse left and right
        } //for either 1 or 3 iterations

        //move then each one bit to the right
        $left = (($left >> 1 & $masks[1]) | ($left << 31));
        $right = (($right >> 1 & $masks[1]) | ($right << 31));

        //now perform IP-1, which is IP in the opposite direction
        $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
        $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
        $temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
        $temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
        $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);

        //for Cipher Block Chaining mode, xor the message with the previous result
        if ($mode == 1) {
            if ($encrypt) {
                $cbcleft = $left; $cbcright = $right;
            } else {$left ^= $cbcleft2; $right ^= $cbcright2;
            }
        }
        $tempresult .= (chr($left>>24 & $masks[24]) . chr(($left>>16 & $masks[16]) & 0xff) . chr(($left>>8 & $masks[8]) & 0xff) . chr($left & 0xff) . chr($right>>24 & $masks[24]) . chr(($right>>16 & $masks[16]) & 0xff) . chr(($right>>8 & $masks[8]) & 0xff) . chr($right & 0xff));

        $chunk += 8;
        if ($chunk == 512) {
            $result .= $tempresult; $tempresult = ""; $chunk = 0;
        }
    } //for every 8 characters, or 64 bits in the message

//return the result as an array
    return ($result . $tempresult);
} //end of des

//des_createKeys
//this takes as input a 64 bit key (even though only 56 bits are used)
//as an array of 2 integers, and returns 16 48 bit keys
function des_createKeys ($key) {
    //declaring this locally speeds things up a bit
    $pc2bytes0  = array (0,0x4,0x20000000,0x20000004,0x10000,0x10004,0x20010000,0x20010004,0x200,0x204,0x20000200,0x20000204,0x10200,0x10204,0x20010200,0x20010204);
    $pc2bytes1  = array (0,0x1,0x100000,0x100001,0x4000000,0x4000001,0x4100000,0x4100001,0x100,0x101,0x100100,0x100101,0x4000100,0x4000101,0x4100100,0x4100101);
    $pc2bytes2  = array (0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808,0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808);
    $pc2bytes3  = array (0,0x200000,0x8000000,0x8200000,0x2000,0x202000,0x8002000,0x8202000,0x20000,0x220000,0x8020000,0x8220000,0x22000,0x222000,0x8022000,0x8222000);
    $pc2bytes4  = array (0,0x40000,0x10,0x40010,0,0x40000,0x10,0x40010,0x1000,0x41000,0x1010,0x41010,0x1000,0x41000,0x1010,0x41010);
    $pc2bytes5  = array (0,0x400,0x20,0x420,0,0x400,0x20,0x420,0x2000000,0x2000400,0x2000020,0x2000420,0x2000000,0x2000400,0x2000020,0x2000420);
    $pc2bytes6  = array (0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002,0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002);
    $pc2bytes7  = array (0,0x10000,0x800,0x10800,0x20000000,0x20010000,0x20000800,0x20010800,0x20000,0x30000,0x20800,0x30800,0x20020000,0x20030000,0x20020800,0x20030800);
    $pc2bytes8  = array (0,0x40000,0,0x40000,0x2,0x40002,0x2,0x40002,0x2000000,0x2040000,0x2000000,0x2040000,0x2000002,0x2040002,0x2000002,0x2040002);
    $pc2bytes9  = array (0,0x10000000,0x8,0x10000008,0,0x10000000,0x8,0x10000008,0x400,0x10000400,0x408,0x10000408,0x400,0x10000400,0x408,0x10000408);
    $pc2bytes10 = array (0,0x20,0,0x20,0x100000,0x100020,0x100000,0x100020,0x2000,0x2020,0x2000,0x2020,0x102000,0x102020,0x102000,0x102020);
    $pc2bytes11 = array (0,0x1000000,0x200,0x1000200,0x200000,0x1200000,0x200200,0x1200200,0x4000000,0x5000000,0x4000200,0x5000200,0x4200000,0x5200000,0x4200200,0x5200200);
    $pc2bytes12 = array (0,0x1000,0x8000000,0x8001000,0x80000,0x81000,0x8080000,0x8081000,0x10,0x1010,0x8000010,0x8001010,0x80010,0x81010,0x8080010,0x8081010);
    $pc2bytes13 = array (0,0x4,0x100,0x104,0,0x4,0x100,0x104,0x1,0x5,0x101,0x105,0x1,0x5,0x101,0x105);
    $masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

    //how many iterations (1 for des, 3 for triple des)
    //  $iterations = ((strlen($key) > 8) ? 3 : 1); //changed by Paul 16/6/2007 to use Triple DES for 9+ byte keys
    $iterations = ((strlen($key) > 24) ? 3 : 1); //changed by Paul 16/6/2007 to use Triple DES for 9+ byte keys
    //stores the return keys
    $keys = array (); // size = 32 * iterations but you don't specify this in php
    //now define the left shifts which need to be done
    $shifts = array (0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0);
    //other variables
    $m=0;
    $n=0;

    for ($j=0; $j<$iterations; $j++) { //either 1 or 3 iterations
        $left = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});
        $right = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});

        $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
        $temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
        $temp = (($left >> 2 & $masks[2]) ^ $right) & 0x33333333; $right ^= $temp; $left ^= ($temp << 2);
        $temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
        $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
        $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
        $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

        //the right side needs to be shifted and to get the last four bits of the left side
        $temp = ($left << 8) | (($right >> 20 & $masks[20]) & 0x000000f0);
        //left needs to be put upside down
        $left = ($right << 24) | (($right << 8) & 0xff0000) | (($right >> 8 & $masks[8]) & 0xff00) | (($right >> 24 & $masks[24]) & 0xf0);
        $right = $temp;

        //now go through and perform these shifts on the left and right keys
        for ($i=0; $i < count($shifts); $i++) {
            //shift the keys either one or two bits to the left
            if ($shifts[$i] > 0) {
                $left = (($left << 2) | ($left >> 26 & $masks[26]));
                $right = (($right << 2) | ($right >> 26 & $masks[26]));
            } else {
                $left = (($left << 1) | ($left >> 27 & $masks[27]));
                $right = (($right << 1) | ($right >> 27 & $masks[27]));
            }
            $left = $left & -0xf;
            $right = $right & -0xf;

            //now apply PC-2, in such a way that E is easier when encrypting or decrypting
            //this conversion will look like PC-2 except only the last 6 bits of each byte are used
            //rather than 48 consecutive bits and the order of lines will be according to
            //how the S selection functions will be applied: S2, S4, S6, S8, S1, S3, S5, S7
            $lefttemp = $pc2bytes0[$left >> 28 & $masks[28]] | $pc2bytes1[($left >> 24 & $masks[24]) & 0xf]
                | $pc2bytes2[($left >> 20 & $masks[20]) & 0xf] | $pc2bytes3[($left >> 16 & $masks[16]) & 0xf]
                | $pc2bytes4[($left >> 12 & $masks[12]) & 0xf] | $pc2bytes5[($left >> 8 & $masks[8]) & 0xf]
                | $pc2bytes6[($left >> 4 & $masks[4]) & 0xf];
            $righttemp = $pc2bytes7[$right >> 28 & $masks[28]] | $pc2bytes8[($right >> 24 & $masks[24]) & 0xf]
                | $pc2bytes9[($right >> 20 & $masks[20]) & 0xf] | $pc2bytes10[($right >> 16 & $masks[16]) & 0xf]
                | $pc2bytes11[($right >> 12 & $masks[12]) & 0xf] | $pc2bytes12[($right >> 8 & $masks[8]) & 0xf]
                | $pc2bytes13[($right >> 4 & $masks[4]) & 0xf];
            $temp = (($righttemp >> 16 & $masks[16]) ^ $lefttemp) & 0x0000ffff;
            $keys[$n++] = $lefttemp ^ $temp; $keys[$n++] = $righttemp ^ ($temp << 16);
        }
    }

    return $keys;
}

/**
 * 对字符串进行通用加密处理
 *
 * @param string $str 被加密的字符串
 * @return string 加密结果
 */
function  basic_encrypt( $str, $key = null){
    $key = $key? $key:"passwork";
    return base64_encode( des( $key, $str, 1, 1, $key ) );
}

/**
 * 对字符串进行通用解密处理，去掉最后的特殊字符串
 *
 * @param string $str 被加密的字符串
 * @return string 加密结果
 */
function  basic_decrypt( $str, $key = null){
    $key = $key? $key:"passwork";
    $result =  des( $key, base64_decode($str), 0, 1, $key );
    return str_replace( chr(7), '',$result ); //去掉特殊字符
}