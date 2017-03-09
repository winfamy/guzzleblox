<?php

namespace GuzzleBlox;

/*
 * This file is part of the GuzzleBlox package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Closure;
use InvalidArgumentException;
use GuzzleHttp\Client as Http;
use Exceptions\RobloxException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class RobloxClient {

	private $jar;
	private $proxy;
	private $token;
	private $cookie;
	private $client;
	private $retries;
	private $username;
	private $password;

	public $fetchProxy;

	public function __construct(array $object) 
	{
		if(!isset($object['username']) || !isset($object['password'])) {
			throw new InvalidArgumentException('Username or password missing');
		}

		// Credentials and ROBLOX information
		$this->username = $object['username'];
		$this->password = $object['password'];

		// HTTP information
		$this->jar = new CookieJar(); 
		$this->retries = (isset($object['retries'])) ? $object['retries'] : 2;

		if(isset($object['proxy'])) {
			$this->client = new Http([
				'base_uri' => 'https://roblox.com',
				'proxy' => $object['proxy']
			]);
		} else {
			$this->client = new Http([
				'base_uri' => 'https://roblox.com'
			]);
		}


		if(isset($object['cookie'])) {
			$this->jar->setCookie(
				new SetCookie([
					'Name' 		=> '.ROBLOSECURITY',
					'Value' 	=> $object['cookie'],
					'Domain' 	=> '.roblox.com'
				])
			);
		}


		if(!isset($object['fetchProxy'])) {
			$this->fetchProxy = function() {
				throw new RobloxException('Proxy error.');
			};
		} else if($object['fetchProxy'] instanceof Closure) {
			$this->fetchProxy = $object['fetchProxy'];
		} else {
			throw new InvalidArgumentException('fetchProxy not an instance of Closure');
		}
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getCookie() {
		return $this->cookie;
	}

	public function getToken() {
		return $this->token;
	}

	public function getProxy() {
		return $this->proxy;
	}

	public function __toString() {
		return __CLASS__ . ":[$this->username]";
	}

	// ------------------------------------
	//
	// Start of ROBLOX site functions
	//
	// ------------------------------------

	public function fetchToken() {}


	// Trade functions

	public function fetchTrades($type = 'inbound', $roblox_user_id = null, $index = null) {}

	public function executeTrade() {}

	public function sendTrade() {}

	// Group functions

	public function fetchJoinRequests() {}

	public function acceptJoinRequest() {}

	public function groupPayout() {}

	public function fetchGroupFunds() {}

	// Account functions

	public function fetchSettings() {}

	public function fetchBcExpiry() {}




}

?>