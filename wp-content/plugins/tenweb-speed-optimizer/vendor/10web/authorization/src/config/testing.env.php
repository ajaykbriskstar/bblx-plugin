<?php
if (!defined('TENWEB_SITE_URL')) {
    define('TENWEB_SITE_URL', "https://test10web.io");
}
if (!defined('TENWEB_DASHBOARD')) {
    define('TENWEB_DASHBOARD', "https://testmy.10web.io");
}
if (!defined('TENWEB_API_URL')) {
    define('TENWEB_API_URL', 'https://testmanager.10web.io/api');
}
if (!defined('TENWEB_S3_BUCKET')) {
    define('TENWEB_S3_BUCKET', '10web-products-testing');
}
if (!defined('TENWEB_MANAGER_ID')) {
    define('TENWEB_MANAGER_ID', 51);
}
if (!defined('TENWEB_DEACTIVATION_REASONS_URL')) {
    define('TENWEB_DEACTIVATION_REASONS_URL', 'https://testcore.10web.io/api/deactivation_reasons');
}
if (!defined('TENWEB_SO_CRITICAL_URL')) {
    define("TENWEB_SO_CRITICAL_URL",'https://testperformance.10web.io');
}
if (!defined('TENWEBIO_API_URL')) {
    define('TENWEBIO_API_URL', "https://testoptimizer.10web.io");
}

if(!defined('TENWEB_SO_FREE_SUBSCRIPTION_ID')) {
    define("TENWEB_SO_FREE_SUBSCRIPTION_ID",323);
}

if(!defined('TENWEB_SO_FREE_SUBSCRIPTION_IDS')) {
    define("TENWEB_SO_FREE_SUBSCRIPTION_IDS",[
        323, //booster free subscription id
        346, 347, 348, 349, 350, 351 //ai builder free booster subscription ids
    ]);
}

if(!defined('TWBB_S3_BUCKET')) {
    define('TWBB_S3_BUCKET', '10webtemplates-testing');
}

if(!defined('TENWEB_SIGNUP_MAGIC_LINK_URL')) {
    define('TENWEB_SIGNUP_MAGIC_LINK_URL', 'https://testcore.10web.io/api/checkout/signup-via-magic-link');
}

global $tenweb_services;

$tenweb_services = array(
  'testoptimizer.10web.io',
  'testsecurity.10web.io',
  'testseo.10web.io',
  'testbackup.10web.io',
  'testmanager.10web.io',
  'testcore.10web.io',
  'testlxd.10web.io'
);

global $tenweb_regions;

$tenweb_regions = array(
	"asia-east1" => "Changhua County, Taiwan",
	"asia-northeast1" => "Tokyo, Japan",
	"asia-south1" => "Mumbai, India",
	"asia-southeast1" => "Jurong West, Singapore",
	"australia-southeast1" => "Sydney, Australia",
	"europe-north1" => "Hamina, Finland",
	"europe-west1" => "St. Ghislain, Belgium",
	"europe-west2" => "London, England, UK",
	"europe-west3" => "Frankfurt, Germany",
	"europe-west4" => "Eemshaven, Netherlands",
	"northamerica-northeast1" => "Montréal, Québec, Canada",
	"southamerica-east1" => "São Paulo, Brazil",
	"us-central1" => "Council Bluffs, Iowa, USA",
	"us-east1" => "Moncks Corner, South Carolina, USA",
	"us-east4" => "Ashburn, Northern Virginia, USA",
	"us-west1" => "The Dalles, Oregon, USA",
	"us-west2" => "Los Angeles, California, USA"
);
