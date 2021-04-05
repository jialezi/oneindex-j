<?php view::layout('layout')?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">

	<div class="mdui-typo">
	  <h1> 账号设置 <small>绑定设置</small></h1>
	</div>
	<form action="" method="post">
		<div class="mdui-textfield">
		  <h4>client_secret</h4>
		  <input class="mdui-textfield-input" type="text" name="client_secret" value="<?php echo $config['client_secret'];?>"/>
		</div>

  		<div class="mdui-textfield">
 		  <h4>client_id</h4>
 		  <input class="mdui-textfield-input" type="text" name="client_id" value="<?php echo $config['client_id'];?>"/>
 		</div>
 		 <div class="mdui-textfield">
		  <h4>redirect_uri</h4>
		  <input class="mdui-textfield-input" type="text" name="redirect_uri" value="<?php echo $config['redirect_uri'];?>"/>
		</div>
        <div class="mdui-textfield">
		  <h4>oauth_url</h4>
		  <input class="mdui-textfield-input" type="text" name="oauth_url" value="<?php echo $config['oauth_url'];?>"/>
		</div>
       	<div class="mdui-textfield">
 		  <h4>API(如切换成SharePoint，请将链接的"me"改成"sites/{site-id}" |<a target="_blank" href="https://od.xkx.me/sp.php">site-id获取</a>)</h4>
		  <input class="mdui-textfield-input" type="text" name="api_url" value="<?php echo $config['api_url'];?>"/>
 		</div>
        <div class="mdui-textfield">
		  <h4>refresh_token(如目录空白,清空refresh_token—>保存—>前台或后台重新绑定账号—>刷新缓存)</h4>
		  <input class="mdui-textfield-input" type="text" name="refresh_token" value="<?php echo $config['refresh_token'];?>"/>
		</div>
		<div class="mdui-textfield">
		  <h4>绑定账号</h4>
		  <a href="<?php echo onedrive::authorize_url();?>" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-left">绑定账号</a>
		  </label>
		</div>
		

	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
	   </button>
	</form>
</div>
<?php view::end('content');?>
