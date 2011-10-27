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
class JMongo {

	private static $instance;

	private function __construct( ) { }

	/**
	 * Creates a mongo connection and connects.
	 *
	 * @throws MongoConnectionException, Exception
	 * @return MongoDB database object
	 */
	private function createInstance( ) {

		//Pull these from a config file
		jimport('sml.smlconfig');
		$conf = new SMLConfig();
		$serverList = $conf->mongo_hosts;
		$database = $conf->mongo_db;
		$persistent = $conf->mongo_persistent;
		$paired = $conf->mongo_paired;
		//End config entries

		if ( count( $serverList ) > 2 ) {
			throw new Exception( "Connection can be established to 2 or fewer instances only." );
		}
		if ( count ($serverList ) == 2 ) {
			$paired = true;
			$servers = implode( ',', $serverList );
		} else {
			$servers = $serverList[0];
		}
		try {
			$m = new Mongo( $servers, true, $persistent, $paired );
			$db = $m->selectDB( $database );
		} catch ( Exception $e ) {
			//we should swallow this, and likely put a site down message up..
			die( '<pre>' . $e->getTraceAsString() );
		}
		return $db;
	}

	/**
	 * Returns the connected mongo instance if it exists, otherwise
	 * creates it.
	 *
	 * @param $serverList
	 * @param $persistent
	 * @return unknown_type
	 */
	public static function getInstance(  ) {
		if ( ! is_object( self::$instance ) ) {
			self::$instance = self::createInstance( );
		}
		return self::$instance;
	}
}
