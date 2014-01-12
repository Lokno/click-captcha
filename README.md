# Click CAPTCHA - A Visual CAPTCHA for Human Authentication #

Click CAPTCHA is a concept PHP implementation of a visual CAPTCHA which requires a single mouse click to authenticate that the user is human. The user must select a circle in a raster image hidden among dashes and like-colored distractors.

The CAPTCHA generates a 320x200 PNG image using PHP. It is a grid of 10x16 cells, in which one is filled with a purple circle of randomally jittered hue. The chance that an informed attacker selects the correct cell is 1 / 10x16 = 0.625 percent. The distracting squares and dashes make an image processing approach difficult.

## Usage ##

- Run index.php on a PHP-enabled web server
- Click the circle in the image
- If "You're Human" is displayed, onCaptchaSuccess() was called in index.php
- Otherwise, onCaptchaFailed() is called, and the image is reloaded
- Modify these methods to change the behavior as desired