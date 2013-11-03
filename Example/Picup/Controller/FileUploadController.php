<?php
namespace Controller;
/**
 * Handles events in the upload form and returns the form with surrounding messages.
 * @param $handler Loginhandler
 * @param $uview FileUploadView
 * @param $uhandler FileUploadHandler
 * @param $validator Validation
 * @param $nav_view NavigationView
 * @return string HTML
 * 
 */
class FileUploadController {
	public function DoUploadControl(\Model\LoginHandler $handler, \View\FileUploadView $uview, 
										\Model\FileUploadHandler $uhandler, \Model\Validation $validator, \View\NavigationView $nav_view) {
											
		$body = \View\FileUploadView::FUV_HEADER;
		$body .= $uview->DoUploadForm();
		if ($uview->TriedToUpload()) {
			$file = $uhandler->UploadFile();
			if (is_string($file)) {
				$image = new \Model\Image();
				$user = $_SESSION[\Model\LoginHandler::USER_SESSION];
				$description = $validator->StrValNonHTML($uview->GetDescription());
				if ($validator->ValStrLength($description, 40)) {
					$image = $image->Create($file, $description, $user->getUserName(), $user->getID());
					
					if($uhandler->AddImage($image)) {
						$nav_view->Refresh();						
					} else {
						$body .= $uview->PrintStateMessage(\View\FileUploadView::ADD_FAILED);						
					}
				} else {
					$body .= $uview->PrintStateMessage(\View\FileUploadView::INVALID_DESCRIPTION);
				}
			} else {
				$body .= $uview->PrintStateMessage($file);					
			}
		}
		return $body;
	}
}
?>