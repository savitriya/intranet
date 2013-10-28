<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
$to=array();
$to[0]['email']="maulik@savitriya.com";
$to[0]['name']="Maulik Shah";
$from=array();
$from['email']="maulik@savitriya.com";
$from['name']="IntraNet";
return array(
    // ...
	'login_summary_recipients'=>array(
		$to	
	),
	'mail_sender'=>array($from),
);
