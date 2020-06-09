<?php
require __DIR__.'/init.php';



$varrr=explode("/",$_SERVER["REQUEST_URI"]);
$驱动器=$varrr["1"] ;array_splice($varrr,0, 1);unset($varrr['0']); $请求路径 = implode("/", $varrr);$请求路径= str_replace("?".$_SERVER["QUERY_STRING"],"",$请求路径); $url=$请求路径;
 if ($驱动器==""){
     $驱动器="default";
 }
 
 
define('CACHE_PATH', ROOT.'cache/'.$驱动器."/");if(!file_exists (CACHE_PATH)){  mkdir(CACHE_PATH); }
 cache::$type = empty( config('cache_type') )?'secache':config('cache_type');

if (file_exists(ROOT.'config/'.$驱动器.'.php')) {
    $配置文件 = include (ROOT.'config/'.$驱动器.'.php'); }
 else
 if (!file_exists(ROOT.'config/base.php') or !file_exists(ROOT.'config/default.php') ) {
      header('Location: /install.php');}

////////////http方法//////////

switch($_SERVER["REQUEST_METHOD"]){
    case "DELETE":
       
       onedrive::delpath()
       ;exit;

   
    
     default:break;
}








 
////////////////////////////////////初始化配置文件start//////////////////////////////////////
onedrive::$client_id =  $配置文件["client_id"];
  onedrive::$client_secret =$配置文件["client_secret"];
  onedrive::$redirect_uri = $配置文件["redirect_uri"];
  onedrive::$api_url = $配置文件["api_url"];
  onedrive::$oauth_url = $配置文件["oauth_url"];
  onedrive::$typeurl=$配置文件["api"] ;
  onedrive::$access_token=access_token($配置文件,$驱动器);
	


	
	


/*
 *    系统后台
 */
route::group(function () {
    return $_COOKIE['admin'] == config('password');
}, function () {
    route::get('/logout', 'AdminController@logout');
    route::any('/admin/', 'AdminController@settings');
    route::any('/admin/cache', 'AdminController@cache');
    route::any('/admin/show', 'AdminController@show');
    route::any('/admin/setpass', 'AdminController@setpass');
    route::any('/admin/images', 'AdminController@images');
    route::any('/admin/drives', 'AdminController@drives');
    route::any('/admin/sharepoint', 'AdminController@sharepoint');
   // route::any('/admin/upload', 'UploadController@index');
    //守护进程
    route::any('/admin/upload/run', 'UploadController@run');
    //上传进程
    route::post('/admin/upload/task', 'UploadController@task');
});
//登陆
route::any('/login', 'AdminController@login');

//跳转到登陆
route::any('/admin/', function () {
    return view::direct(get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).'?/login');
});

define('VIEW_PATH', ROOT.'view/'.(config('style') ? config('style') : 'material').'/');
/**
 *    OneImg.
 */
$images = config('images@base');
if (($_COOKIE['admin'] == config('password') || $images['public'])) {
    route::any('/'.$驱动器.'/images', 'ImagesController@index');
    if ($images['home']) {
        route::any('/', 'ImagesController@index');
    }
}



////////////文件管理////////////////////////////
//新建文件夹
if($_GET["create_folder"])
{
    onedrive::create_folder( $请求路径,$_GET["create_folder"]);
    exit;
}
//删
if($_GET["delitem"])
{
    
    onedrive::delete($_GET["delitem"]);
    exit;
}
//改

if($_GET["rename"]){
     onedrive::rename($_GET["rename"],$_GET["name"]);
    exit;
}
//上传
	
if ($_GET["action"]=="upbigfile")
{
        $filename=  $_GET['upbigfilename'];
        $path=$请求路径.$filename;
        $path = onedrive::urlencode($path);
		$path = empty($path)?'/':":/{$path}:/";
	    $token=$配置文件["access_token"];
		$request['headers'] = "Authorization: bearer {$token}".PHP_EOL."Content-Type: application/json".PHP_EOL;
		$request['url']= $配置文件["api"].$path."createUploadSession";
	    $request['post_data'] = '{"item": {"@microsoft.graph.conflictBehavior": "rename"}}';
		$resp = fetch::post($request);
		$data = json_decode($resp->content, true);
			if($resp->http_code == 409){
				return false;
			}
	
		echo $resp->content;

    exit;
}
   	

