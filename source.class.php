<?php

class VChainSource
{

	public static function exists($key)
	{
		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->sources;

 		$cursor = $collection->find(array(
			"key" => $key
		));

 		$finded_count = 0;
 		foreach ($cursor as $document)
 		{
 			$finded_count++;
 		}

		return ($finded_count == 1);
	}

	public static function getByKey($key)
	{
		$output = false;

		$m = new MongoClient();

		$db = $m->vchain;

		$collection = $db->sources;

 		$cursor = $collection->find(array(
			"key" => $key
		));

 		$finded_count = 0;
 		foreach ($cursor as $document)
 		{
 			$output = array(
 				"id"         => (string) $document["_id"],
 				"key"        => $document["key"],
 				"public_key" => $document["public_key"],
 				"name"       => $document["name"]
 			);
 		}

		return $output;
	}

	public static function getPublicKey($key)
	{
		$source = self::getByKey($key);

		if (is_array($source) && sizeof($source) > 0)
		{
			if (isset($source["public_key"]) && !empty($source["public_key"]))
			{
				return file_get_contents($source["public_key"]);
			}
		}

		return false;
	}

}

?>
