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

	public static function create($data, $data_email, $data_phone, $source_id, $ip)
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

		$user_data = array();
		if ($data_email != null && !empty($data_email))
		{
			$user_data["email"] = $data_email;
		}
		if ($data_phone != null && !empty($data_phone))
		{
			$user_data["phone"] = $data_phone;
		}
		if (sizeof($user_data) > 0)
		{
			$data["user_data"] = $user_data;
		}

		$collection->insert($data);

		$data["id"] = (string) $data["_id"];
		unset($data["_id"]);

		return $data;
	}

	private static function formUpdate($data, $root = "")
	{
		$output = array();

		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				$new_root = $key;
				if (strcmp(trim($root), "") != 0)
				{
					$new_root = $root.".".$key;
				}

				$temp = self::formUpdate($data[$key], $new_root);
				if (is_array($temp))
				{
					foreach ($temp as $k => $v)
					{
						$output[$k] = $v;
					}
				}

			} else {
				$root_key = $key;
				if (strcmp(trim($root), "") != 0)
				{
					$root_key = $root.".".$key;
				}

				$output[$root_key] = $value;
			}
		}

		return $output;
	}

	public static function clearVerifications($identity_id, $diff)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$verifications_update = self::formVerificationsUpdate($diff);

		foreach ($verifications_update as $key => $value)
		{
			$collection->update(
				array("_id" => new MongoId($identity_id)),
				array('$set' => array( $key => array() ))
			);
		}

		return true;
	}

	public static function update($identity_id, $update_data, $ip, $source_id)
	{
		$current_time = time();

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$new_history_element = array(
			"time"   => $current_time,
			"action" => "update",
			"fields" => VChainIdentity::getInputFields($update_data),
			"ip"     => $ip,
			"source" => $source_id
		);

		$identity["history"][] = $new_history_element;

		$collection->update(
			array("_id" => new MongoId($identity_id)),
			array(
				'$push' => array(
					"history" => $new_history_element
				)
			)
		);

		$updates = self::formUpdate($update_data);

		foreach ($updates as $key => $value)
		{
			$collection->update(
				array("_id" => new MongoId($identity_id)),
				array('$set' => array( $key => $value ))
			);
		}

		return true;
	}

	public static function recordCheck(&$identity, $data, $diff_fields, $source_id, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->identities;

		$current_time = time();

		$new_history_element = array(
			"time"   => $current_time,
			"action" => "check",
			"fields" => VChainIdentity::getInputFields($data),
			"diff_fields" => $diff_fields,
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

	public static function recordUsage(&$identity, $data, $source_id, $using_cause, $ip)
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
