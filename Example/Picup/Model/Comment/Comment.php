<?php
namespace Model;
class Comment {
	public $m_id = NULL;
	public $m_comment = NULL;
	public $m_timestamp = NULL;
	public $m_imageID = NULL;
	public $m_userID = NULL;
	public $m_user = NULL;
	
	const TABLE_NAME = "picup_comment";
	const CLASS_NAME = "Model\Comment";
	
  /**
   * This is used instead of a constuctor to let the objects be created with different paramentrs
   * because I use fetch_object to create objects.
   * 
   * @return ImageObject
   * @param string $comment
   * @param int $imageID
   * @param string UserName
   */
	public static function Create($comment, $imageid, $userID, $user) {
		$ret = new Comment();
		
		$ret->m_comment = $comment;
		$ret->m_imageID = $imageid;
		$ret->m_userID = $userID;
		$ret->m_user = $user;
		
		return $ret;
	}
	
	public function getID() {
		return $this->m_id;
	}
	
	public function getComment() {
		return $this->m_comment;
	}
	
	public function getTime() {
		return $this->m_timestamp;
	}
	
	public function getImageID() {
		return $this->m_imageID;
	}
	
	public function getUserID() {
		return $this->m_userID;
	}
	
	public function getUser() {
		return $this->m_user;
	}
}
?>