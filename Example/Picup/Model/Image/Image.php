<?php
namespace Model;

class Image {
	public $m_id;
	public $m_filename;
	public $m_description;
	public $m_dateadded;
	public $m_userid;
	public $m_username;
	public $m_likes;
	public $m_numcomments;
	
	
	const TABLE_NAME = "picup_image";
	const CLASS_NAME = "Model\Image";
	const SORT_DATE = "SortByDate";
	const SORT_RATINGS = "SortByNumRatings";
	const SORT_COMMENTS = "SortByNumComments";
	
  /**
   * This is used instead of a constuctor to let the objects be created with different paramentrs
   * because I use fetch_object to create objects.
   * 
   * @return ImageObject
   * TODO: Fix intvalidation for $userid
   * @param int $imageid
   * @param string $filename
   * @param string $description
   * @param int $userid
   */
	public static function Create($filename, $description, $username, $userid) {
		$ret = new Image();
		
		$ret->m_filename = $filename;
		$ret->m_description = $description;
		$ret->m_username = $username;
		$ret->m_userid = $userid;
		
		return $ret;
	}
	
	public function getImageID() {
		return $this->m_id;
	}
	
	public function getFileName() {
		return $this->m_filename;
	}
	
	public function getDescription() {
		return $this->m_description;
	}
	
	public function getDate() {
		return $this->m_dateadded;
	}
	
	public function getuserName() {
		return $this->m_username;
	}
	
	public function getUserId() {
		return $this->m_userid;
	}
	
	public function getNumComments() {
		return $this->m_numcomments;
	}
	
	public function getNumRatings() {
		return $this->m_likes;
	}
	
	public static function SortByDate($a, $b) {
		return self::SortArray($a, $b, "m_dateadded");
	}
	
	public static function SortByNumRatings($a, $b) {
		return self::SortArray($a, $b, "m_likes");
	}
	
	public static function SortByNumComments($a, $b) {
		return self::SortArray($a, $b, "m_numcomments");
	}
	
	public static function SortArray($a, $b, $field) {
		if ($a->$field == $b->$field) {
			return 0;
		}
		return ($a->$field > $b->$field) ? -1 : 1;
	}
}
?>