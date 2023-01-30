<?php
 defined('BASE_URL') or   define("BASE_URL", env('APP_URL').'/');
 defined('ASSETS_URL') or   define("ASSETS_URL", BASE_URL."/public/");
 defined('CSS_URL') or   define("CSS_URL", ASSETS_URL."css/");
 defined('JS_URL') or   define("JS_URL", ASSETS_URL."js/");
 defined('IMAGES_URL') or  define("IMAGES_URL",config('app.storage_url'));
 defined('FONTS_URL') or  define("FONTS_URL", ASSETS_URL."font/");
 defined('BASE_PATH') or  define ("BASE_PATH", realpath(dirname(__FILE__)).'/');
 defined('ASSETS_DIR') or  define("ASSETS_DIR", BASE_PATH."public/");
 defined('FONTS_DIR') or  define("FONTS_DIR", ASSETS_DIR."fonts/");
 defined('ALLOWED_IMAGES') or define("ALLOWED_IMAGES", array("jpg", "jpeg", "png"));
 defined('SITE_NAME') or  define("SITE_NAME", env('APP_NAME'));
?>
