<?php

class VChainUser
{

	public static function startActivation($user)
	{
		$activation_token = hash("sha512", uniqid());

		VChainUserDao::update(
			$user["id"],
			array(
				"status"           => USER_STATUS_ACTIVATION_STARTED,
				"activation_token" => $activation_token
			)
		);

		return $activation_token;
	}

}

?>
