<?php
Class Database{
	private $link;

	public function __construct()
	{
		$this->connect();
	}

	public function connect()
	{
		$config = require_once '../config/db.php';
		$dsn = 'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset='.$config['charset'].';';
		$this->link = new PDO($dsn, $config['user'], $config['password']);
		return $this;//объект ПДО
	}

	public function execute($sql)
	{
		$sth = $this->link->prepare($sql);
		return $sth->execute();
	}

	public function query($sql)
	{
		$sth = $this->link->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if($result === false)
		{
			return [];
		}
		return $result;
	}
}
