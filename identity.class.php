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
					if (!isset($output[CREDENTIAL_PASSPORTS_FIELD])) $output[CREDENTIAL_PASSPORTS_FIELD] = array();

					$passport_lookup = array();

					foreach ($lookup_field as $passport_lookup_index => $passport_lookup_field)
					{
						$passport_lookup[$passport_lookup_field] = $passport_data[$passport_lookup_field];
					}

					$output[CREDENTIAL_PASSPORTS_FIELD][] = $passport_lookup;
				}

			} else {
				$output[$lookup_field] = $formatted_data[$lookup_field];
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

		$credentials = self::getCredentialsFields($formatted_data);

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

					error_log("CREDENTIALS LEVEL ". $credential_level .": ". sizeof($identities) ." results");

					if (sizeof($identities) > 0)
					{
						// нашли identitiy по этим credentials

						if (sizeof($identities) > 1)
						{
							$identity_founded = true;

							// TODO: что делаем, если нашли не одну identity по этим credentials?
							error_log("MORE THAN ONE IDENTITY FOUND!!!");
							exit;

						} else {

							$identity_founded = true;

							return array(
								"status"   => "success",
								"identity" => self::export($identities[0], $input_fields, $key)
							);
						}

					} else {
						// не нашли identitity по credentials - ищем возможную ошибку

						error_log("CREDENTIALS LEVEL ". $credential_level .": looking for mistakes");

						// TODO: поиск возможной ошибки
						;

						error_log("CREDENTIALS LEVEL ". $credential_level .": no mistakes found");

						error_log("INTERRUPT");

						//exit;
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

	private static function export($identity, $input_fields, $key)
	{
		$output = array();

		$output["id"] = $identity["id"];

		// TODO сделать нормальную выгрузку данных о верификациях
		error_log("NORMAL VERIFICATION EXPORT NEEDED");

		$output_verifications = array_keys_intersect_recursive($identity["verifications"], $input_fields);

		$output["verifications"] = $output_verifications;

		return $output;
	}

	public static function record($data, $key, $using_cause, $ip, $force_possible_matches = false)
	{
		unset($data["id"]);
		unset($data["_id"]);
		unset($data["key"]);
		unset($data["signature"]);
		unset($data["using_cause"]);
		unset($data["ignore_possible_matches"]);

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
				&& $check_identity_result["error_reason_code"] == ERROR_CODE_IDENTITY_NOT_FOUND)
			{
				// такой identity нет, надо создать новую
				// или было подозрение на совпадения, но оно форсируется
				// ($force_possible_matches == true)

				$formatted_data = $data;

				$input_fields = self::getInputFields($formatted_data);

				$created_identity = VChainIdentityDao::create($formatted_data, $source_id, $ip);

				VChainIdentityDao::record($created_identity, $formatted_data, $source_id, $using_cause, $ip);

				return array(
					"status"   => "success",
					"identity" => self::export($created_identity, $input_fields, $key)
				);

			} else if ($check_identity_result["status"] == "success")
			{
				$formatted_data = $data;

				$input_fields = self::getInputFields($formatted_data);

				$identity = $check_identity_result["identity"];

				VChainIdentityDao::record($identity, $formatted_data, $source_id, $using_cause, $ip);

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

	private static function getCredentialsFields($data)
	{
		$output = array();

		$email_isset = false;
		$phone_isset = false;
		$passport_number_isset = false;
		$passport_nationality_isset = false;
		$first_name_isset = false;
		$last_name_isset = false;
		$birthdate_isset = false;

		$credentials = array();

		if (is_array($data))
		{
			if (isset($data[CREDENTIAL_PASSPORTS_FIELD]) && is_array($data[CREDENTIAL_PASSPORTS_FIELD]))
			{
				foreach ($data[CREDENTIAL_PASSPORTS_FIELD] as $passportIndex => $passportArr)
				{
					if (isset($passportArr[CREDENTIAL_PASSPORT_NUMBER_FIELD]) && !empty($passportArr[CREDENTIAL_PASSPORT_NUMBER_FIELD]))
					{
						$passport_number_isset = true;
					}
					if (isset($passportArr[CREDENTIAL_PASSPORT_NATIONALITY_FIELD]) && !empty($passportArr[CREDENTIAL_PASSPORT_NATIONALITY_FIELD]))
					{
						$passport_nationality_isset = true;
					}
				}
			}
			if (isset($data[CREDENTIAL_PHONE_FIELD]) && !empty($data[CREDENTIAL_PHONE_FIELD]))
			{
				$phone_isset = true;
			}
			if (isset($data[CREDENTIAL_EMAIL_FIELD]) && !empty($data[CREDENTIAL_EMAIL_FIELD]))
			{
				$email_isset = true;
			}
			if (isset($data[CREDENTIAL_FIRST_NAME_FIELD]) && !empty($data[CREDENTIAL_FIRST_NAME_FIELD]))
			{
				$first_name_isset = true;
			}
			if (isset($data[CREDENTIAL_LAST_NAME_FIELD]) && !empty($data[CREDENTIAL_LAST_NAME_FIELD]))
			{
				$last_name_isset = true;
			}
			if (isset($data[CREDENTIAL_BIRTHDATE_FIELD]) && !empty($data[CREDENTIAL_BIRTHDATE_FIELD]))
			{
				$birthdate_isset = true;
			}
		}

		if (   $passport_number_isset
			&& $passport_nationality_isset
			&& $first_name_isset
			&& $last_name_isset
			&& $birthdate_isset)
		{
			if (!isset($output[0])) $output[0] = array();

			$output[0][] = array(
				CREDENTIAL_FIRST_NAME_FIELD,
				CREDENTIAL_LAST_NAME_FIELD,
				CREDENTIAL_BIRTHDATE_FIELD,
				CREDENTIAL_PASSPORTS_FIELD => array(
					CREDENTIAL_PASSPORT_NUMBER_FIELD,
					CREDENTIAL_PASSPORT_NATIONALITY_FIELD
				)
			);
		}

		if (   $email_isset
			&& $phone_isset)
		{
			if (!isset($output[1])) $output[1] = array();

			$output[1][] = array(
				0 => CREDENTIAL_EMAIL_FIELD,
				1 => CREDENTIAL_PHONE_FIELD
			);
		}

		if ($email_isset)
		{
			if (!isset($output[2])) $output[2] = array();

			$output[2][] = array(
				0 => CREDENTIAL_EMAIL_FIELD
			);
		}

		if ($phone_isset)
		{
			if (!isset($output[2])) $output[2] = array();

			$output[2][] = array(
				0 => CREDENTIAL_PHONE_FIELD
			);
		}

		return $output;
	}

}

?>
