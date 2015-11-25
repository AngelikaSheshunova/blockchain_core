<?php

class VChainClaimDao
{

	public static function create($data, $source_id, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->claims;

		$current_time = time();

		$data["created"] = $current_time;

		$data["source_id"] = $source_id;
		$data["ip"] = $ip;

		$collection->insert($data);

		$data["id"] = (string) $data["_id"];
		unset($data["_id"]);

		return $data;
	}

	public static function getByIdentityId($identity_id)
	{
		$output = array();

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->claims;

		$cursor = $collection->find(array( "affected_identities.identity_id" => $identity_id ));

		foreach ($cursor as $document)
		{
			$document["id"] = (string) $document["_id"];
			unset($document["_id"]);

			$output[] = $document;
		}

		return $output;
	}

}

?>
