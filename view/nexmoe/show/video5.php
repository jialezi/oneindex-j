<?php view::layout('layout')?>
<?php 
$item['thumb'] = onedrive::thumbnail($item['path']);

$downloadUrl = $item['downloadUrl'];
 	if (config('proxy_domain') != ""){
 	$downloadUrl = str_replace(config('main_domain'),config('proxy_domain'),$item['downloadUrl']);
 	}else {
 		$downloadUrl = $item['downloadUrl'];
 	}
?>
<style>
	.mdui-img-fluid, .mdui-video-fluid {
    	max-height: -webkit-fill-available !important;
    }
</style>
<?php view::begin('content');?>
<div class="mdui-container-fluid">
	<div class="nexmoe-item">
		<video class="mdui-video-fluid mdui-center" preload controls poster="<?php @e($item['thumb']);?>">
			<source src="<?php e($item['downloadUrl']);?>" type="video/mp4">
		</video>
		<div class="mdui-row">
		<br>
		<div class="mdui-textfield">
			<label class="mdui-textfield-label">下载地址</label>
			<input class="mdui-textfield-input" type="text" value="<?php e($url);?>"/>
		</div>
		<div class="mdui-textfield">
			<label class="mdui-textfield-label">HTML 引用地址</label>
			<input class="mdui-textfield-input" type="text" value="<video><source src='<?php e($url);?>' type='video/mp4'></video>"/>
		</div>
		<br>
		</div>
	</div>
</div>
<a href="<?php e($url);?>" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">file_download</i></a>
<?php view::end('content');?>

