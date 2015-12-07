<?php

class VChainUserDao
{

	public static function get($params = array())
	{
		$output = array();

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->users;

		$cursor = $collection->find($params);

		foreach ($cursor as $document)
		{
			$document["id"] = (string) $document["_id"];
			unset($document["_id"]);

			$output[] = $document;
		}

		return $output;
	}

	public static function create($identity_id, $email, $phone)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->users;

		$current_time = time();

		$data = array();

		$data["created"] = $current_time;
		$data["identity_id"] = $identity_id;
		$data["email"] = $email;
		$data["phone"] = $phone;
		$data["status"] = USER_STATUS_NOT_ACTIVATED;

		$collection->insert($data);

		$data["id"] = (string) $data["_id"];
		unset($data["_id"]);

		return $data;
	}

	public static function update($user_id, $params)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->users;

		$collection->update(
			array( "_id" => new MongoId($user_id) ),
			array(
				'$set' => $params
			)
		);

		return true;
	}

}

?>
