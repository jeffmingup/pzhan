<?php 
function obtain($url){
    $ch = curl_init(); 
    //设置访问的url
    curl_setopt($ch, CURLOPT_URL,$url); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  //终止从服务端验证
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
    curl_setopt($ch , CURLOPT_COOKIE,'com.zjcloud.subscriberinfo="admin@hxylt.cn@@@admin@2019";');
    // curl_setopt($ch,CURLOPT_REFERER,'https://www.pixiv.net'); 
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'); 
    $output = curl_exec($ch);  //执行获取内容
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode==404){
        return false;
    }
    return $output;
}


$cookie_file = dirname(__FILE__).'/cookie.txt';
//$cookie_file = tempnam("tmp","cookie");

//先获取cookies并保存
$url = "https://cs.zijingcloud.com/meet/doLogin.json";
$params = ['account'=>'',
'pwd'=>'',
'remember'=>'ture',
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
curl_setopt($curl, CURLOPT_POST, 1);
if($params){
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
}
curl_setopt($curl, CURLOPT_URL, $url);
curl_exec($curl);
curl_close($curl);

$url='https://cs.zijingcloud.com/meet/recordedSpace/recordedSpacePage.do';
$output=obtain($url);
// print_r($output);
preg_match_all('/<a href="(.*?)">download<\/a>/', $output, $matches);
print_r($matches);
foreach ($matches[1] as $key => $value) {
    $result = substr($value,strrpos($value,".mp4?")-12,16);
     if(!file_exists(dirname(__FILE__).'/'.$result)){
        exec("wget {$value} -O  {$result}");
        print_r('ok');
     }
}
?>