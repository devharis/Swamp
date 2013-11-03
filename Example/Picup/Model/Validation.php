<?php

namespace Model;

class Validation {
	private $capSession = "captcha";
	
	public function MatchCaptcha($string) {
		if (isset($_SESSION[$this->capSession])) {
			if (strlen($string) == 6) {
				if ($_SESSION[$this->capSession] == $string) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}	
	
	/**
	 * Undersöker om inskickad sträng är tom, returnerar
	 * true om antal tecken är fler än 0
	 * $param string
	 * @return boolean
	 */
	public function IsValidUsername($username) {
		if(strlen($username) > 0 && strlen($username) <= 40) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function ValStrLength($str, $length) {
		if(strlen($str) > 0 && strlen($str) <= $length) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Funktion som jämnför en inskickad email-adress mot ett reguljärt uttryck.
	 */
	public function IsValidEmailAddress($email) {

		/**
		 * Matchar
		 * Tecken . eventuellt fler tecken @ tecken . eventuellt fler tecken . domän
		 */
		$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
		
		if(preg_match($pattern, $email)){
			// Success
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Formaterar ett inskickat personnummer till ett standardformat som sedan kontrolleras mot ett reguljärt uttryck.
	 * Kontrollsiffra beräknas på det formaterade och kontrollerade personnummret.
	 */

	public function SocialIdentityNumber($sinumber) {
		$sinumber = str_replace("-", "", $sinumber);

		if (strlen($sinumber) == 12) {
			$sinumber = substr($sinumber, 2);
		}

		if (!preg_match("/\b(((20)((0[0-9])|(1[0-1])))|(([1][^0-8])?\d{2}))((0[1-9])|1[0-2])((0[1-9])|(2[0-9])|(3[01]))[-+]?\d{4}[,.]?\b/", $sinumber)) {
			return FALSE;
		}

		$n = 2;
		$sum = 0;

		// Luhn-algoritmen

		for ($i = 0; $i < strlen($sinumber); $i++) {
			$tmp = $sinumber[$i] * $n;
			($tmp > 9) ? $sum += 1 + ($tmp % 10) : $sum += $tmp;
			($n == 2) ? $n = 1 : $n = 2;								
		}
				
		return ($sum % 10 == 0) ? $sinumber : FALSE;
	}

	/**
	 * Formaterar datum till yymmdd kontrollerar att datumet är giltigt. Även datum i framtiden returnerar true.
	 */
	public function ValidateDate($date) {
		
		$parts = array();
		$parts[0] = "";
		$date = str_replace("-", "", $date);

		// Är datumet angivet i yyyymmdd-format? Då sätter vi det till standardformatet yymmdd.
		if (strlen($date) == 8) {
			$date = substr($date, 2);
		}

		// Kontrollerar så att formatet är ett giltigt yymmdd.
		if (preg_match("^(((\d{4}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|((\d{2}[02468][048]|\d{2}[13579][26]))0229)){0,8}$^", $date)) {
			$parts[0] = $date[0] . $date[1];
			$parts[1] = $date[2] . $date[3];
			$parts[2] = $date[4] . $date[5];
			
			// Är det ett giltigt datum och inte exempelvis 2000-02-30.
			if (checkdate($parts[1], $parts[2], $parts[0])) {
				return $date;
			}
			
		}
		return FALSE;
	}
	
	/**
	 * Kontrollerar om det finns scripttaggar i en text och ändrar den så att parsen inte tolkar den som en scipt-tagg.
	 */
	public function StringValidationHTML($string) {

		$search = "<script>";
		$replace = "&ltscript&gt";

		$returnstring = str_replace($search, $replace, $string);
		
		$search = "</script>";
		$replace = "&lt/script&gt";
		
		$returnstring = str_replace($search, $replace, $returnstring);

		return $returnstring;
	}

	/**
	 * Tar bort alla taggar från en sträng.
	 */
	public function StrValNonHTML($string) {

		$returnstring = strip_tags($string);

		return $returnstring;
	}

	/**
	 * Kontrollerar ett lösenord mot ett reguljärt uttryck.
	 */
	public function IsValidPassword($password) {
		// Tillåt endast a-z A-Z 0-9 samt -. Lösenordet måste vara mellan 6-18 tecken

		// Undersöker först om lösenordet är minst 6 tecken långt
		if(strlen($password) >= 6) {
			$regex = "/^[a-zA-Z0-9_-]{6,18}$/";
	
			if (preg_match($regex, $password) == TRUE) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		return FALSE;
	}

	public function NumberValidation($string) {
		return is_numeric($string);
	}
}
?>