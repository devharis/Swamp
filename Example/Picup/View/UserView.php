<?php
	namespace View;
	class UserView {
		private $userId = "UserID";
		private $delUser = "DeleteUser";
		private $loginname = "LoginName";
		private $oldpassword = "OldPassword";
		private $newpassword1 = "NewPassword1";
		private $newpassword2 = "NewPassword2";
		private $edituser = "EditUser";
		
		const INVALID_OLD_PASS = 6;
		const INVALID_CONF_PASS = 7;
		const INVALID_USERNAME_PWD = 8;
		
		/**
		 * Renders a div containing information of the user
		 * @param User $user user that are to be viewed
		 * @param $loggedIn User if logged in else false
		 * @param $images Image array containing recent images by the user
		 * @return string HTML
		 */
		public function ViewUser(\Model\User $user, $loggedIn, $images) {
			$currentUser = $loggedIn;
			$div = "<div class='ViewUser'>";
				$div .= "<a class='BackButton' href='" . \View\NavigationView::INDEX . "'><<- Back</a>";	
							
				$div .= "<div class='UserControll'>";
					$div .= "<h2 class='UserHeader'>" . $user->getUsername() . "</h2>";
					$div .= "<img src='" . \Model\FileUploadHandler::PROFILE_PIC_DIRECTORY . "UnknownProfile.png' />";
					if ($currentUser) {
						if ($user->getID() === $currentUser->getID() || $currentUser->getRole() === \View\AdminView::ADMIN_ROLE) {					
							$div .= "<form action='' method='post' class='UserDeleteForm'>";
								$div .= "<input type='hidden' value='" . $user->getID() . "' name='$this->userId' />";
								$div .= "<input type='submit' value='Delete' name='$this->delUser' class='DelUserButton'/>";
							$div .= "</form>";			
									
							$div .= "<span class='ShowEditUserForm'>Edit</span>
									<form action='' method='post' class='EditUserForm'>
										<label class='grey' for=$this->loginname>Name:</label>
										<input class='inputfield' type='text' id='signupName' name=$this->loginname maxlength='40' value='" . $user->getUsername() . "' />
										<label class='grey' for=$this->oldpassword>Old password:</label>
										<input class='inputfield' type='password' name=$this->oldpassword id='editPwd1' />
										<label class='grey' for=$this->newpassword1>New password:</label>
										<input class='inputfield' type='password' name=$this->newpassword1 id='editPwd2' />
										<label class='grey' for=$this->newpassword2>New password again:</label>
										<input class='inputfield' type='password' name=$this->newpassword2 id='editPwd3' />
										<div class='clear'></div>
										<input type='submit' name=$this->edituser value='Save changes' class='formButton' />";
							$div .= "</form>";
						}
					}		
				$div .= "</div>";
				$div .= "<div class='RecentImages'><h2 id='RecentImagesHeader'>Recent uploads</h2>";
					$div .= "<ul class='RecentImagesList'>";
					foreach ($images as $image) {
						$div .= "<li><a href='". \View\NavigationView::INDEX . "?" . \View\NavigationView::IMAGE. "=" . $image->getImageID() . "'><img src='" . \Model\FileUploadHandler::THUMB_DIRECTORY . $image->getFileName() .  
																																"' alt='" . $image->getFileName() . "'/></a>";
					}
					$div .= "</ul>";
				$div .= "</div>";
			$div .= "</div>";
			
			return $div;
		}

		/**
		 * Checks if user pressed deletebutton.
		 * @return BOOL
		 */
		public function GetIdToDelete() {
			if(isset($_POST[$this->delUser])) {
				return $_POST[$this->userId];
			}
			return NULL;
		}		

		/**
		 * Checks if user pressed editbutton.
		 * @return BOOL
		 */
		public function TriedToEdit() {
			if(isset($_POST[$this->edituser])) {
				return TRUE;
			}
			return FALSE;
		}
		
		
		/**
		 * Returns the username if set.
		 * @return string
		 */
		public function GetUserName() {
			if(isset($_POST[$this->loginname])) {
				return $_POST[$this->loginname];
			}
			return NULL;
		}
		
		/**
		 * Returns the password if set.
		 * @return string
		 */
		public function GetOldPassword() {
			if(isset($_POST[$this->oldpassword])) {
				return $_POST[$this->oldpassword]; 
			}
			return NULL;
		}
				
		/**
		 * Returns the new password if set.
		 * @return string
		 */
		public function GetNewPassword() {
			if(isset($_POST[$this->newpassword1])) {
				return $_POST[$this->newpassword1];	
			}
			return NULL;
		}	
				
		/**
		 * Returns the confirmpassword if set.
		 * @return string
		 */
		public function GetNewConfPassword() {
			if(isset($_POST[$this->newpassword2])) {
				return $_POST[$this->newpassword2];
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
					case \Model\UserHandler::NOT_VALID_ID:
						return "<p>Invalid UserID</p>";
				
					case \Model\UserHandler::USER_NOT_LOGGED_IN:
						return "<p>You must be logged in to do that.</p>";
					
					case \Model\UserHandler::NOT_VALID_USER:
						return "<p>Updated user was invalid</p>";
						
					case \Model\UserHandler::UPDATE_FAILED:
						return "<p>An error occured upon update</p>";
					
					case \Model\UserHandler::DELETE_FAILED:
						return "<p>An error occured upon delete</p>";
					
					case \Model\UserHandler::NOT_VALID_USERNAME:
						return "<p>Invalid username(max 40 chars)</p>";
					
					case self::INVALID_OLD_PASS:
						return "<p>Old password was incorrect.</p>";
						
					case self::INVALID_CONF_PASS:
						return "<p>Confirmationpassword didn't match</p>";
						
					case self::INVALID_USERNAME_PWD:
						return "<p>Invalid username or password(6-18 characters of the type: 'a-z A-Z 0-9')</p>";
				}
		}
	}
?>