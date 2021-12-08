<?php

use think\response\Json;
use think\Response;
use think\facade\Config;
use think\facade\Cache;

if (!function_exists('getFirstCharters')) {
    function getFirstCharters($str)
    {
        if (empty($str)) {
            return '';
        }
        //取出参数字符串中的首个字符
        $temp_str = substr($str, 0, 1);
        if (ord($temp_str) > 127) {
            $str = substr($str, 0, 3);
        } else {
            $str = $temp_str;
            $fchar = ord($str[0]);
            if ($fchar >= ord('A') && $fchar <= ord('z')) {
                return strtoupper($temp_str);
            } else {
                return null;
            }
        }
        $s1 = iconv('UTF-8', 'gb2312//IGNORE', $str);
        if (empty($s1)) {
            return null;
        }
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        if (empty($s2)) {
            return null;
        }
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
        return rare_words($asc);
    }
}
if (!function_exists('rare_words')) {
    function rare_words($asc='')
    {
        $rare_arr = array(
                -3652=>array('word'=>"窦",'first_char'=>'D'),
                -8503=>array('word'=>"奚",'first_char'=>'X'),
                -9286=>array('word'=>"酆",'first_char'=>'F'),
                -7761=>array('word'=>"岑",'first_char'=>'C'),
                -5128=>array('word'=>"滕",'first_char'=>'T'),
                -9479=>array('word'=>"邬",'first_char'=>'W'),
                -5456=>array('word'=>"臧",'first_char'=>'Z'),
                -7223=>array('word'=>"闵",'first_char'=>'M'),
                -2877=>array('word'=>"裘",'first_char'=>'Q'),
                -6191=>array('word'=>"缪",'first_char'=>'M'),
                -5414=>array('word'=>"贲",'first_char'=>'B'),
                -4102=>array('word'=>"嵇",'first_char'=>'J'),
                -8969=>array('word'=>"荀",'first_char'=>'X'),
                -4938=>array('word'=>"於",'first_char'=>'Y'),
                -9017=>array('word'=>"芮",'first_char'=>'R'),
                -2848=>array('word'=>"羿",'first_char'=>'Y'),
                -9477=>array('word'=>"邴",'first_char'=>'B'),
                -9485=>array('word'=>"隗",'first_char'=>'K'),
                -6731=>array('word'=>"宓",'first_char'=>'M'),
                -9299=>array('word'=>"郗",'first_char'=>'X'),
                -5905=>array('word'=>"栾",'first_char'=>'L'),
                -4393=>array('word'=>"钭",'first_char'=>'T'),
                -9300=>array('word'=>"郜",'first_char'=>'G'),
                -8706=>array('word'=>"蔺",'first_char'=>'L'),
                -3613=>array('word'=>"胥",'first_char'=>'X'),
                -8777=>array('word'=>"莘",'first_char'=>'S'),
                -6708=>array('word'=>"逄",'first_char'=>'P'),
                -9302=>array('word'=>"郦",'first_char'=>'L'),
                -5965=>array('word'=>"璩",'first_char'=>'Q'),
                -6745=>array('word'=>"濮",'first_char'=>'P'),
                -4888=>array('word'=>"扈",'first_char'=>'H'),
                -9309=>array('word'=>"郏",'first_char'=>'J'),
                -5428=>array('word'=>"晏",'first_char'=>'Y'),
                -2849=>array('word'=>"暨",'first_char'=>'J'),
                -7206=>array('word'=>"阙",'first_char'=>'Q'),
                -4945=>array('word'=>"殳",'first_char'=>'S'),
                -9753=>array('word'=>"夔",'first_char'=>'K'),
                -10041=>array('word'=>"厍",'first_char'=>'S'),
                -5429=>array('word'=>"晁",'first_char'=>'C'),
                -2396=>array('word'=>"訾",'first_char'=>'Z'),
                -7205=>array('word'=>"阚",'first_char'=>'K'),
                -10049=>array('word'=>"乜",'first_char'=>'N'),
                -10015=>array('word'=>"蒯",'first_char'=>'K'),
                -3133=>array('word'=>"竺",'first_char'=>'Z'),
                -6698=>array('word'=>"逯",'first_char'=>'L'),
                -9799=>array('word'=>"俟",'first_char'=>'Q'),
                -6749=>array('word'=>"澹",'first_char'=>'T'),
                -7220=>array('word'=>"闾",'first_char'=>'L'),
                -10047=>array('word'=>"亓",'first_char'=>'Q'),
                -10005=>array('word'=>"仉",'first_char'=>'Z'),
                -3417=>array('word'=>"颛",'first_char'=>'Z'),
                -6431=>array('word'=>"驷",'first_char'=>'S'),
                -7226=>array('word'=>"闫",'first_char'=>'Y'),
                -9293=>array('word'=>"鄢",'first_char'=>'Y'),
                -6205=>array('word'=>"缑",'first_char'=>'G'),
                -9764=>array('word'=>"佘",'first_char'=>'S'),
                -9818=>array('word'=>"佴",'first_char'=>'N'),
                -9509=>array('word'=>"谯",'first_char'=>'Q'),
                -3122=>array('word'=>"笪",'first_char'=>'D'),
                -9823=>array('word'=>"佟",'first_char'=>'T'),
            );
        if (array_key_exists($asc, $rare_arr) && $rare_arr[$asc]['first_char']) {
            return $rare_arr[$asc]['first_char'] ;
        } else {
            return null;
        }
    }
}
if (!function_exists('getfiles')) {
    /**
         * 遍历获取目录下的指定类型的文件
         * @param $path
         * @param array $files
         * @return array
         */
    function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) {
            return null;
        }
        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = $file;
//                      $files[] = array('url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])), 'mtime' => filemtime($path2));
                    }
                }
            }
        }
        return $files;
    }
}
if (!function_exists('rand_string')) {
    function rand_string($len = 6, $type = '', $addChars = '')
    {
        $str = '';
        switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            $chars = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借' . $addChars;
            break;
        default:
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
        if ($len > 10) {//位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }
}
if (!function_exists('PSW_MD6')) {
    function PSW_MD6($value)
    {
        $pwd = md5(substr(md5(substr(md5($value . 'HaoMVC'), 8)), 5) . $value);
        return $pwd;
    }
}
if (!function_exists('writeHtml')) {
    function writeHtml(string $cacheFile, string $content)
    {
    	$cacheFile = public_path().$cacheFile;
        // 检测模板目录
        $dir = dirname($cacheFile);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // 生成模板缓存文件
        if (false === file_put_contents($cacheFile, $content)) {
            return "文件生成错误，文件路径：".$cacheFile;
        }else{
        	return $cacheFile." 文件生成成功！";
        }
    }
}
if (!function_exists('Success')) {
    function Success($msg='success', $data = [], $code = 20000): Json
    {
        header('Content-Type:application/json; charset=utf-8');
        $jsonData = is_array($msg)?$msg:['code'=>$code,'message'=>$msg,'data'=>$data];
        return Response::create($jsonData, 'json', 200)->header([])->options([]);
    }
}
if (!function_exists('Error')) {
    function Error($msg='error', $code = 1, $data = [])
    {
        header('Content-Type:application/json; charset=utf-8');
        $jsonData = is_array($msg)?$msg:['code'=>$code,'message'=>$msg,'data'=>$data];
        return Response::create($jsonData, 'json', 200)->send();
    }
}