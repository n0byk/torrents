<?php

/*
	The important thing to realize is that the config file should be included in every
	page of your project (or at least any page you want access to these settings).
	This allows you to confidently use these settings throughout a big project because
	if something changes such as your DB credentials or a path to a specific resource
	you'll only need to update it here.
*/

$config = array(
	"db" => array(
		"db1" => array(
			"dbname" => "database1",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		),
		"db2" => array(
			"dbname" => "database2",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		)
	),
	"urls" => array(
		"baseUrl" => "http://example.com"
	),
	"paths" => array(
		"resources" => "/path/to/resources",
		"images" => array(
			"content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
			"layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
		)
	)
);
print_r($config);
/*
	I will usually place these next things in a bootstrap file or some type of environment
	setup file, but they work just as well in your config file if it's in php (some alternatives
	to php are xml or ini files).
*/

/*
	Creating constants for heavily used paths makes things a lot easier.
	ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
	
defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

/*
	Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>