<?php
namespace View;
class ImageView {
	private $sort = "SortImages";
	private $select = "Selectmenu";
	private $delImage = "DeleteImageButton";
	private $imageId = "imageid";
	private $filename = "filename";
	private $editinput = "editinput";
	private $editImage = "EditImageButton";
	
	const NO_IMAGES = 1;
	const INVALID_IMAGE_ID = 2;
	
	const SORT_NEWEST = 1;
	const SORT_MOST_RATED = 2;
	const SORT_MOST_COMMENTS = 3;
	
	
	/**
	 * Render a list with links to all the files.
	 * @param $filearray array with files.
	 * @return string HTML
	 * 
	 */
		public function RenderFileList($filearray) {
			$filearray = $this->SortImageList($filearray);
			$list = "<div class='ImageList'>";
			$list .= "<form action='' method='post' name='SortForm'>
						  <select name=$this->select> 
							<option value='". self::SORT_NEWEST ."'>Date added</option>
							<option value='". self::SORT_MOST_RATED ."'>Most ratings</option>
							<option value='". self::SORT_MOST_COMMENTS ."'>Most comments</option>
						  </select>
					  	  <input type='submit' name=$this->sort class='SortButton' value='Sort' />	  
					  </form>";
			$list .= "<ul id='horiz_container_outer'><li id='horiz_container_inner'><ul id='horiz_container'>";
					foreach ($filearray as $file) {
						$list .= "<div class='imageDiv'>";
							$list .= "<span class='imagedescription'>" . $file->getDescription() . "</span>";
							$list .= "<li><a href='". \View\NavigationView::INDEX . "?" . \View\NavigationView::IMAGE. "=" . $file->getImageID() . "'><img src='" . \Model\FileUploadHandler::THUMB_DIRECTORY . $file->getFileName() .  
																																"' alt='" . $file->getFileName() . "'/></a></li>";
							$list .= "<div class='NumComNRate'>";
								$list .= "<span class='NumComments'>". $file->getNumComments() ." comments</span>";
								$list .= "<span class='NumRatings'>" . $file->getNumRatings() . " ratings</span>";
							$list .= "</div>";
						$list .= "</div>";
					}
			$list .= "</ul></li></ul>
				<div id='scrollbar'>
					<a id='left_scroll' class='mouseover_left' href='#'></a>
					<div id='track'> 
						<div id='dragBar'></div>
					</div>
					<a id='right_scroll' class='mouseover_right' href='#'></a>
				</div>
			</div>";
			
			return $list;
		}
		
		/**
		 * Render a div that contains an image with comments.
		 * @param $image image to display
		 * @param $loggedin \Model\User if logged in else false.
		 * @param $commentDiv HTML containing div and form for commenting.
		 * @param $ratingForm HTML containing a form for like and dislike.
		 * @return string HTML
		 * 
		 */
		public function ViewImage(\Model\Image $image, $user, $commentDiv, $ratingForm) {
			$div = "<div class='ImageNComment'>";
				$div .= "<a class='BackButton' href='" . \View\NavigationView::INDEX . "'><<- Back</a>";
				$div .= "<div class='ViewImage'><div class='ViewImageDiv'>";
					$div .= "<h2 id='ImageHeader'>" . $image->getDescription() . "</h2>";
					$div .= "<h4>" . $image->getDate() . "</h4>";
					$div .= "<a href='" . \Model\FileUploadHandler::IMAGE_DIRECTORY . $image->getFileName() . "'><img id='ViewImage' src='" . \Model\FileUploadHandler::IMAGE_DIRECTORY . $image->getFileName() . "'
									alt='" . $image->getFileName() ."'/></a>";	
				if ($user != NULL) {
					if ($image->getUserId() === $user->getID() || $user->getRole() === \View\AdminView::ADMIN_ROLE) {					
							$div .= "<form action='' method='post' class='ImageDeleteForm'>";
								$div .= "<input type='hidden' value='" . $image->getImageID() . "' name='$this->imageId' />";
								$div .= "<input type='hidden' value='" . $image->getFileName() . "' name='$this->filename' />";
								$div .= "<input type='submit' value='Delete' name='$this->delImage' class='DelImageButton'/>";
							$div .= "</form>";					
							$div .= "<span class='ShowEditForm'>Edit</span>
									<form action='' method='post' class='EditDescForm'>";
								$div .= "<input type='hidden' value='" . $image->getImageID() . "' name='$this->imageId' />";
								$div .= "<input type='text' value='" . $image->getDescription() . "' name='$this->editinput' class='EditDescInput' />";
								$div .= "<input type='submit' value='Save edit' name='$this->editImage' class='EditDescButton'/>";
							$div .= "</form>";
					}
				}
				$div .= "<span class='ImageUploaderHeader'>Uploaded by: <a class='ImageUploader' href='" . \View\NavigationView::INDEX . "?" . \View\NavigationView::USER . "=" . $image->getUserId() . "'>" . $image->getuserName() . "</a></span>";	
				$div .= $ratingForm . "
						</div>
					</div>";
				$div .= $commentDiv;
			$div .= "</div>";
			
			return $div;
		}
		
