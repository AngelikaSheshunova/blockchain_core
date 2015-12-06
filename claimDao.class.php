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

		$data["status"] = CLAIM_STATUS_UNRESOLVED;

		$data["source_id"] = $source_id;
		$data["ip"] = $ip;

		$collection->insert($data);

		$data["id"] = (string) $data["_id"];
		unset($data["_id"]);

		return $data;
	}

	public static function setResolved($claim_id, $resolution, $source_id, $ip)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->claims;

		$current_time = time();

		$collection->update(
			array("_id" => new MongoId($claim_id)),
			array(
				'$set' => array(
					"status" => CLAIM_STATUS_RESOLVED
				)
			)
		);

		$collection->update(
			array("_id" => new MongoId($claim_id)),
			array(
				'$set' => array(
					"resolution" => array(
						"type"      => $resolution,
						"source_id" => $source_id,
						"ip"        => $ip,
						"time"      => $current_time
					)
				)
			)
		);

		return true;
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
