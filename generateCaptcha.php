<?php
	// Click Captcha 0.1.0
	// Created by Jonathan Decker

	$rows = 10;
	$cols = 16;
	$dotSize = 20;
	$distractors = 10;
	$confetti = 300;
	$fettiWidth = 8;
	$fettiHeight = 1.2;

	function HSVtoRGB(array $hsv) 
	{
	    list($H,$S,$V) = $hsv;

	    $H *= 6;

	    $I = floor($H);
	    $F = $H - $I;

	    $M = $V * (1 - $S);
	    $N = $V * (1 - $S * $F);
	    $K = $V * (1 - $S * (1 - $F));

	    switch ($I) {
	        case 0:
	            list($R,$G,$B) = array($V,$K,$M);
	            break;
	        case 1:
	            list($R,$G,$B) = array($N,$V,$M);
	            break;
	        case 2:
	            list($R,$G,$B) = array($M,$V,$K);
	            break;
	        case 3:
	            list($R,$G,$B) = array($M,$N,$V);
	            break;
	        case 4:
	            list($R,$G,$B) = array($K,$M,$V);
	            break;
	        case 5:
	        case 6: //for when $H=1 is given
	            list($R,$G,$B) = array($V,$M,$N);
	            break;
	    }
	    return array($R, $G, $B);
	}

	function rotate2D( $v, $theta )
	{
		$temp = array(0,0);
		$c = cos($theta);
		$s = sin($theta);

		$temp[0] = $c * $v[0] + -$s * $v[1];
		$temp[1] = $s * $v[0] +  $c * $v[1];

		return $temp;
	}

	function createImage($x,$y,$s,$w,$h,$distractors,$confetti,$fettiWidth,$fettiHeight) { 
		$img = imagecreatetruecolor($w,$h); 
		imagefill($img, 0, 0, imagecolorallocate($img,64,64,64)); 

		$hsv = array(0,1.0,1.0);
		$hsv[0] = (rand() / getrandmax()) * 0.20 + 0.80;
	    $color = HSVtoRGB($hsv);
	    $imgColor = imagecolorallocate($img,intval($color[0]*255.0),intval($color[1]*255.0),intval($color[2]*255.0));
		imagefilledarc ($img, $x, $y, $s, $s, 0, 360, $imgColor, IMG_ARC_PIE);

		$dw = $s;
		$dh = $s;
		
		for( $i = 0; $i < $distractors; $i++ )
		{
			$dx = rand(0,$w);
			$dy = rand(0,$h);
			$temp = array($dx-$x,$dy-$y);
			$delta = $temp[0]*$temp[0] + $temp[1]*$temp[1];
			while( $delta < $s*$s*2 )
			{
				$dx = rand(0,$w);
				$dy = rand(0,$h);
				$temp = array($dx-$x,$dy-$y);
				$delta = $temp[0]*$temp[0] + $temp[1]*$temp[1];
			}

			imagefilledrectangle($img, $dx, $dy, $dx+$dw, $dy+$dh, $imgColor);
		}

		$values = array( 0, 0, 0, 0, 0, 0, 0, 0 );
		$hsv = array(0,0.60,0.60);
		$temp = array(0,0);

		for( $i = 0; $i < $confetti; $i++ )
		{
		    $cx = rand(0,$w);
		    $cy = rand(0,$h);
		    $theta = 3.1415926513 * (rand() / getrandmax());
		    $hsv[0] = (rand() / getrandmax()) * 0.70;
		    $color = HSVtoRGB($hsv);
			
			$c = cos($theta);
			$s = sin($theta);

		    $values[0] = $fettiWidth;
		    $values[1] = $fettiHeight;

		    $values[2] = $fettiWidth;
		    $values[3] = -$fettiHeight;

		    $values[4] = -$fettiWidth;
		    $values[5] = -$fettiHeight;

		    $values[6] = -$fettiWidth;
		    $values[7] = $fettiHeight;

            // upper left
			$temp[0] = $c * $values[0] + -$s * $values[1];
			$temp[1] = $s * $values[0] +  $c * $values[1];
			$values[0] = $cx+$temp[0];
			$values[1] = $cy+$temp[1];

            // bottom left
			$temp[0] = $c * $values[2] + -$s * $values[3];
			$temp[1] = $s * $values[2] +  $c * $values[3];
			$values[2] = $cx+$temp[0];
			$values[3] = $cy+$temp[1];

			// bottom right
			$temp[0] = $c * $values[4] + -$s * $values[5];
			$temp[1] = $s * $values[4] +  $c * $values[5];
			$values[4] = $cx+$temp[0];
			$values[5] = $cy+$temp[1];

            // upper right
			$temp[0] = $c * $values[6] + -$s * $values[7];
			$temp[1] = $s * $values[6] +  $c * $values[7];
			$values[6] = $cx+$temp[0];
			$values[7] = $cy+$temp[1];

		    $imgColor = imagecolorallocate($img,intval($color[0]*255.0),intval($color[1]*255.0),intval($color[2]*255.0));

			imagefilledpolygon ($img, $values, 4, $imgColor);
		}

		ob_start();
		imagepng($img);
		$imdata = base64_encode(ob_get_clean());    
		imagedestroy($img);
		return $imdata;
	}

	function generateImage($cols,$rows,$dotSize,$distractors,$confetti,$fettiWidth,$fettiHeight,$salt)
	{
		$x = mt_rand( 0, $cols-1 );
		$y = mt_rand( 0, $rows-1 );
		$imdata = createImage($x*$dotSize+$dotSize*0.5,
			                  $y*$dotSize+$dotSize*0.5,
			                  $dotSize,
			                  $cols*$dotSize,
			                  $rows*$dotSize,
			                  $distractors,
			                  $confetti,$fettiWidth,$fettiHeight );

		$targetHash = sha1( "$x:$y:$salt" );

		return array($imdata,$targetHash);
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		// included for salt value
		include('compare.php');
		
		$retdata = generateImage($cols,$rows,$dotSize,$distractors,
			                     $confetti,$fettiWidth,$fettiHeight,$salt);

	    $imgData = $retdata[0];
		$targetHash = $retdata[1];

		print '{ "imgData" : "' . $imgData . '", "targetHash" : "' . $targetHash . '" }';
	}
?>