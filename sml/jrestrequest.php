<?php 
/**
 * @package Spacemonkeylabs
 * @author Mitch Pirtle <mitch@spacemonkeylabs.com>
 * @note This class is very much a work in progress
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @copyright Spacemonkey Labs, LLC. All rights reserved.
 * 
 * This class is a descendant of code written for integrating
 * MongoDB and Joomla, contributed by Colin Kroll.
 */
class JRestRequest {
	
	private $arguments = array();
	
	public function __construct( ) { }
	
	public function setArgument( $key, $value ) {
		if ( !isset( $key ) || strlen( $key ) == 0 ) {
			return;
		} elseif ( !isset( $value ) || strlen( $value ) == 0 ) {
			return;
		}
		$this->arguments["$key"] = $value;
		return $this;
	}
	
	public function clearAllArguments( ) {
		$this->arguments = array();
		return $this;
	}
	
	private function request( $curl ) {
 		curl_setopt($curl, CURLOPT_FOLLOWLOCATION  ,1);
 		curl_setopt($curl, CURLOPT_HEADER      ,0);
 		curl_setopt($curl, CURLOPT_RETURNTRANSFER  ,1);
		
 		$response = curl_exec( $curl );
		if ( $response === false ) {
			throw new Exception( 'The request returned no response' );
		}
		return $response;
	}
	
	public function get( $url ) {
		if ( !$this->isValidUrl( $url ) ) {
			throw new Exception ( 'The rest endpoint ' . $url . ' is not valid' );
		}
		$curl = curl_init( $url );	
		curl_setopt($curl, CURLOPT_URL, $url . '?' . $this->createQueryString() );
 		$content = $this->request( $curl );
 		return $content;
	}
	
	public function post( $url ) {
		if ( !$this->isValidUrl( $url ) ) {
			throw new Exception ( 'The rest endpoint ' . $url . ' is not valid' );
		}
		$curl = curl_init( $url );
		
		curl_setopt($curl, CURLOPT_URL, $url );
		curl_setopt($curl, CURLOPT_POST, 1);
 		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->createQueryString( ) );
		
 		$content = $this->request( $curl );
 		return $content;
	}
	
	private function createQueryString( ) {
		$query = '';
		if ( count( $this->arguments ) > 0 ) {
			foreach ( $this->arguments as $key => $value ) {
				$query .= $key . '=' . $value . '&';
			}
			$query = substr( $query, 0, -1 );
		}
		return $query;
	}
	
	private function isValidUrl( $url ) {
		if ( ! preg_match('/http:\/\/.*/', $url ) ) {
			return false;
		}
		return true;
	}
}