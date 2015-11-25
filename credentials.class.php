<?php

class VChainCredentials
{

	public static function getCredentialFieldsForPossibleMistakes($credential_level, $lookup)
	{
		if ($credential_level == 0)
		{
			if (   isset($lookup["first_name"])
				&& isset($lookup["last_name"])
				&& isset($lookup["birthdate"])
				&& isset($lookup["passports.number"])
				&& isset($lookup["passports.nationality"]))
			{
				return array(
					// фамилия+дата рождения+номер пасспорта+гражданство => имя
					array(
						"lookup" => array(
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_FIRST_NAME_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// имя+дата рождения+номер пасспорта+гражданство => фамилия
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_LAST_NAME_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// имя+фамилия+дата рождения+номер пасспорта => гражданство
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"restrictions" => array(
						)
					),
					// имя+фамилия+дата рождения+гражданство => номер пасспорта
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD
							)
						),
						"restrictions" => array(
						)
					),
					// имя+фамилия+номер пасспорта+гражданство => дата рождения
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_BIRTHDATE_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// гражданство+фамилия+номер пасспорта => имя+дата рождения
					array(
						"lookup" => array(
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// гражданство+имя+номер пасспорта => фамилия+дата рождения
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// гражданство+дата рождения+номер пасспорта => имя+фамилия
					array(
						"lookup" => array(
							CREDENTIAL_BIRTHDATE_FIELD,
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					),
					// имя+фамилия+дата рождения => гражданство+номер пасспорта
					array(
						"lookup" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD
						),
						"possible_mistake" => array(
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"restrictions" => array(
						)
					),
					// гражданство+номер пасспорта => имя+фамилия+дата рождения
					array(
						"lookup" => array(
							CREDENTIAL_PASSPORTS_FIELD => array(
								CREDENTIAL_PASSPORT_NUMBER_FIELD,
								CREDENTIAL_PASSPORT_NATIONALITY_FIELD
							)
						),
						"possible_mistake" => array(
							CREDENTIAL_FIRST_NAME_FIELD,
							CREDENTIAL_LAST_NAME_FIELD,
							CREDENTIAL_BIRTHDATE_FIELD
						),
						"restrictions" => array(
							IDENTITY_RESTRICTION_CREATION_PROHIBITED
						)
					)
				);
			}
		}

		return array();
	}

	public static function getCredentialsFields($data)
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
				CREDENTIAL_EMAIL_FIELD,
				CREDENTIAL_PHONE_FIELD
			);
		}

		if ($email_isset)
		{
			if (!isset($output[2])) $output[2] = array();

			$output[2][] = array(
				CREDENTIAL_EMAIL_FIELD
			);
		}

		if ($phone_isset)
		{
			if (!isset($output[2])) $output[2] = array();

			$output[2][] = array(
				CREDENTIAL_PHONE_FIELD
			);
		}

		return $output;
	}

}

?>
