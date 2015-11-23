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
			error_log("verification::addVerifications | WRONG SOURCE_ID");

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
				&& isset($check_identity_result["status"]))
			{
				if ($check_identity_result["status"] == "success")
				{
					$formatted_data = $data;

					$verification_fields = VChainIdentity::getInputFields($formatted_data);

					$verification_data = self::applyVerificationSignatureRecursive($verification_fields, $verifications_binary_signatures);

					$identity = VChainIdentityDao::get($check_identity_result["identity"]["id"]);

					VChainIdentityDao::saveVerifications($identity, $verification_data, $source_id, $ip);

					return array(
						"status"   => "success"
					);

				} else if ($check_identity_result["status"] == "error") {

					if ($check_identity_result["error_reason_code"] == ERROR_CODE_IDENTITY_NOT_FOUND)
					{
						$formatted_data = $data;

						$verification_fields = VChainIdentity::getInputFields($formatted_data);

						$verification_data = self::applyVerificationSignatureRecursive($verification_fields, $verifications_binary_signatures);

						$identity = VChainIdentityDao::create($formatted_data, $source_id, $ip);

						VChainIdentityDao::saveVerifications($identity, $verification_data, $source_id, $ip);

						return array(
							"status"   => "success"
						);

					} else {
						// TODO что делать при верификации, если мы нашли много identity?
						// TODO что делать при верификации, если мы нашли identity, но есть возможные ошибки?
						error_log($check_identity_result["error_reason_code"]);
						error_log("there are multiple identities OR possible mistake in previous stored data");
						error_log("INTERRUPT");
						exit;
					}
				}
			}

		} else {
			error_log("verification::addVerifications | WRONG SIGNATURE");

			return array(
				"status"            => "ERROR",
				"error_reason_code" => ERROR_CODE_NOT_AUTHORIZED
			);
		}

		error_log("verification::addVerifications | NOT ENOUGH CREDENTIALS");

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
				$output[$key] = array();
			}
		}

		return $output;
	}

	private static function applyVerificationSignatureRecursive($data, $binary_signatures)
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
				$output[$key] = self::applyVerificationSignatureRecursive($value, $binary_signatures[$key]);

			} else {
				if (isset($binary_signatures[$key]))
				{
					$output[$key] = base64_encode($binary_signatures[$key]);
				}
			}
		}

		return $output;
	}

	public static function getOnlyRequestedVerification($verifications, $input_fields)
	{
		$output = array();

		foreach ($input_fields as $key => $input_field_value)
		{
			if (is_array($input_field_value))
			{
				if (isset($verifications[$key]))
				{
					$t = self::getOnlyRequestedVerification($verifications[$key], $input_field_value);
					if (is_array($t) && sizeof($t) > 0)
					{
						$output[$key] = $t;
					}
				}

			} else {
				if (isset($verifications[$key]))
				{
					$output[$key] = $verifications[$key];
				}
			}
		}

		return $output;
	}

	public static function validateAndExportVerifications($formatted_data, $export_verifications)
	{
		$output = array();

		foreach ($export_verifications as $key => $validations)
		{
			if (is_array($validations) && sizeof($validations) > 0)
			{
				// сначала проверим, это вообще валидация, или просто массив?
				$is_validation_arr = false;
				foreach ($validations as $v_key => $values)
				{
					if (is_array($values) && isset($values["signature"]))
					{
						$is_validation_arr = true;
						break;
					}
				}

				if ($is_validation_arr)
				{
					$verification_level = null;
					$verification_time = null;
					$verification_signature = null;
					$verification_source_id = null;
					$verification_source_key = null;

					foreach ($validations as $validation)
					{
						$validation_source_id = $validation["source_id"];
						$validation_time = $validation["time"];
						$validation_signature = base64_decode($validation["signature"]);

						$source = VChainSource::getById($validation_source_id);
						$validation_source_id = false;

						if (is_array($source) && isset($source["id"]) && !empty($source["id"]))
						{
							$validation_source_id = $source["id"];
						}

						if ($validation_source_id)
						{
							if (   $verification_level == null
								|| $verification_level < $source["level"]
								|| (   $verification_level == $source["level"]
									&& $verification_time < $validation_time))
							{
								$verification_level = $source["level"];
								$verification_time  = $validation_time;
								$verification_signature = $validation_signature;
								$verification_source_id = $validation_source_id;
								$verification_source_key = $source["key"];
							}
						}
					}

					if ($verification_source_id != null)
					{
						$source_public_key = VChainSource::getPublicKey($verification_source_key);

						$signature_check_result = openssl_verify($formatted_data[$key],
																 $verification_signature,
																 $source_public_key,
																 OPENSSL_ALGO_SHA512);

						if ($signature_check_result === 1)
						{
							$output[$key] = array(
								"verified" => true,
								"level"    => $verification_level
							);
						}
					}

				} else {
					foreach ($validations as $v_key => $values)
					{
						$output[$key] = self::validateAndExportVerifications($formatted_data[$key], $validations);
					}
				}

			} else {
				$output[$key] = null;
			}
		}

		return $output;
	}

}

?>
