<?php

class easy_mailinator

{
	private $token;
	private $api_endpoint = "https://api.mailinator.com/api/";
	private $mail_count = 0;
	private $saved_mail_count = 0;
	private $private_mail_count = 0;
	private $private_domain = false;
    
	public function __construct($token = null, $private_domain = false)
	{
		if (empty(trim($token)))
		{
			throw new Exception('You must set your Mailinator API key. For details see: https://www.mailinator.com/apidocs.jsp');
		}

		$this->token = $token;
		if (is_bool($private_domain))
		{
			$this->private_domain = $private_domain;
		}
	}

	/**
	 * Performs API call operations to the Mailinator API server
	 * @param  string $method API method
	 * @param  string $params API URL parameters
	 * @return string Server response string
	 */
	private	function web($method, $params)
	{
		try
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $this->api_endpoint . $method . $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$source = curl_exec($ch);
			return $source;
		}

		catch(Exception $e)
		{
            throw $e->getMessage();
		}
	}

	/**
	 * Returns the amount of emails in the specified inbox
	 * @return integer
	 */
	public function get_mail_count()
	{
		return $this->mail_count;
	}

	/**
	 * Returns the amount of emails in the saved inbox
	 * @return integer
	 */
	public function get_saved_count()
	{
		return $this->saved_mail_count;
	}

	/**
	 * Returns the amount of emails in the private inbox
	 * @return integer
	 */
	public function get_private_count()
	{
		return $this->saved_mail_count;
	}

	/**
	 * Returns the saved inbox emails as a json array object
	 * @return array
	 */
	public function saved()
	{
		$params = http_build_query(array(
			'token' => $this->token,
			'private_domain' => $this->private_domain
		));
		$emails = $this->web('inbox?', $params);
		if (!is_array($emails)) throw new Exception('Error parsing data returned from Mailinator.');
		$this->saved_mail_count = count($emails);
		return $emails;
	}

	/**
	 * Returns the private domain inbox emails as a json array object
	 * @return array
	 */
	public function private_domain()
	{
		if ($this->private_domain == true)
		{
			$params = http_build_query(array(
				'token' => $this->token,
				'private_domain' => $this->private_domain
			));
			$emails = $this->web('inbox?', $params);
			if (!is_array($emails)) throw new Exception('Error parsing data returned from Mailinator.');
			$this->private_mail_count = count($emails);
			return $emails;
		}
	}

	/**
	 * Returns the emails for the specified email name as a json array object
	 * @param  string $email Name of email box to check
	 * @return array
	 */
	public function inbox($email)
	{
		$params = http_build_query(array(
			'to' => $email,
			'token' => $this->token,
			'private_domain' => $this->private_domain
		));
		$emails = $this->web('inbox?', $params);
		if (!is_array($emails)) throw new Exception('Error parsing data returned from Mailinator.');
		$this->mail_count = count($emails);
		return $emails;
	}

	/**
	 * Returns the specified email message
	 * @param string $msgID
	 */
	public function get($msg_id)
	{
		$params = http_build_query(array(
			'id' => $msg_id,
			'token' => $this->token,
			'private_domain' => $this->private_domain
		));
		$email = $this->web('email?', $params);
		if (!is_array($email)) throw new Exception('Error parsing data returned from Mailinator.');
		echo $email;
	}

	/**
	 * Deletes the specified email message
	 * @param  string $msgID
	 * @return boolean
	 */
	public function delete($msg_id)
	{
		$params = http_build_query(array(
			'id' => $msg_id,
			'token' => $this->token,
			'private_domain' => $this->private_domain
		));
		$status = json_decode($this->web('delete?', $params));
		if (!is_array($status)) throw new Exception('Error parsing data returned from Mailinator.');
		if ($status->status == 'ok')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}