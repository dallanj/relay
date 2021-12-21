<?php
class GetChatProfileStatus extends Dbh {

	// change scripts.php to catch and display $alert

	protected function getChatProfileStatus($chatUid, $uid) {

		// fetch user info and store it in user
		$user = $this->fetchUserInfo($chatUid);

		// explode user's friend's list
		$friends = explode(',',$user['friends']);

		// check if uid is in the user's friend's list
		if(!in_array($uid,$friends)) {
			$data = null;

			// close the connection
			$stmt = null;

			// return null data
			echo json_encode($data);
			exit();
		}

		// if current time is less than user's last active timestamp + 60 set to active
		if(time() < $user['last_active']+60) {
			$activeNow = ' (active now)';
			$statusColor = 'green';
		}

		// if current time is greater than or equal to user's last active timestamp + 60 set to away
		if(time() >= $user['last_active']+60) {
			$activeNow = ' (away)';
			$statusColor = 'orange';
		}

		// if current time is greater than to user's last active timestamp + 120 set to offline
		if(time() > $user['last_active']+120) {
			$activeNow = ' (away)';
			$statusColor = '#eee';
		}

		// store user's info in data which will be returned
		$data['profile']['id'] = $chatUid;
		$data['profile']['username'] = $user['username'];
		$data['profile']['email'] = $user['email'];
		$data['profile']['last_active'] = $user['last_active'];
		$data['profile']['online_status'] = $user['online_status'];
		$data['profile']['status_color'] = $statusColor;
		$data['profile']['active_now'] = $activeNow;
		
		if($user['profile_pic'] == 1) {
		    $data['profile']['profilePic'] = $chatUid.'_'.$user['username'].'.jpg';
		} else {
		    $data['profile']['profilePic'] = 'no_pic.jpg';
		}
		
		// close the connection
		$stmt = null;

		// return user's info thats store in data
		echo json_encode($data);
		exit();
	
	}

	// fetch chatUid's friends list, last_active, online_status, and email
	private function fetchUserInfo($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's info
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// return users info
		return $user[0];

	}

}