//查
route::any('{path:#all}', 'IndexController@index');


 echo'
 <script language=javascript>
<!--

var startTime,endTime;
var d=new Date();
startTime=d.getTime();
//-->
</script>';






$etime=microtime(true);//获取程序执行结束的时间
$total=$etime-$stime;   //计算差值

if($_COOKIE["admin"]!==config("password")){echo '<a href= "/admin">登陆</a>
';
    exit;}
  ////账户信息  
//$req["headers"]="Authorization: bearer {$配置文件["access_token"]}".PHP_EOL."Content-Type: application/json".PHP_EOL;;
//$req["url"]=$配置文件["api"];
//$req["url"]=str_replace("root","",$req["url"]);
//$ss=fetch::get($req);
//	$data = json_decode($ss->content, true);
	
//echo $data["id"];
//echo "账户". $data["owner"]["user"]["email"];

//echo"已用空间". onedrive::human_filesize($data["quota"]["used"]);
//echo"总空间". onedrive::human_filesize($data["quota"]["total"]);
//echo"回收站". onedrive::human_filesize($data["quota"]["deleted"]).'网页执行时间';
echo "<div style=text-align:center>执行时间为：{$total} 秒";
echo '网页执行时间:<script language=javascript>d=new Date();endTime=d.getTime
();document.write((endTime-startTime)/1000);</script>秒';

echo "</div>";





if ($_SERVER["REQUEST_URI"]=="/admin?/logout" 
|$_SERVER["REQUEST_URI"]=="/admin" 
|$_SERVER["REQUEST_URI"]=="/?/admin" 
|$_SERVER["REQUEST_URI"]=="/?/login" 
| $_SERVER["REQUEST_URI"]=="/admin?/logout"
| $_SERVER["REQUEST_URI"]=="/?/admin/setpass" 
| $_SERVER["REQUEST_URI"]=="/?/admin/show"
| $_SERVER["REQUEST_URI"]=="/?/admin/upload"
| $_SERVER["REQUEST_URI"]=="/?/admin/cache"
| $_SERVER["REQUEST_URI"]=="/?/admin/sharepoint"
| $_SERVER["REQUEST_URI"]=="/?/admin/images"
| $_SERVER["REQUEST_URI"]=="/admin/file"
){
   
  exit ;
}








?>





<style type="text/css">

* {
  -webkit-touch-callout:none;
  -webkit-user-select:none;
  -khtml-user-select:none;
  -moz-user-select:none;
  -ms-user-select:none;
  user-select:none;
}

</style>	
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>










<ul class="mdui-menu" id="menu">
  
    <li class="mdui-menu-item">
        
        <a href="javascript:;" onclick="deldel()"; class="mdui-ripple">刷新</a>
    </li>
    
    
    <li class="mdui-menu-item">
        <a href="javascript:;" class="mdui-ripple" onclick="create_folder()">新建文件夹</a>
    </li>
      <li class="mdui-menu-item">
        <a href="javascript:;" class="mdui-ripple">移动</a>
    </li> <li class="mdui-menu-item">
        <a href="javascript:;" class="mdui-ripple">粘贴</a>
    </li>
    
     <li class="mdui-menu-item">
        <a href="javascript:;" class="mdui-ripple" onclick="renamebox()";>重命名</a>
    </li>
    <li class="mdui-menu-item">
        <a href="javascript:;" class="mdui-ripple"  onclick="delitem()"; >删除</a>
    </li>
   
      <li class="mdui-menu-item">
        <a href="/admin" class="mdui-ripple">系统设置</a>
    </li>
</ul>









