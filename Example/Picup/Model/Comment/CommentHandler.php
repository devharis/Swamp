<?php
namespace Model;
class CommentHandler {
	private $m_db = NULL;
	private $m_user = NULL;
	private $m_validation = NULL;
	
	const USER_NOT_LOGGED_IN = 1;
	const NOT_VALID_COMMENT = 2;
	const NOT_VALID_ID = 3;
	const DELETE_FAILED = 4;
	const COMMENT_FAILED = 5;
	
	public function __construct (Database $db, LoginHandler $lh, Validation $val) {
		$this->m_db = $db;
		$this->m_user = $lh->GetUserSession();
		$this->m_validation = $val;
	}	
	
	/**
	 * Sends a sqlquery to Database.php to get all the comments to the image.
	 * @param int $imageID
	 * @return Array with Comments.
	 * 
	 */
	public function GetComments($imageID) {
		if (!$this->m_validation->NumberValidation($imageID)) {
			return self::NOT_VALID_ID;
		}
		
		$sql = "SELECT * FROM " . Comment::TABLE_NAME . " WHERE m_imageID = ?";
		return $this->m_db->RunPreparedSelectQuery(Comment::CLASS_NAME, $sql,"i", array($imageID));
	}
	
	
	/**
	 * Sends a sqlquery to Database.php to add an comment.
	 * @param Comment $comment
	 * @return bool
	 * 
	 */		
	public function AddComment(Comment $comment) {
		if ($comment === NULL) {
			return self::NOT_VALID_COMMENT;
		} else if ($this->m_user === NULL) {
			return self::USER_NOT_LOGGED_IN;
		}
		
		$text = $comment->getComment();
		$imageID = $comment->getImageID();
		$userID = $comment->getUserID();
		$user = $comment->getUser();
		
		$sql = "INSERT INTO " . Comment::TABLE_NAME . " (m_comment, m_imageID, m_userID, m_user) VALUES(?, ?, ?, ?)";
		
		$ret = $this->m_db->RunPreparedQuery($sql, "siis", array(&$text,
																&$imageID,
																&$userID,
																&$user));
		if (!$ret) {
			return self::COMMENT_FAILED;
		}
		return $ret;
	}
	
	/**
	 * Sends a sqlquery to Database.php to delete an comment.
	 * @param int $commentID
	 * @return bool
	 * 
	 */	
	public function DeleteComment($commentID) {
		if (!$this->m_validation->NumberValidation($commentID)) {
			return self::NOT_VALID_ID;
		}		
		
		$sql = "DELETE FROM " . Comment::TABLE_NAME . " WHERE m_id = ?";
		$ret = $this->m_db->RunPreparedQuery($sql,"i", array($commentID));
		
		if (!$ret) {
			return self::DELETE_FAILED;
		}
		return $ret;
	}
	
	
	public function Test(Database $db) {
		$sut = New CommentHandler($db);
		$commentsAtStart = $sut->GetComments(1); // Måste vara en bild med kommentarer.
		
		if (count($commentsAtStart) === 0) {
			echo "Inga kommentarer hämtades trots att kommentarer finns på bilden";
			return FALSE;
		}
		
		$newComment = new Comment();
		$newComment = $newComment->Create("Comment", 1, 1, "asdasd");
		$sut->AddComment($newComment);
		$commentsAfterAdd = $sut->GetComments(1);
		
		if (count($commentsAtStart) + 1 != count($commentsAfterAdd)) {
			echo "Lägga till kommentarer fungerade inte";
			return FALSE;
		}
		
		foreach ($sut->GetComments(1) as $comment) {
			if ($comment->getComment() === "Comment") {
				$id = $comment->getID();
			}
		}
		$sut->DeleteComment($id);
		$commentsAfterDelete = $sut->GetComments(1);
		
		if (count($commentsAtStart) != count($commentsAfterDelete)) {
			echo "Ta bort kommentar misslyckades";
			return FALSE;
		}
		
		return TRUE;
	}
}
?>