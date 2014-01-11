<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Click Captcha</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <?php include('clickCaptcha.php'); ?>
		<script type="text/javascript">
			 // Click Captcha 0.1.0
			 // Created by Jonathan Decker

			 var start = 0;

		     function onCaptchaSuccess()
		     {
		     	document.getElementById("textBox").innerHTML = "YOU'RE HUMAN!";
		     }

		     function onCaptchaFailed()
		     {
		     	start = new Date().getTime();
		     	requestNewCaptcha();

		     	document.getElementById("textBox").innerHTML = "Loading...";
		     }

		     function onNewCaptcha()
		     {
	            var elapsed = new Date().getTime() - start;
	            document.getElementById("textBox").innerHTML = "Image generated in " + elapsed / 1000.0 + " sec";
		     }

		</script>       
	</head>
	<body onload="initCaptcha()"> 

	<div id="captcha"></div>

	<div id="textBox"></div>
	</body>
</html>