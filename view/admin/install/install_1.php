<?php view::layout('install/layout')?>

<?php view::begin('content');?>

<?php $default_client_id = 'ea2b36f6-b8ad-40be-bc0f-e5e4a4a7d4fa';?>
<?php $default_client_secret = 'h27zG8pr8BNsLU0JbBh5AOznNS5Of5Y540l/koc7048=';?>

<div class="mdui-container-fluid">
	<div class="mdui-typo">
	  <h1>系统安装 <small>设置应用ID和Secret</small></h1>
	</div>

	<div class="mdui-typo">
      <h4 class="doc-article-title">
	    填入<code>client_id</code>和<code>client_secret</code>
      </h4>
    </div>

	<form action="" method="post">
		<div class="mdui-textfield">
		<label class="mdui-textfield-label">版本</label>
			<label class="mdui-radio">
    			<input type="radio" name="type" checked value="1"/>
				<i class="mdui-radio-icon"></i>国际版
			</label>
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<label class="mdui-radio">
				<input type="radio" name="type" value="2"/>
    			<i class="mdui-radio-icon"></i>世纪互联
			</label>
		</div>
		<div class="mdui-textfield mdui-textfield-floating-label">
			<i class="mdui-icon material-icons">https</i>
			<label class="mdui-textfield-label">应用机密(client secret)</label>
 			<?php if(config('client_secret')) { $client_secret_value = config('client_secret');} else {$client_secret_value = $default_client_secret;};?>
 			<input type="text" type="text" class="mdui-textfield-input" name="client_secret" required value="<?php echo $client_secret_value;?>"/>
			<div class="mdui-textfield-error">应用机密不能为空</div>
		</div>
		<br>
		<div class="mdui-textfield mdui-textfield-floating-label">
		  	<i class="mdui-icon material-icons">&#xe5c3;</i>
		  	<label class="mdui-textfield-label">应用 ID(client_id)</label>
 			<?php if(config('client_id')) { $client_id_value = config('client_id');} else {$client_id_value = $default_client_id;};?>
 		  	<input type="text" class="mdui-textfield-input" name="client_id" required value="<?php echo $client_id_value;?>"/>
		  	<div class="mdui-textfield-error">应用 ID不能为空</div>
		</div>
		<br>

		<div class="mdui-textfield mdui-textfield-floating-label">
		   <i class="mdui-icon material-icons">&#xe41a;</i>
 		   <?php if($redirect_uri != 'http://localhost/'):?>
 		   <label class="mdui-textfield-label">由于你的网站不是 <b>http://localhost/</b> , 将进行中转授权.</label>
		   <?php endif;?>
		   <input type="text" class="mdui-textfield-input" name="redirect_uri" value="<?php echo $redirect_uri;?>"/>
		   <div class="mdui-textfield-error">重定向URL不能为空</div>
		</div>
		<br>

		
	 <a class="mdui-btn mdui-btn-raised mdui-float-left" href="?step=0">上一步</a>
	 <button class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" type="submit">下一步</button>
	</form>

	
</div>

<?php view::end('content');?>