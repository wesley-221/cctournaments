<?php
	require_once 'core/init.php';
	$db = new Db(Config::get('mysql/host'), Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
	$db -> setFetchMode(PDO::FETCH_ASSOC);

	echo $_POST['teams'];

	// if(isset($_POST['teams'])) {
	// 	if(count($_POST['teams']) === 1) {
	// 		// check if a person is already participating in this tournament
	// 		foreach($_POST['teams'] as $team) {
	// 			foreach($team as $userid => $value) {
	// 				$isAlreadyParticipating = $db -> fetch('SELECT tournamentsignups.userId, username FROM tournamentsignups, users WHERE tournamentsignups.userId = users.userId AND tournamentId = ? AND tournamentsignups.userId = ?', [$_GET['t'], $userid]);
	//
	// 				if($isAlreadyParticipating) {
	// 					$sFinalString .= '"' . $isAlreadyParticipating['username'] . '" is already participating in this tournament. <br />';
	// 				}
	// 			}
	//
	// 			// check if there are at least 2 people for a 2v2, 3 people for a 3v3 etc
	// 			if(!strcmp($curTournament['tournamentTeamFormat'], '2v2')) {
	// 				if(count($team) < 2) {
	// 					$sFinalString .= 'This tournament is a 2v2. You need to at least select 2 people to join to participate as a team. <br />';
	// 				}
	// 			}
	// 			else if(!strcmp($curTournament['tournamentTeamFormat'], '3v3')) {
	// 				if(count($team) < 3) {
	// 					$sFinalString .= 'This tournament is a 3v3. You need to at least select 3 people to join to participate as a team. <br />';
	// 				}
	// 			}
	// 			else if(!strcmp($curTournament['tournamentTeamFormat'], '4v4')) {
	// 				if(count($team) < 4) {
	// 					$sFinalString .= 'This tournament is a 4v4. You need to at least select 4 people to join to participate as a team. <br />';
	// 				}
	// 			}
	// 			else if(!strcmp($curTournament['tournamentTeamFormat'], '5v5')) {
	// 				if(count($team) < 5) {
	// 					$sFinalString .= 'This tournament is a 5v5. You need to at least select 5 people to join to participate as a team. <br />';
	// 				}
	// 			}
	// 		}
	//
	// 		if(strlen($sFinalString) > 0) {
	// 			echo '<div class="alert alert-danger">' . $sFinalString . '</div>';
	// 		}
	// 		else {
	// 			foreach($_POST['teams'] as $teamId => $members) {
	// 				foreach($members as $member => $value) {
	// 					$db -> execute('INSERT INTO tournamentsignups(tournamentId, userId, teamId) VALUES(?, ?, ?)', [$_GET['t'], $member, $teamId]);
	// 				}
	// 			}
	//
	// 			Header('Location: ../../tournaments/t/' . $_GET['t']);
	// 		}
	// 	}
	// 	else {
	// 		echo '<div class="alert alert-danger">You can only select players from <b>one team</b>.</div>';
	// 	}
	// }
	// else {
	// 	echo '<div class="alert alert-danger">You haven\'t selected any players to join this tournament with, please try again!</div>';
	// }
