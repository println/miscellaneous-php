<?php
/**
 * 
 * @author Proto <live.proto@hotmail.com>
 * @description Code to store and recovery a object to/from the session cache
 * 
 */
 
class card {
	private $name;
	private $id;
	private $version;
	public function __construct($n,$i,$v) {
		$this->name = $n;
		$this->id = $i;
		$this->version = $v;
	}
	public function getName(){
		return $this->name;
	}
	public function getId(){
		return $this->id;
	}
	public function getVersion(){
		return $this->version;
	}
	public function getMonster() {
		return $this->version;
	}
}
session_start();
echo "<pre>";
if(isset($_SESSION['class'])){
	$c = $_SESSION['class'];
	echo "Name: \t".$c->getName()."\nId: \t".$c->getId()."\nVer: \t".$c->getVersion();
	echo "\n\nPrinted from the class cache into session!";
	session_destroy();
}else{
	echo "Cached!";
	$c = new card("Someone",mt_rand(5, 150),mt_rand(1, 2));
	$_SESSION['class'] = $c;
}
echo "</pre>";