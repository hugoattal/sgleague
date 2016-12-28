<?php
class Mail
{
	private $mail;

	public function __construct($dest, $subject, $content, $debug = false)
	{
		$curl_post_data=array(
		'from'    => 'Student Gaming League <noreply@sgnw.fr>',
		'to'      => $dest,
		'subject' => $subject,
		'text'    => $content
		);

		$service_url = 'https://api.mailgun.net/v3/mg.league.sgnw.fr/messages';
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, "api:key-b1699a3f9d3b38ce6f5d9e1ff04ea1f3"); 

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 


		$curl_response = curl_exec($curl);  
		$response = json_decode($curl_response);
		curl_close($curl);

		if ($debug)
		{
			var_dump($response);
		}
	}
}
?>