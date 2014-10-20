PHP/GD imagestyle 
=================

### Requirements:
* PHP 5+
* PHP-GD

**@link** https://github.com/lyrisias/PHP-GD-Imagestyle  
**@author** John Athanasiadis <joe.athanasiadis@gmail.com>  
**@copyright** 2014 John Athanasiadis  
**@license** http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License  
**@note** This program is distributed in the hope that it will be useful - WITHOUT  
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or  
FITNESS FOR A PARTICULAR PURPOSE.  

###WHAT IS IMAGESTYLE?

Imagestyle is a function which works as an implement for the PHP/GD library.  
*You can edit your images in a single line.*

e.g. #1 - *Resize a big image from 1000x1000 pixels to 200x200*
```php
require_once('gd_imagestyle.php');
$big_image = imagecreatefrompng('img/1.png');
$resized = imagestyle($big_image,'resize:200 200');
```
e.g. #2 - *Crop a part of the big image and convert it to grayscale*
```php
require_once('gd_imagestyle.php');
$big_image = imagecreatefrompng('img/1.png');
$cropped = imagestyle($big_image,'crop:200 200; grayscale:on;'); 
```
e.g. #3 - *Create a centered thumbnail 200x200*
```php
require_once('gd_imagestyle.php');
$big_image = imagecreatefrompng('img/1.png');
$thumb = imagestyle($big_image,'autosize:200 200;'); 
```

###INSTALLATION

There is no need for installation, all you have to do is to copy the gd_imagestyle.php and include it to your code.
```php
require_once('path/to/gd_imagestyle.php');
```

###IMAGESTYLE FUNCTION

*gd-img-object* imagestyle( *gd-img-object* **$src**, *string* **$style**)  
@param *gd-img-object* **$src**  
@param *string* **$style**  
@return *gd-img-object*

###STYLE REFERENCE

#####background
Draws a background color to the image. Works only for images with transparency  
*Values: __none | r.g.b__*  
*__none__= no background, __r.g.b__=string formatted 255.255.255*
>Do nothing to the background  
```php
 imagestyle($image,'background:none'); 
```
>Draw a red background  
```php
imagestyle($image,'background:255.0.0'); 
```

#####resize
Resizes the image.  
*Values: __w h | w h min-w min-h | w h min-w min-h max-w max-h__*  
*__w__=int width, __h__=int height*
>Resize the image to height 100 and width auto 
```php
imagestyle($image,'resize:0 100'); 
```
>Resize the image to width 500 and height 300
```php
imagestyle($image,'resize:500 300'); 
```
>Resize the image to width 500 and height auto, but if after the resize,the height is less than 450 it will resize it again with height 450 and with auto
```php
imagestyle($image,'resize:500 0 0 450'); 
```
>Resize the image to width 750 but if after the resize, the height is less than 350 it will resize it again with height 350 and with auto,but if after the second resize the width is greater than 800 it will resize the image for one more time with width 800 and height auto
```php
imagestyle($image,'resize:700 0 0 350 800 0'); 
```

#####crop
Takes a part of the image.  
*Values: __w h | l t w h__*  
*__w__=int width, __h__=int height __l__=int left __t__=int top*
>Take the part *x1=0,y1=0,x2=100,y2=100* 
```php
imagestyle($image,'crop:100 100'); 
```
>Take the part *x1=50,y1=20,x2=150,y2=120* 
```php
imagestyle($image,'crop:50 20 100 100');  
```

#####autosize
Creates a thumbnail from the image on the given size. It's good for thumbnails and avatars.  
*Values: __w h__*  
*__w__=int width, __h__=int height*
>Resize the image to the 100x100 and crop it to the center. 
```php
imagestyle($image,'autosize:100 100');  
```

#####negative
Reverses all colors of the image.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'negative:on;');   
```

#####grayscale
Converts the image into grayscale.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'grayscale:on;');  
```

#####brightness
Changes the brightness of the image.  
*Values: __-255 ~ 255__*
>Apply the filter
```php
imagestyle($image,'brightness:100;'); 
```

#####contrast
Changes the contrast of the image.  
*Values: __-255 ~ 255__*
>Apply the filter
```php
imagestyle($image,'contrast:-100;'); 
```

#####colorize
Colorize the image.  
*Values: __none | r.g.b__*  
*__none__= do nothing, __r.g.b__=string formatted 255.255.255*
>Apply the filter
```php
imagestyle($image,'colorize:0.160.0;'); 
```

#####edgedetect
Uses edge detection to highlight the edges in the image.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'edgedetect:on;'); 
```

#####emboss
Embosses the image.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'emboss:on;'); 
```

#####gaussian-blur
Blurs the image using the Gaussian method.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'gaussian-blur:on;');  
```

#####selective-blur
Blurs the image.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'selective-blur:on;'); 
```

#####mean-removal
Uses mean removal to achieve a "sketchy" effect.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'mean-removal:on;'); 
```

#####smooth
Makes the image smoother.  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'smooth:100;'); 
```

#####pixelate
Applies pixelation effect to the image.  
*Values: __>=0__ pixels*
>Apply the filter
```php
imagestyle($image,'pixelate:5;'); 
```

#####white-balance
Auto white-balance  
*Values: __on | off__*
>Apply the filter
```php
imagestyle($image,'white-balance:on;'); 
```
 
I hope it will help you.
 
