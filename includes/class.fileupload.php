<?php
/**
 * File Upload
 *
 *
 * @author Sunil Barkhane <sunil.barkhane@gmail.com>
 * Update date 27-04-2019
 */
class fileUpload {
	
	public function fileUploader($file,$path,$tempfile) {
		if($file!='') {
			$fileName = $this->renameFile($path,$file);
			@move_uploaded_file($tempfile,$path.$fileName);
			return $fileName;
		} else {
			return false;
		}
	}
	
	public function resizeImage($src_dir,$suffix,$width='',$height='') {
		if($width!=''){
			$dir = pathinfo($src_dir,PATHINFO_DIRNAME);
			$name = pathinfo($src_dir,PATHINFO_FILENAME);
			$ext = pathinfo($src_dir,PATHINFO_EXTENSION);	
			$dst_dir = $dir."/".$name."-".$suffix.".".$ext;
			$this->resizeCropImage($width,$height,$name,$src_dir,$dst_dir,80);
		}
		return $dst_dir;
	}
	
	public function sanatizeFileName($fileName) {
		$name = pathinfo($fileName,PATHINFO_FILENAME);
		$extension = pathinfo($fileName,PATHINFO_EXTENSION);		
		$name = str_replace(".", "_", $name);
		$name = preg_replace('/[^a-z0-9\-_\.]/i','_',$name);
		$name = trim($name,"_");
		$name = preg_replace('#[ _]+#', '_', $name);
		return $name.".".$extension;
	}
	
	public function combineFile($filePath, $fileName) {
		return $filePath."/".$fileName;
	}
	
	public function getFileNameWithoutExtension($fileName) {
		$actual_name = pathinfo($fileName,PATHINFO_FILENAME);
		return $actual_name;
	}
	
	public function getExtension($fileName) {
		$extension = pathinfo($fileName,PATHINFO_EXTENSION);
		return $extension;
	}
	
	public function renameFile($filePath, $fileName) {
		$fileName = $this->sanatizeFileName($fileName);
		$sFileNameOrginal = $fileName;
		$iCounter = 1;
		while(true) {
			$sFilePath = $this->combineFile($filePath, $fileName);
			if(file_exists($sFilePath)){			
				$fileName = $this->getFileNameWithoutExtension($sFileNameOrginal)."-".$iCounter.".".$this->getExtension($fileName);
				$iCounter++;
			} else {
				break;
			}
		}
		return $fileName;
	}
	
	public function resizeCropImage($max_width,$max_height,$file,$src_dir,$dst_dir,$quality=80){
		$files = $src_dir;
		$imgsize = getimagesize($files);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];
		
		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;
		
			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 7;
				break;
		
			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
				break;
		
			default:
				return false;
				break;
		}
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($files);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    
    if($width_new > $width){
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
			$w_point = (($width - $width_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
		
    $image($dst_img, $dst_dir, $quality);
 
    if($dst_img) { imagedestroy($dst_img); }
    if($src_img) { imagedestroy($src_img); }
	}
}
?>