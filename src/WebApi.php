<?php

namespace GuzzleBlox;

/*
 * This file is part of the GuzzleBlox package.
 *
 * This is meant as a wrapper for my NodeJS package for speed reasons,
 * and a wrapper for various single-request ROBLOX endpoints.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class WebApi {

	private $http;

	public function __construct() 
	{
		$this->http = new Client([
			'base_uri' => 'http://localhost:8080/'
		]);
	}

	public function getInventory(int $roblox_user_id) 
	{
		try {
			$resp = $http->request('GET', "inventory/$roblox_user_id");
			$json = json_decode($resp->getBody(), true);
		}

		catch(Exception $e) 
		{
			if($e instanceof RequestException) {

			}

			if($e instanceof ConnectException) {

			}

			throw $e;
		}
	}

}

?>