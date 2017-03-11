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
use GuzzleHttp\RedirectMiddleware;
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

	public function login() {
		$resp = $this->client->request('POST', 'https://www.roblox.com/newlogin', [
			'form_params' => [
			'Username' => $this->username,
			'Password' => $this->password,
			'ReturnUrl' => ''
		],
			'allow_redirects' => [
				'track_redirects' => true
			],
			'cookies' => $this->jar
		]);

		$history = $resp->getHeader(RedirectMiddleware::HISTORY_HEADER);
		if(isset($history) && isset($history[0])) {
			if($history[0] == "https://www.roblox.com/home?nl=true" || $history[0] == "https://www.roblox.com/home") {
				if(isset($this->jar->toArray()[2]) && $this->jar->toArray()[2]['Name'] == ".ROBLOSECURITY") {
					$this->cookie = $this->jar->toArray()[2]['Value'];
					$this->token = $this->parseTokenFromHtml($resp->getBody());
					echo $this->token;

					return true;
				}
			}
		}

		return false;
	}

	public function fetchSettings() {}

	public function fetchBcExpiry() {}

	// Helper functions

	public function parseTokenFromHtml($html) {
		return (preg_match("/\.setToken\('(.*?)'\);/", $html, $matches) === 1) ? $matches[1] : false;
	}


}

?>