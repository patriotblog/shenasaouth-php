<?php
	
	const SHENASA_ADDR = 'http://accounts.shenasaservice.me/';
	const SHENASA_API = 'http://api.shenasaservice.me/';
		
	class ShenasaOAuth{
		
		private $client_id;
		private $client_secret;
		private $redirect_uri;
		
		private $access_token;
		private $user_info;
		
		
		public function __construct($clinet_id, $client_secret, $redirect_uri=false) {
		
			$this->clinet_id = $clinet_id;
			$this->client_secret = $client_secret;
			
			if($redirect_uri){
				$this->redirect_uri = $redirect_uri;
			}else{
				if(isset($_SERVER['SERVER_NAME']) && isset($_SERVER['SCRIPT_NAME']))
					$this->redirect_uri = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
				else
					throw new Exception('redirect uri can not be empty');
			}
			
			$this->access_token = NULL;
			$this->user_info = NULL;
			
			if(isset($_GET['code']) && !empty($_GET['code'])){
				$this->getAccessToken($_GET['code']);
			}else{
			
			}
		}
		public function generateLoginUri($echo=false){
			$uri = SHENASA_ADDR."oauth2/auth?client_id=$this->clinet_id&response_type=code&redirect_uri=".urlencode($this->redirect_uri)."&scope=email";
			if($echo)
				echo $uri;
			else
				return $uri;
		}
		public function getUserEmail(){
			if($this->access_token){
				return $this->user_info->email;		
			}else{
				return false;
			}
		}
		private function getAccessToken($code){
		
			$uri = SHENASA_ADDR."oauth2/token?client_id=$this->clinet_id&redirect_uri=$this->redirect_uri&client_secret=$this->client_secret&code=$code&grant_type=authorization_code";
			
			$data = $this->get_url($uri);
			if($data){
				$params = json_decode($data);
				$this->access_token = $params->access_token;
				$_SESSION['shenasa_access_token'] = $this->access_token;
				$this->getUserInfo();
			}else{
				throw new Exception('Check Internet Connection');
			}
			
		}
		public function getUserInfo(){
			$uri = SHENASA_API."oauth2/userinfo?access_token=".$this->findAccessToken();
			$data  = $this->get_url($uri);
			if($data){
				$this->user_info = json_decode($data);
			}
			return $this->user_info;
		}
		private function get_url($url) {
			try {
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
				$content = curl_exec($ch);
				curl_close($ch);
				return $content;
			}catch (Exception $e) {
				throw new Exception('CURL needed.');
			}
		}
		private function findAccessToken(){
			if(isset($this->access_token) && !empty($this->access_token)){
				
			}else if(isset($_SESSION['shenasa_access_token']) && !empty($_SESSION['shenasa_access_token'])){
				$this->access_token = $_SESSION['shenasa_access_token'];
			}else{
				throw new Exception('access_token not find');
			}
			return $this->access_token;	
		}
	}
?>