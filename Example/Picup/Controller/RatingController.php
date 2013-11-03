<?php
namespace Controller;
class RatingController {
	private $likes = 1;
	private $dislikes = 0;
	
	/**
	 * Renders the rating DIV
	 * @param $r_view RatingView
	 * @param $r_handler RatingHandler
	 * @param $rating ImageRating
	 * @param $l_handler LoginHandler
	 * @param $nav_view NavigationView
	 * @return string HTML
	 * 
	 */
	public function DoRatingControll(\View\RatingView $r_view, \Model\RatingHandler $r_handler, 
										\Model\ImageRating $rating, \Model\LoginHandler $l_handler, 
											\View\NavigationView $nav_view) {
		$likediv = NULL;
		
		if ($nav_view->GetController() === \View\NavigationView::IMAGE) {
				$imageID = $nav_view->GetControllerVariable();
				$ratings = array($r_handler->GetRatings($imageID, $this->likes), $r_handler->GetRatings($imageID, $this->dislikes));
				$likediv = $r_view->ShowRatings($ratings);
				
			if ($l_handler->GetUserSession() != NULL) {
				$user = $l_handler->GetUserSession();
				$userID = $user->getID();
					
				if ($r_handler->UserAlreadyRated($imageID, $userID) === FALSE) {
					$likediv .= $r_view->RenderLikeForm();
					
					if ($r_view->TriedToLike()) {
						$rating = $rating->Create($userID, $imageID, TRUE); // TRUE for like, FALSE for dislike.
						
						$ret = $r_handler->RateImage($rating);
						
						if (is_numeric($ret)) {
							$likediv .= $r_view->PrintUserMessage($ret);
						} else {
							$nav_view->Refresh();							
						}
						
					} else if ($r_view->TriedToDislike()) {
						$rating = $rating->Create($userID, $imageID, FALSE);
						
						$ret = $r_handler->RateImage($rating);
						
						if (is_numeric($ret)) {
							$likediv .= $r_view->PrintUserMessage($ret);							
						} else {
							$nav_view->Refresh();
						}
					}				
				} else {
					$likediv .= $r_view->RenderUnrateForm();
					
					if ($r_view->TriedToUnrate()) {
						if ($r_handler->UnrateImage($userID, $imageID)) {
							$nav_view->Refresh();							
						} else {
							$likediv .= $r_view->PrintUserMessage(\View\RatingView::UNRATE_FAILED);							
						}
					}
				}
			}
		}
		
		return $likediv;
	}
}
?>