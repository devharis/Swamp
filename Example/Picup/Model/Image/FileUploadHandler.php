<?php
namespace Model;
class FileUploadHandler {
	private $file = "file";
	private $error = "error";
	private $name = "name";
	private $type = "type";
	private $tmpname = "tmp_name";
	private $directory = "Upload/";
	private $thumbDirectory = "Upload/Thumbs/";
	private $allowedExts = array("gif", "jpg", "png");
	private $allowedFileTypes = array("image/gif", "image/jpeg", "image/png", "image/pjpeg");
	
	const FILE_NO_FILE = 1;
	const FILE_ERROR_OCCURED = 2;
	const FILE_EXISTS = 3;
	const FILE_SUCCESS = 4;
	const USER_NOT_LOGGED_IN = 5;
	const VALIDATION_FAILED = 6;
	
	const THUMB_WIDTH = 400;
	const THUMB_HEIGHT = 300;
	const IMAGE_DIRECTORY = "Upload/";
	const THUMB_DIRECTORY = "Upload/Thumbs/";
	const PROFILE_PIC_DIRECTORY = "Upload/ProfilePic/";
				
	public function __construct(Database $db) {
		$this->m_db = $db;
	}
	
	/**
	 * Saves the chosen file into the directory(Upload/) and returns a state with the status of the upload.
	 * @return int
	 * 
	 */
	public function UploadFile() {
		if($_FILES[$this->file][$this->error] > 0) {
			if ($_FILES[$this->file][$this->error] == 4) {
				return self::FILE_NO_FILE;
			} else {
				return self::FILE_ERROR_OCCURED;				
			}
		} else {
			if ($this->ValidateFile())
			{				
				if(file_exists(self::IMAGE_DIRECTORY . $_FILES[$this->file][$this->name])) {
					return self::FILE_EXISTS;
				} else {
					move_uploaded_file($_FILES[$this->file][$this->tmpname], 
					self::IMAGE_DIRECTORY . $_FILES[$this->file][$this->name]);
					$this->CreateThumb($_FILES[$this->file][$this->name]);
					return $_FILES[$this->file][$this->name];
				}
			} else {
				return self::VALIDATION_FAILED;
			}			
		}
	}
	
	/**
	 * Checks so that the file is an image of the correct format.
	 * @return bool
	 * 
	 */
	public function ValidateFile() {
		$fileName = explode(".", $_FILES[$this->file][$this->name]);
		$fileType = $_FILES[$this->file][$this->type];
		$fileExt = end($fileName);
		
		if (in_array($fileExt, $this->allowedExts) && in_array($fileType, $this->allowedFileTypes)) {
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	 * Adds an image to the databaste.
	 * @param $image the image file containing set fields with filename, description and userid.
	 * 
	 */
	public function AddImage(Image $image) {
		$filename = $image->getFileName();
		$description = $image->getDescription();
		$userid = $image->getUserId();
		
		$sql = "INSERT INTO " . Image::TABLE_NAME . " (m_filename, m_description, m_userid) VALUES(?, ?, ?)";
		
		return $this->m_db->RunPreparedQuery($sql, "ssi", array(&$filename,
																&$description,
																&$userid));
	}
	
	/**
	 * Creats a thumbnail of the file that are to be uploaded.
	 * @param $fileName (abc.ext)
	 * 
	 */
	public function CreateThumb($fileName) {
		$file = self::IMAGE_DIRECTORY . $fileName; // Location of the image.
		$target = self::THUMB_DIRECTORY . $fileName; // Targetlocation of the thumbs.
		$size = getimagesize($file); // Gets the hight and width of the image.
		$width = $size[0];
		$height = $size[1];
		$targetWidth = self::THUMB_WIDTH; // Gets the targetwidth for the thumb.
		$targetHeight = self::THUMB_HEIGHT;
		
		$imgType = explode(".", $file);
		$imgType = $imgType[count($imgType) - 1];
		
		switch ($imgType) { // Creates image based on the imagetype gif/jpg/png.
			case $this->allowedExts[0]:
				$simg = imagecreatefromgif($file);
				break;
			case $this->allowedExts[1]:
				$simg = imagecreatefromjpeg($file);
				break;
			case $this->allowedExts[2]:
				$simg = imagecreatefrompng($file);
				break;
		}
		
		$targetImg = imagecreatetruecolor($targetWidth, $targetHeight); // Create the thumb-image.
		$aspectX = $width/$targetWidth;
		$aspectY = $height/$targetHeight;
		$centerY = $targetHeight/2;
		$centerX = $targetWidth/2;
			
		
		$ratio = max($targetWidth / $width, $targetHeight / $height);
		$new_width = $ratio * $width;
		$new_height = $ratio * $height;
		
		if ($width > $height) {
			$adjusted_width = $width / $aspectX;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $centerX;
						
			imagecopyresampled($targetImg, $simg, -$int_width, 0, 0, 0, $new_width, $targetHeight, $width, $height);
		} else if (($width < $height) || ($width == $height)) {
			$adjusted_height = $height / $aspectX;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $centerY;
			
			
			imagecopyresampled($targetImg, $simg, 0, -$int_height, 0, 0, $targetWidth, $new_height, $width, $height);
		} else {
			imagecopyresampled($targetImg, $simg, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
		}
		
		imagejpeg($targetImg, $target, 100);
	}
}
?>