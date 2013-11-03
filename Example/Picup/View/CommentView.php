<?php
namespace View;
class CommentView {
	private $comment = "comment";
	private $button = "commentbutton";
	private $commentid = "CommentID";
	private $delbutton = "DeleteButton";
	
	const INVALID_COMMENT = 6;

		
	/**
	 * Renders a div containing comments and commentform.
	 * @param $comments array containing comments.
	 * @return HTML
	 */		
	public function RenderCommentForm($comments, $user) {
		$div = "<div class='Comments'>";
		if (count($comments) == 0) {
			$div .= "<div class='Comment'><p>No comments</p></div>";
		} else {
			foreach ($comments as $comment) {
				$text = $this->FormatStringWithLink($comment->getComment());
				
				$div .= "<div class='Comment'><span class='CommentUser'><a href='" . \View\NavigationView::INDEX . "?" . \View\NavigationView::USER . "=" . $comment->getUserID() . "'>" . $comment->getUser() . "</a></span>";
				if ($user != NULL) {
					if ($user->getId() === $comment->getUserID() || $user->getRole() == \View\AdminView::ADMIN_ROLE) {		
						$div .=	"<form action='' method='post' enctype='multipart/form-data'>
									<input type='hidden' value='" . $comment->getID() . "' name=$this->commentid />
									<input type='submit' value='Delete' name=$this->delbutton class='DelComment' />
								</form>";							
					}
								
				} 					
				$div .= "<span class='CommentDate'>" . $comment->getTime() . "</span><br /><p>" . $text . "</p></div>";						
			}			
		}
				$div .= "<div class='Scroller-Container'>
							<div class='Scrollbar-Up'></div>
							<div class='Scrollbar-Down'></div>
							<div class='Scrollbar-Track'>
								<div class='Scrollbar-Handle'></div>
								</div>
						  </div>
					</div>"; 
		if ($user != NULL) {
			$div .= "<div class='CommentForm'>";
				$div .= "<form action='' method='post' enctype='multipart/form-data'>
							<label class='grey' for=$this->comment>Leave a Comment:</label>
							<div id='charNum'></div>
							<div class='clear'></div>
							<textarea name=$this->comment maxlength='140' id='textarea'></textarea>
							<div class='clear'></div>
							<input class='formButton' type='submit' name='$this->button' value='Comment' />
							<span>as:</span> <a class='Aprofile' href='" . \View\NavigationView::INDEX . "?" . \View\NavigationView::USER . "=" . $user->getId() . "'>" . $user->getUsername() . "</a>
						</form>";
		} else {
			$div .= "<p class='LoginToComment'><span class='openPanel'>Login</span> to comment</p>";
		}
		$div .= "</div>";
		return $div;
	}
	
	/**
	 * Checks if the string contains a link and surrounds it with a A href.
	 * @param $string the string which are to be checked
	 * @return string
	 */		
	public function FormatStringWithLink($string) {
		if(preg_match('/[a-zA-Z]+:\/\/[0-9a-zA-Z;.\/?:@=_#&%~,+$]+/', $string, $matches)) {
			foreach ($matches as $match) {
				$string = str_replace($match, "<a href='" . $match . "'>" . $match . "</a>", $string);						
			}
		}
		return $string;		
	}

		
	/**
	 * Controlls if the commentbutton was pressed.
	 * @return if pressed(true) -> string comment else false;
	 */		
	public function GetNewComment() {
		if (isset($_POST[$this->button])) {
			return $_POST[$this->comment];
		}
		return NULL;
	}
	
	/**
	 * Controlls if the deletecommentbutton was pressed.
	 * @return if pressed(true) -> commentID else NULL;
	 */		
	public function GetCommentIdToDelete() {
		if (isset($_POST[$this->delbutton])) {
			return $_POST[$this->commentid];
		}
		return NULL;
	}
	
	
		
	/**
	 * Prints various messages based on a state.
	 * @param int $messageId - is the state of the message.
	 * @return string HTML
	 */
	public function PrintUserMessage($messageId) {		
		switch ($messageId) {
			case \Model\CommentHandler::NOT_VALID_ID:
				return "<p>ImageID not valid</p>";
			
			case \Model\CommentHandler::DELETE_FAILED:
				return "<p>Something went wrong when deleting the comment</p>";
			
			case \Model\CommentHandler::COMMENT_FAILED:
				return "<p>Something went wrong when adding the comment</p>";
			
			case \Model\CommentHandler::USER_NOT_LOGGED_IN:
				return "<p>You must be logged in to do that.</p>";
			
			case \Model\CommentHandler::NOT_VALID_COMMENT:
				return "<p>Invalid comment</p>";
				
			case self::INVALID_COMMENT:
				return "<p>Max 140 chars.</p>";			
			
		}
	}
}
?>