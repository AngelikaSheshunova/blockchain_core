<?php

$m = new MongoClient();

$db = $m->vchain;

$collection = $db->identities;

$collection->remove();


$db = $m->demo_verifier;

$collection = $db->personas;

$collection->remove();

?>