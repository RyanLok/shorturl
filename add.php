<?php
require 'include/shorturl.class.php';
$option = include ('config.php');
$sourceurl = trim(urldecode($_POST['url']));
$shorturl = '';
if($sourceurl){
	$murl = new ShortUrl($option);
	$url = trim($_POST['shorturl']);
	$msg = '';
	if (!empty($url) && !preg_match("/^http\:\/\/".$option['domain']."/is", $url)) {
		if (!preg_match("/^[A-Za-z0-9\-_]{4,12}$/",$url)){
			if (strlen($url)>50)
				$msg = '字符串不能超过50';
			elseif (strlen($url)<3)
				$msg = '字符串不能少于3';
			else
				$msg = '仅接受A-Z，a-z，0-9或-和_，不允许使用其他字符';
		}
	    $url = "http://".$option['domain']."/".$url;
	}
	if ($msg == '') {
		$re = $murl->set($sourceurl,$url);
		if (preg_match("/^http\:\/\/".$option['domain']."/", $re)) {
			$shorturl = str_replace("http://".$option['domain']."/",'',$re);
			$msg ='result：'. $re.' <br/><img src="qrcode.php?url='.$re.'" />';
		} else {
			$shorturl = trim($_POST['shorturl']);
			$msg = '这个短网址存在。 请换另一个。';
		}
	}
}else{
	$msg = '请输入源URL';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $option['domain'];?> 短地址服务</title>
<meta name="keywords" content="Shorturl" />
<meta name="description" content="<?php echo $option['domain'];?>短地址服务" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="row">
<div class="span12">
<form class="form-horizontal" method="post">
<legend><?php echo $option['domain'];?> 短地址服务</legend>
    <ul class="nav nav-tabs">
    	<li class="active"><a href="#">短地址服务</a></li>
    	<li><a href="edit.php">修改短地址</a></li>
    </ul>
<div class="control-group">
 <label class="control-label" for="url">Source: </label>
 <div class="controls">
	<input class="input-xxlarge" required="required" type="url" name="url" id="url" placeholder="Long URL" value="<?php echo $sourceurl; ?>" />
 </div>
</div>
<div class="control-group info">
 <label class="control-label" for="shorturl">短地址：</label>
 <div class="controls">
 	<div class="input-prepend">
	<span class="add-on">http://<?php echo $option['domain'];?>/</span><input class="input-large" type="text" name="shorturl" id="shorturl" value="" />
	</div>
	<span class="help-inline">您可以定义您的短URl或为空以自动生成</span>
 </div>
</div>
<div class="control-group">
 <div class="controls">
  	<label class="tips">
 		<?php echo $msg; ?>
 	</label>
	<button type="submit" class="btn">提交</button>
 </div>
</div>
</form>
</div>
</div>
</div>
</body>
</html>