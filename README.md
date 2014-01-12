# Click CAPTCHA - A Visual CAPTCHA for Human Authentication #

Click CAPTCHA is a concept PHP implementation of a visual CAPTCHA which requires a single mouse click to authenticate that the user is human. The user must select a circle in a raster image hidden among dashes and like-colored distractors.

The CAPTCHA generates a 320x200 PNG image using PHP. It is a grid of 10x16 cells, in which one is filled with a circle of random hue. A click attempt is sent via AJAX to the server using POST, and compared to the hashed answer. The hash is sent to the page, but it is salted such that an attack could not just try 10x16 positions against the hash. The chance that an informed attacker selects the correct cell at random is 1 / 10x16 = 0.625 percent. The distracting squares and dashes make image processing approach difficult.

Example generated image. The target circle is on the far right, middle:

![alt tag](https://raw.github.com/lokno/click-captcha/master/example_image.png)

For a running example, visit: http://www.rabbitfury.com/captcha/

## Usage ##

- Run example file index.php on a PHP-enabled web server
- Click the circle in the image
- If "You're Human" is displayed, onCaptchaSuccess() was called in index.php
- Otherwise, onCaptchaFailed() is called, and the image is reloaded
- Modify these methods to change the behavior as desired

