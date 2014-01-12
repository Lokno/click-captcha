<?php
// Click CAPTCHA 0.1.0
// Created by Jonathan Decker


// hard-coded salt for hashing
$salt = 'sdfs8d6f9sd6f97s80df8s6d8f89f8sdfs7adtf79tasd76ftsd7a69tf79rasd68rtg79hgfsd8yhn68dftrg68tfasd7nb79';

if($_SERVER['REQUEST_METHOD'] == 'GET')
{

?>

<?php

}
else if( array_key_exists('h',$_POST) )
{
	$refHash = $_POST['h'];
	$x = $_POST['x'];
	$y = $_POST['y'];

	$attempt = sha1( "$x:$y:$salt" );

	if( $refHash == $attempt )
	{
		print 'TRUE';
	}
	else
	{
		print 'FALSE';
	}
}

?>