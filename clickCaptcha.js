// Click CAPTCHA 0.1.1
// Created by Jonathan Decker

URLGenerate = "generateCaptcha.php";
URLSubmit = "compare.php";
divStr = 'Click the circle<input type="image" id="captcha_image" src="data:image/png;base64,';

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

    divele.innerHTML = divStr + imgData + '"/>';
    
    imgele = document.getElementById("captcha_image");
    imgele.onclick = submitForm;
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
			onCaptchaFailed();
		}
	}
}

function submitForm(e)
{
	imgele = document.getElementById("captcha_image");
	img_left = imgele.offsetLeft;
    img_top = imgele.offsetTop;	

	var pos_x = e.offsetX ? (e.offsetX) : e.pageX-img_left;
	var pos_y = e.offsetY ? (e.offsetY) : e.pageY-img_top;

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
	http.open('POST', URLGenerate, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.onreadystatechange = handleGenerateResponse;
	http.send();

	divele = document.getElementById("captcha");
	divele.onclick = null;
}