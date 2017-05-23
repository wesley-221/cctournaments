<?php
	require_once 'core/init.php';
	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);

	if(isset($_COOKIE[Config::get('cookie/cookie_name')])) {
		$userData = User::authenticate(isset($_COOKIE[Config::get('cookie/cookie_name')]) ? $_COOKIE[Config::get('cookie/cookie_name')] : null);
		$db -> execute('DELETE FROM cookies WHERE userId = ? AND cookie = ?', [$userData['userId'], $_COOKIE[Config::get('cookie/cookie_name')]]);
		Header('Location: ./');
		return;
	}
	else {
		Header('Location: ./');
		return;
	}
