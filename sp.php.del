<?php
require  __DIR__.'/init.php';

$config=include("config/token.php");
include("lib/fetch.php");

echo '<h4>获取SharePoint网站site-id</h4><br>' ; 
echo '<form action="sp.php" method="get">  
国际版填1，世纪互联填2<br>
<input type="text" name="type" value ="1" /><br><br>
你SharePoint的域名<br>
<input type="text" name="hostname" value ="xxx.sharepoint.com" /><br><br>
网址上域名后面的 /sites/xxx 或teams/xxx<br>
<input type="text" name="site" value ="/sites/xkx" /><br><br>
<input type="submit" value="GET site-id" />
</form>';
  
if ( $_GET["type"] == 2){
$api_url = 'https://microsoftgraph.chinacloudapi.cn/v1.0';
}
else { $api_url = 'https://graph.microsoft.com/v1.0';}
  
  

$hostname = $_GET["hostname"];
$site =  $_GET["site"];

	$request['headers'] = "Authorization: bearer {$config["access_token"]}".PHP_EOL."Content-Type: application/json".PHP_EOL;
	$request["url"]=$api_url.'/sites/'.$_GET["hostname"].':'.$_GET["site"];	
	$data=fetch::get($request)	;
	$sitedata = json_decode($data->content, true);



echo  "api_url:".$api_url;
echo  "<br>";
echo  "hostname:".$hostname;
echo  "<br>";
echo  "site:".$site;
echo  "<br>";
echo  "site-id:".$siteidurl=($sitedata["id"]);

?>
