<?php
namespace View;
class RatingView {
	private $likebutton = "LikeButton";
	private $dislikebutton = "DislikeButton";
	private $unratebutton = "UnrateButton";	
	
	/**
	 * Renders like and dislike buttons.
	 * @return HTML
	 */
	public function RenderLikeForm() {
		return
			"<div class='LikeForm'>
				<form action='' method='post' enctype='multipart/form-data'>
					<input class='LikeButton' type='submit' name='$this->likebutton' value='Like' />
				</form>
				<form action='' method='post' enctype='multipart/form-data'>
					<input class='DislikeButton' type='submit' name='$this->dislikebutton' value='Dislike' />
				</form>
			</div>";	
	}
	
	/**
	 * Renders unrate button.
	 * @return HTML
	 */
	public function RenderUnrateForm() {
		return
			"<div class ='LikeForm'>
				<form action='' method='post' enctype='multipart/form-data'>
					<input class='UnrateButton' type='submit' name='$this->unratebutton' value='remove vote' />
				</form>
			</div>";
	}
	
	public function ShowRatings($ratings) {
		$likes = $ratings[0];
		$dislikes = $ratings[1];
		return
		"<div class='ImageRatings'>
			<span class='Likes'>" . $likes ."<p>Likes</p></span>
			<spam class='Dislikes'>" . $dislikes . "<p>Dislikes</p></span>
		</div>
		";
	}
		
	/**
	 * Check's if user pressed likebutton.
	 * @return BOOL
	 */
	public function TriedToLike() {
		if (isset($_POST[$this->likebutton])) {
			return TRUE;
		}
		return FALSE;
	}
			
	/**
	 * Check's if user pressed dislikebutton.
	 * @return BOOL
	 */
	public function TriedToDislike() {
		if (isset($_POST[$this->dislikebutton])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Check's if user pressed unratebutton.
	 * @return BOOL
	 */
	public function TriedToUnrate() {
		if (isset($_POST[$this->unratebutton])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Prints various messages based on a state.
	 * @param int $messageId - is the state of the message.
	 * @return string HTML
	 */
	public function PrintUserMessage($messageId) {
			switch ($messageId) {
				case \Model\RatingHandler::USER_NOT_LOGGED_IN;
					return "<p>You must be logged in to do that.</p>";
					
				case \Model\RatingHandler::RATE_FAILED;
					return "<p>The rate couldn't be stored in the database.</p>";			
							
			}
	}
}
?>