<?php
namespace Model;
class ImageHandler {
	private $m_db = NULL;
	private $m_user = NULL;
	private $m_validator = NULL;
	
	const USER_NOT_LOGGED_IN = 1;
	const NOT_VALID_IMAGE = 2;
	const NOT_VALID_ID = 3;
	const UPDATE_FAILED = 4;
	const DELETE_FAILED = 5;
	const NOT_VALID_DESC = 6;
	
	public function __construct (Database $db, LoginHandler $lh, Validation $val) {
		$this->m_db = $db;
		$this->m_user = $lh->GetUserSession();
		$this->m_validator = $val;
	}

	/**
	 * Sends a sqlquery to Database.php to get all the rows from the table.
	 * @return Array with Images.
	 * 
	 */
	public function GetImages() {
		$sql = "SELECT picup_image.m_id, picup_image.m_filename, picup_image.m_description, picup_image.m_dateadded, (SELECT picup_user.m_username FROM picup_user WHERE picup_user.m_id = picup_image.m_userid GROUP BY picup_image.m_userid) as m_username, picup_image.m_userid,
					   COUNT(picup_imagerating.m_like) AS m_likes, (SELECT COUNT(*) FROM picup_comment WHERE picup_comment.m_imageID = picup_image.m_id GROUP BY picup_comment.m_imageID) as m_numcomments
				FROM " . Image::TABLE_NAME . "
				LEFT JOIN " . ImageRating::TABLE_NAME . " ON picup_image.m_id = picup_imagerating.m_imageID
				LEFT JOIN " . User::TABLE_NAME . " ON picup_image.m_userid = picup_user.m_id
				GROUP BY picup_image.m_id";
				
		return $this->m_db->GetAllInstances(Image::CLASS_NAME, $sql);
	}
	
	/**
	 * Sends a sqlquery to Database.php to get an image.
	 * @param int $imageID
	 * @return Image
	 * 
	 */
	public function GetImage($id) {
		if (!$this->m_validator->NumberValidation($id)) {
			return self::NOT_VALID_ID;
		}
		
		$sql = "SELECT picup_image.m_id, picup_image.m_filename, picup_image.m_description, picup_image.m_dateadded, picup_image.m_userid, (SELECT picup_user.m_username FROM picup_user WHERE picup_user.m_id = picup_image.m_userid GROUP BY picup_image.m_userid) as m_username
				FROM " . Image::TABLE_NAME . "
				LEFT JOIN " . User::TABLE_NAME . " ON picup_image.m_userid = picup_user.m_id 
				WHERE picup_image.m_id = ?";
		$image = $this->m_db->RunPreparedSelectQuery(Image::CLASS_NAME, $sql,"i", array($id));
		$image = current($image);
		
		if ($image === FALSE) {
			return self::NOT_VALID_ID;
		}
		return $image;
	}
	
	public function DeleteImage(Image $image) {
		$imageID = $image->getImageID();
		$filename = $image->getFileName();
		
		if ($this->m_user === NULL) {
			return self::USER_NOT_LOGGED_IN;
		} else if (!$this->m_validator->NumberValidation($imageID)) {
  			return self::NOT_VALID_ID;
  		}
		
				
		$sql = "DELETE FROM " . Image::TABLE_NAME . " WHERE m_id = ?";
		$this->DeleteFile($filename);
		$ret = $this->m_db->RunPreparedQuery($sql,"i", array($imageID));	
		
		if (!$ret) {
			return self::DELETE_FAILED;
		}		
		return $ret;
		
	}
	  
  	public function UpdateImage(Image $image) {
  		$imageDesc = $this->m_validator->StrValNonHTML($image->getDescription());
  		$imageID = $image->getImageID();
  		  		
  		if ($this->m_user === NULL) {
  			return self::USER_NOT_LOGGED_IN;
  		} else if (!$this->m_validator->NumberValidation($imageID)) {
  			return self::NOT_VALID_ID;
  		} else if (!$this->m_validator->ValStrLength($imageDesc, 40)) {
  			return self::NOT_VALID_DESC;
  		}  		
		
        $sql = "UPDATE " . Image::TABLE_NAME . " SET m_description = ? WHERE m_id = ?";
        $ret = $this->m_db->RunPreparedQuery($sql, "si", array(&$imageDesc, &$imageID));
		
		if (!$ret) {
			return self::UPDATE_FAILED;
		}
    }
	
	/**
	 * Deletes the file from the image and thumb folder.
	 * @param $file the file(name.ext) that are to be deleted.
	 * 
	 */
	public function DeleteFile($file) {
		unlink(FileUploadHandler::IMAGE_DIRECTORY . $file);
		unlink(FileUploadHandler::THUMB_DIRECTORY . $file);
	}
	
	public function Test(Database $db) {
		$sut = new ImageHandler($db);		
		
		$imagesAtStart = $sut->GetImages();
		
		if(count($imagesAtStart) === 0) {
			echo "Hämtning av bilder misslyckades";
			return FALSE;
		}
		
		$oneImage = $sut->GetImage(1);
		
		if (count($oneImage) != 1) {
			echo "Hämtning av en bild misslyckades";
			return FALSE;
		}
		
		$updatedImage = new Image();
		$updatedImage = $updatedImage->Create($oneImage->getFileName(), "sadsadas", $oneImage->getuserName(), $oneImage->getUserId());
		if ($sut->UpdateImage($updatedImage) == FALSE) {
			echo "Updatering av en bild misslyckades";
			return FALSE;
		}
		
		return TRUE;
	}
}
?>