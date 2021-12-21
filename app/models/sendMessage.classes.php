<?php
class SendMessage extends Dbh {

	protected function insertMessage($msg, $senderId, $receiverId) { // login the user

		// get both user's public keys
		$senderPubKey = $this->getPublicKey($senderId);
		$receiverPubKey = $this->getPublicKey($receiverId);

		// initiate array for both user's keys
		$keyArray = [];

		// push both user's public keys to keyArray
		array_push($keyArray, $receiverPubKey, $senderPubKey);

		// initiate array for both user's packets for later use
		$packets = [];

		// iterate through keyArray
		foreach($keyArray as $keys) {
			$key = OpenPGP_Message::parse(OpenPGP::unarmor($keys, "PGP PUBLIC KEY BLOCK"));
			// $key holds key packets for the users
			foreach($key as $packet) {
				// push each packet to $newKeys
				array_push($packets, $packet);
			}
		}

		// store message in plainData
		$plainData  = new OpenPGP_LiteralDataPacket(
		$msg,
		array('format' => 'u', 'filename' => 'encrypted.gpg')
		);

		// encrypt the plainData using each user packet
		$encrypted = OpenPGP_Crypt_Symmetric::encrypt(
		$packets,
		new OpenPGP_Message(array($plainData))
		);

		// convert encrypted message to ASCII format
		$message = OpenPGP::enarmor($encrypted->to_bytes(), "PGP MESSAGE");

		// prepare statement for inserting encrypted message to database
		$stmt = $this->connect()->prepare('INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?);'); // connect to database

		// execute prepare statement (insert encrypted message to the database)
		if(!$stmt->execute(array($senderId, $receiverId, $message))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		$stmt = null; // close connection
		exit();

	}

	// fetch user's public key from database
	private function getPublicKey($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT publickey FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's public key
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// return users public key
		return $user[0]['publickey'];

	}
	
}