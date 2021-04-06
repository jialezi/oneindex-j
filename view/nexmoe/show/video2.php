<?php 
//仅支持教育版和企业版
if(strpos($item['downloadUrl'],"sharepoint.com") == false){
	header('Location: '.$item['downloadUrl']);exit();
}
$item['thumb'] = onedrive::thumbnail($item['path']);
$mpd =  str_replace("thumbnail","videomanifest",$item['thumb'])."&part=index&format=dash&useScf=True&pretranscode=0&transcodeahead=0";
?>
<link class="dplayer-css" rel="stylesheet" href="//cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.css">
<div class="mdui-container-fluid">
	<div class="nexmoe-item">
	<div class="mdui-center" id="dplayer"></div>
	</div>
</div>
<script src="//cdn.jsdelivr.net/npm/dashjs/dist/dash.all.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.js"></script>
<script>
const dp = new DPlayer({
	container: document.getElementById('dplayer'),
	lang:'zh-cn',
	video: {
	    url: '<?php echo $mpd;?>',
	    pic: '<?php @e($item['thumb']);?>',
	    type: 'dash'
	}
});
</script>
