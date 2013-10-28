<?php
namespace IntranetUtils;

use Zend\Mail\Exception\RuntimeException;

use Zend\Di\ServiceLocator;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Config\Reader\Ini;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Common
{
	/**
	 * USe: To convert GMT timezone to local(other) timezone
	 * @param datetime $gmttime :date in Y-m-d H:i:s format
	 * @param string $timezoneRequired :Timezone name to which you want to convert $gmttime to localtime
	 * @param string $format :required date format Ex.d-m-y H:i:s
	 * @return string
	 */
	function ConvertGMTToLocalTimezone($gmttime,$timezoneRequired,$format="d/m/Y H:i:s")
	{
		$system_timezone = date_default_timezone_get();

		date_default_timezone_set("GMT");
		$gmt = date("Y-m-d H:i:s");

		$local_timezone = $timezoneRequired;
		date_default_timezone_set($local_timezone);
		$local = date("Y-m-d H:i:s");
		
		date_default_timezone_set($system_timezone);
		$diff = (strtotime($local) - strtotime($gmt));		
		//$gmttt= (strtotime($gmt));
		$localtim= (strtotime($gmttime));
		$loc = $diff + $localtim;


		return date($format, $loc);
	}
	/**
	 * Use: To convert local(other) timezone in GMT timezone
	 * @param datetime $localtime : date in Y-m-d H:i:s format
	 * @param string $timezoneRequired :Timezone name from which you want to convert $localtime to GMT(source date timezone name)
	 * @param string $format : required date format Ex.d-m-y H:i:s
	 * @return string
	 */

	function ConvertLocalTimezoneToGMT($localtime,$clientTimezone,$format="d/m/Y H:i:s")
	{
		$system_timezone = date_default_timezone_get();
		date_default_timezone_set("GMT");
		$gmt = date("Y-m-d H:i:s");
		date_default_timezone_set($clientTimezone);
		$local = date("Y-m-d H:i:s");
		date_default_timezone_set($system_timezone);
		$diff = (strtotime($local) - strtotime($gmt));
		$localtim= (strtotime($localtime));
		$loc = $localtim - $diff;
		return  date($format,$loc);
	}
	/**
	 * Use: To calculate time difference b/w two datetime
	 * @param datetime $time1 :maxdate
	 * @param datetime $time2 :mindate
	 * @param string $format :format of time diff Ex- H:i:s
	 * @return string
	 */
	function  getTimeDiff($time1,$time2,$format="H:i:s"){
		$diff = (strtotime($time1) - strtotime($time2));
		return  date($format,$diff);
	}	

	public function sendEmail($subject,$message,$to,$from,$cc=NULL,$bcc=NULL,$replyto=NULL) {

		$reader = new Ini();
		$data =$reader->fromFile(__DIR__."/../../../config/application.ini");
		try {
			$mail = new Mail\Message();
			
			// Setup SMTP transport using LOGIN authentication
			$sendmail=(Array)$data['sendmail'];
				
			$transport = new SmtpTransport();
			$options   = new SmtpOptions($sendmail);
            $transport->setOptions($options);
			
			$htmlPart = new MimePart($message);
			$htmlPart->type = "text/html";
			$body = new MimeMessage();
			$body->setParts(array($htmlPart));
			$mail->setBody($body);
			if(isset($from) && $from!=""){
				$mail->setFrom($data['mail_sender']['from'], $from);
			}
			else{
				$mail->setFrom($data['mail_sender']['from'], $data['mail_sender']['name']);
			}
			if(is_object($to)){
				$mail->addTo($to->email,$to->name);
			}
			else if(is_array($to)){
 				for($i=0;$i<sizeof($to);$i++){
					if(isset($to[$i]['email']) && isset($to[$i]['name']) && is_array($to[$i]) ){		
						$mail->addTo($to[$i]['email'],$to[$i]['name']);
					}else if(isset($to[$i]['email'])){
						
						$mail->addTo($to[$i]['email'],null);
					}
					else{
						$mail->addTo($to[$i],null);
					}
				}
			}
			else if(is_string($to)){
				$mail->addTo($to,null);
			}
			if (isset($cc) && $cc!=""){
				if(is_object($cc)){
					$mail->addCC($cc->email,$cc->name);
				}
				else if(is_array($cc)){
					for($i=0;$i<sizeof($cc);$i++){
						if(isset($cc[$i]['email']) && isset($cc[$i]['name']) ){
						//if(array_key_exists($cc[$i]['email'],$cc) && array_key_exists($cc[$i]['name'],$cc)){
							$mail->addCc($cc[$i]['email'],$cc[$i]['name']);
						}else if(isset($cc[$i]['email'])){
							$mail->addCc($cc[$i]['email'],null);
						}
						else{
							$mail->addCc($cc[$i],null);
						}
					}
				}
				else if(is_string($cc)){
					$mail->addCc($cc,null);
				}
			}
			if (isset($bcc) && $bcc!=""){
				if(is_object($bcc)){
					$mail->addBcc($bcc->email,$bcc->name);
				}
				else if(is_array($bcc)){
					for($i=0;$i<count($bcc);$i++){
						if(isset($bcc[$i]['email']) && isset($bcc[$i]['name'])){
							$mail->addBcc($bcc[$i]['email'],$bcc[$i]['name']);
						}
						else if(isset($bcc[$i]['email'])){
							$mail->addBcc($bcc[$i]['email'],null);
						}
						else{
							$mail->addBcc($bcc[$i],null);
						}
					}
				}
				else if(is_string($bcc)){
					$mail->addBcc($bcc,null);
				}
			}
			if(isset($replyto)){
				$mail->setReplyTo($replyto);
			}
			$mail->setSubject($subject);
			//$transport = new Mail\Transport\Sendmail();
			if($mail->isValid()){
				$mail->setEncoding('utf-8');
				try{
					$transport->send($mail);
				}
				catch (RuntimeException $e){
					echo $e->getTraceAsString();exit;
					return false;
				}
				return true;
			}
		} catch(Exception $e) {
			// TODO add zend logger code here
			//echo $e->getMessage();
		}
		return false;
	}

	/**
	 * Use: To date d/m/y H:i:s format convert into other date format EX Y-m-d H:i:s
	 * @param datetime $fdate :date in d/m/y H:i:s format
	 * @param string $format : date format u need Ex. d-m-Y h:i:s
	 * @return NULL|string
	 */
	function Timeformat($fdate,$format)
	{
		if($fdate==""){
			return null;
		}
		$fdate=explode("/",$fdate);
		$hour=0;
		$min=0;
		$sec=0;
		$day=$fdate[0];
		$month=$fdate[1];
		$yearWithTime=explode(" ",$fdate[2]);
		$year=$yearWithTime[0];

		if(!checkdate ($month,$day,$year)){
			return null;
		}

		if(count($yearWithTime)>1){
			$separatedTime=explode(":",$yearWithTime[1]);
			if(count($separatedTime)==3){
				$min=$separatedTime[1];
				$sec=$separatedTime[2];
				$hour=$separatedTime[0];
			}
			else if(count($separatedTime)==2){
				$min=$separatedTime[1];
				$hour=$separatedTime[0];
			}
			else if(count($separatedTime)==1){
				$hour=$separatedTime[0];
			}
		}
		return  date($format,mktime($hour,$min,$sec,$month,$day,$year));
	}
	/**
	 *
	 * @param date $date
	 * @return boolean
	 */
	function checkdate($date){
		if($date==""){
			return false;
		}
		$date=explode("-", $date);
		if(count($date)>2){
			$year=$date[0];
			$month=$date[1];
			$day=$date[2];
			if (checkdate($month, $day, $year))
			{
				return TRUE;
			}
		}
		return false;
	}

	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip ispass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function validateSpentTime($hour) {
		if (preg_match("/^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$/", $hour)) {
			return true;
		}
		return false;
	}


	function convertSpentTime($hourAndMinInSeconds) {
		$spenTime='';
		$hour=0;
		$remainingMins=0;
		if($hourAndMinInSeconds>=3600){
			$hour=(int)($hourAndMinInSeconds/3600);
			$remainingMins=$hourAndMinInSeconds%(3600*(int)$hour);
			$remainingMins=$remainingMins/60;
			//$remainingMins=round($remainingMins);
		}
		else{
			$remainingMins=$hourAndMinInSeconds/60;
			//$remainingMins=round($remainingMins);
		}
		if($hour<10){
			$hour="0".$hour;
		}
		$remainingMins=round($remainingMins);
		if($remainingMins<10){
			$remainingMins="0".$remainingMins;
		}
		if($hour>0 && $remainingMins>0){
			$spenTime=$hour.":".$remainingMins;
		}
		else if($hour>0){
			$spenTime="$hour:00";
		}
		else{
			$spenTime="00:".$remainingMins;
		}
		return $spenTime;
	}
	/*
	 * this function return the date interval between two dates
	*/
	public function getintervaltime($fromdatetime,$todatetime){

		$to_date=new DateTime($todatetime);
		$from_date=new DateTime($fromdatetime);
		$interval=date_diff($to_date,$from_date);
		$retrunstring="";
		if($interval->y>0){
			if($interval->y>1){
				$retrunstring.=$interval->y." years";
			}
			else{
				$retrunstring.=$interval->y." year";
			}
		}
		else if($interval->m>0){
			if($interval->m>1){
				$retrunstring.=$interval->m." months";
			}
			else{
				$retrunstring.=$interval->m." month";
			}
		}
		else if($interval->d>0){
			if($interval->d>1){
				$retrunstring.=$interval->d." days";
			}
			else{
				$retrunstring.=$interval->d." day";
			}
		}
		else if($interval->h>0){
			if($interval->h>1){
				$retrunstring.=$interval->h." hours";
			}
			else{
				$retrunstring.=$interval->h." hour";
			}
		}
		else if($interval->i>30){
			$retrunstring.=$interval->i." minutes";
		}
		else{
			$retrunstring.= $interval->i." minutes";
		}
		return $retrunstring;
	}

	//set real dates for start and end, otherwise *nix the strtotime() lines.
	//$return 'days' will return days/hours/minutes/seconds.
	//$return 'hours' will return hours/minutes/seconds.
	//$return 'minutes' will return minutes/seconds.
	//$return 'seconds' will return seconds.
	function timeDifference($start,$end,$return='year') {
		//change times to Unix timestamp.
		$start = strtotime($start);
		$end = strtotime($end);
		$time = array();
		if($start > $end) {
			$time['year']=0;
			$time['month']=0;
			$time['days']=0;
			$time['hours']=0;
			$time['minutes']=0;
			return $time;
		}
		//subtract dates
		$difference = $end - $start;
		//calculate time difference.
		switch($return) {
			case 'year':
				$year=floor($difference/31536000);
				$difference=$difference%31536000;
				$time['year']=$year;
			case 'month':
				$month=floor($difference/2628000);
				$difference=$difference%2628000;
				$time['month']=$month;
			case 'days':
				$days = floor($difference/86400);
				$difference = $difference % 86400;
				$time['days']= $days;
			case 'hours':
				$hours = floor($difference/3600);
				$difference = $difference % 3600;
				$time['hours']= $hours;
			case 'minutes':
				$minutes = floor($difference/60);
				$difference = $difference % 60;
				$time['minutes']= $minutes;
		}

		return $time;

	}

	function get_timezone_offset($remote_tz, $origin_tz = null) {
		if($origin_tz === null) {
			if(!is_string($origin_tz = date_default_timezone_get())) {
				return false; // A UTC timestamp was returned -- bail out!
			}
		}
		$origin_dtz = new \DateTimeZone($origin_tz);
		$remote_dtz = new \DateTimeZone($remote_tz);
		$origin_dt = new \DateTime("now", $origin_dtz);
		$remote_dt = new \DateTime("now", $remote_dtz);
		$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
		return $offset;
	}
	
}