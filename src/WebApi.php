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
use GuzzleBlox\Exceptions\RobloxException;
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
				throw new RobloxException('Connection to ROBLOX failed.');
			}

			if($e instanceof ConnectException) {
				throw new RobloxException('Connection to ROBLOX failed.');
			}

			throw $e;
		}
	}

	public function fetchAssetThumbnail($asset_id) {
		try {
			$resp = $this->http('GET', "https://www.roblox.com/thumbnail/asset?assetId=$asset_id&thumbnailFormatId=254&width=420&height=420");
			$html = $resp->getBody();
			preg_match("/class='' src='(.*)' \/>/", $html, $matches);
    		return $matches[1];
		}

		catch(Exception $e) 
		{
			if($e instanceof RequestException) {
				throw new RobloxException('Connection to ROBLOX failed.');
			}

			if($e instanceof ConnectException) {
				throw new RobloxException('Connection to ROBLOX failed.');
			}

			throw $e;
		} 
	}

}

?>