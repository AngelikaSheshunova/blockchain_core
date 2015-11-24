<?php

class VChainIdentity
{

	private static function formLookup($lookup_fields, $formatted_data)
	{
		$output = array();

		foreach ($lookup_fields as $lookup_key => $lookup_field)
		{
			if ($lookup_key === CREDENTIAL_PASSPORTS_FIELD)
			{
				foreach ($formatted_data[CREDENTIAL_PASSPORTS_FIELD] as $passport_data)
				{
					$passport_lookup = array();

					foreach ($lookup_field as $passport_lookup_index => $passport_lookup_field)
					{
						$passport_lookup[$passport_lookup_field] = $passport_data[$passport_lookup_field];
					}

					if (sizeof($passport_lookup) > 0)
					{
						foreach ($passport_lookup as $key => $value)
						{
							$output[CREDENTIAL_PASSPORTS_FIELD.".".$key] = $value;
						}
					}
				}

			} else {
				$output[$lookup_field] = $formatted_data[$lookup_field];
			}
		}

		return $output;
	}

	private static function export($identity, $input_fields, $key)
	{
		$output = array();

		$output["id"] = $identity["id"];

		$export_verifications = VChainVerification::getOnlyRequestedVerification($identity["verifications"], $input_fields);

		$output["verifications"] = VChainVerification::validateAndExportVerifications($identity, $export_verifications);

		return $output;
	}

	public static function diff($identity, $formatted_data)
	{
		$output = array();

		foreach ($formatted_data as $key => $value)
		{
			if (is_array($value))
			{
				$t = self::diff($identity[$key], $value);
				if (is_array($t) && sizeof($t) > 0)
				{
					$output[$key] = $t;
				}

			} else {
				if (   isset($identity[$key])
					&& $identity[$key] !== $value)
				{
					$output[$key] = $identity[$key];

				}
			}
		}

		return $output;
	}

	public static function getDiffFields($diff)
	{
		$output = array();

		foreach ($diff as $key => $value)
		{
			if (is_array($value))
			{
				$output[$key] = self::getInputFields($value);

			} else {
				$output[$key] = 1;
			}
		}

		return $output;
	}

	public static function check($data, $key, $ip)
	{
		unset($data["id"]);
		unset($data["_id"]);
		unset($data["key"]);
		unset($data["signature"]);
		unset($data["using_cause"]);
		unset($data["ignore_possible_matches"]);

		$data_email = null;
		$data_phone = null;
		if (isset($data["user_data"]) && is_array($data["user_data"]))
		{
			if (isset($data["user_data"]["email"]))
				$data_email = $data["user_data"]["email"];

			if (isset($data["user_data"]["phone"]))
				$data_phone = $data["user_data"]["phone"];
		}
		unset($data["user_data"]);

		$source = VChainSource::getByKey($key);
		$source_id = false;

		if (is_array($source) && isset($source["id"]) && !empty($source["id"]))
		{
			$source_id = $source["id"];
		}

		if (!$source_id)
		{
			return array(
				"status"            => "ERROR",
				"error_reason_code" => ERROR_CODE_NOT_AUTHORIZED
			);
		}

		$formatted_data = $data;

		$input_fields = self::getInputFields($formatted_data);

		$credentials = VChainCredentials::getCredentialsFields($formatted_data);

		if (is_array($credentials) && sizeof($credentials) > 0)
		{
			ksort($credentials);

			$identity_founded = false;

			foreach ($credentials as $credential_level => $level_credentials)
			{
				foreach ($level_credentials as $key => $lookup_fields)
				{

					$lookup = self::formLookup($lookup_fields, $formatted_data);

					$identities = VChainIdentityDao::search($lookup);

					if (sizeof($identities) > 0)
					{
						// нашли identitiy по этим credentials

						if (sizeof($identities) > 1)
						{
							$identity_founded = true;

							// TODO: что делаем, если нашли не одну identity по этим credentials?
							error_log("INTERRUPT");
							error_log("MORE THAN ONE IDENTITY FOUND!!!");
							exit;

						} else {

							// нашли identity, теперь проверим все поля запроса
							$comparsion_result = self::diff($identities[0], $formatted_data);

							if (sizeof($comparsion_result) > 0)
							{
								$diff_fields = self::getDiffFields($comparsion_result);

								VChainIdentityDao::recordCheck($identities[0], $input_fields, $diff_fields, $source_id, $ip);

								return array(
									"status"            => "error",
									"error_reason_code" => ERROR_CODE_IDENTITY_POSSIBLE_MISTAKES,
									"possible_mistakes" => $diff_fields
								);
							}

							$identity_founded = true;

							VChainIdentityDao::recordCheck($identities[0], $input_fields, array(), $source_id, $ip);

							return array(
								"status"   => "success",
								"identity" => self::export($identities[0], $input_fields, $key)
							);
						}

					} else {
						// не нашли identitity по credentials - ищем возможную ошибку

						$lookups_for_mistakes = VChainCredentials::getCredentialFieldsForPossibleMistakes($credential_level, $lookup);

						$mistake_level = 0;

						foreach ($lookups_for_mistakes as $arr)
						{
							$mistake_lookup_fields = $arr["lookup"];
							$possible_mistake_fields = $arr["possible_mistake"];

							$mistake_lookup = self::formLookup($mistake_lookup_fields, $formatted_data);

							$identities = VChainIdentityDao::search($mistake_lookup);

							if (sizeof($identities) > 0)
							{
								// нашли identity, теперь проверим все поля запроса
								$comparsion_result = self::diff($identities[0], $formatted_data);

								$diff_fields = self::getDiffFields($comparsion_result);

								VChainIdentityDao::recordCheck($identities[0], $input_fields, $diff_fields, $source_id, $ip);

								return array(
									"status"            => "error",
									"error_reason_code" => ERROR_CODE_IDENTITY_POSSIBLE_MISTAKES,
									"possible_mistakes" => $diff_fields
								);
							}

							$mistake_level++;
						}
					}
				}
			}

			if (!$identity_founded)
			{
				// identity не нашли,
				// но есть достаточный набор для индентификации личности в дальнейшем

				return array(
					"status"            => "error",
					"error_reason_code" => ERROR_CODE_IDENTITY_NOT_FOUND
				);
			}
		}

		return array(
			"status"            => "error",
			"error_reason_code" => ERROR_CODE_NOT_ENOUGH_CREDENTIALS
		);
	}

