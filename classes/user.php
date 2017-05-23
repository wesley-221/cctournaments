<?php
	require_once 'core/init.php';

	class User
	{
		public static function authenticate($cookie) {
			$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
			$db -> setFetchMode(PDO::FETCH_ASSOC);

			$cookieExist = $db -> fetch('SELECT userId FROM cookies WHERE cookie = ?', [$cookie]);

			if($cookieExist) {
				$returnArr = $db -> fetch('SELECT userId, username, email, registrationDate, osuProfile, permissionId FROM users WHERE userId = ?', [$cookieExist["userId"]]);
				$returnArr["loggedin"] = 1;
				return $returnArr;
			}
			else
				return array('loggedin' => 0);

		}

		// returns true if validated
		public static function validateUsername($username)
		{
			if(strlen($username) >= Config::get('validation/namemin') && strlen($username) <= Config::get('validation/namemax'))
				return true;
			else
				return false;
		}

		public static function validatePassword($password) {
			if(strlen($password) >= Config::get('validation/passwordmin') && strlen($password) <= Config::get('validation/passwordmax'))
				return true;
			else
				return false;
		}

		public static function getPermissionNameFromId($id) {
			$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
			$db -> setFetchMode(PDO::FETCH_ASSOC);
			$result = $db -> fetch('SELECT permissionName FROM permission WHERE permissionId = ?', [$id]);

			return $result["permissionName"];
		}

		public static function createSalt($salt)
		{
			return hash("sha512", $salt);
		}

		public static function hashPassword($password)
		{
			return hash("whirlpool", $password);
		}

		public static function GenerateCookie($username)
		{
			return hash("whirlpool", $username . Functions::generate_uniqueID(5));
		}
	}
