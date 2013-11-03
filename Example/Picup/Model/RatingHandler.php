<?php
namespace Model;
class RatingHandler {
	private $m_db = NULL;
	private $m_user = NULL;
	private $m_validation = NULL;
	
	const USER_NOT_LOGGED_IN = 1;
	const RATE_FAILED = 2;
	
	public function __construct(Database $db, LoginHandler $lh, Validation $val) {
		$this->m_db = $db;
		$this->m_user = $lh->GetUserSession();
		$this->m_validation = $val;
	}
	
	/**
	 * Checks if the user has rated the image bedfore.
	 * @param $imageID id of the image.
	 * @param $like 1 for likes, 0 for dislikes
	 * @return int
	 */	
	public function GetRatings($imageID, $like) {	
	 	$sql = "SELECT * FROM " . ImageRating::TABLE_NAME . " WHERE m_imageID = ? AND m_like = ?";
		
		return count($this->m_db->RunPreparedSelectQuery(ImageRating::CLASS_NAME, $sql, "ii", array(&$imageID, &$like)));
	}
	
	/**
	 * Checks if the user has rated the image bedfore.
	 * @param $imageID id of the image.
	 * @param $userID id of the user.
	 * @return BOOL
	 */	
	public function UserAlreadyRated($imageID, $userID) {	
	 	$sql = "SELECT * FROM " . ImageRating::TABLE_NAME . " WHERE m_id = ? AND m_imageID = ?";
		
		if (count($this->m_db->RunPreparedSelectQuery(ImageRating::CLASS_NAME, $sql, "ii", array(&$userID, &$imageID))) === 0) {
			return FALSE;
		}
		return TRUE;	
	}
		
	/**
	 * Adds a like to the Liketabe.
	 * @param $rating the rating to be applied.
	 * @return BOOL
	 */	
	public function RateImage (ImageRating $rating) {	
		$userID = $rating->GetUserID();
		$imageID = $rating->GetImageID();	
		
		if ($this->m_user === NULL) {
			return self::USER_NOT_LOGGED_IN;
		}
		
		if($rating->GetRating()) {
			$like = 1;
		} else {
			$like = 0;
		}	
		
		$sql = "INSERT INTO " . ImageRating::TABLE_NAME . " (m_id, m_imageID, m_like) VALUES(?, ?, ?)";
		
		$ret = $this->m_db->RunPreparedQuery($sql, "iii", array(&$userID, &$imageID, &$like));		
		
		if (!$ret) {
			return self::RATE_FAILED;
		}
		return $ret;
	}
	
		
	/**
	 * Deletes the rating from the rating-table.
	 * @param int $userID
	 * @param int $imageID
	 */
	public function UnrateImage($userID, $imageID) {
		if($this->m_user === NULL) {
			return self::USER_NOT_LOGGED_IN;
		}
		
		$sql = "DELETE FROM " . ImageRating::TABLE_NAME . " WHERE m_id = ? AND m_imageID = ?";
		
		$ret = $this->m_db->RunPreparedQuery($sql,"ii", array(&$userID, &$imageID));
		
		if (!$ret) {
			return self::RATE_FAILED;
		}
		return $ret;
	}
	
	public function Test($db) {
		$sut = new RatingHandler($db);
		
		$ratingsAtStart = $sut->GetRatings(1, 1);
		
		if ($ratingsAtStart === 0) {
			echo "Hämtning av ratings misslyckades";
			return FALSE;
		}
		
		$newRate = new ImageRating();
		$newRate = $newRate->Create(1, 1, 1);
		$sut->RateImage($newRate);
		$ratingsAfterNewRate = $sut->GetRatings(1, 1);
		
		if ($ratingsAtStart + 1 != $ratingsAfterNewRate) {
			echo "Det gick inte rösta på en bild.";
			return FALSE;
		}
		
		if ($sut->UserAlreadyRated(1, 1) === FALSE) {
			echo "En användare kan rösta trots att den röstat innan";
			return FALSE;
		}
		
		$sut->UnrateImage(1, 1);
		$numAfterUnrate = $sut->GetRatings(1, 1);
		if ($ratingsAtStart != $numAfterUnrate) {
			echo "Det gick inte tabort en röst.";
			return FALSE;
		}
		return TRUE;
	}
}
?>