<?php
/**
* This is file used to send notification messages when a new message is arrived
*/
 header("Access-Control-Allow-Origin: *");
ini_set('date.timezone','Asia/Calcutta');
$dbCon = dbConnect(); 
 
function getURL()
{
			return sprintf("%s://%s%s",isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']);
}
		
//Db Connection
function dbConnect(){
			try{
				if (strpos(getURL(),'http://localhost') !== false)
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
				$db = mysql_connect($DB_SERVER,$DB_USER,$DB_PASSWORD);
				if($db)
					mysql_select_db($DB,$db);
				return $db;
			}catch(Exception $ex){
				echo "Warning Message:".$ex->getMessage();
			}
			
}

/**
* Returns the userName
* @param  $userId
* 
* @return
*/
function getUserName($userId,$dbCon){
	$name = "";
	try{
		$result = mysql_query("select name from pigeon_users where mobile_no = '$userId' limit 1",$dbCon);
		
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
		
		
/**
* This function retrieves the registration id from the app
* 
* @return
*/
function setNotificationDeatils($dbCon){ 
	try{
		
		$notify = mysql_query("SELECT c.sent_from,c.sent_to,c.reply,p.registration_id FROM  conversation c  left join  pigeon_users p on c.sent_to = p.mobile_no  where c.group_chat != 1 and c.notification = 0 group by c.sent_to order by c.time",$dbCon);
		if(mysql_num_rows($notify) > 0){
			while($row = mysql_fetch_array($notify)){
				$to = $row[3]; //registration ID of the device
				$title = "From:".getUserName($row[0],$dbCon);
				$message = $row[2];
				sendPushNotification($to,$title,$message);
				
				mysql_query("update conversation set notification = 1 where sent_from = '$row[0]' and sent_to = '$row[1]'",$dbCon);
				
			}
		}  
	}catch(Exception $ex){
		echo $ex->getMessage();
	} 
}


function sendPushNotification($to,$title,$message)
{
	// API access key from Google API's Console
	// replace API
	//define( 'API_ACCESS_KEY', 'AIzaSyCtDCehTVdcYJQVQa_tJ6cuUnYI_6uWXKc');
	if (!defined('API_ACCESS_KEY')) define('API_ACCESS_KEY', 'AIzaSyCtDCehTVdcYJQVQa_tJ6cuUnYI_6uWXKc');
	$registrationIds = array($to);
	$msg = array
	(
	'message' => $message,
	'title' => $title,
	'vibrate' => 1,
	'sound' => 'beep.wav'

	// you can also add images, additionalData
	);
	$fields = array
	(
	'registration_ids' => $registrationIds,
	'data' => $msg
	);
	$headers = array
	(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
	);
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://gcm-http.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	echo $result;
}


setNotificationDeatils($dbCon);

?>