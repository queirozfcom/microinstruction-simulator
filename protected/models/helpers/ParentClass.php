<?php
 class ParentClass{
	/**
	 * 
	 * Connect to mysql server at GoDaddy, using default info (username,password)
	 * 
	 */
	public function DBConnectMySQL(){
		$hostname = "usuario1988.db.6923779.hostedresource.com";
		$username = "usuario1988";
		$pass     = "Senha1988";
		
		try {
			$dbh  = new PDO("mysql:host=$hostname;dbname=usuario1988", $username, $pass);
			return $dbh;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}

	}
	
	
	
}