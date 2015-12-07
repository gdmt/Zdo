<?php

class Zdo extends PDO
{	
	public function __construct($config)
	{
		if($config['adapter']=='sqlite'){
			parent::__construct('sqlite:'.$config['dbname']);
		}elseif($config['adapter']=='pgsql'){
			parent::__construct('pgsql:dbname='.$config['dbname'].';host='.$config['host'].';user='.$config['username'].';password='.$config['password']);
		}
		
		$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		if(isset($config['schema']) && $config['schema'] && $this->exec('SET SCHEMA '.$this->quote($config['schema']))===false){
			throw new Exception(__METHOD__.': Failed to set schema.');
		}
	}
	
	public function query($sql,$bind=array())
    {
		return $this->prepare($sql)->execute($bind);
	}
	
	public function prepare($sql,$driverOptions=array())
	{
		return new Zdo_Statement(parent::prepare($sql,$driverOptions));
	}
	
	public function getParent()
	{
		return parent;
	}
}

class Zdo_Statement 
{
	protected $pdoStatement=false;
	
	public function __construct($pdoStatement)
	{
		$this->pdoStatement=$pdoStatement;
	}
	
	public function execute($bind=array())
	{
		if(!is_array($bind)){
			$bind=array($bind);
		}
		$this->pdoStatement->execute($bind);
		return $this;
	}
	
	public function fetchNum($cursorOrientation=PDO::FETCH_ORI_NEXT, $cursorOffset=0)
    {
		return $this->pdoStatement->fetch(PDO::FETCH_NUM, $cursorOrientation, $cursorOffset);
	}
	
	public function fetchAssoc($cursorOrientation=PDO::FETCH_ORI_NEXT, $cursorOffset=0)
    {
		return $this->pdoStatement->fetch(PDO::FETCH_ASSOC, $cursorOrientation, $cursorOffset);
	}	
	
	public function fetchAllAssoc()
	{
		return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function fetchAllNum()
    {
		return $this->pdoStatement->fetchAll(PDO::FETCH_NUM);
	}
	
	public function __call($name,$arguments)
	{
		return call_user_func_array(array($this->pdoStatement,$name),$arguments);
	}
}
