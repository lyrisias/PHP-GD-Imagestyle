<?php
/* 
 * @link https://github.com/lyrisias/PHP-GD-Imagestyle
 * @author John Athanasiadis <joe.athanasiadis@gmail.com>
 * @copyright 2014 John Athanasiadis
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/* REGEXES */
$__gdis_regexes = array();
$__gdis_regexes['r.g.b'] = '/^\d{1,3}\.\d{1,3}\.\d{1,3}$/i';

function __gdis_strtorgbarray($str){
	/* Takes a string e.g. "255.255.255" and converts it to an rgb array: array(255,255,255).
	 * @param string $str
	 * @return array
	 */
	global $__gdis_regexes;
	
	if( preg_match($__gdis_regexes['r.g.b'], $str) ){
		$colors = explode('.',$str);
		$r = (intval($colors[0])>=0?(intval($colors[0])<=255?intval($colors[0]):255):0);
		$g = (intval($colors[1])>=0?(intval($colors[1])<=255?intval($colors[1]):255):0);
		$b = (intval($colors[2])>=0?(intval($colors[2])<=255?intval($colors[2]):255):0);
		return array($r,$g,$b);
	}
	return array(0,0,0);
}
function __gdis_rgbarraytostr($arr){
	/* Takes an rgbarray e.g. array(255,255,255) and converts it to an string: "255.255.255".
	 * @param string $str
	 * @return array
	 */
	global $__gdis_regexes;
	
	if( __gdis_isrgbarray($arr) ){
		return "{$arr[0]}.{$arr[1]}.{$arr[2]}";
	}
	return "0.0.0";
}
function __gdis_isrgbstring($v){
	/* Takes a string e.g. "255.255.255" and returns true if it is "r.g.b" string.
	 * @param string $v
	 * @return boolean
	 */
	global $__gdis_regexes;
	
	if( is_string($v) && preg_match($__gdis_regexes['r.g.b'], $v) ){
		return true;
	}
	return false;
}
function __gdis_isrgbarray($v){
	/* Takes an rgbarray e.g. array(255,255,255) and returns true if it is an r.g.b array.
	 * @param array $v
	 * @return boolean
	 */
	global $__gdis_regexes;
	
	if ( is_array($v) && count($v)==3 ){
		return true;
	}
	return false;
}
function imagestyle($src,$style){
	/* Takes a GD Image Object and returns a new stylized copy of the image.
	 * @param gd image object $src 
	 * @param string $style 
	 * @return gd image object
	 */
	global $__gdis_regexes;
	/* DEFAULT STYLES */
	$filters=array();
	$filters['background'] = null ; 	// null | array(r,g,b)
	$filters['resize'] = 0 ; 			// 0 = no-resize | 1 = do-resize
	$filters['resize-width'] = 0 ; 		// >=0
	$filters['resize-height'] = 0 ;		// >=0
	$filters['resize-min-width'] = 0 ;	// >=0
	$filters['resize-min-height'] = 0 ; // >=0
	$filters['resize-max-width'] = 0 ;	// >=0
	$filters['resize-max-height'] = 0 ;	// >=0
	$filters['crop'] = 0 ; 				// 0 = no-crop | 1 = do-crop
	$filters['crop-left'] = 0 ;			// >=0
	$filters['crop-top'] = 0 ;			// >=0
	$filters['crop-width'] = 0 ;		// >=0
	$filters['crop-height'] = 0 ;		// >=0
	$filters['autosize'] = 0 ; 			// 0 = no-autosize | 1 = do-autosize
	$filters['autosize-width'] = 0 ; 	// >=0
	$filters['autosize-height'] = 0 ; 	// >=0
	$filters['negative'] = 0 ; 			// 0 | 1
	$filters['grayscale'] = 0 ; 		// 0 | 1
	$filters['brightness'] = 0 ;		// -255 ~ +255 
	$filters['contrast'] = 0 ; 			// -255 ~ +255 
	$filters['colorize'] = null ;		// null | array(r,g,b)
	$filters['edgedetect'] = 0;			// 0 | 1
	$filters['emboss'] = 0;				// 0 | 1
	$filters['gaussian-blur'] = 0;		// 0 | 1
	$filters['selective-blur'] = 0;		// 0 | 1
	$filters['mean-removal'] = 0; 		// 0 | 1
	$filters['smooth'] = 0; 			// 0 ~ +100
	$filters['pixelate'] = 0; 			// >=0
	$filters['white-balance'] = 0; 		// 0 | 1
	
	
	/* PARSE STYLES */
	$lines = explode(";",$style);
	foreach($lines as $line){
		$clean_line = trim($line);
		$line_parts = explode(":",$clean_line);
		if(count($line_parts)==2){
			$left_value = strtolower(trim($line_parts[0]));
			$right_value = strtolower(trim($line_parts[1]));
			// background
			if($left_value=='background'){ 
				if($right_value=='none'){
					$filters['background'] = null;
				}else if(__gdis_isrgbstring($right_value)){
					$filters['background'] = __gdis_strtorgbarray($right_value);
				}
			// resize
			}else if($left_value=='resize'){ 
				$values = explode(" ",$right_value);
				if(count($values)==1){
					$filters['resize'] = 1;
					$filters['resize-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['resize-height'] = 0;
					$filters['resize-min-width'] = 0 ; 
					$filters['resize-min-height'] = 0 ; 
					$filters['resize-max-width'] = 0 ;
					$filters['resize-max-height'] = 0 ;
				}else if(count($values)==2){
					$filters['resize'] = 1;
					$filters['resize-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['resize-height'] = (intval($values[1])>0?intval($values[1]):0);
					$filters['resize-min-width'] = 0 ; 
					$filters['resize-min-height'] = 0 ; 
					$filters['resize-max-width'] = 0 ;
					$filters['resize-max-height'] = 0 ;
				}else if(count($values)==4){
					$filters['resize'] = 1;
					$filters['resize-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['resize-height'] = (intval($values[1])>0?intval($values[1]):0);
					$filters['resize-min-width'] = (intval($values[2])>0?intval($values[2]):0);
					$filters['resize-min-height'] =  (intval($values[3])>0?intval($values[3]):0);
					$filters['resize-max-width'] = 0 ;
					$filters['resize-max-height'] = 0 ;
				}else if(count($values)==6){
					$filters['resize'] = 1;
					$filters['resize-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['resize-height'] = (intval($values[1])>0?intval($values[1]):0);
					$filters['resize-min-width'] = (intval($values[2])>0?intval($values[2]):0);
					$filters['resize-min-height'] =  (intval($values[3])>0?intval($values[3]):0);
					$filters['resize-max-width'] = (intval($values[4])>0?intval($values[4]):0);
					$filters['resize-max-height'] = (intval($values[5])>0?intval($values[5]):0);
				}else{
					
				}
			}else if($left_value=='resize-width'){ 
				$filters['resize'] = 1;
				$filters['resize-width'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='resize-height'){ 
				$filters['resize'] = 1;
				$filters['resize-height'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='resize-min-width'){
				$filters['resize-min-width'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='resize-min-height'){ 
				$filters['resize-min-height'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='resize-max-width'){
				$filters['resize-max-width'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='resize-max-height'){ 
				$filters['resize-max-height'] = (intval($right_value)>0?intval($right_value):0);
			// crop
			}else if($left_value=='crop'){ 
				$values = explode(" ",$right_value);
				if(count($values)==2){
					$filters['crop'] = 1;
					$filters['crop-left'] = 0;
					$filters['crop-top'] = 0;
					$filters['crop-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['crop-height'] =  (intval($values[1])>0?intval($values[1]):0);
				}else if(count($values)==4){
					$filters['crop'] = 1;
					$filters['crop-left'] = (intval($values[0])?intval($values[0]):0);
					$filters['crop-top'] = (intval($values[1])?intval($values[1]):0);
					$filters['crop-width'] = (intval($values[2])>0?intval($values[2]):0);
					$filters['crop-height'] =  (intval($values[3])>0?intval($values[3]):0);
				}
			}else if($left_value=='crop-left'){ 
				$filters['crop'] = 1;
				$filters['crop-left'] = (intval($right_value)?intval($right_value):0);
			}else if($left_value=='crop-top'){ 
				$filters['crop'] = 1;
				$filters['crop-top'] = (intval($right_value)?intval($right_value):0);
			}else if($left_value=='crop-width'){
				$filters['crop'] = 1;
				$filters['crop-width'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='crop-height'){ 
				$filters['crop'] = 1;
				$filters['crop-height'] = (intval($right_value)>0?intval($right_value):0);
			// autosize
			}else if($left_value=='autosize'){ 
				$values = explode(" ",$right_value);
				if(count($values)==2){
					$filters['autosize'] = 1;
					$filters['autosize-width'] = (intval($values[0])>0?intval($values[0]):0);
					$filters['autosize-height'] =  (intval($values[1])>0?intval($values[1]):0);
				}
			}else if($left_value=='autosize-width'){
				$filters['autosize'] = 1;
				$filters['autosize-width'] = (intval($right_value)>0?intval($right_value):0);
			}else if($left_value=='autosize-height'){ 
				$filters['autosize'] = 1;
				$filters['autosize-height'] = (intval($right_value)>0?intval($right_value):0);
			}
			// negative
			else if($left_value=='negative'){ 
				$filters['negative'] = ($right_value=='on'?1:0);
			}
			// grayscale
			else if($left_value=='grayscale'){ 
				$filters['grayscale'] = ($right_value=='on'?1:0);
			}
			//brightness
			else if($left_value=='brightness'){ 
				$filters['brightness'] = (intval($right_value)>=-255?(intval($right_value)<=255?intval($right_value):255):-255);
			}
			//contrast
			else if($left_value=='contrast'){ 
				$filters['contrast'] = (intval($right_value)>=-255?(intval($right_value)<=255?intval($right_value):255):-255);
			}
			//colorize
			else if($left_value=='colorize'){ 
				if($right_value=='none'){
					$filters['colorize'] = null;
				}else if(__gdis_isrgbstring($right_value)){
					$filters['colorize'] = __gdis_strtorgbarray($right_value);
				}
			}
			// edgedetect
			else if($left_value=='edgedetect'){ 
				$filters['edgedetect'] = ($right_value=='on'?1:0);
			}
			// emboss
			else if($left_value=='emboss'){ 
				$filters['emboss'] = ($right_value=='on'?1:0);
			}
			// gaussian-blur
			else if($left_value=='gaussian-blur'){ 
				$filters['gaussian-blur'] = ($right_value=='on'?1:0);
			}
			// selective-blur
			else if($left_value=='selective-blur'){ 
				$filters['selective-blur'] = ($right_value=='on'?1:0);
			}
			// mean-removal
			else if($left_value=='mean-removal'){ 
				$filters['mean-removal'] = ($right_value=='on'?1:0);
			}
			// smooth
			else if($left_value=='smooth'){ 
				$filters['smooth'] = (intval($right_value)>=0?(intval($right_value)<=100?intval($right_value):100):0);
			}
			// pixelate
			else if($left_value=='pixelate'){ 
				$filters['pixelate'] = (intval($right_value)>=0?intval($right_value):0);
			}
			// white-balance
			else if($left_value=='white-balance'){ 
				$filters['white-balance'] = ($right_value=='on'?1:0);
			}
		}
	}
	/* APPLY STYLES */
	$w = imagesx($src);
	$h = imagesy($src);
	$filtered_src = imagecreatetruecolor($w, $h);
	imagecopy($filtered_src, $src, 0, 0, 0, 0, $w, $h);
	foreach($filters as $filter => $value){
		// background 
		if($filter=='background'){
			if(__gdis_isrgbarray($value)){
				$w = imagesx($filtered_src);
				$h = imagesy($filtered_src);
				$layer = imagecreatetruecolor($w, $h);
				$bg = imagecolorallocate($layer, $value[0], $value[1], $value[2]);
				imagefilledrectangle($layer, 0, 0, $w, $h, $bg);
				imagecopyresampled( $layer, $filtered_src,  0, 0, 0, 0, $w, $h,$w,$h);
				imagedestroy($filtered_src);
				$filtered_src=$layer;
			}
		}
		// resize 
		else if($filter=='resize'){
			if($value==1){
				$w = imagesx($filtered_src);
				$h = imagesy($filtered_src);
				$new_w = $w;
				$new_h = $h;
				if($filters['resize-width']>0){
					$new_w = $filters['resize-width'];
					if($filters['resize-height']==0){
						$new_h = ($new_w * $h) / $w;
					}
				}
				if($filters['resize-height']>0){
					$new_h = $filters['resize-height'];
					if($filters['resize-width']==0){
						$new_w = ($new_h * $w) / $h;
					}
				}
				$resized = imagecreatetruecolor($new_w, $new_h);
				imagealphablending($resized , false);
				$col=imagecolorallocatealpha($resized ,255,255,255,127);
				imagefilledrectangle($resized ,0,0,$new_w,$new_h,$col);
				imagealphablending($resized,true);
				imagecopyresampled($resized,$filtered_src,0,0,0,0,$new_w,$new_h,$w,$h);
				imagedestroy($filtered_src);
				$filtered_src=$resized;
				// resize minimums
				if($filters['resize-min-width']>0 || $filters['resize-min-height']>0){
					$w = imagesx($filtered_src);
					$h = imagesy($filtered_src);
					$new_w = $w;
					$new_h = $h;
					if($filters['resize-min-width']>0){
						if($w<$filters['resize-min-width']){
							$new_w = $filters['resize-min-width'];
							$new_h = ($new_w * $h) / $w;
						}
					}
					if($filters['resize-min-height']>0){
						if($h<$filters['resize-min-height']){
							$new_h = $filters['resize-min-height'];
							$new_w = ($new_h * $w) / $h;
						}
					}
					$resized = imagecreatetruecolor($new_w, $new_h);
					imagealphablending($resized , false);
					$col=imagecolorallocatealpha($resized ,255,255,255,127);
					imagefilledrectangle($resized ,0,0,$new_w,$new_h,$col);
					imagealphablending($resized,true);
					imagecopyresampled($resized,$filtered_src,0,0,0,0,$new_w,$new_h,$w,$h);
					imagedestroy($filtered_src);
					$filtered_src=$resized;
				}
				// resize maximums
				if($filters['resize-max-width']>0 || $filters['resize-max-height']>0){
					$w = imagesx($filtered_src);
					$h = imagesy($filtered_src);
					$new_w = $w;
					$new_h = $h;
					if($filters['resize-max-width']>0){
						if($w>$filters['resize-max-width']){
							$new_w = $filters['resize-max-width'];
							$new_h = ($new_w * $h) / $w;
						}
					}
					if($filters['resize-max-height']>0){
						if($h>$filters['resize-max-height']){
							$new_h = $filters['resize-max-height'];
							$new_w = ($new_h * $w) / $h;
						}
					}
					$resized = imagecreatetruecolor($new_w, $new_h);
					imagealphablending($resized , false);
					$col=imagecolorallocatealpha($resized ,255,255,255,127);
					imagefilledrectangle($resized ,0,0,$new_w,$new_h,$col);
					imagealphablending($resized,true);
					imagecopyresampled($resized,$filtered_src,0,0,0,0,$new_w,$new_h,$w,$h);
					imagedestroy($filtered_src);
					$filtered_src=$resized;
				}
			}
		}
		// crop 
		else if($filter=='crop'){
			if($value==1 && $filters['crop-width']>0 && $filters['crop-height']>0){
				$w = imagesx($filtered_src);
				$h = imagesy($filtered_src);
				$cropped_l = $filters['crop-left'];
				$cropped_t = $filters['crop-top'];
				$cropped_w = $filters['crop-width'];
				$cropped_h = $filters['crop-height'];
				
				$cropped = imagecreatetruecolor($cropped_w, $cropped_h);
				imagealphablending($cropped, false);
				$col=imagecolorallocatealpha($cropped,255,255,255,127);
				imagefilledrectangle($cropped,0,0,$cropped_w, $cropped_h,$col);
				imagealphablending($cropped,true);
				imagecopyresampled($cropped,$filtered_src,0,0,$cropped_l,$cropped_t,$w,$h,$w,$h);
				imagedestroy($filtered_src);
				if(__gdis_isrgbarray($filters['background'])){
					$filtered_src=imagestyle($cropped,"background:".__gdis_rgbarraytostr($filters['background']).";");
				}else{
					$filtered_src=$cropped;
				}
				
			}
		}
		// autosize 
		else if($filter=='autosize'){
			if($value==1 && $filters['autosize-width']>0 && $filters['autosize-height']>0){
				$w = imagesx($filtered_src);
				$h = imagesy($filtered_src);
				$new_w = $w;
				$new_h = $h;
				$autosized_w = $filters['autosize-width'];
				$autosized_h = $filters['autosize-height'];
				if($w>$h){
					$new_h = $autosized_h;
					$new_w = ceil( ($new_h * $w) / $h );
				}else{
					$new_w = $autosized_w;
					$new_h = ceil( ($new_w * $h) / $w );
				}
				$filtered_src=imagestyle($filtered_src,"resize:{$new_w} {$new_h};");

				$w = $new_w;
				$h = $new_h;
				$autosized_l = ceil(($w/2)-($autosized_w/2));
				$autosized_t = ceil(($h/2)-($autosized_h/2));
				$filtered_src=imagestyle($filtered_src,"crop:{$autosized_l} {$autosized_t} {$autosized_w} {$autosized_h};");
	
			}
		}
		// negative 
		else if($filter=='negative'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_NEGATE);
			}
		}
		// grayscale 
		else if($filter=='grayscale'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_GRAYSCALE);
			}
		}
		// brightness
		else if($filter=='brightness'){
			if($value){
				imagefilter($filtered_src, IMG_FILTER_BRIGHTNESS,$value);
			}
		}
		// contrast
		else if($filter=='contrast'){
			if($value){
				imagefilter($filtered_src, IMG_FILTER_CONTRAST,$value);
			}
		}
		// colorize
		else if($filter=='colorize'){
			if(__gdis_isrgbarray($value)){
				imagefilter($filtered_src, IMG_FILTER_COLORIZE, $value[0],$value[1],$value[2], 0); 
			}
		}
		// edgedetect
		else if($filter=='edgedetect'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_EDGEDETECT);
			}
		}
		// emboss 
		else if($filter=='emboss'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_EMBOSS);
			}
		}
		// gaussian-blur 
		else if($filter=='gaussian-blur'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_GAUSSIAN_BLUR);
			}
		}
		// selective-blur 
		else if($filter=='selective-blur'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_SELECTIVE_BLUR);
			}
		}
		// mean-removal 
		else if($filter=='mean-removal'){
			if($value==1){
				imagefilter($filtered_src, IMG_FILTER_MEAN_REMOVAL);
			}
		}
		// smooth 
		else if($filter=='smooth'){
			if($value>0){
				imagefilter($filtered_src, IMG_FILTER_SMOOTH,$value);
			}
		}
		// pixelate
		else if($filter=='pixelate'){
			if($value>0){
				imagefilter($filtered_src, IMG_FILTER_PIXELATE,$value,true);
			}
		}
		// white-balance 
		else if($filter=='white-balance'){
			if($value==1){ 
				imagefilter($filtered_src, IMG_FILTER_BRIGHTNESS,30);
				// analysis
				$Analysis=array('red'=>array(),'green'=>array(),'blue'=>array(),'alpha'=>array());
				$w = imagesx($filtered_src);
				$h = imagesy($filtered_src);
				for ($x = 0; $x < $w; $x++){
					for ($y = 0; $y < $h; $y++){
						$OriginalPixel = @imagecolorsforindex($filtered_src, @imagecolorat($filtered_src, $x, $y) );
						@$Analysis['red'][$OriginalPixel['red']]++;
						@$Analysis['green'][$OriginalPixel['green']]++;
						@$Analysis['blue'][$OriginalPixel['blue']]++;
						@$Analysis['alpha'][$OriginalPixel['alpha']]++;
					}
				}
				$keys = array('red', 'green', 'blue', 'alpha');
				foreach ($keys as $key){
					ksort($Analysis[$key]);
				}
				//effect
				$targetPixel = array('red' => max(array_keys($Analysis['red'])),
                'green' => max(array_keys($Analysis['green'])),
                'blue' => max(array_keys($Analysis['blue']))
                );
				$grayValue = round(($targetPixel['red'] * 0.30) + ($targetPixel['green'] * 0.59) + ($targetPixel['blue'] * 0.11));
				$scaleR = $grayValue / $targetPixel['red'];
				$scaleG = $grayValue / $targetPixel['green'];
				$scaleB = $grayValue / $targetPixel['blue'];
				for ($x = 0; $x < $w; $x++){
					for ($y = 0; $y < $h; $y++){
						$currentPixel = @imagecolorsforindex($filtered_src, @imagecolorat($filtered_src, $x, $y) );
						$newColor = @imagecolorallocatealpha($filtered_src, 
							max(0, min(255, round($currentPixel['red'] * $scaleR))),
							max(0, min(255, round($currentPixel['green'] * $scaleG))),
							max(0, min(255, round($currentPixel['blue'] * $scaleB))),
							intval($currentPixel['alpha']) 
							);
						imagesetpixel($filtered_src, $x, $y, $newColor);
					}
				}
				//end effect
				imagefilter($filtered_src, IMG_FILTER_BRIGHTNESS,10);
				imagefilter($filtered_src, IMG_FILTER_CONTRAST,-15);
			}
		}
	}
	return $filtered_src;
}?>
