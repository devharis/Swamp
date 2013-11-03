<?php
namespace View;
class FileUploadView {
	private $button = "filesubmit";
	private $file = "file";
	private $description = "description";
	
	const FUV_HEADER = "<h1>File Uploader</h1>";
	const INVALID_DESCRIPTION = 7;
	const ADD_FAILED = 8;
	
/**
 * Render a upload form.
 * @return string HTML
 * 
 */
	public function DoUploadForm() {
		return
			"<form action='" . $_SERVER['PHP_SELF'] . "' method='post' enctype='multipart/form-data'>
				<label class='grey' for=$this->description>Image description:</label>
				<input class='inputfield' type='text' maxlength='40' name=$this->description />
				<label class='grey' for=$this->file>File</label>					
				<input type='file' name='$this->file' id='file' />
				<div class='clear'></div>
				<input class='formButton' type='submit' name='$this->button' value='Submit' />
			</form>"
		;
	}
	
	public function GetDescription() {
		if (isset($_POST[$this->description])) {
			return $_POST[$this->description];
		}
		return FALSE;
	}
	
	/**
	 * Checks if the upload-button was pressed.
	 * @return bool
	 * 
	 */
	public function TriedToUpload() {
		if(isset($_POST[$this->button])) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Returns a message based on the state of the upload.
	 * @param $state int the state of the upload.
	 * @return string HTML
	 * 
	 */
	public function PrintStateMessage($state) {
		switch ($state) {
			case \Model\FileUploadHandler::FILE_NO_FILE:
				return "<p class='ui-state-error'>You must select a file!</p>";
				break;
			case \Model\FileUploadHandler::FILE_ERROR_OCCURED:
				return "<p class='ui-state-error'>File is to big</p>";
				break;
			case \Model\FileUploadHandler::FILE_EXISTS:
				return "<p class='ui-state-error'>The file already exists.</p>";
				break;
			case \Model\FileUploadHandler::FILE_SUCCESS:
				return "<p>The file was successfully uploaded!</p>";
				break;
			case \Model\FileUploadHandler::USER_NOT_LOGGED_IN;
				return "<p class='ui-state-error'>You must be logged in to upload a file.</p>";
				break;
			case \Model\FileUploadHandler::VALIDATION_FAILED;
				return "<p class='ui-state-error'>Only gif/jpeg/png.</p>";
				break;
			case self::INVALID_DESCRIPTION;
				return "<p class='ui-state-error'>You need to enter a description(max 40 chars)</p>";
				break;
			case self::ADD_FAILED;
				return "<p class='ui-state-error'>Something went wrong when the image were to be added to the database.</p>";
				break;
			default:
				return "<p class='ui-state-error'>Something happened...</p>";
				break;
		}
	}
}
?>