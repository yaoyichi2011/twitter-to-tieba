<?php
header("Content-type: text/html; charset=utf-8");
ignore_user_abort(true);
require dirname(__FILE__).'/cookie.php';
$name=$_GET['name'];
$tweetid=json_decode(file_get_contents('https://kdwnil.ml/api/twitter/?name='.$name),1)["state"]["timeline"]["items"][0]["data"]["tweet"]["id"];
$check= file($name.'.txt')[0];
if($tweetid>$check){
$url = 'https://tieba.baidu.com/mo/q/apubpost';
$t = file_get_contents("https://kdwnil.ml/api/twitter/tb.php?name=$name&id=0");
$pic=file_get_contents("https://kdwnil.ml/api/twitter/pic.php?name=$name&id=0");
$tbs='';//tbs这不用我说了吧
$fid='';//贴吧对应的fid
$tid='';//帖子对应的tid
$word=urlencode('');//贴吧名称
$data = 'co='.$t.'
图片地址(如果有):'.$pic.'&_t=1483857485637&tag=11&upload_img_info=&fid=$fid&src=1&word=$word&tbs=$tbs&z=4863894912&lp=6026';
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 6 Build/LYZ28E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Mobile Safari/537.36');
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_COOKIE,$cookie);
curl_setopt($ch, CURLOPT_REFERER,'http://tieba.baidu.com/p/'.$tid.'?pn=0&');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
$content = curl_exec($ch);
curl_close($ch);
$a = json_decode($content,1);

if($a['no']>"0") {
  echo "失败！";
}else {$fp = fopen($name.'.txt',"w");
    flock($fp,LOCK_EX);
    fwrite($fp,$tweetid);
    fclose($fp,LOCK_UN);
echo '发送成功';
}
}
else{echo '该推文已被发送过';}
