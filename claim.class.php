<?php

class VChainClaim
{

	public static function generateForNewIdentity($new_identity, $comparsion_identities, $possible_mistakes)
	{
		$claim = array();

		$claim["affected_identities"] = array();

		$comparsion_identities_ids = array();
		foreach ($comparsion_identities as $comparsion_identity)
		{
			$claim["affected_identities"][] = array(
				"identity_id" => $comparsion_identity["id"],
				"type"        => CLAIM_TYPE_POSSIBLE_CHANGES,
				"diff"        => VChainIdentity::getOnlyRequestedFields($possible_mistakes, $new_identity),
				"refers_to"   => array( $new_identity["id"] )
			);

			$comparsion_identities_ids[] = $comparsion_identity["id"];
		}

		$claim["affected_identities"][] = array(
			"identity_id" => $new_identity["id"],
			"type"        => CLAIM_TYPE_POSSIBLE_DUBLICATE,
			"refers_to"   => $comparsion_identities_ids
		);

		return $claim;
	}

	public static function generate($identity_id, $input, $possible_mistakes)
	{
		$claim = array();

		$claim["affected_identities"] = array();

		$claim["affected_identities"][] = array(
			"identity_id" => $identity_id,
			"type"        => CLAIM_TYPE_POSSIBLE_CHANGES,
			"diff"        => VChainIdentity::getOnlyRequestedFields($possible_mistakes, $input),
			"refers_to"   => array( $identity_id )
		);

		return $claim;
	}

}

?>
