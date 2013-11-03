<?php
namespace Model;
	class InstallHandler {
		private $m_db = NULL;
		
		public function __construct(Database $db) {
			$this->m_db = $db;
		}
		
		public function CreateDatabase () {
			$sql = "CREATE DATABASE `164681-picup` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
					USE `164681-picup`;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function CreateUserTable () {
			$sql = "CREATE TABLE IF NOT EXISTS " . User::TABLE_NAME . " (
				  `m_id` int(11) NOT NULL AUTO_INCREMENT,
				  `m_username` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `m_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `m_role` varchar(30) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`m_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;";	
			return $this->m_db->RunQuery($sql);
		}
		
		public function CreateImageTable () {
			$sql = "CREATE TABLE IF NOT EXISTS " . Image::TABLE_NAME . " (
				  `m_id` int(11) NOT NULL AUTO_INCREMENT,
				  `m_filename` varchar(100) CHARACTER SET latin1 NOT NULL,
				  `m_description` varchar(40) NOT NULL,
				  `m_dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `m_userid` int(11) NOT NULL,
				  PRIMARY KEY (`m_id`),
				  KEY `m_userid` (`m_userid`),
				  KEY `m_dateadded` (`m_dateadded`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function CreateCommentTable () {
			$sql = "CREATE TABLE IF NOT EXISTS " . Comment::TABLE_NAME . " (
				  `m_id` int(11) NOT NULL AUTO_INCREMENT,
				  `m_comment` varchar(140) CHARACTER SET latin1 NOT NULL,
				  `m_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `m_imageID` int(11) NOT NULL,
				  `m_userID` int(11) NOT NULL,
				  `m_user` varchar(40) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`m_id`),
				  KEY `m_imageID` (`m_imageID`),
				  KEY `m_userID` (`m_userID`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function CreateImageRatingTable () {
			$sql = "CREATE TABLE IF NOT EXISTS " . ImageRating::TABLE_NAME . " (
				  `m_id` int(11) NOT NULL,
				  `m_imageID` int(11) NOT NULL,
				  `m_like` bit(1) NOT NULL,
				  PRIMARY KEY (`m_id`,`m_imageID`),
				  KEY `m_imageID` (`m_imageID`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function AddImageRelations () {
			$sql = "ALTER TABLE " . Image::TABLE_NAME . "
  					ADD CONSTRAINT `picup_image_ibfk_1` FOREIGN KEY (`m_userid`) REFERENCES `picup_user` (`m_id`) ON DELETE CASCADE ON UPDATE NO ACTION;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function AddCommentRelations () {
			$sql = "ALTER TABLE " . Comment::TABLE_NAME . "
				  ADD CONSTRAINT `picup_comment_ibfk_1` FOREIGN KEY (`m_imageID`) REFERENCES `picup_image` (`m_id`) ON DELETE CASCADE,
				  ADD CONSTRAINT `picup_comment_ibfk_2` FOREIGN KEY (`m_userID`) REFERENCES `picup_user` (`m_id`) ON DELETE CASCADE;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function AddImageRatingRelations () {
			$sql = "ALTER TABLE " . ImageRating::TABLE_NAME . "
				  ADD CONSTRAINT `picup_imagerating_ibfk_2` FOREIGN KEY (`m_imageID`) REFERENCES `picup_image` (`m_id`) ON DELETE CASCADE,
				  ADD CONSTRAINT `picup_imagerating_ibfk_3` FOREIGN KEY (`m_id`) REFERENCES `picup_user` (`m_id`) ON DELETE CASCADE;";
			return $this->m_db->RunQuery($sql);
		}
		
		public function CreateAdminAccount (User $user) {
			 $id = $user->getID();
			 $username = $user->getUsername();
			 $password = $user->getPassword();
			 $role = User::STANDARD_ADMIN_ROLE;
			 $sql = "INSERT INTO " . User::TABLE_NAME . " (m_id, m_username, m_password, m_role) VALUES(?, ?, ?, ?)";
			 
			 return $this->m_db->RunPreparedQuery($sql, "isss", array(&$id,
			 													&$username,
																&$password,
																&$role));
		} 
		
		public function DeleteDatabase() {
			$sql = "DROP DATABASE `164681-picup`;";
			return $this->m_db->RunQuery($sql);
		}
	}
?>