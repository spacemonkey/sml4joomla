<?php

class SMLConfig
{

	var $mongo_hosts = array('localhost');  // array of hosts for your database
	var $mongo_db = 'test';					// name of your database
	var $mongo_persistent = false;			// persistent connections
	var $mongo_paired = false;				// OBSOLETE: for mirrored pair servers

}