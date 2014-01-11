// Click Captcha 0.1.0
// Created by Jonathan Decker

URLGenerate = "generateCaptcha.php";
URLSubmit = "compare.php";
divStr = 'Click the circle<img src="data:image/png;base64,';
img_left = 0;
img_top = 0;
count_misses = 0;
count_threshold = 0;

function createRequestObject() {
  var ro;

  // almost everyone but MSIE less than 7
  try
  {
	  ro = new XMLHttpRequest();
	  // MSIE branches
  }
  catch (e)
  {
	  try
	  {
		  ro = new ActiveXObject("Msxml2.XMLHTTP");
	  }
	  catch (e)
	  {
		  try
		  {
			  ro = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  catch (e)
		  {
			  ro = null;
		  }
	  }
  }
  return ro;
}

var http = createRequestObject();

function initCaptcha()
{
	divele = document.getElementById("captcha");
    img_left = divele.offsetLeft;
    img_top = divele.offsetTop;	

    divele.onclick = submitForm;

    divele.innerHTML = divStr + imgData + '"/>';
}

function handleSubmitResponse()
{
	if(http.readyState == 4)
	{
		if( http.responseText == 'TRUE')
		{
			onCaptchaSuccess();
		}
		else
		{
			count_misses++;

			if( count_misses > count_threshold )
			{
				document.getElementById("captcha").onclick = null;
				onCaptchaFailed();
			}
		}
	}
}

function submitForm()
{
	var pos_x = event.offsetX ? (event.offsetX) : event.pageX-img_left;
	var pos_y = event.offsetY ? (event.offsetY) : event.pageY-img_top;

	http.open('POST', URLSubmit, true);

	var paramStr = 'h=' + targetHash;
	paramStr += '&x=' + Math.floor(pos_x / dotSize);
	paramStr += '&y=' + Math.floor(pos_y / dotSize);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.onreadystatechange = handleSubmitResponse;
	http.send(paramStr);
}

function handleGenerateResponse()
{
	if(http.readyState == 4)
	{
		//var obj jQuery.parseJSON( http.responseText );
		var obj = eval("(" + http.responseText + ')');

	    targetHash = obj.targetHash;
	    imgData = obj.imgData;	
	    count_misses = 0;

		divele = document.getElementById("captcha");
	    divele.onclick = submitForm;
	    divele.innerHTML = divStr + imgData + '"/>';	

	    onNewCaptcha();
	}
}

function requestNewCaptcha()
{
	var pos_x = event.offsetX ? (event.offsetX) : event.pageX-img_left;
	var pos_y = event.offsetY ? (event.offsetY) : event.pageY-img_top;

	http.open('POST', URLGenerate, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.onreadystatechange = handleGenerateResponse;
	http.send();

	divele = document.getElementById("captcha");
	divele.onclick = null;
}