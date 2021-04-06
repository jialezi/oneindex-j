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
  if(in_array($ext,config('show')['image'])){
  	return "image";
  }
  if(in_array($ext,config('show')['video'])||in_array($ext,config('show')['video2'])){
  	return "ondemand_video";
  }
  if(in_array($ext,config('show')['video5'])){
  	return "personal_video";
  }
  if(in_array($ext,config('show')['audio'])){
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
<div class="nexmoe-item">
<div class="mdui-row">
	<ul class="mdui-list">
		<li class="mdui-list-item th" style="padding-right:36px;">
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

		<li class="mdui-list-item mdui-ripple" data-sort data-sort-name="<?php e($item['name']);?>" data-sort-date="<?php echo $item['lastModifiedDateTime'];?>" data-sort-size="<?php echo $item['size'];?>" style="padding-right:36px;">
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
			<a <?php echo file_ico($item)=="image"?'data-fancybox="gallery" class="fancybox"':"";echo file_ico($item)=="ondemand_video"?'class="iframe"':"";echo file_ico($item)=="personal_video"?'data-fancybox="gallery" class="fancybox"':"";echo file_ico($item)=="audiotrack"?'class="audio"':"";echo file_ico($item)=="insert_drive_file"?'class="dl"':""?> data-name="<?php e($item['name']);?>" data-readypreview="<?php echo strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));?>" href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
              <?php if(isImage($item['name']) and $_COOKIE["image_mode"] == "1"):?>
			  <img class="mdui-img-fluid" src="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']); ?>">
              <?php else:?>
              <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
		    	<span><?php e($item['name']);?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?>
			  
			  </div>
              <?php endif;?>
		  	</a>
		  	
			<div class="forcedownload "  >
 			      <a title="直接下载" href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>">
			          <button class="mdui-btn mdui-ripple mdui-btn-icon"><i class="mdui-icon material-icons">file_download</i></button>
			      </a>
			</div>

		</li>
			<?php endif;?>
		<?php endforeach;?>

		  <?php if($totalpage > 1 ):?>
		  <li class="mdui-list-item page">
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
<?php view::end('content');?>