<div class="mdui-dialog mdui-dialog-open" id="exampleDialog" style="display: none; top: 137px; height: 266px;">
         
          <div class="mdui-dialog-content" style="height: 1000px;">
          
           
     <div id="upload_div" style="margin:0 0 16px 0;">
                <div id="upload_btns" align="center" style="display: none"; >
                    <select onchange="document.getElementById('upload_file').webkitdirectory=this.value;">
                        <option value="">上传文件</option>
                        <option value="1">上传文件夹</option>
                    </select>
                    <input id="upload_file" type="file" name="upload_filename" multiple="multiple" class=" layui-btn"   onchange="preup();">
                    <input id="upload_submit" onclick="preup();" value="上传" type="button">
          
          
          
          </div>
          
        </div>






   
 






<script>

    var $$ = mdui.JQ;
    //监听鼠标右击事件 / 移动端长按事件
    $$(document).on('contextmenu', function (e) {
      //   console.log(e);

        //0：移动端长按（iOS 测试未通过）
        //2：电脑端右键
        
            e.preventDefault();//阻止冒泡，阻止默认的浏览器菜单

            //鼠标点击位置，相对于浏览器
            var _x = e.pageX,
                _y = e.pageY;

            let $div = $$("<div></div>").css({
                position: 'absolute',
                top: _y+'px',
                left: _x+'px',
            });
            $$('body').append($div);//创建临时DOM

            // anchorSelector 表示触发菜单的元素的 CSS 选择器或 DOM 元素
            // menuSelector 表示菜单的 CSS 选择器或 DOM 元素
            // options 表示组件的配置参数，见下面的参数列表
            // 完整文档列表：https://doc.nowtime.cc/mdui/menu.html
            var instq = new mdui.Menu($div, '#menu');
            instq.open();//打开菜单栏
            $div.remove();//销毁创建的临时DOM
            
        
        console.log(e);
             console.log(e);(e.target.id);
      if(e.target.id=="" | e.target.id <999999999999999){
           instq.close();
      }
   Cookies.set('flieid', e.target.id, { expires: 0.025 });
        // console.log(e.relatedTarget.tagName);
        console.log(e.target.id);
    });
    
    
  
///////文件上传
  function uploadfieone()
{document.getElementById('upload_file').webkitdirectory="";
     document.getElementById("upload_file").click();
  
   
}
function uploadfietwo()
{
   document.getElementById('upload_file').webkitdirectory=1;
     document.getElementById("upload_file").click();
  
}

//删除文件
function delitem(){
    
    var id = Cookies.get('flieid')
    var 驱动器="<?php echo $驱动器?>"    
    var xhr2 = new XMLHttpRequest();
    xhr2.withCredentials = true;
    xhr2.addEventListener("readystatechange", function() {
    if(this.readyState === 4) {
    console.log(this.responseText);
   // location.reload();
    deldel() }});
    xhr2.open("GET", "/"+驱动器+"/?delitem="+id);
    xhr2.send();
    console.log(xhr2);
}
    
    
    
    
function renamebox(){
   mdui.prompt('重命名',
    function (value) {
        var id = Cookies.get('flieid')
        var 驱动器="<?php echo $驱动器?>"
        var xhr4 = new XMLHttpRequest();
        xhr4.withCredentials = true;
        xhr4.addEventListener("readystatechange", function() {
        if(this.readyState === 4) {
    console.log(this.responseText);deldel()}
    });
xhr4.open("GET", "/"+驱动器+"/?rename="+id+"&name="+value);
xhr4.send();
console.log(xhr4);
 
  },
  function (value) {
   
  }
);
}
  
  
  
  function create_folder()
  {
     mdui.prompt('新建文件夹',
    function (value) {
       
        var url="<?php echo "/".$驱动器. $请求路径?>"
      
        var xhr4 = new XMLHttpRequest();
        xhr4.withCredentials = true;
        xhr4.addEventListener("readystatechange", function() {
        if(this.readyState === 4) {
         //   alert(this.responseText);
    console.log(this.responseText);
    deldel()}
    });
xhr4.open("GET", ""+"?create_folder="+value);
xhr4.send();
console.log(xhr4);
 
  },
  function (value) {
   
  }
);
      
  }
    
    /////////////重建缓存
 function deldel(){
        
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.addEventListener("readystatechange", function() {
    if(this.readyState === 4) {
    console.log(this.responseText);
    location.reload();
     }
    });

xhr.open("GET", "/del.php");
 xhr.send();
}
  
