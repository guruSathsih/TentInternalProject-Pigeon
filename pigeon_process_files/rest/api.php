<?php

 header("Access-Control-Allow-Origin: *");
ini_set('date.timezone','Asia/Calcutta');
    
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		
		CREATE TABLE IF NOT EXISTS `pigeon_users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `user_email` varchar(50) NOT NULL,
		  `user_password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		/*const DB_SERVER = "localhost";
		const DB_USER = "smadmin";
		const DB_PASSWORD = "sm@admin";
		const DB = "pigeon";*/
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			try{
				if (strpos($this->getURL(),'http://localhost') !== false)
				{
					$DB_SERVER = "localhost";
					$DB_USER = "smadmin";
					$DB_PASSWORD = "sm@admin";
					$DB = "pigeon";
				}
				else
				{		
					 
					$DB_SERVER = "TSLMS.db.7752695.hostedresource.com";
					$DB_USER = "TSLMS";
					$DB_PASSWORD = "Admin1234!";
					$DB = "TSLMS";
				}	
				$this->db = mysql_connect($DB_SERVER,$DB_USER,$DB_PASSWORD);
				if($this->db)
					mysql_select_db($DB,$this->db);
			}catch(Exception $ex){
				echo "Warning Message:".$ex->getMessage();
			}
			
		}
		
		public function getURL()
		{
			return sprintf("%s://%s%s",isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){   	
			 
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$registrationId = $this->_request['registrationId']; 
			$mobile_no = $this->_request['mob-num'];		
			$password = $this->_request['pword']; 
			
			// Input validations 
			if(!empty($mobile_no) and !empty($password)){ 
					$sql = mysql_query("SELECT * FROM pigeon_users WHERE mobile_no = '$mobile_no' AND password = '$password' LIMIT 1", $this->db);
					if(mysql_num_rows($sql) > 0){
						mysql_query("UPDATE pigeon_users SET REGISTRATION_ID = '$registrationId' WHERE mobile_no = '$mobile_no' AND password = '$password'", $this->db);
						$result = mysql_fetch_array($sql,MYSQL_ASSOC); 
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}else{
						$this->http_response(0, 200);	// If no records "No Content" status	
					}
				 
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		
		private function loadPigeons(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			try{
				
			$userId = $this->_request['userId'];
			
			
			$sql = mysql_query("SELECT * FROM pigeon_users", $this->db);
			if(mysql_num_rows($sql) > 0){ 
				$list = "<div class='row' style='padding-left:25px;padding-right:25px;padding-top:10px;padding-bottom:10px' id='pigeons'> 
				  <form class='ui-filterable'>
				    <input id='filterBasic-input' data-type='search' placeholder='Search Pigeons'>
				</form>	<ul data-role='listview' data-filter='true' data-input='#filterBasic-input'>";
				  
				while($rlt  = mysql_fetch_array($sql)){ 
					$mobile_no = $rlt[1]; 
					$name = $rlt[2];
					if($userId != $mobile_no){
						$list = $list.""."<li style='padding-bottom:3px'><a href='open_chat.html?id=".$mobile_no."&name=".$name."' value=".$mobile_no." class='ui-btn ui-icon-user ui-btn-icon-left' data-ajax='false' >".$name."</a></li>"; 
					}
				}
				$list = $list.""."</ul></div>";
				// If success everythig is good send header as "OK" and return list of pigeon_users in JSON format  
				$this->http_response($list, 200);
			}
			$this->http_response(0, 200);	// If no records "No Content" status
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		}
		
		private function deleteUser(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				mysql_query("DELETE FROM pigeon_users WHERE user_id = $id");
				$success = array('status' => "Success", "msg" => "Successfully one record deleted.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
		
		/* 
		 *	Simple SignUP API
		 *  SignUp must be POST method 
		 */
		
		private function signuppigeon(){ 
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			try{
				
			$name = $this->_request['name'];		
			$email = $this->_request['email'];	
			$mobile = $this->_request['mob-num'];	
			$password = $this->_request['re-pword']; 
			$registrationId = $this->_request['registrationId']; 
			$profile = '';
			$status = 0; 
			
			 
			// Input validations 
			if(empty($email)){
				$email= NULL;
			}
			if(!empty($mobile) and !empty($password)){ 
	
					mysql_query("INSERT INTO PIGEON_USERS VALUES(default,'$mobile','$name','$email','$password','$profile','$status','$registrationId')",$this->db); 
					 
					$success = array('username' => $mobile, "password" => $password, "msg"=>"Signed UP Successfully");
					$this->response($this->json($success), 200);
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "some problem");
			$this->http_response(0,200);  
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		} 
		
		/**
		* Function loads history of the person
		* 
		* @return history , 2 - if no history, 1 - if it is added to contact but not chatted
		*/
		private function loadHistory(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			$lastMessage = $this->_request['lastMessage']; 
			$from_userId = $this->_request['fromUser'];
			$to_userId = $this->_request['toUser'];
			
			try{
				
			if(!empty($from_userId) and !empty($to_userId)){
				$rs = mysql_query("select * from user_contacts where user_id = '$from_userId' and contacts='$to_userId' limit 1",$this->db);  
				if(mysql_num_rows($rs) > 0){ 
					$row = mysql_fetch_row($rs);
						if($row[3] == 0){
							$contact_request = '<div class="row text-center" style="padding-top:15px;padding-bottom:15px">
									<div class="col-sm-12"> 
							 			<font color="#aaa"><label id="atc">Request Has been sent !!!<label></font>
									</div>
								</div>';
							$this->http_response($contact_request,200); 
						}else if($row[3] == 1){ 
							$chat_history = $this->loadChatHistory($from_userId,$to_userId,$lastMessage); 
							$this->http_response($chat_history,200);
						} 
					}else{ 
						$result_1 = mysql_query("select * from user_contacts where user_id = '$to_userId' and contacts='$from_userId' limit 1",$this->db); 
					if(mysql_num_rows($result_1) > 0){ 
						$row1 = mysql_fetch_row($result_1);
						if($row1[3] == 0){
							 $contact_request = '<div class="row text-center" style="padding-top:15px;">
									<div class="col-sm-12"> 
							 			<font color="#aaa">Please Accept My Contact Request !!!</font>
									</div>
								</div>
							 	<div class="row"> 
									<div class="col-sm-2" style="float: right">
										<input id="atc" type="button" value="Accept Request" class="ui-btn" onclick="acceptContactRequest('.$to_userId.','.$from_userId.')"/>	
									</div>
								</div>';
							$this->http_response($contact_request,200); 
						}else if($row1[3] == 1){
							$chat_history = $this->loadChatHistory($from_userId,$to_userId,$lastMessage);
							$this->http_response($chat_history,200);
						}
				  }else{
							  
						 $contact_request = '<div class="row text-center" style="padding-top:15px">
								<div class="col-sm-12"> 
						 			<font color="#aaa">This person is not in your contact !!!</font>
								</div>
							</div>
						 	<div class="row"> 
								<div class="col-sm-2" style="float: right">
									<input id="atc" type="button" value="Say Hi !!!" class="ui-btn" onclick="sendContactRequest('.$from_userId.','.$to_userId.')"/>	
								</div>
							</div>';
						$this->http_response($contact_request,200);
					}
				}
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "some problem");
			$this->http_response(0,200);  
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		} //End of loadHistory()
		
		private function sendContactRequest(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			
			$from = $this->_request['fromUser'];
			$to = $this->_request['toUser']; 
			
			try{
				
			if(!empty($from) and !empty($to)){
				try{
					
				 $rs = mysql_query("insert into user_contacts values(default,'$from','$to',0)",$this->db);
				 /*$this->http_response("s",200);die;*/
				$contact_request = '<div class="row text-center" style="padding-top:15px;padding-bottom:15px">
								<div class="col-sm-12"> 
						 			<font color="#aaa">Request Has been sent !!!</font>
								</div>
							</div>';
				$this->http_response($contact_request,200);  
				}catch(Exception $ex){
					echo $ex->getMessage();
				}
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "some problem");
			$this->http_response(0,200);  
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		}
		
		private function acceptContactRequest(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			
			$from = $this->_request['fromUser'];
			$to = $this->_request['toUser']; 
			try{
				
			if(!empty($from) and !empty($to)){
				mysql_query("update user_contacts set request_status = 1  where user_id = '$from' and contacts = '$to' ",$this->db);
				/*mysql_query("insert into user_contacts values(default,'$to','$from',1)",$this->db);*/
				 
				$this->http_response(1,200);  
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "some problem");
			$this->http_response(0,200);  
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		}
		
		private function loadChatHistory($from_userId,$to_userId,$lastMessage){
			try{
				
			$result = mysql_query("select * from conversation where sent_from = $from_userId and sent_to = $to_userId or sent_from = $to_userId and sent_to = $from_userId ORDER BY time",$this->db);
			
			$dateStatus = 2;
			$i = 1; 
			$chat = "";  
			$resLenth = mysql_num_rows($result);
				if(mysql_num_rows($result) > 0){
					
					$chat = '<div class="chat">
 <div class="chat-history">
				        <ul>';
						
					while($row = mysql_fetch_array($result)){ 
						
						
						if($row[1] == $from_userId){
							$name = $this->getUserName($row[1]);
							  
							$sms_date = $this->getDayAndTime($row[4]);
							
							$chat .= '<li class="clearfix">
										<div class="message-data align-right">
											<span class="message-data-time" >'.$sms_date.'</span> &nbsp; &nbsp;
											<span class="message-data-name" style="color:#94C2ED">'.$name.'</span>
											<i class="fa fa-circle me"></i></div>';
							
					            $msg  = explode(".",$row[5]);
							
							if(count($msg) > 1){
								if(strcmp($msg[count($msg)-1],"jpg") == 0){
									$chat .= '<div class="message other-message float-right">
										<img src = "http://creatustent.com/pigeon/rest/uploads/images/'.$row[5].'" width="100" height="100"/><a href="">download</a>
									</div> </li>';
								}else if(strcmp($msg[count($msg)-1],"mp3") == 0){
									$chat .= '<div class="message other-message float-right"><audio controls style="display:block;width:100%">   <source src="http://creatustent.com/pigeon/rest/uploads/audios/'.$row[5].'" type="audio/mp3" preload="auto" controls></audio>									</div> </li>';
								}
								else{
									$chat.='<div class="message other-message float-right">'.$row[5].'</div> </li>';
								}
							}else{
								$chat.='<div class="message other-message float-right">'.$row[5].'</div> </li>';
							}
				        }else{ 
							$name = $this->getUserName($row[1]); 
							$sms_date = $this->getDayAndTime($row[4]);
							$chat .= '<li>
				            <div class="message-data">
				              <span class="message-data-name" style="color:#86BB71"><i class="fa fa-circle online"></i> '.$name.'</span>
				              <span class="message-data-time" >'.$sms_date.'</span>
				            </div>';
				            $msg  = explode(".",$row[5]);
							
							if(count($msg) > 1){
								if(strcmp($msg[count($msg)-1],"jpg") == 0){
									$chat.='<div class="message my-message">
										<img src = "http://creatustent.com/pigeon/rest/uploads/images/'.$row[5].'" width="100" height="100"/>
										<a href="">download</a>
									</div> </li>';
								}else if(strcmp($msg[count($msg)-1],"mp3") == 0){
									$chat .= '<div class="message my-message">										<audio controls style="display:block;width:100%">   <source src="http://creatustent.com/pigeon/rest/uploads/audios/'.$row[5].'" type="audio/mp3" preload="auto" controls></audio>		</div> </li>';
								}else{
									$chat.='<div class="message my-message">'.$row[5].'</div> </li>';
								}
							}else{
								$chat.='<div class="message my-message">'.$row[5].'</div> </li>';
							}
				          
				      }     
				      if($i == $resLenth){
							$rsMsgDate = $row[4]; 
					  }    
				      $i++;
					} 
			 $chat .= '</ul>
				        <div id="foc" style="display:none"/><input type="hidden" value="'.$rsMsgDate.'" id="lastMsgDate"/>';    
					 if(!empty($lastMessage)){  
					 	$lastMessage = new DateTime(date('Ymd h:i:s',strtotime($lastMessage)));
						$rsMsgDate = new DateTime(date('Ymd h:i:s',strtotime($rsMsgDate))); 
						$dateStatus = $lastMessage < $rsMsgDate; 
					 }
					 if($dateStatus == 1){ 
					 	$chat .= '<input type="hidden" value="1" id="load"/>';   
					 }else{ 
					 	$chat .= '<input type="hidden" value="0" id="load"/>';   
					 } 
					  $chat .= '</div> <!-- end chat-history -->';
				}else{
					$chat = '<div class="chat">
 <div class="chat-history" style="color:#aaa;text-align:center"> 
 	Start Chatting with '.$this->getUserName($to_userId).'
 </div>  </div> ';
				}
			}catch(Exception $ex){  
					echo $ex->getMessage();
				} 
			return $chat;
		}
		
		/**
		* Function is used to send text messages
		* @param Who sents  $from
		* @param To Whome  $to
		* @param undefined $message
		* 
		* @return
		*/
		function sendTextMessage(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			$lastMessage = $this->_request['lastMessage']; 
			$from = $this->_request['fromUser'];
			$to = $this->_request['toUser'];
			$message = $this->_request['message'];
			$message_type = $this->_request['type'];
			$server_addr = $_SERVER['SERVER_ADDR']; 
			$now_time = date("Y-m-d H:i:s");
			if(!empty($message_type) and strcmp($message_type,"single") == 0){
				$message_type = 0;//For one to one chat
			}else if(!empty($message_type) and strcmp($message_type,"group") == 0){
				$message_type = 1;//For One to Many chat
			}else{
				$message_type = null;
			}
			
			if(!empty($from) and !empty($to) and !empty($message)){
				 
				try{  
					mysql_query("insert into conversation values(default,'$from','$to','$server_addr','$now_time','$message',$message_type,0)",$this->db); 
				$chat_history = $this->loadChatHistory($from,$to,$lastMessage);
				$this->http_response($chat_history,200);
				}catch(Exception $ex){  
					echo $ex->getMessage();
				}
				
			}
			//If errror 
			$this->http_response(0,200);  
		}
		
		private function getUserName($userId){
			$name = "";
			try{
				$result = mysql_query("select name from pigeon_users where mobile_no = '$userId' limit 1",$this->db);
				
				if(mysql_num_rows($result) > 0){
					while($row = mysql_fetch_array($result)){
						$name = $row[0];
					}	
				}
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
			return $name;
		} 
		
		private function activateUser(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			$userId = $this->_request['userId'];    
			if(!empty($userId)){
				 
				try{  
					mysql_query("update pigeon_users set status = 1 WHERE mobile_no = '$userId'",$this->db);  
					 $this->http_response(1,200);  
				}catch(Exception $ex){  
					echo $ex->getMessage();
				}
				
			}
			//If errror 
			$this->http_response(0,200);  
		}
		
		/**
		* 
		* 
		* @return
		*/
		private function activeOrNot(){
			if($this->get_request_method() == 'GET'){
				$this->response('',406);
			}
			$userId = $this->_request['userId'];    
			if(!empty($userId)){
				 
				try{  
					$rs = mysql_query("select status from pigeon_users WHERE mobile_no = '$userId' LIMIT 1",$this->db);  
					if(mysql_num_rows($rs) > 0){
						$row = mysql_fetch_row($rs);
						$status = $row[0];
						if(!empty($status) and $status == 1){
							$this->http_response(1,200);  
						}else{
							$this->http_response(2,200);  	
						}
						
					}	
					 
				}catch(Exception $ex){  
					echo $ex->getMessage();
				}
				
			}
			//If errror 
			$this->http_response(0,200);   
		}
		
		/**
		* 
		* 
		* @return
		*/
		private function loadCurrentChat(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			try{
				
			$userId = $this->_request['userId']; 
			$sql1 = mysql_query("select * from pigeon_users where mobile_no in (select user_id from user_contacts where request_status = 1 and contacts = '$userId') or mobile_no in (select contacts from user_contacts where request_status = 1 and user_id = '$userId' )", $this->db);
		 
			if(mysql_num_rows($sql1) > 0){ 
				$list = "<div class='row' id='pigeons'><ul data-role='listview'>";
				  
				while($rlt  = mysql_fetch_array($sql1)){ 
					$mobile_no = $rlt[1]; 
					$name = $rlt[2];
					$status = "&nbsp;<i class='fa fa-circle offline' style='font-size:10px'></i>";
					if($rlt[6] == 1){
						$status = "&nbsp;<i class='fa fa-circle online' style='font-size:10px'></i>";
					}
					
					$sql = mysql_fetch_assoc(mysql_query("select * from conversation where (sent_to = '$userId' and sent_from = '$mobile_no') or (sent_from = '$userId' and sent_to = '$mobile_no') order by time DESC limit 1", $this->db));
					
					$time = $sql['time'];
					$datetime1 = new DateTime(date("Y-m-d H:i:s"));
					$datetime2 = new DateTime($time);
					$interval = date_diff($datetime1, $datetime2);
					
					if($sql['sent_from'] == $userId){
						$message = "You: ";
					}else{
						$message = $this->getUserName($sql['sent_from']).": ";
					} 
					$message .=  substr($sql['reply'],0,15)."...";
					$time = $this->getDayAndTime($sql['time']);
							
					
					if($userId != $mobile_no){
						$list = $list.""."<li data-icon='false'>
						
						<a href='open_chat.html?id=".$mobile_no."&name=".$name."' value=".$mobile_no." class='ui-btn' data-ajax='false' style='margin-left:15px'>
						<div class='ui-grid-a' style='padding:10px 2px '>
							<div class='ui-block-a' style='width:20%'>
									<img src='img/sample.jpg' class='pic-circle-corner'/>
								</div>
							<div class='ui-block-b' style='width:80%'>
								<div class='ui-grid-a'>
									<div class='ui-block-a'>
										".$name."&nbsp;".$status." 
									</div>
									<div class='ui-block-b'> 
									</div>
									<div class='ui-block-a' style='font-size:11px'>
										".$message." 
									</div>
									<div class='ui-block-b' style='text-align:right;padding-right:25px;font-size:11px'>
										".$time."
									</div>
								</div> 
							</div> 
						 </div>
						 </a>
						
						</li>"; 
					} 
				}
				$list = $list."".$this->loadCurrentGroupChat($userId,0)."</ul></div>";
				// If success everythig is good send header as "OK" and return list of pigeon_users in JSON format 
				$this->http_response($list, 200);
			}else{
					$this->http_response("<div style='color:#aaa;text-align:center'>You have not chatted with any pigeon</div", 200);
				}
			 
				$this->http_response(0, 200);	// If no records "No Content" status
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		}
		
		/**
		* Function returns the day and time
		*/
		private function getDayAndTime($timestamp){
			try{
				$today_YMD = new DateTime(date('Ymd h:i:s')); 
				/*$today_YMD = date('Ymd',date($now));*/
				$given_YMD = new DateTime(date('Ymd h:i:s',strtotime($timestamp)));
				/*if($today_YMD == $given_YMD){
					$sms_day = "Today, "; 
				}else if(date_diff($given_YMD,$today_YMD) == 1){
					$sms_day =  date("l, ",strtotime($timestamp));
				}*/
				/*$sms_day = "";
				$sms_date = "";
				$dif1 = date_diff($given_YMD,$today_YMD);
				$dif = $dif1->format('%d'); 
				switch($dif){
					case 0: 
							$sms_day = "Today, ";  
							$sms_date = $sms_day.date("g:i A",strtotime($timestamp));
					      	break;
					case 1:
					       $sms_day = "Yesterday, "; 
						   $sms_date = $sms_day.date("g:i A",strtotime($timestamp));
					      	break;
					case ($dif == 2 || $dif <= 5):
							$sms_day =  date("l, ",strtotime($timestamp));
							$sms_date = $sms_day.date("g:i A",strtotime($timestamp));
					       break;
					case 6:
						   $sms_date =  "1 week ago";
					       break;
					case ($dif == 7 || $dif <= 14):
						   $sms_date =  "2 weeks ago";
					       break;
					case ($dif == 15 || $dif <= 21):
						   $sms_date =  "3 weeks ago";
					       break;
					case ($dif == 22 || $dif <= 28):
						   $sms_date =  "4 weeks ago";
					       break;
					case ($dif == 29 || $dif <= 30):
						   $sms_date =  "1 month ago";
					       break;
					case ($dif == 31 || $dif <= 60):
						   $sms_date =  "2 month ago";
					       break;
					case ($dif == 61 || $dif <= 90):
						   $sms_date =  "3 month ago";
					       break;
					default: 
				} */
				
				  $timestamp = time() - strtotime($timestamp); // to get the time since that moment
				    $timestamp = ($timestamp<1)? 1 : $timestamp;
				    $tokens = array (
				        31536000 => 'year',
				        2592000 => 'month',
				        604800 => 'week',
				        86400 => 'day',
				        3600 => 'hour',
				        60 => 'minute',
				        1 => 'second'
				    );

				    foreach ($tokens as $unit => $text) {
				        if ($timestamp < $unit) continue;
				        $numberOfUnits = floor($timestamp / $unit);
				        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
				    }
				 
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			return $sms_date;
		}
		
		/**
		* Function creates a group
		* 
		* @return
		*/
		private function createGroup(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$groupName = $this->_request['gname'];
			$userId = $this->_request['userId'];
			$pic = $this->_request['pic'];
			try{ 
				if(!empty($groupName) and !empty($userId)){
					mysql_query("insert into group_chat values(default,'$groupName','$userId','$pic')",$this->db);
					$last_inserted = mysql_insert_id($this->db);
					$this->http_response($last_inserted,200);  //Created
				}
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			//If error
			$this->http_response(0,200);  
		}
		
		/**
		* 
		* 
		* @return
		*/
		private function searchPigeons(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			try{
				
			$userId = $this->_request['userId'];
			$groupId = $this->_request['groupId'];
			if(!empty($userId) and !empty($groupId)){
				
			$sql = mysql_query("SELECT * FROM pigeon_users where mobile_no not in(select parti_id from group_contacts where group_id = $groupId )", $this->db);
			if(mysql_num_rows($sql) > 0){ 
				$list = " 
				    <fieldset data-input='#filterBasic-input' data-filter='true' data-role='controlgroup' data-iconpos='right'>";
				  
				while($rlt  = mysql_fetch_array($sql)){ 
					$mobile_no = $rlt[1]; 
					$name = $rlt[2];
					if($userId != $mobile_no){
						$list = $list.""."<input style='padding-bottom:3px' id='$mobile_no' name='$name' type='checkbox' value='$mobile_no'/><label for='$mobile_no'>$name -- $mobile_no</label>"; 
					}
				}
				$list = $list.""."</fieldset>";
				// If success everythig is good send header as "OK" and return list of pigeon_users in JSON format  
				$this->http_response($list, 200);
			}
			
			}
			$this->http_response(0, 200);	// If no records "No Content" status
			}catch(Exception $ex){  
					echo $ex->getMessage();
				}
		}
		
		/**
		* Function to add participants to the Group
		* 
		* @return
		*/
		private function addParticipants(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			try{
				$userId = $this->_request['userId'];
				$groupId = $this->_request['groupId'];
				/*$contactId = [];*/
				$contactId = $this->_request['contacts']; 
				if(!empty($groupId) and !empty($contactId) and (count($contactId) > 0)){
					
					for($i = 0;$i<count($contactId);$i++){
						$participant_id = $contactId[$i];
						$query = "insert into group_contacts values(default,$groupId,'$participant_id')";				mysql_query($query,$this->db);
					}
					$query = "insert into group_contacts values(default,$groupId,'$userId')";						mysql_query($query,$this->db);			
					$this->http_response("Success", 200);	
				}
				$this->http_response(0, 200);
			}catch(Exception $ex){
				$ex->getMessage();
			}
		}
		
		function loadGroupChatHistory(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			try{
			$lastMessage = $this->_request['lastMessage']; 	
			$userId = $this->_request['userId'];
			$groupId = $this->_request['groupId'];
			$dateStatus = 2;
			$i = 1; 
			$result = mysql_query("select * from conversation where sent_from in (select parti_id from group_contacts where group_id = $groupId) and sent_to = $groupId and group_chat = 1 order by time asc",$this->db); 
			$chat = "";
			$resLenth = mysql_num_rows($result);
				
				if(mysql_num_rows($result) > 0){
					
					$chat = '<div class="chat">
 <div class="chat-history">
				        <ul>';
						
					while($row = mysql_fetch_array($result)){
						
						if($row[1] == $userId){
							$name = $this->getUserName($row[1]);
							 
							 
							$sms_date = $this->getDayAndTime($row[4]);
							
							$chat .= '<li class="clearfix">
					            <div class="message-data align-right">
					              <span class="message-data-time" >'.$sms_date.'</span> &nbsp; &nbsp;
					              <span class="message-data-name" style="color:#94C2ED">'.$name.'</span> 
								  <i class="fa fa-circle me"></i>           
					            </div>';
							$msg  = explode(".",$row[5]);
							
							if(count($msg) > 1){
								if(strcmp($msg[count($msg)-1],"jpg") == 0){
									$chat .= '<div class="message other-message float-right">
										<img src = "http://creatustent.com/pigeon/rest/uploads/images/'.$row[5].'" width="100" height="100"/><a href="">download</a>
									</div> </li>';
								}else if(strcmp($msg[count($msg)-1],"mp3") == 0){
									$chat .= '<div class="message other-message float-right"><audio controls style="display:block;width:100%">   <source src="http://creatustent.com/pigeon/rest/uploads/audios/'.$row[5].'" type="audio/mp3" preload="auto" controls></audio>									</div> </li>';
								}
								else{
									$chat.='<div class="message other-message float-right">'.$row[5].'</div> </li>';
								}
							}
				        }else{
							$name = $this->getUserName($row[1]);  
							 $sms_date = $this->getDayAndTime($row[4]);
							$chat .= '
				          <li>
				            <div class="message-data">
				              <span class="message-data-name" style="color:#86BB71"><i class="fa fa-circle online"></i> '.$name.'</span>
				              <span class="message-data-time" >'.$sms_date.'</span>
				            </div>';
						  $msg  = explode(".",$row[5]);
							
							if(count($msg) > 1){
								if(strcmp($msg[count($msg)-1],"jpg") == 0){
									$chat.='<div class="message my-message">
										<img src = "http://creatustent.com/pigeon/rest/uploads/images/'.$row[5].'" width="100" height="100"/>
										<a href="">download</a>
									</div> </li>';
								}else if(strcmp($msg[count($msg)-1],"mp3") == 0){
									$chat .= '<div class="message my-message">										<audio controls style="display:block;width:100%">   <source src="http://creatustent.com/pigeon/rest/uploads/audios/'.$row[5].'" type="audio/mp3" preload="auto" controls></audio>		</div> </li>';
								}else{
									$chat.='<div class="message my-message">'.$row[5].'</div> </li>';
								}
							}else{
								$chat.='<div class="message my-message">'.$row[5].'</div> </li>';
							}
				           
				      }     
				        if($i == $resLenth){
							$rsMsgDate = $row[4]; 
					  }    
				      $i++;   
				      
					}
				
			 $chat .= '</ul><input type="hidden" value="'.$rsMsgDate.'" id="lastMsgDate"/>';  
			 	if(!empty($lastMessage)){  
					 	$lastMessage = new DateTime(date('Ymd h:i:s',strtotime($lastMessage)));
						$rsMsgDate = new DateTime(date('Ymd h:i:s',strtotime($rsMsgDate))); 
						$dateStatus = $lastMessage < $rsMsgDate; 
					 }
					 if($dateStatus == 1){ 
					 	$chat .= '<input type="hidden" value="1" id="load"/>';   
					 }else{ 
					 	$chat .= '<input type="hidden" value="0" id="load"/>';   
					 } 
					  $chat .= '</div> <!-- end chat-history -->';
				}else{
					$chat = '<div class="chat">
 <div class="chat-history" style="color:#aaa;text-align:center"> 
 	Start Chatting
 </div>  </div> ';
 
				}
			}catch(Exception $ex){  
					echo $ex->getMessage();
				} 
			$this->http_response($chat, 200);
		}
		
		/**
		* 
		* @param undefined $userId
		* @param undefined $load 0 - current | 1 - load all
		* 
		* @return
		*/
		private function loadCurrentGroupChat($userId,$load){
			try{
				$rs = mysql_query("select group_id from group_contacts WHERE parti_id = '$userId'",$this->db);
				$gchat = "";
				if(mysql_num_rows($rs) > 0){
					
					while($row = mysql_fetch_array($rs)){
						$groupId = $row[0]; 
						$con = mysql_fetch_assoc(mysql_query("select * from conversation where sent_to = $groupId and group_chat = 1 order by time desc limit 1",$this->db));
						
						if(!empty($con)){
							$gName = $this->getGroupName($groupId);
							$time = $this->getDayAndTime($con['time']);
							if($con['sent_from'] == $userId){
								$message = "You: ";
							}else{
								$message = $this->getUserName($con['sent_from']).": ";
							} 
							$message .=  substr($con['reply'],0,15)."...";
							$gchat .= "<li data-icon='false'>
						
							<a href='group_chat.html?groupId=".$groupId."&groupName=".$gName."'  class='ui-btn' data-ajax='false' style='margin-left:15px'>
							<div class='ui-grid-a' style='padding:10px 2px '>
								<div class='ui-block-a' style='width:20%'>
										<img src='img/sample.jpg' class='pic-circle-corner'/>
									</div>
								<div class='ui-block-b' style='width:80%'>
									<div class='ui-grid-a'>
										<div class='ui-block-a'>
											".$gName."&nbsp;"." 
										</div>
										<div class='ui-block-b'> 
										</div>
										<div class='ui-block-a' style='font-size:11px'>
											".$message." 
										</div>
										<div class='ui-block-b' style='text-align:right;padding-right:25px;font-size:11px'>
											".$time."
										</div>
									</div> 
								</div> 
							 </div>
							 </a>
							
							</li>";
						}else if($load == 1){
							$gName = $this->getGroupName($groupId);
							$gchat .= "<li data-icon='false'>
						
							<a href='group_chat.html?groupId=".$groupId."&groupName=".$gName."'  class='ui-btn' data-ajax='false' style='margin-left:15px'>
							<div class='ui-grid-a' style='padding:10px 2px '>
								<div class='ui-block-a' style='width:20%'>
										<img src='img/sample.jpg' class='pic-circle-corner'/>
									</div>
								<div class='ui-block-b' style='width:80%'>
									<div class='ui-grid-a'>
										<div class='ui-block-a'>
											".$gName."&nbsp;"." 
										</div>
										<div class='ui-block-b'> 
										</div>
										<div class='ui-block-a' style='font-size:11px'>
											 
										</div>
										<div class='ui-block-b' style='text-align:right;padding-right:25px;font-size:11px'>
											
										</div>
									</div> 
								</div> 
							 </div>
							 </a>
							
							</li>";
						}
						
					}
				}
				
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			
			return $gchat;
		}
		
		private function getGroupDetails($groupId){
			try{ 
				if(!empty($groupId)){
					$rs = mysql_fetch_assoc(mysql_query("select * from group_chat where group_id = $groupId",$this->db));
				}
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			return $rs;
		}
		
		private function getGroupName($groupId){
			try{
				$gName = "";
				if(!empty($groupId)){
					$group = mysql_fetch_assoc(mysql_query("select group_name from group_chat where group_id = $groupId",$this->db)); 
					$gName = $group['group_name'];
				}
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			return $gName;
		}
		
		/**
		* Loads the Friends of the Given UserId
		* 
		* @return
		*/
		public function loadFriendsList(){
			try{
					// Cross validation if the request method is GET else it will return "Not Acceptable" status
				if($this->get_request_method() != "POST"){
					$this->response('',406);
				}
				$userId = $this->_request['userId']; 
			$sql1 = mysql_query("select * from pigeon_users where mobile_no in (select user_id from user_contacts where request_status = 1 and contacts = '$userId') or mobile_no in (select contacts from user_contacts where request_status = 1 and user_id = '$userId' ) order by name", $this->db);
		 
			if(mysql_num_rows($sql1) > 0){ 
				$list = "<div class='row' id='pigeons' style='margin:1px'><ul data-role='listview'  data-filter='true' data-inset='false'>";
				  
				while($rlt  = mysql_fetch_array($sql1)){ 
					$mobile_no = $rlt[1]; 
					$name = $rlt[2];
					$status = "&nbsp;<i class='fa fa-circle offline' style='font-size:10px'></i>";
					if($rlt[6] == 1){
						$status = "&nbsp;<i class='fa fa-circle online' style='font-size:10px'></i>";
					}
					
					$sql = mysql_fetch_assoc(mysql_query("select * from conversation where (sent_to = '$userId' and sent_from = '$mobile_no') or (sent_from = '$userId' and sent_to = '$mobile_no') order by time DESC limit 1", $this->db));
					
					$time = $sql['time'];
					$datetime1 = new DateTime(date("Y-m-d H:i:s"));
					$datetime2 = new DateTime($time);
					$interval = date_diff($datetime1, $datetime2);
					
					if($sql['sent_from'] == $userId){
						$message = "You: ";
					}else{
						$message = $this->getUserName($sql['sent_from']).": ";
					} 
					$message .=  substr($sql['reply'],0,15)."...";
					$time = $this->getDayAndTime($sql['time']);
							
					
					if($userId != $mobile_no){
						$list = $list.""."<li data-icon='false'>
						
						<a href='open_chat.html?id=".$mobile_no."&name=".$name."' value=".$mobile_no." class='ui-btn' data-ajax='false' style='margin-left:15px'>
						<div class='ui-grid-a' style='padding:10px 2px '>
							<div class='ui-block-a' style='width:20%'>
									<img src='img/sample.jpg' class='pic-circle-corner'/>
								</div>
							<div class='ui-block-b' style='width:80%'>
								<div class='ui-grid-a'>
									<div class='ui-block-a'>
										".$name."&nbsp;".$status." 
									</div>
									<div class='ui-block-b'> 
									</div>
									<div class='ui-block-a' style='font-size:11px'>
										".$message." 
									</div>
									<div class='ui-block-b' style='text-align:right;padding-right:25px;font-size:11px'>
										".$time."
									</div>
								</div> 
							</div> 
						 </div>
						 </a>
						
						</li>"; 
					} 
				}
				$list = $list."".$this->loadCurrentGroupChat($userId,1)."</ul></div>";
				// If success everythig is good send header as "OK" and return list of pigeon_users in JSON format 
				$this->http_response($list, 200);
			}else{
					$this->http_response("<div style='color:#aaa;text-align:center'>You have not chatted with any pigeon</div", 200);
				}
			 
				$this->http_response(0, 200);	// If no records "No Content" status
				
			}catch(Exception $ex){
				$ex->getMessage();
			}
		}
		
		function uploadImage(){ 
				if($this->get_request_method() != "POST"){
					$this->response('',406);
				}  
				$message_type = 0;
				$timestamp = date('Y_m_d_G_i_s');
				$userId = $_POST['userId'];
				$fileName = $userId."_".$timestamp.".jpg";  
				if(!empty($userId)){
					$from = $userId;
					$to = $_POST['toUser'];
					$message = $fileName;
					$message_type = $_POST['type'];
					$server_addr = $_SERVER['SERVER_ADDR']; 
					$now_time = $timestamp;
					if(!empty($message_type) and strcmp($message_type,"single") == 0){
						$message_type = 0;//For one to one chat
					}else if(!empty($message_type) and strcmp($message_type,"group") == 0){
						$message_type = 1;//For One to Many chat
					}else{
						$message_type = null;
					}
					 
					
					
				move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/images/".$fileName);	
					 
						if(!empty($from) and !empty($to) and !empty($message)){   
								mysql_query("insert into conversation values(default,'$from','$to','$server_addr','$now_time','$message',$message_type,0)",$this->db); 
								echo "insert into conversation values(default,'$from','$to','$server_addr','$now_time','$message',$message_type)";
								$chat_history = $this->loadChatHistory($from,$to); 
								$this->http_response($chat_history,200); 				 
					}  
			  }
			 
			
		}
		private function uploadAudio(){
			try{
				if($this->get_request_method() != "POST"){
					$this->response('',406);
				} 
				$message_type = 0;
				$timestamp = date('Y_m_d_G_i_s');
				$userId = $_POST['userId'];
				$fileName = $userId."_".$timestamp.".mp3"; 
				print_r($_FILES);
				if(!empty($userId)){
					$from = $userId;
					$to = $_POST['toUser'];
					$message = $fileName;
					$message_type = $_POST['type'];
					$server_addr = $_SERVER['SERVER_ADDR']; 
					$now_time = $timestamp;
					if(!empty($message_type) and strcmp($message_type,"single") == 0){
						$message_type = 0;//For one to one chat
					}else if(!empty($message_type) and strcmp($message_type,"group") == 0){
						$message_type = 1;//For One to Many chat
					}else{
						$message_type = null;
					}
				move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/audios/".$fileName);				if(!empty($from) and !empty($to) and !empty($message)){   
								mysql_query("insert into conversation values(default,'$from','$to','$server_addr','$now_time','$message',$message_type,0)",$this->db); 
								echo "insert into conversation values(default,'$from','$to','$server_addr','$now_time','$message',$message_type,0)";
								$chat_history = $this->loadChatHistory($from,$to); 
								$this->http_response($chat_history,200); 				 
					} 
				}
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			$this->http_response("succsess php", 200);
		}
		private function uploadVideo(){
			try{
				if($this->get_request_method() != "POST"){
					$this->response('',406);
				} 
				#print_r($_FILES);
				#$new_image_name = "namethisimage.jpg";
				print_r($_FILES);
				move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/videos/videofile.mp4");
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
			$this->http_response("succsess php", 200);
		}
		
		
		
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi(); 
	
?>