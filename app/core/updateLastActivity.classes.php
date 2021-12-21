<?php
/*
    this trait is used whenever the user interacts with the website
    the user's last activity timestamp is updated which is then verified using
    fetchData to change their activity status to online


NOT USING THIS TRAIT SINCE I NEED DB INTERACTION AND PERMISSION

	*/

trait UpdateLastActivity{


	public function updateLastActivity($uid) { // to use this class, set it to protected and you must extend
		
        $timestamp = current_date();

        // prepare statement to check if already existing friend request between receiver and sender
		$stmt = $this->connect()->prepare('UPDATE users SET last_active = ? WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($timestamp, $uid))) 
		{
			$stmt = null;
			return false;
		}
        else
        {
		    $stmt = null;
            return true;
        }

		
        
	}

}