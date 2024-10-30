<?php
/**********************************
*  Will resize an image to a      *
*  max width or height and keep   *
*  aspect ratio Name this file    *
*  anything you like.             *
*  I will use imageresize.php     *
**********************************/

header('Content-type: image/jpeg');


    function url_exists($url){
        $url = str_replace("http://", "", $url);
        if (strstr($url, "/")) {
            $url = explode("/", $url, 2);
            $url[1] = "/".$url[1];
        } else {
            $url = array($url, "/");
        }
 
        $fh = fsockopen($url[0], 80);
        if ($fh) {
            fputs($fh,"GET ".$url[1]." HTTP/1.1\nHost:".$url[0]."\n\n");
            if (fread($fh, 22) == "HTTP/1.1 404 Not Found") { return FALSE; }
            else { return TRUE;    }
 
        } else { return FALSE;}
    }    



function resampleimage($maxsize, $sourcefile, $imgcomp=0){
	
// SET THE IMAGE COMPRESSION
$g_imgcomp=100-$imgcomp;
  // CHECK TO SEE IF THE IMAGE EXISTS FIRST
  if(url_exists($sourcefile)){
  // FIRST WE GET THE CURRENT IMAGE SIZE
  $g_is=getimagesize($sourcefile);
    /********* CALCULATE THE WIDTH AND THE HEIGHT ***************/
    // CHECK TO SEE IF THE WIDTH AND HEIGHT ARE ALREADY SMALLER THAN THE MAX SIZE
	

    if($g_is[0] <= $maxsize && $g_is[1] <= $maxsize){
    // LEAVE WIDTH AND HEIGHT ALONE IF IMAGE IS SMALLER THAN MAXSIZE
    $new_width=$g_is[0];
    $new_height=$g_is[1];
    }
	else {
	
		
    // GET VALUE TO CALCULATE WIDTH AND HEIGHT
    $w_adjust = ($maxsize / $g_is[0]);
    $h_adjust = ($maxsize / $g_is[1]);
      // CHECK TO WHICH DIMENSION REQUIRES THE SMALLER ADJUSTMENT
      if($w_adjust <= $h_adjust){
      // CALCULATE WIDTH AND HEIGHT IF THE WIDTH VALUE IS SMALLER
      $new_width=($g_is[0]*$w_adjust);
      $new_height=($g_is[1]*$w_adjust);
      } else {
      // CALCULATE WIDTH AND HEIGHT IF THE HEIGHT VALUE IS SMALLER
      $new_width=($g_is[0]*$h_adjust);
      $new_height=($g_is[1]*$h_adjust);
      }
	  
	  if($g_is[0] <= $maxsize){
			$new_width=$g_is[0];
		}
		
		if($g_is[1] <= $maxsize){
			$new_height=$g_is[1];
		}
    }


  //SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER THE LAST "." )
	$image_type = strrchr($sourcefile, ".");

	//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
	switch(strtolower($image_type)) {
		case '.jpg':
			$img_src = imagecreatefromjpeg($sourcefile);
			break;
		case '.jpeg':
			$img_src = imagecreatefromjpeg($sourcefile);
			break;
		case '.png':
			$img_src = imagecreatefrompng($sourcefile);
			break;
		case '.gif':
			$img_src = imagecreatefromgif($sourcefile);
			break;
		default:
			echo("Error Invalid Image Type");
			die;
			break;
	}
  // CREATE THE TRUE COLOR IMAGE WITH NE WIDTH AND HEIGHT
  $img_dst=imagecreatetruecolor($new_width,$new_height);
  // RESAMPLE THE IMAGE TO NEW WIDTH AND HEIGHT
  imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $new_width, $new_height, $g_is[0], $g_is[1]);
  // OUTPUT THE IMAGE AS A JPEG.
  // THIS CAN BE CHANGED IF YOU WANT TRANSPARENCY OR PREFER ANOTHER FORMAT. MAKE SURE YOU CHANGE HEADER ABOVE.
  imagejpeg($img_dst);
  // DESTROY THE NEW IMAGE
  imagedestroy($img_dst);
  
  return true;
  } else {
  return false;
  }
  
 
}
// NOW CALL THE IMAGE FROM ANY OTHER PAGE WITH <img src="imageresize.php?maxsize=xxx&source=path/to/image/file" border=0 /> xxx=a value for the max size
resampleimage($_GET['maxsize'], $_GET['source']);
?>