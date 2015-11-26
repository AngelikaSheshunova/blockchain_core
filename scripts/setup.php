<?php

require_once("../vchain.inc.php");

$m = new MongoClient();

$db = $m->vchain;

// SOURCES

$collection = $db->sources;

$collection->remove();

$collection->insert(array(
	"key"        => VCHAIN_SOURCE_KEY_DEMO_VERIFIER,
	"ips"        => array("127.0.0.1", "146.185.180.65"),
	"public_key" => VCHAIN_URL_DEMO_VERIFIER."vchain-public.key",
	"level"      => 3,
	"name"       => "vchain demo verifier"
));

$collection->insert(array(
	"key"        => VCHAIN_SOURCE_KEY,
	"ips"        => array("127.0.0.1", "146.185.180.65"),
	"public_key" => VCHAIN_URL_VCHAIN."vchain-public.key",
	"level"      => 1,
	"name"       => "vchain"
));

$collection->insert(array(
	"key"        => VCHAIN_SOURCE_KEY_DEMO_ADMIN,
	"ips"        => array("127.0.0.1", "146.185.180.65"),
	"public_key" => VCHAIN_URL_DEMO_ADMIN."vchain-public.key",
	"level"      => 0,
	"name"       => "vchain demo admin"
));




?>
