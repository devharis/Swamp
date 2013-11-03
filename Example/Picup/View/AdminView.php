<?php
namespace View;
class AdminView {
	private $delUser = "DeleteUserButton";
	private $userId = "userid";
	const ADMIN_HEADER = "<h2>Administration</h2>";
	const ADMIN_ROLE = "Administrator";
	const NO_ADMIN_ACCESS = 1;
	const DELETE_FAILED = 2;
	
	public function DoUserTable ($users) {
		$table = "<h3>Useradministration</h3>";
		$table .= "<table id='Useradministration' cellspacing='0'>";
			$table .= "<thead>";
				$table .= "<tr><th>UserID</th><th>Name</th><th></th></tr>";
			$table .= "</thead>";
			$table .= "<tbody>";
				foreach ($users as $user) {
					$table .= "<tr>";
							$table .= "<td><a href='" . \View\NavigationView::INDEX . "?" . \View\NavigationView::USER . "=" . $user->getID() . "'>" . $user->getID() . "</a></td>";
							$table .= "<td>" . $user->getUsername() . "</td>";
					$table .= "</tr>";
				}
			$table .= "</tbody>";
		$table .= "</table>";
		return $table;
	}
	
	public function DoImageTable ($images) {
		$table = "<h3>Imageadministration</h3>";
		$table .= "<table id='Imageadministration' cellspacing='0'>";
			$table .= "<thead>";
				$table .= "<tr><th>ImageID</th><th>Filename</th><th>Description</th><th>Uploaded by</th></tr>";
			$table .= "</thead>";
			$table .= "<tbody>";
				foreach ($images as $image) {
					$table .= "<tr>";
							$table .= "<td><a href='". \View\NavigationView::INDEX . "?" . \View\NavigationView::IMAGE. "=" . $image->getImageID() . "'>" . $image->getImageID() . "</a></td>";
							$table .= "<td>" . $image->getFileName() . "</td>";
							$table .= "<td>" . $image->getDescription() . "</td>";
							$table .= "<td>" . $image->getuserName() . "</td>";
					$table .= "</tr>";
				}
			$table .= "</tbody>";
		$table .= "</table>";
		return $table;
	}
	
	/**
	 * Prints various messages based on a state.
	 * @param int $messageId - is the state of the message.
	 * @return string HTML
	 */
	public function PrintUserMessage($messageId) {
		switch ($messageId) {
			case self::NO_ADMIN_ACCESS:
				return "<p>Access denied</p>";
				
			case self::DELETE_FAILED:
				return "<p>Userdelete failed</p>";
		}
	}
}
?>