	public static function recordUsage($data, $key, $using_cause, $ip, $force_possible_matches = false)
	{
		unset($data["id"]);
		unset($data["_id"]);
		unset($data["key"]);
		unset($data["signature"]);
		unset($data["using_cause"]);
		unset($data["ignore_possible_matches"]);

		$data_email = null;
		$data_phone = null;
		if (isset($data["user_data"]) && is_array($data["user_data"]))
		{
			if (isset($data["user_data"]["email"]))
				$data_email = $data["user_data"]["email"];

			if (isset($data["user_data"]["phone"]))
				$data_phone = $data["user_data"]["phone"];
		}
		unset($data["user_data"]);

		$source = VChainSource::getByKey($key);
		$source_id = false;

		if (is_array($source) && isset($source["id"]) && !empty($source["id"]))
		{
			$source_id = $source["id"];
		}

		if (!$source_id)
		{
			return array(
				"status"            => "ERROR",
				"error_reason_code" => ERROR_CODE_NOT_AUTHORIZED
			);
		}

		$check_identity_result = self::check($data, $key, $ip);
		if (   is_array($check_identity_result)
			&& isset($check_identity_result["status"]))
		{
			if (   $check_identity_result["status"] == "error"
				&& (   $check_identity_result["error_reason_code"] == ERROR_CODE_IDENTITY_NOT_FOUND
					|| (   $check_identity_result["error_reason_code"] == ERROR_CODE_IDENTITY_POSSIBLE_MISTAKES
						&& $force_possible_matches))
			   )
			{
				// такой identity нет, надо создать новую
				// или было подозрение на совпадения, но оно форсируется
				// ($force_possible_matches == true)

				$formatted_data = $data;

				$input_fields = self::getInputFields($formatted_data);

				$created_identity = VChainIdentityDao::create($formatted_data, $data_email, $data_phone, $source_id, $ip);

				VChainIdentityDao::recordUsage($created_identity, $formatted_data, $source_id, $using_cause, $ip);

				return array(
					"status"   => "success",
					"identity" => self::export($created_identity, $input_fields, $key)
				);

			} else if (   $check_identity_result["status"] == "error"
				       && $check_identity_result["error_reason_code"] == ERROR_CODE_IDENTITY_POSSIBLE_MISTAKES
				       && !$force_possible_matches)
			{
				// TODO что делать, если на use пришли данные с возможной ошибкой,
				// но флаг игнорирования этого не выставлен?
				error_log("INTERRUPT!");
				error_log("POSSIBLE MISTAKES ON USE WITHOUT FORCE FLAG");
				exit;

			} else if ($check_identity_result["status"] == "success")
			{
				$formatted_data = $data;

				$input_fields = self::getInputFields($formatted_data);

				$identity = $check_identity_result["identity"];

				VChainIdentityDao::recordUsage($identity, $formatted_data, $source_id, $using_cause, $ip);

				return array(
					"status"   => "success",
					"identity" => self::export($identity, $input_fields, $key)
				);

			} else {
				return $check_identity_result;
			}

		} else {
			// возвращаем рещультат check
			return $check_identity_result;
		}
	}

	public static function getInputFields($data)
	{
		$output = array();

		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				$output[$key] = self::getInputFields($value);

			} else {
				$output[$key] = 1;
			}
		}

		return $output;
	}

}

?>
