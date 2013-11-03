<?php
namespace Model;
class ImageRating {
	public $m_id = NULL;
	public $m_imageID = NULL;
	public $m_like = NULL;
	
	const TABLE_NAME = "picup_imagerating";
	const CLASS_NAME = "Model\ImageRating";
	
  /**
   * This is used instead of a constuctor to let the objects be created with different paramentrs
   * because I use fetch_object to create objects.
   * 
   * @param $userID The ID of the user who is rating.
   * @param $imageID ID of the Image to be rated.
   * @param $like bool true for like, false for dislike.
   */
	public function Create($userID, $imageID, $like) {
		$ret = new ImageRating();
		
		$ret->m_id = $userID;
		$ret->m_imageID = $imageID;
		$ret->m_like = $like;
		
		return $ret;
	}
	
	public function GetUserID() {
		return $this->m_id;
	}
	
	public function GetImageID() {
		return $this->m_imageID;
	}
	
	public function GetRating() {
		return $this->m_like;
	}
}
?>