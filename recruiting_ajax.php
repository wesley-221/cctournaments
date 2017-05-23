<?php
	require_once 'core/init.php';
	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);

	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$recruiting = isset($_POST['recruiting']) ? $_POST['recruiting'] : '';
	$teamId = isset($_POST['teamId']) ? $_POST['teamId'] : '';
	$userId = isset($_POST['userId']) ? $_POST['userId'] : '';

	echo '<pre>' , print_r($_POST) , '</pre>';

	if(!strcmp($action, "recruiting")) {
		$db -> execute('UPDATE teams SET teamRecruiting = ? WHERE teamId = ? AND teamOwnerUserId = ?', [$recruiting, $teamId, $userId]);
		return;
	}
	else if(!strcmp($action, "acceptRecruitment")) {
		$curDate = date('Y-m-d h:i:s');
		$db -> execute('INSERT INTO teammembers VALUES(?, ?, ?)', [$teamId, $userId, $curDate]);
		$db -> execute('DELETE FROM teamsignup WHERE userId = ? AND teamId = ?', [$userId, $teamId]);
		return;
	}
	else if(!strcmp($action, "declineRecruitment")) {
		$db -> execute('DELETE FROM teamsignup WHERE userId = ? AND teamId = ?', [$userId, $teamId]);
		return;
	}
