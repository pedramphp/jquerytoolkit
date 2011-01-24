<?php

class CurlUtil
{

	
	private static $status = 0;
	
	
	
	/**
	 * Returns the Status Code of the last requested Curl call
	 * @return integer
	 *
	 */
	public function GetLastRequestStatus() { return self::$status; }


	// Fetch a URL using curl. Recognized options...
	// timeout:     Timeout in seconds (integer)
	// post_array: 	Variables to be sent via POST.
	// auth_string: For https connection.  Should be of the form "username:password"
	// headers:	Additional headers to set via CURLOPT_HTTPHEADER
	// follow: set to true to set CURLOPT_FOLLOWLOCATION
	function FetchURL($url, $options = array())
	{
		$timeout = 20;
		if (isset($options['timeout'])) { $timeout = $options['timeout']; }

		$curl_handler = curl_init();

		if (!empty($options['get_fields']))
		{
			$url_parameters = array();
			foreach($options['get_fields'] as $field => $value)
			{ $url_parameters[] = urlencode($field) . '=' . urlencode($value); }

			$url .= '?' . join('&', $url_parameters);
		}

		curl_setopt($curl_handler, CURLOPT_URL, $url);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, $timeout);
		//curl_setopt($curl_handler, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		if (!empty($options['post_array']))
		{
			curl_setopt($curl_handler, CURLOPT_POST, 1);
			curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $options['post_array']);
		}

		if (!empty($options['auth_string']))
		{
			curl_setopt($curl_handler, CURLOPT_USERPWD, $auth_string);
			curl_setopt($curl_handler, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		if (!empty($options['follow']))
		{
			curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, true);
		}

		if (!empty($options['headers'])) { curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers); }

		$response = curl_exec($curl_handler);
		self::$status = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);
		curl_close($curl_handler);

		return $response;
	}

	/**
	 * Fetch a page using curl.
	 * 
	 * @param string $url
	 * @param array	 $postData The data to send to the URL
	 * @param array	 $options
	 * @return string
	 * @see CurlUtil::FetchURL
	 */
	function PostURL($url, $postData, $options = array())
	{
		//		$postString = '';
		//		foreach ($postData as $key => $value) { $postString .= "$key=$value&"; }

//		
//		$options['post_string'] = http_build_query($postData);
		$options['post_array'] = $postData;
		return self::FetchURL($url, $options);
		
	}
}

?>