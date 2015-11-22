<?php

class VChainVerification
{

	public static function addVerifications($data, $key, $binary_signature, $verifications_binary_signatures, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);
		unset($data["key"]);
		unset($data["signature"]);
		unset($data["using_cause"]);
		unset($data["ignore_possible_matches"]);

		ksort($data);

		$source = VChainSource::getByKey($key);
		$source_id = false;

		if (is_array($source) && isset($source["id"]) && !empty($source["id"]))
		{
			$source_id = $source["id"];
		}

		if ($source_id === false)
		{
			return array(
				"status"            => "ERROR",
				"error_reason_code" => ERROR_CODE_NOT_AUTHORIZED
			);
		}

		$json_input = json_encode($data);

		$source_public_key = VChainSource::getPublicKey($key);

		$signature_check_result = openssl_verify($json_input, $binary_signature, $source_public_key, OPENSSL_ALGO_SHA512);

		if ($signature_check_result === 1)
		{
			$check_identity_result = VChainIdentity::check($data, $key, $ip);

			if (   is_array($check_identity_result)
				&& isset($check_identity_result["status"])
				&& $check_identity_result["status"] == "success")
			{
				$formatted_data = $data;

				$verification_fields = VChainIdentity::getInputFields($formatted_data);

				$verification_data = self::applyVerificationRecursive($verification_fields, $verifications_binary_signatures);

				$identity = $check_identity_result["identity"];

				VChainIdentityDao::saveVerifications($identity, $verification_data, $source_id, $ip);

				return array(
					"status"   => "success"
				);

			} else
			{
				// TODO что делать при верификации, если такого identity мы найти не можем?
				error_log("INTERRUPT: cant find indentity to verify OR there are multiple identities");
				exit;

			}

		} else {
			return array(
				"status"            => "ERROR",
				"error_reason_code" => ERROR_CODE_NOT_AUTHORIZED
			);
		}

		return array(
			"status"            => "ERROR",
			"error_reason_code" => ERROR_CODE_NOT_ENOUGH_CREDENTIALS
		);
	}

	public static function getDefaultVerificationsTemplate($identity)
	{
		return self::applyDefaultVerificationRecursive($identity);
	}

	private static function applyDefaultVerificationRecursive($data)
	{
		$output = array();

		foreach ($data as $key => $value)
		{
			if (   strcmp($key, "created") == 0
				|| strcmp($key, "history") == 0
				|| strcmp($key, "verifications") == 0)
			{
				// skip

			} else if (is_array($value))
			{
				$output[$key] = self::applyDefaultVerificationRecursive($value);

			} else {
				$output[$key] = null;
			}
		}

		return $output;
	}

	private static function applyVerificationRecursive($data, $binary_signatures)
	{
		$output = array();

		foreach ($data as $key => $value)
		{
			if (   strcmp($key, "created") == 0
				|| strcmp($key, "history") == 0
				|| strcmp($key, "verifications") == 0)
			{
				// skip

			} else if (is_array($value))
			{
				$output[$key] = self::applyVerificationRecursive($value, $binary_signatures[$key]);

			} else {
				if (isset($binary_signatures[$key]))
				{
					$output[$key] = base64_encode($binary_signatures[$key]);

				} else {
					$output[$key] = null;
				}
			}
		}

		return $output;
	}

}

?>