		/**
		 * Controlls if the deletebutton for images was pressed.
		 * @return if pressed(true) -> array($imageID, $imageFilename) else NULL;
		 */
		public function GetImageToDelete () {
			if (isset($_POST[$this->delImage])) {
				$image = new \Model\Image();
				$image->m_id = $_POST[$this->imageId];
				$image->m_filename = $_POST[$this->filename];
				return $image;
			}
			return NULL;
		}
		
		/**
		 * Controlls if the editbutton for images was pressed.
		 * @return array($imageID,$imageDesc)/NULL;
		 */
		public function GetEditedImage () {
			if (isset($_POST[$this->editImage])) {
				$image = new \Model\Image();
				$image->m_id = $_POST[$this->imageId];
				$image->m_description = $_POST[$this->editinput];
				return $image;
			}
			return NULL;
		}
		
		/**
		 * Returns the array sorted.
		 * @return ImageArray
		 */
		public function SortImageList($imageArray) {
			if (isset($_POST[$this->sort])) {
				switch ($_POST[$this->select]) {
					case \View\ImageView::SORT_NEWEST:
						usort($imageArray, array(\Model\Image::CLASS_NAME, \Model\Image::SORT_DATE));
						break;
						
					case \View\ImageView::SORT_MOST_RATED:					
						usort($imageArray, array(\Model\Image::CLASS_NAME, \Model\Image::SORT_RATINGS));
						break;
						
					case \View\ImageView::SORT_MOST_COMMENTS:					
						usort($imageArray, array(\Model\Image::CLASS_NAME, \Model\Image::SORT_COMMENTS));
						break;					
					
					default:
						usort($imageArray, array(\Model\Image::CLASS_NAME, \Model\Image::SORT_DATE));					
						break;
				}
				return $imageArray;	
			}
			return $imageArray;
		}
		
		/**
		 * Prints various messages based on a state.
		 * @param int $messageId - is the state of the message.
		 * @return string HTML
		 */
		public function PrintUserMessage($messageId) {		
			switch ($messageId) {
				case self::NO_IMAGES:
					return "<p class='NoImagesToDisplay'>No images to show, <span class='openPanel'>Sign up</span> and start <span class='openPanel'>uploading</span>!</p>";
					return "<p>No image to display</p>";
				
				case \Model\ImageHandler::USER_NOT_LOGGED_IN:
					return "<p>You must be logged in to do that.</p>";
				
				case \Model\ImageHandler::NOT_VALID_IMAGE:
					return "<p>Not a valid image.</p>";
					
				case \Model\ImageHandler::NOT_VALID_ID:
					return "<p>ImageID was invalid</p>";
					
				case \Model\ImageHandler::UPDATE_FAILED:
					return "<p>Error occured upon update.</p>";
				
				case \Model\ImageHandler::DELETE_FAILED:
					return "<p>Error occured upon delete.</p>";
				
				case \Model\ImageHandler::NOT_VALID_DESC:
					return "<p>The imagedescription couldn't validate.(Max 40 chars)</p>";
			}
		}
	}
?>