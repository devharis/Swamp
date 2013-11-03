<?php
namespace Controller;
class ImageController {
	/**
	 * Renders viewImage DIV
	 * @param $imgView ImageView
	 * @param $imgHandler ImageHandler
	 * @param $fu_handler FileUploadHandler
	 * @param $nav_view NavigationView
	 * @param $l_handler LoginHandler
	 * @param $validator Validation
	 * @param $commentDIV DIV containing comments and commentform.
	 * @return string HTML
	 * 
	 */
	public function DoControl(\View\ImageView $imgView, \Model\ImageHandler $imgHandler,
								\Model\FileUploadHandler $fu_handler,\View\NavigationView $nav_view, 
									\Model\LoginHandler $l_handler,\Model\Validation $validator, $ratingForm, $commentDIV) {
									
		
		if ($nav_view->GetController() == \View\NavigationView::IMAGE) {
			$imageID = $nav_view->GetControllerVariable();
			
			$image = $imgHandler->GetImage($imageID);
			
			if ($image === \Model\ImageHandler::NOT_VALID_ID) {
				return $imgView->PrintUserMessage(\Model\ImageHandler::NOT_VALID_ID);
			}
			
			$user = $l_handler->GetUserSession();
			$body = $imgView->ViewImage($image, $user, $commentDIV, $ratingForm);
			
			if ($imgView->GetImageToDelete() != NULL) {
				$image = $imgView->GetImageToDelete();					
				$ret = $imgHandler->DeleteImage($image);
				if (is_numeric($ret)) {		
					$body .= $imgView->PrintUserMessage($ret);		
				} else {	
					$nav_view->SetIndex();				
				}
			}
			
			if ($imgView->GetEditedImage() != NULL) {
				$image = $imgView->GetEditedImage();					
				$ret = $imgHandler->UpdateImage($image);
				if (is_numeric($ret)) {
					$body .= $imgView->PrintUserMessage($ret);						
				} else {	
					$nav_view->Refresh();						
				}
			}
				
		} else {
			$imageArray = $imgHandler->GetImages();
			
			if (count($imageArray) > 0) {
				$body = $imgView->RenderFileList($imageArray);
			} else {
				$body = $imgView->PrintUserMessage(\View\ImageView::NO_IMAGES);
			}
		}
		
		return $body;
	}
}
?>