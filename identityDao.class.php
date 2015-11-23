<?php

class VChainIdentityDao
{

	public static function search($lookup)
	{
		$output = array();

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$cursor = $collection->find($lookup);

		foreach ($cursor as $document)
		{
			$document["id"] = (string) $document["_id"];
			unset($document["_id"]);

			$output[] = $document;
		}

		return $output;
	}

	public static function get($identity_id)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$document = $collection->findOne(array('_id' => new MongoId($identity_id)));

		$document["id"] = (string) $document["_id"];
		unset($document["_id"]);

		return $document;
	}

	public static function create($data, $source_id, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$current_time = time();

		$data["created"] = $current_time;

		$data["history"] = array();
		$data["history"][] = array(
			"time"   => $current_time,
			"action" => "created",
			"ip"     => $ip,
			"source" => $source_id
		);

		$data["verifications"] = VChainVerification::getDefaultVerificationsTemplate($data);

		$collection->insert($data);

		$data["id"] = (string) $data["_id"];
		unset($data["_id"]);

		return $data;
	}

	public static function record(&$identity, $data, $source_id, $using_cause, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$current_time = time();

		$new_history_element = array(
			"time"   => $current_time,
			"action" => "used",
			"cause"  => $using_cause,
			"fields" => VChainIdentity::getInputFields($data),
			"ip"     => $ip,
			"source" => $source_id
		);

		$identity["history"][] = $new_history_element;

		$collection->update(
			array("_id" => new MongoId($identity["id"])),
			array(
				'$push' => array(
					"history" => $new_history_element
				)
			)
		);

		return true;
	}

	private static function formVerificationsUpdate($verifications, $root = "verifications")
	{
		$output = array();

		foreach ($verifications as $key => $value)
		{
			if (is_array($value))
			{
				$temp = self::formVerificationsUpdate($verifications[$key], $root.".".$key);
				if (is_array($temp))
				{
					foreach ($temp as $k => $v)
					{
						$output[$k] = $v;
					}
				}

			} else {
				$output[$root.".".$key] = $value;
			}
		}

		return $output;
	}

	public static function saveVerifications(&$identity, $verifications, $source_id, $ip)
	{
		$current_time = time();

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$new_history_element = array(
			"time"   => $current_time,
			"action" => "verification",
			"fields" => VChainIdentity::getInputFields($verifications),
			"ip"     => $ip,
			"source" => $source_id
		);

		$identity["history"][] = $new_history_element;

		$collection->update(
			array("_id" => new MongoId($identity["id"])),
			array(
				'$push' => array(
					"history" => $new_history_element
				)
			)
		);

		$verifications_update = self::formVerificationsUpdate($verifications);

		foreach ($verifications_update as $key => $value)
		{
			$verifications_update[$key] = array(
				"source_id" => $source_id,
				"time"      => $current_time,
				"signature" => $value
			);
		}

		foreach ($verifications_update as $key => $value)
		{
			$collection->update(
				array("_id" => new MongoId($identity["id"])),
				array('$push' => array($key => $value))
			);
		}

		return true;
	}

}

?>
