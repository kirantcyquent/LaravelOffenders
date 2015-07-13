<?php namespace App\Library;

use \ccproto;

class Decaptcha
{
    public $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function process()
    {
        $image = $this->image;
        if (!defined('HOST')) define('HOST', "poster.de-captcher.com");    // HOST
        if (!defined('PORT')) define('PORT', 12297);    // PORT 80 or 443
        if (!defined('USERNAME')) define('USERNAME', "prashantup");    // YOUR LOGIN
        if (!defined('PASSWORD')) define('PASSWORD', "homefacts12");    // YOUR PASSWORD
        if (!defined('PIC_FILE_NAME')) define('PIC_FILE_NAME', $image);

        $ccp = new ccproto();

        $ccp->init();

        if ($ccp->login(HOST, PORT, USERNAME, PASSWORD) < 0) {
            print(" FAILED\n");
            return;
        }


        $system_load = 0;
        if ($ccp->system_load($system_load) != ccERR_OK) {
            print("system_load() FAILED\n");
            return;
        }

        $balance = 0;
        if ($ccp->balance($balance) != ccERR_OK) {
            print("balance() FAILED\n");
            return;
        }


        $major_id = 0;
        $minor_id = 0;

        $pict = file_get_contents($image);
        $text = '';

        $pict_to = ptoDEFAULT;
        $pict_type = ptUNSPECIFIED;

        $res = $ccp->picture2($pict, $pict_to, $pict_type, $text, $major_id, $minor_id);

        return $text;
    }
}



