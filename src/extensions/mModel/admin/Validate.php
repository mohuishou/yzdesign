<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2017/3/4
 * Time: 15:00
 */

Class Validate{
    public function valid($k,$rule){
        $all_rule=[
            "empty"=>"isEmpty",

        ];

        $error_msg=[
            "empty"=>"不能为空！",
        ];

        if (!isset($all_rule[$rule])){
            throw new \Exception($rule."验证规则不存在");
        }

        $func=$all_rule[$rule];

        if (!$this->$func($k)){
            return $k.$error_msg[$rule];
        }

    }

    private function isEmpty($val)
    {
        if (!is_string($val)) return false; //是否是字符串类型

        if (empty($val)) return false; //是否已设定

        if ($val=='') return false; //是否为空

        return true;
    }
    /*
     -----------------------------------------------------------
    函数名称：isNumber
    简要描述：检查输入的是否为数字
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isNumber($val)
    {
        if(ereg("^[0-9]+$", $val))
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称：isPhone
    简要描述：检查输入的是否为电话
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isPhone($val)
    {
        //eg: xxx-xxxxxxxx-xxx | xxxx-xxxxxxx-xxx ...
        if(ereg("^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$",$val))
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称：isPostcode
    简要描述：检查输入的是否为邮编
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isPostcode($val)
    {
        if(ereg("^[0-9]{4,6}$",$val))
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称：isEmail
    简要描述：邮箱地址合法性检查
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isEmail($val,$domain="")
    {
        if(!$domain)
        {
            if( preg_match("/^[a-z0-9-_.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $val) )
            {
                return true;
            }
            else
                return false;
        }
        else
        {
            if( preg_match("/^[a-z0-9-_.]+@".$domain."$/i", $val) )
            {
                return true;
            }
            else
                return false;
        }
    }//end func

    /*
     -----------------------------------------------------------
    函数名称：isName
    简要描述：姓名昵称合法性检查，只能输入中文英文
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isName($val)
    {
        if( preg_match("/^[\x80-\xffa-zA-Z0-9]{3,60}$/", $val) )//2008-7-24
        {
            return true;
        }
        return false;
    }//end func


    /*
     -----------------------------------------------------------
    函数名称:isStrLength($theelement, $min, $max)
    简要描述:检查字符串长度是否符合要求
    输入:mixed (字符串，最小长度，最大长度)
    输出:boolean
    修改日志:------
    -----------------------------------------------------------
    */
    private function isStrLength($val, $min, $max)
    {
        $theelement= trim($val);
        if(ereg("^[a-zA-Z0-9]{".$min.",".$max."}$",$val))
            return true;
        return false;
    }


    /*
     -----------------------------------------------------------
    函数名称:isNumberLength($theelement, $min, $max)
    简要描述:检查字符串长度是否符合要求
    输入:mixed (字符串，最小长度，最大长度)
    输出:boolean
    修改日志:------
    -----------------------------------------------------------
    */
    private function isNumLength($val, $min, $max)
    {
        $theelement= trim($val);
        if(ereg("^[0-9]{".$min.",".$max."}$",$val))
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称:isNumberLength($theelement, $min, $max)
    简要描述:检查字符串长度是否符合要求
    输入:mixed (字符串，最小长度，最大长度)
    输出:boolean
    修改日志:------
    -----------------------------------------------------------
    */
    private function isEngLength($val, $min, $max)
    {
        $theelement= trim($val);
        if(ereg("^[a-zA-Z]{".$min.",".$max."}$",$val))
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称：isEnglish
    简要描述：检查输入是否为英文
    输入：string
    输出：boolean
    作者：------
    修改日志：------
    -----------------------------------------------------------
    */
    private function isEnglish($theelement)
    {
        if( ereg("[\x80-\xff].",$theelement) )
        {
            Return false;
        }
        Return true;
    }


    private function isChinese($sInBuf)//正确的函数
    {
        if (preg_match("/^[\x7f-\xff]+$/", $sInBuf)) { //兼容gb2312,utf-8

            return true;
        }
        else
        {
            return false;
        }
    }
    /*
     -----------------------------------------------------------
    函数名称:isDomain($Domain)
    简要描述:检查一个（英文）域名是否合法
    输入:string 域名
    输出:boolean
    修改日志:------
    -----------------------------------------------------------
    */
    private function isDomain($Domain)
    {
        if(!eregi("^[0-9a-z]+[0-9a-z\.-]+[0-9a-z]+$", $Domain))
        {
            Return false;
        }
        if( !eregi("\.", $Domain))
        {
            Return false;
        }

        if(eregi("\-\.", $Domain) or eregi("\-\-", $Domain) or eregi("\.\.", $Domain) or eregi("\.\-", $Domain))
        {
            Return false;
        }

        $aDomain= explode(".",$Domain);
        if( !eregi("[a-zA-Z]",$aDomain[count($aDomain)-1]) )
        {
            Return false;
        }

        if(strlen($aDomain[0]) > 63 || strlen($aDomain[0]) < 1)
        {
            Return false;
        }
        Return true;
    }
    /**
     * 验证是否日期的函数
     * @param unknown_type $date
     * @param unknown_type $format
     * @throws Exception
     * @return boolean
     */
    private function validateDate( $date, $format='YYYY-MM-DD')
    {
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list( $y, $m, $d ) = preg_split( '/[-./ ]/', $date );
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list( $y, $d, $m ) = preg_split( '/[-./ ]/', $date );
                break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list( $d, $m, $y ) = preg_split( '/[-./ ]/', $date );
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list( $m, $d, $y ) = preg_split( '/[-./ ]/', $date );
                break;

            case 'YYYYMMDD':
                $y = substr( $date, 0, 4 );
                $m = substr( $date, 4, 2 );
                $d = substr( $date, 6, 2 );
                break;

            case 'YYYYDDMM':
                $y = substr( $date, 0, 4 );
                $d = substr( $date, 4, 2 );
                $m = substr( $date, 6, 2 );
                break;

            default:
                throw new Exception( "Invalid Date Format" );
        }
        return checkdate( $m, $d, $y );
    }


    /*
    -----------------------------------------------------------
    函数名称：isDate
    简要描述：检查日期是否符合0000-00-00
      输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
    private function isDate($sDate)
    {
        if( ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$",$sDate) )
        {
            Return true;
        }
        else
        {
            Return false;
        }
    }
    /*
    -----------------------------------------------------------
    函数名称：isTime
    简要描述：检查日期是否符合0000-00-00 00:00:00
    输入：string
    输出：boolean
    修改日志：------
    -----------------------------------------------------------
    */
    private function isTime($sTime)
    {
        if( ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$",$sTime) )
        {
            Return true;
        }
        else
        {
            Return false;
        }
    }

    /*
    -----------------------------------------------------------
    函数名称:isMoney($val)
    简要描述:检查输入值是否为合法人民币格式
    输入:string
    输出:boolean
    修改日志:------
    -----------------------------------------------------------
    */
    private function isMoney($val)
    {
        if(ereg("^[0-9]{1,}$", $val))
            return true;
        if( ereg("^[0-9]{1,}\.[0-9]{1,2}$", $val) )
            return true;
        return false;
    }

    /*
     -----------------------------------------------------------
    函数名称:isIp($val)
      简要描述:检查输入IP是否符合要求
      输入:string
        输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
    private function isIp($val)
    {
        return(bool) ip2long($val);
    }
    //-----------------------------------------------------------------------------



    /**
     * 验证手机号
     * @param int $mobile
     */
    private function valid_mobile($mobile){
        if(strlen($mobile)!=11) return false;
        if(preg_match('/13[0-9]\d{8}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/',$mobile)){
            return true;
        }else{
            return false;
        }
    }


    //去除字符串空格
    private function strTrim($str)
    {
        return preg_replace("/\s/","",$str);
    }

    //验证用户名
    private function userName($str,$type,$len)
    {
        $str=self::strTrim($str);
        if($len<strlen($str))
        {
            return false;
        }else{
            switch($type)
            {
                case "EN"://纯英文
                    if(preg_match("/^[a-zA-Z]+$/",$str))
                    {
                        return true;
                    }else{
                        return false;
                    }
                    break;
                case "ENNUM"://英文数字
                    if(preg_match("/^[a-zA-Z0-9]+$/",$str))
                    {
                        return true;
                    }else{
                        return false;
                    }
                    break;
                case "ALL":    //允许的符号(|-_字母数字)
                    if(preg_match("/^[\|\-\_a-zA-Z0-9]+$/",$str))
                    {
                        return true;
                    }else{
                        return false;
                    }
                    break;
            }
        }
    }

    //验证密码长度
    private function passWord($min,$max,$str)
    {
        $str=self::strTrim($str);
        if(strlen($str)>=$min && strlen($str)<=$max)
        {
            return true;
        }else{
            return false;
        }
    }

    //验证Email
    private function Email($str)
    {
        $str=self::strTrim($str);

        if(preg_match("/^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.){1,2}[a-z]{2,4}$/i",$str))
        {
            return true;
        }else{
            return false;
        }

    }

    //验证身份证(中国)
    private function idCard($str)
    {
        $str=self::strTrim($str);
        if(preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i",$str))
        {
            return true;
        }else{
            return false;
        }
    }

    //验证座机电话
    private function Phone($type,$str)
    {
        $str=self::strTrim($str);
        switch($type)
        {
            case "CHN":
                if(preg_match("/^([0-9]{3}|0[0-9]{3})-[0-9]{7,8}$/",$str))
                {
                    return true;
                }else{
                    return false;
                }
                break;
            case "INT":
                if(preg_match("/^[0-9]{4}-([0-9]{3}|0[0-9]{3})-[0-9]{7,8}$/",$str))
                {
                    return true;
                }else{
                    return false;
                }
                break;
        }
    }

    /**
     * 过滤二维数组的值
     * @param 二维数组 $arr_data
     * @param 一维数组 $field
     * @return Ambigous <multitype:, string, unknown>
     */
    private function getarrayfield($arr_data, $field)
    {
        $resultArr = array();
        foreach ( $arr_data as $key => $value ) {
            foreach ($field as $k=>$v)
            {
                if(array_key_exists($v, $value))//存在才添加
                {
                    $resultArr[$key][$v] =$value[$v] ;
                }
                else
                {
                    $resultArr[$key][$v]="不存在这个列";
                }
            }
        }

        return $resultArr;
    }
    /**
     * 获取客户端IP地址
     * @return ip
     */
    private function get_client_ip(){
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

    private function get_http_user_agent(){
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
    }

    /**
     * 从IP地址获取真实地址
     * @param IP $ip
     */
    private function get_address_from_ip($ip){
        $url='http://www.youdao.com/smartresult-xml/search.s?type=ip&q=';
        $xml=file_get_contents($url.$ip);
        $data=simplexml_load_string($xml);
        return $data->product->location;
    }



    //获取url中参数的值
    private function geturlval($url,$name)
    {
        $arr = parse_url($url);
        $arr_query = $this->convertUrlQuery($arr['query']);

        return $arr_query[$name];

    }
    private function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);

        $params = array();
        foreach ($queryParts as $param)
        {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        return $params;
    }
}