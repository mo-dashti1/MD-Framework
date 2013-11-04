<?php
class MD_Plugin_Sms {

	public $username ;
	public $password ;
	public $number ;
	public $ch  ;
	public $timeout  ;

	public function __construct( $us , $ps , $num)
	{
		$this->ch = curl_init();
		$this->username = $us;
		$this->password = $ps;
		$this->number = $num;
		$this->timeout = 5;		
	}
	public function sendsms($recipient,$body){
		$apiurl = "http://websms.amootco.ir/Send.aspx?Username=$this->username&Password=$this->password&Number=$this->number&Recipient=$recipient&Body=$body&Flash=false&Unicode=false";

		curl_setopt($this->ch,CURLOPT_URL,$apiurl);
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT,$this->timeout);
		$data = curl_exec($this->ch);
		curl_close($this->ch);
		return $data ;
		

	}
	
	
}