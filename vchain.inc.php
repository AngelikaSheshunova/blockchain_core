<?php

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/source.class.php";
require_once __DIR__ . "/verification.class.php";
require_once __DIR__ . "/identityDao.class.php";
require_once __DIR__ . "/identity.class.php";

define("VCHAIN_API_URL_BASE", "http://api.vchain.dev:8080/");
define("VCHAIN_API_URL_CHECK", VCHAIN_API_URL_BASE."v0.1/check/");
define("VCHAIN_API_URL_USE", VCHAIN_API_URL_BASE."v0.1/use/");
define("VCHAIN_API_URL_VERIFY", VCHAIN_API_URL_BASE."v0.1/verify/");

define("VCHAIN_URL_DEMO", "http://demo.vchain.dev:8080/");
define("VCHAIN_URL_CDN", "http://cdn.vchain.dev:8080/");
define("VCHAIN_URL_DEMO_VERIFIER", "http://demo-verifier.vchain.dev:8080/");

define("VCHAIN_SOURCE_KEY_DEMO_VERIFIER", "279312d19d2fr39820c2787fa1451e70cb0f5b662d062ec12a23d623fa0192f6d8728c912680a8e399d569eaa0f9c949d5a0d3d743050ea2ab4411a951b4e5c1");

define("ERROR_CODE_NOT_ENOUGH_CREDENTIALS", "NOT_ENOUGH_CREDENTIALS");
define("ERROR_CODE_NOT_AUTHORIZED", "NOT_AUTHORIZED_FOR_OPERATION");
define("ERROR_CODE_IDENTITY_NOT_FOUND", "IDENTITY_NOT_FOUND");

define("CREDENTIAL_FIRST_NAME_FIELD", "first_name");
define("CREDENTIAL_LAST_NAME_FIELD", "last_name");
define("CREDENTIAL_EMAIL_FIELD", "email");
define("CREDENTIAL_PHONE_FIELD", "phone");
define("CREDENTIAL_PASSPORTS_FIELD", "passports");
define("CREDENTIAL_PASSPORT_NUMBER_FIELD", "number");
define("CREDENTIAL_PASSPORT_NATIONALITY_FIELD", "nationality");
define("CREDENTIAL_BIRTHDATE_FIELD", "birthdate");

?>
