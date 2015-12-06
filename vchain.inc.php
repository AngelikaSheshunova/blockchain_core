<?php

require_once __DIR__ . "/config.loc.php";

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/credentials.class.php";
require_once __DIR__ . "/source.class.php";
require_once __DIR__ . "/verification.class.php";
require_once __DIR__ . "/claim.class.php";
require_once __DIR__ . "/claimDao.class.php";
require_once __DIR__ . "/identityDao.class.php";
require_once __DIR__ . "/identity.class.php";

define("ERROR_CODE_NOT_ENOUGH_CREDENTIALS", "NOT_ENOUGH_CREDENTIALS");
define("ERROR_CODE_NOT_AUTHORIZED", "NOT_AUTHORIZED_FOR_OPERATION");
define("ERROR_CODE_IDENTITY_NOT_FOUND", "IDENTITY_NOT_FOUND");
define("ERROR_CODE_IDENTITY_POSSIBLE_MISTAKES", "IDENTITY_POSSIBLE_MISTAKES");

define("IDENTITY_RESTRICTION_CREATION_PROHIBITED", 1);

define("CLAIM_TYPE_POSSIBLE_DUBLICATE", 1);
define("CLAIM_TYPE_POSSIBLE_CHANGES", 2);

define("CLAIM_STATUS_RESOLVED", "resolved");
define("CLAIM_STATUS_UNRESOLVED", "unresolved");

define("CLAIM_RESOLUTION_TYPE_ACCEPTED", "accepted");

define("CREDENTIAL_FIRST_NAME_FIELD", "first_name");
define("CREDENTIAL_LAST_NAME_FIELD", "last_name");
define("CREDENTIAL_EMAIL_FIELD", "email");
define("CREDENTIAL_PHONE_FIELD", "phone");
define("CREDENTIAL_PASSPORTS_FIELD", "passports");
define("CREDENTIAL_PASSPORT_NUMBER_FIELD", "number");
define("CREDENTIAL_PASSPORT_NATIONALITY_FIELD", "nationality");
define("CREDENTIAL_BIRTHDATE_FIELD", "birthdate");

?>