</script>


<script>



  function uploadbuttonhide() {
        document.getElementById('exampleDialog').style.display='block';
        
    }
    function uploadbuttonshow() {
        document.getElementById('').style.display='block';
       
    }
    function preup() {
        
        uploadbuttonhide();
        var files=document.getElementById('upload_file').files;
	    if (files.length<1) {
            uploadbuttonshow();
            return;
        };
        var table1=document.createElement('table');
        document.getElementById('upload_div').appendChild(table1);
        table1.setAttribute('class','list-table');
        var timea=new Date().getTime();
        var i=0;
        getuplink(i);
        function getuplink(i) {
            var file=files[i];
            var tr1=document.createElement('tr');
            table1.appendChild(tr1);
            tr1.setAttribute('data-to',1);
            var td1=document.createElement('td');
            tr1.appendChild(td1);
            td1.setAttribute('style','width:30%;word-break:break-word;');
            td1.setAttribute('id','upfile_td1_'+timea+'_'+i);
            td1.innerHTML=(file.webkitRelativePath||file.name)+'<br>'+size_format(file.size);
            var td2=document.createElement('td');
            tr1.appendChild(td2);
            td2.setAttribute('id','upfile_td2_'+timea+'_'+i);
            if (file.size>15*1024*1024*1024) {
                td2.innerHTML='<font color="red">文件过大，终止上传。</font>';
                uploadbuttonshow();
                return;
            }
            upbigfilename = encodeURIComponent((file.webkitRelativePath||file.name));

            td2.innerHTML='获取上传链接 ...';
            var xhr1 = new XMLHttpRequest();
            xhr1.open("GET", '/<?php echo $驱动器."/".$请求路径 ?>?action=upbigfile&upbigfilename='+ upbigfilename +'&filesize='+ file.size +'&lastModified='+ file.lastModified);
            xhr1.setRequestHeader('x-requested-with','XMLHttpRequest');
            xhr1.send(null);
            xhr1.onload = function(e){
                td2.innerHTML='<font color="red">'+xhr1.responseText+'</font>';
                if (xhr1.status==200) {
                   
                    console.log(xhr1.responseText);
                    var html=JSON.parse(xhr1.responseText);
                    if (!html['uploadUrl']) {
                        td2.innerHTML='<font color="red">'+xhr1.responseText+'</font><br>';
                        uploadbuttonshow();
                    } else {
                        td2.innerHTML='开始上传 ...';
                        binupfile(file,html['uploadUrl'],timea+'_'+i, upbigfilename);
                    }
                }
                if (xhr1.status==409) {
                    td2.innerHTML='md5: '+filemd5;
                    tdnum = timea+'_'+i;
                    document.getElementById('upfile_td1_'+tdnum).innerHTML='<div style="color:green"><a href="/'+upbigfilename+'" id="upfile_a_'+tdnum+'" target="_blank">'+document.getElementById('upfile_td1_'+tdnum).innerHTML+'</a>上传完成';
                }
                if (i<files.length-1) {
                    i++;
                    getuplink(i);
                }
                //
                else{
               
                    
                }
            
            }

        }
    }
    function size_format(num) {
        if (num>1024) {
            num=num/1024;
        } else {
            return num.toFixed(2) + ' B';
        }
        if (num>1024) {
            num=num/1024;
        } else {
            return num.toFixed(2) + ' KB';
        }
        if (num>1024) {
            num=num/1024;
        } else {
            return num.toFixed(2) + ' MB';
        }
        return num.toFixed(2) + ' GB';
    }
    function binupfile(file,url,tdnum,filename){
        var label=document.getElementById('upfile_td2_'+tdnum);
        var reader = new FileReader();
        var StartStr='';
        var MiddleStr='';
        var StartTime;
        var EndTime;
        var newstartsize = 0;
        if(!!file){
            var asize=0;
            var totalsize=file.size;
            var xhr2 = new XMLHttpRequest();
            xhr2.open("GET", url);
                    //xhr2.setRequestHeader('x-requested-with','XMLHttpRequest');
            xhr2.send(null);
            xhr2.onload = function(e){
                if (xhr2.status==200) {
                    var html = JSON.parse(xhr2.responseText);
                    var a = html['nextExpectedRanges'][0];
                    newstartsize = Number( a.slice(0,a.indexOf("-")) );
                    StartTime = new Date();
                    asize = newstartsize;
                    if (newstartsize==0) {
                        StartStr='开始于:' +StartTime.toLocaleString()+'<br>' ;
                    } else {
                        StartStr='上次上传'+size_format(newstartsize)+ '<br>本次开始于:' +StartTime.toLocaleString()+'<br>' ;
                    }
                    var chunksize=5*1024*1024; // chunk size, max 60M. 每小块上传大小，最大60M，微软建议10M
                    if (totalsize>200*1024*1024) chunksize=100*1024*1024;
                    function readblob(start) {
                        var end=start+chunksize;
                        var blob = file.slice(start,end);
                        reader.readAsArrayBuffer(blob);
                    }
                    readblob(asize);

                    reader.onload = function(e){
                        var binary = this.result;
                        var xhr = new XMLHttpRequest();
                        xhr.open("PUT", url, true);
                        //xhr.setRequestHeader('x-requested-with','XMLHttpRequest');
                        bsize=asize+e.loaded-1;
                        xhr.setRequestHeader('Content-Range', 'bytes ' + asize + '-' + bsize +'/'+ totalsize);
                        xhr.upload.onprogress = function(e){
                            if (e.lengthComputable) {
                                var tmptime = new Date();
                                var tmpspeed = e.loaded*1000/(tmptime.getTime()-C_starttime.getTime());
                                var remaintime = (totalsize-asize-e.loaded)/tmpspeed;
                                label.innerHTML=StartStr+'上传 ' +size_format(asize+e.loaded)+ ' / '+size_format(totalsize) + ' = ' + ((asize+e.loaded)*100/totalsize).toFixed(2) + '% 平均速度:'+size_format((asize+e.loaded-newstartsize)*1000/(tmptime.getTime()-StartTime.getTime()))+'/s<br>即时速度 '+size_format(tmpspeed)+'/s 预计还要 '+remaintime.toFixed(1)+'s';
                            }
                        }
                        var C_starttime = new Date();
                        xhr.onload = function(e){
                            if (xhr.status<500) {
                            var response=JSON.parse(xhr.responseText);
                            if (response['size']>0) {
                                // contain size, upload finish. 有size说明是最终返回，上传结束
                                var xhr3 = new XMLHttpRequest();
                                xhr3.open("GET", '?action=del_upload_cache&filelastModified='+file.lastModified+'&filesize='+file.size+'&filename='+filename);
                                xhr3.setRequestHeader('x-requested-with','XMLHttpRequest');
                                xhr3.send(null);
                                xhr3.onload = function(e){
                                    console.log(xhr3.responseText+','+xhr3.status);
                                }
                                EndTime=new Date();
                                MiddleStr = '结束于:'+EndTime.toLocaleString()+'<br>';
                                if (newstartsize==0) {
                                    MiddleStr += '平均速度:'+size_format(totalsize*1000/(EndTime.getTime()-StartTime.getTime()))+'/s<br>';
                                } else {
                                    MiddleStr += '本次平均速度:'+size_format((totalsize-newstartsize)*1000/(EndTime.getTime()-StartTime.getTime()))+'/s<br>';
                                }
                                document.getElementById('upfile_td1_'+tdnum).innerHTML='<div style="color:green"><a href="/<?php echo $驱动器."/".$请求路径 ;?>'+(file.webkitRelativePath||response.name)+'?preview" id="upfile_a_'+tdnum+'" target="_blank">'+document.getElementById('upfile_td1_'+tdnum).innerHTML+'</a><br><a href="/<?php echo $驱动器."/".$请求路径 ;?>'+(file.webkitRelativePath||response.name)+'" id="upfile_a1_'+tdnum+'"></a>上传完成<button onclick="CopyAllDownloadUrl(\'#upfile_a1_'+tdnum+'\');" id="upfile_cpbt_'+tdnum+'"  style="display:none" >复制链接</button></div>';
                                label.innerHTML=StartStr+MiddleStr;
                                uploadbuttonshow();

                                response.name=file.webkitRelativePath||response.name;
                                addelement(response);

                            } else {
                                if (!response['nextExpectedRanges']) {
                                    label.innerHTML='<font color="red">'+xhr.responseText+'</font><br>';
                                } else {
                                    var a=response['nextExpectedRanges'][0];
                                    asize=Number( a.slice(0,a.indexOf("-")) );
                                    readblob(asize);
                                }
                            } } else readblob(asize);
                        }
                        xhr.send(binary);
                    }
                } else {
                    if (window.location.pathname.indexOf('%23')>0||filename.indexOf('%23')>0) {
                        label.innerHTML='<font color="red">目录或文件名含有#，上传失败。</font>';
                    } else {
                        label.innerHTML='<font color="red">'+xhr2.responseText+'</font>';
                    }
                    uploadbuttonshow();
                }
            }
        }
    }


    function operatediv_close(operate) {
        document.getElementById(operate+'_div').style.display='none';
        document.getElementById('mask').style.display='none';
    }

    function logout() {
        document.cookie = "admin=; path=/";
        location.href = location.href;
    }

    function showdiv(event,action,num) {
        var $operatediv=document.getElementsByName('operatediv');
        for ($i=0;$i<$operatediv.length;$i++) {
            $operatediv[$i].style.display='none';
        }
        document.getElementById('mask').style.display='';
        //document.getElementById('mask').style.width=document.documentElement.scrollWidth+'px';
        document.getElementById('mask').style.height=document.documentElement.scrollHeight<window.innerHeight?window.innerHeight:document.documentElement.scrollHeight+'px';
        if (num=='') {
            var str='';
        } else {
            var str=document.getElementById('file_a'+num).innerText;
            if (str=='') {
                str=document.getElementById('file_a'+num).getElementsByTagName("img")[0].alt;
                if (str=='') {
                    alert('获取文件名失败！');
                    operatediv_close(action);
                    return;
                }
            }
            if (str.substr(-1)==' ') str=str.substr(0,str.length-1);
        }
        document.getElementById(action + '_div').style.display='';
        document.getElementById(action + '_label').innerText=str;//.replace(/&/,'&amp;');
        document.getElementById(action + '_sid').value=num;
        document.getElementById(action + '_hidden').value=str;
        if (action=='rename') document.getElementById(action + '_input').value=str;
        var $e = event || window.event;
        var $scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
        var $scrollY = document.documentElement.scrollTop || document.body.scrollTop;
        var $x = $e.pageX || $e.clientX + $scrollX;
        var $y = $e.pageY || $e.clientY + $scrollY;
        if (action=='create') {
            document.getElementById(action + '_div').style.left=(document.body.clientWidth-document.getElementById(action + '_div').offsetWidth)/2 +'px';
            document.getElementById(action + '_div').style.top=(window.innerHeight-document.getElementById(action + '_div').offsetHeight)/2+$scrollY +'px';
        } else {
            if ($x + document.getElementById(action + '_div').offsetWidth > document.body.clientWidth) {
                if (document.getElementById(action + '_div').offsetWidth > document.body.clientWidth) {
                    document.getElementById(action + '_div').offsetWidth=document.body.clientWidth+'px';
                    document.getElementById(action + '_div').style.left='0px';
                } else {
                    document.getElementById(action + '_div').style.left=document.body.clientWidth-document.getElementById(action + '_div').offsetWidth+'px';
                }
            } else {
                document.getElementById(action + '_div').style.left=$x+'px';
            }
            document.getElementById(action + '_div').style.top=$y+'px';
        }
        document.getElementById(action + '_input').focus();
    }
    function submit_operate(str) {
        var num=document.getElementById(str+'_sid').value;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", '?'+serializeForm(str+'_form'));
        xhr.setRequestHeader('x-requested-with','XMLHttpRequest');
        xhr.send(null);
        xhr.onload = function(e){
            var html;
            if (xhr.status<300) {
                console.log(xhr.status+','+xhr.responseText);
                if (str=='rename') {
                    html=JSON.parse(xhr.responseText);
                    var file_a = document.getElementById('file_a'+num);
                    file_a.innerText=html.name;
                    file_a.href = (file_a.href.substr(-8)=='?preview')?(html.name.replace(/#/,'%23')+'?preview'):(html.name.replace(/#/,'%23')+'/');
                }
                if (str=='move'||str=='delete') document.getElementById('tr'+num).parentNode.removeChild(document.getElementById('tr'+num));
                if (str=='create') {
                    html=JSON.parse(xhr.responseText);
                    addelement(html);
                }
            } else alert(xhr.status+'\n'+xhr.responseText);
            document.getElementById(str+'_div').style.display='none';
            document.getElementById('mask').style.display='none';
        }
        return false;
    }
    function addelement(html) {
        var tr1=document.createElement('tr');
        tr1.setAttribute('data-to',1);
        var td1=document.createElement('td');
        td1.setAttribute('class','file');
        var a1=document.createElement('a');
        a1.href='/'+html.name.replace(/#/,'%23');
        a1.innerText=html.name;
        a1.target='_blank';
        var td2=document.createElement('td');
        td2.setAttribute('class','updated_at');
        td2.innerText=html.lastModifiedDateTime.replace(/T/,' ').replace(/Z/,'');
        var td3=document.createElement('td');
        td3.setAttribute('class','size');
        td3.innerText=size_format(html.size);
        if (!!html.folder) {
            a1.href+='/';
            document.getElementById('tr0').parentNode.insertBefore(tr1,document.getElementById('tr0').nextSibling);
        }
        if (!!html.file) {
            a1.href+='?preview';
            a1.name='filelist';
            //document.getElementById('tr0').parentNode.appendChild(tr1);
        }
        tr1.appendChild(td1);
        td1.appendChild(a1);
        tr1.appendChild(td2);
        tr1.appendChild(td3);
    }
    function getElements(formId) {
        var form = document.getElementById(formId);
        var elements = new Array();
        var tagElements = form.getElementsByTagName('input');
        for (var j = 0; j < tagElements.length; j++){
            elements.push(tagElements[j]);
        }
        var tagElements = form.getElementsByTagName('select');
        for (var j = 0; j < tagElements.length; j++){
            elements.push(tagElements[j]);
        }
        var tagElements = form.getElementsByTagName('textarea');
        for (var j = 0; j < tagElements.length; j++){
            elements.push(tagElements[j]);
        }
        return elements;
    }
    function serializeElement(element) {
        var method = element.tagName.toLowerCase();
        var parameter;
        if (method == 'select') {
            parameter = [element.name, element.value];
        }
        switch (element.type.toLowerCase()) {
            case 'submit':
            case 'hidden':
            case 'password':
            case 'text':
            case 'date':
            case 'textarea':
                parameter = [element.name, element.value];
                break;
            case 'checkbox':
            case 'radio':
                if (element.checked){
                    parameter = [element.name, element.value];
                }
                break;
        }
        if (parameter) {
            var key = encodeURIComponent(parameter[0]);
            if (key.length == 0) return;
            if (parameter[1].constructor != Array) parameter[1] = [parameter[1]];
            var values = parameter[1];
            var results = [];
            for (var i = 0; i < values.length; i++) {
                results.push(key + '=' + encodeURIComponent(values[i]));
            }
            return results.join('&');
        }
    }
    function serializeForm(formId) {
        var elements = getElements(formId);
        var queryComponents = new Array();
        for (var i = 0; i < elements.length; i++) {
            var queryComponent = serializeElement(elements[i]);
            if (queryComponent) {
                queryComponents.push(queryComponent);
            }
        }
        return queryComponents.join('&');
    }



</script>





