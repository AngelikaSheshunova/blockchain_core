<?php

require_once("../vchain.inc.php");
require_once("clear.inc.php");

$m = new MongoClient();


$db = $m->demo_verifier;

$collection = $db->personas;

$collection->remove();
$collection = $db->personas;
foreach ($personas as $persona_raw)
{
	$persona_obj = json_decode($persona_raw, true);
	$persona_obj["_id"] = new MongoId($persona_obj["_id"]);
	$collection->insert($persona_obj);
}


$db = $m->vchain;

$collection = $db->identities;
$collection->remove();
foreach ($identities as $identity_raw)
{
	$identity_obj = json_decode($identity_raw, true);
	$identity_obj["_id"] = new MongoId($identity_obj["_id"]);
	$collection->insert($identity_obj);
}

$collection = $db->claims;
$collection->remove();
foreach ($claims as $claim_raw)
{
	$claim_obj = json_decode($claim_raw, true);
	$claim_obj["_id"] = new MongoId($claim_obj["_id"]);
	$collection->insert($claim_obj);
}

$collection = $db->users;
$collection->remove();
foreach ($users as $user_raw)
{
	$user_obj = json_decode($user_raw, true);
	$collection->insert($user_obj);
}

?>
