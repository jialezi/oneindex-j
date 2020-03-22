<?php view::layout('layout')?>
<?php 
    function isImage($filename){
      $types = '/(\.jpg$|\.png$|\.jpeg$)/i';
      if(preg_match($types, trim($filename))){
          return true;
      }else{
          return false;
      }
    }
  ?>
<?php 
function file_ico($item){
  $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
  if(in_array($ext,['bmp','jpg','jpeg','png','gif','webp'])){
  	return "image";
  }
  if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf'])){
  	return "ondemand_video";
  }
  if(in_array($ext,['ogg','mp3','wav'])){
  	return "audiotrack";
  }
  return "insert_drive_file";
}
?>

<?php view::begin('content');?>
	
<div class="mdui-container-fluid">
<?php if($head):?>
<div class="mdui-typo" style="padding: 20px;">
	<?php e($head);?>
</div>
<?php endif;?>
<style>
.thumb .th{
	display: none;
}
.thumb .mdui-text-right{
	display: none;
}
.thumb .mdui-list-item a ,.thumb .mdui-list-item {
	width:213px;
	height: 230px;
	float: left;
	margin: 10px 10px !important;
}

.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7{
	width:100% !important;
	height:230px;
}

.thumb .mdui-list-item .mdui-icon{
	font-size:100px;
	display: block;
	margin-top: 40px;
	color: #7ab5ef;
}
.thumb .mdui-list-item span{
	float: left;
	display: block;
	text-align: center;
	width:100%;
	position: absolute;
    top: 180px;
}
</style>

<div class="nexmoe-item">
<div class="mdui-row">
	<ul class="mdui-list">
		<li class="mdui-list-item th">
		  <div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="downward">expand_more</i></div>
		</li>
		<?php if($path != '/'):?>
		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path.'../');?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7">
				<i class="mdui-icon material-icons">arrow_upward</i>
		    	..
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"></div>
			  <div class="mdui-col-sm-2 mdui-text-right"></div>
		  	</a>
		</li>
		<?php endif;?>
		
		<?php foreach((array)$items as $item):?>
			<?php if(!empty($item['folder'])):?>

		<li class="mdui-list-item mdui-ripple" data-sort data-sort-name="<?php e($item['name']);?>" data-sort-date="<?php echo $item['lastModifiedDateTime'];?>" data-sort-size="<?php echo $item['size'];?>">
			<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons">folder_open</i>
		    	<span><?php e($item['name']);?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php else:?>
		<li class="mdui-list-item file mdui-ripple" data-sort data-sort-name="<?php e($item['name']);?>" data-sort-date="<?php echo $item['lastModifiedDateTime'];?>" data-sort-size="<?php echo $item['size'];?>">
			<a <?php echo file_ico($item)=="image"?'class="glightbox"':"";echo file_ico($item)=="ondemand_video"?'class="iframe"':"";echo file_ico($item)=="audiotrack"?'class="iframe"':"";?> href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
              <?php if(isImage($item['name']) and $_COOKIE["image_mode"] == "1"):?>
			  <img class="mdui-img-fluid" src="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']); ?>">
              <?php else:?>
              <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
		    	<span><?php e($item['name']);?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
              <?php endif;?>
		  	</a>
		</li>
			<?php endif;?>
		<?php endforeach;?>

		  <?php if($totalpage > 1 ):?>
		  <li class="mdui-list-item th">
		    <div class="mdui-col-sm-6 mdui-left mdui-text-left">
		      <?php if(($page-1) >= 1 ):?>
		        <a href="<?php echo preg_replace('/\/$/', '', "$root"); ?><?php e($path) ?>.page-<?php e($page-1) ?>/" class="mdui-btn mdui-btn-raised">上一页</a>
		      <?php endif;?>
		      <?php if(($page+1) <= $totalpage ):?>
		        <a href="<?php echo preg_replace('/\/$/', '', "$root"); ?><?php e($path) ?>.page-<?php e($page+1) ?>/" class="mdui-btn mdui-btn-raised">下一页</a>
		      <?php endif;?>
		    </div>
		    <div class="mdui-col-sm-6 mdui-right mdui-text-right">
		      <div class="mdui-right mdui-text-right"><span class="mdui-chip-title">Page: <?php e($page);?>/<?php e($totalpage);?></span></div>
		    </div>
		  </li>
		  <?php endif;?>
	</ul>
</div>
</div>
<?php if($readme):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; 0">
	<div class="mdui-chip">
	  <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
	  <span class="mdui-chip-title">README.md</span>
	</div>
	<?php e($readme);?>
</div>
<?php endif;?>
</div>
<script>
var $$ = mdui.JQ;
$$(function() {
    $$('.file .iframe').each(function() {
        $$(this).on('click', function() {
            layer.open({
              type: 2,
              title: '播放窗口', 
              shadeClose: true,
              shade: 0.8,
              area: ['100%', '100%'],
              content: $$(this).attr('href')+"&s=1" //iframe的url
            });
            return false;
        });
    });
});


	
$ = mdui.JQ;

$.fn.extend({
    sortElements: function (comparator, getSortable) {
        getSortable = getSortable || function () { return this; };

        var placements = this.map(function () {
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);
            };
        });

        return [].sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });
    }
});

function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}

function thumb(){
	if($('.mdui-fab i').text() == "apps"){
		$('.mdui-fab i').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$('.mdui-fab i').text("apps");
		$('.nexmoe-item').addClass('thumb');
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image" || $(this).text() == "ondemand_video"){
				var href = $(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$(this).hide();
				$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
			}
		});
	}

}	

var lightbox = GLightbox();
	
$(function(){
	$('.icon-sort').on('click', function () {
        let sort_type = $(this).attr("data-sort"), sort_order = $(this).attr("data-order");
        let sort_order_to = (sort_order === "less") ? "more" : "less";

        $('li[data-sort]').sortElements(function (a, b) {
            let data_a = $(a).attr("data-sort-" + sort_type), data_b = $(b).attr("data-sort-" + sort_type);
            let rt = data_a.localeCompare(data_b, undefined, {numeric: true});
            return (sort_order === "more") ? 0-rt : rt;
        });

        $(this).attr("data-order", sort_order_to).text("expand_" + sort_order_to);
    });

  	
  
});
  
var ckname='image_mode';
function getCookie(name) 
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]); 
    else
        return null; 
} 
function setCookie(key,value,day){
	var exp = new Date(); 
	exp.setTime(exp.getTime() - 1); 
	var cval=getCookie(key); 
	if(cval!=null) 
	document.cookie= key + "="+cval+";expires="+exp.toGMTString(); 
	var date = new Date();
	var nowDate = date.getDate();
	date.setDate(nowDate + day);
	var cookie = key+"="+value+"; expires="+date;
	document.cookie = cookie;
	return cookie;
}
$('#image_view').on('click', function () {
	if($(this).prop('checked') == true){
		setCookie(ckname,1,1);
		window.location.href=window.location.href;
	}else{
		setCookie(ckname,0,1);
		window.location.href=window.location.href;
	}
});
  
</script>
<a href="javascript:thumb();" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">format_list_bulleted</i></a>
<?php view::end('content');?>
