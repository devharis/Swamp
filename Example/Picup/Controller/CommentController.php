<?php
namespace Controller;
class CommentController {
	
	/**
	 * Renders the commentdiv
	 * @param $com_view CommentView
	 * @param $com_handler CommentHandler
	 * @param $nav_view NavigationView
	 * @param $l_handler LoginHandler
	 * @param $validator Validation
	 * @return string HTML
	 * 
	 */
	public function DoControll(\View\CommentView $com_view, \Model\CommentHandler $com_handler, 
								\View\NavigationView $nav_view, \Model\LoginHandler $l_handler, \Model\Validation $validator) {
		$body = NULL;
		
		if($nav_view->GetController() == \View\NavigationView::IMAGE) {	
			$imageID = $nav_view->GetControllerVariable();		
			$comments = $com_handler->GetComments($imageID);
			$user = $l_handler->GetUserSession();
			
			if ($comments === \Model\CommentHandler::NOT_VALID_ID) {
				return $com_view->PrintUserMessage(\Model\CommentHandler::NOT_VALID_ID);
			}
			
				$body = $com_view->RenderCommentForm($comments, $user);
			
			if ($user != NULL) {	
				if ($com_view->GetCommentIdToDelete() != NULL) {
					$commentID = $com_view->GetCommentIdToDelete();
					$ret = $com_handler->DeleteComment($commentID);
					if (is_numeric($ret)) {
						$body .= $com_view->PrintUserMessage($ret);					
					} else {			
						$nav_view->Refresh();					
					}
				}
													
				if ($isComment = $com_view->GetNewComment()) {
					$comment = new \Model\Comment();
					$isComment = $validator->StrValNonHTML($isComment);
					
					if (!$validator->ValStrLength($isComment, 140)) {
						$body .= $com_view->PrintUserMessage(\View\CommentView::INVALID_COMMENT);
					} else {
						$comment = $comment->Create($isComment, $imageID, $user->getId(), $user->getUsername());
						
						$ret = $com_handler->AddComment($comment);
						if (is_numeric($ret)) {
							$body .= $com_view->PrintUserMessage($ret);						
						} else {
							 $nav_view->Refresh();
						}
					}
				}
			}
		}
		
		return $body;
	}
}
?>