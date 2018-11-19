<?php

//获取链接内容
function obtain($url){
    $ch = curl_init(); 
    //设置访问的url
    curl_setopt($ch, CURLOPT_URL,$url); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  //终止从服务端验证
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_REFERER,'https://www.pixiv.net'); 
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'); 
    $output = curl_exec($ch);  //执行获取内容
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode==404){
        return false;
    }
    return $output;
}

//获取图片
function downpicture($url,$path){

    $output=obtain($url);

    //链接为png图片
    if ($output==false && substr($url,-3) == 'jpg'){
        $url=str_replace('jpg','png',$url);
        $path=str_replace('jpg','png',$path);
        $output=obtain($url);
    }
    //创建一个文件
    $myfile = fopen($path, 'w');    //将获取的网站内容保存到文件中
    fwrite($myfile, $output);    //关闭文件资源
    fclose($myfile);
}
    // $dayurl='https://www.pixiv.net/ranking.php?mode=daily&date=20180520';
    //日--daily 周--weekly 月--monthly
    $time= empty($argv[1])? date("Ymd",strtotime("-2 day")):$argv[1];
    $dayurl='https://www.pixiv.net/ranking.php?mode=monthly&date='.$time;
    $output=obtain($dayurl);

    //获取图片url
    preg_match_all('/data-filter="thumbnail-filter lazy-image"data-src="(.*?)"data-type="illust"/', $output, $matches);
    $url=array();
    foreach ($matches[1] as $key => $value) {
        $value=str_replace('c/240x480/img-master','img-original',$value);
        $url[]=str_replace('_master1200','',$value);
    }
    //获取数量 默认5最多不过50
    $url=array_slice($url,0,empty($argv[2])?5:$argv[2]);
     foreach ($url as $key => $value) {
         $path='C:\Users\Administrator\Desktop\\'.$key.'.jpg';
         downpicture($value,$path);
     }




 ?>