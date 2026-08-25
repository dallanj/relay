<?php
// Database handler (PDO) — used by model classes that extend Dbh.
class Dbh {

	protected function connect()
	{
		try {
			require_once __DIR__ . '/../../config.php';
			$db = app_config()['db'];
			$dbh = new PDO(
				'mysql:host=' . $db['host'] . ';dbname=' . $db['database'],
				$db['username'],
				$db['password']
			);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbh;
		}
		catch (PDOException $e) {
			$_SESSION['error'] = 'Error! ' . $e->getMessage();
			header('location: ../index.php');
			die();
		}
	}

}