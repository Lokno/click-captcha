<?php
	// Click CAPTCHA 0.1.1
	// Created by Jonathan Decker

	include('compare.php');
	include('generateCaptcha.php');

	$retdata = generateImage($cols,$rows,$dotSize,$distractors,
		                     $confetti,$fettiWidth,$fettiHeight,$salt);

    $imgData = $retdata[0];
	$targetHash = $retdata[1];
?>

<script type="text/javascript">
    var targetHash = '<?php print $targetHash ?>';
    var dotSize = <?php print $dotSize ?>;
    var imgData = "<?php print $imgData; ?>";
</script>
<link rel="stylesheet" type="text/css" href="clickCaptcha.css" />
<script type="text/javascript" src="clickCaptcha.js"></script>