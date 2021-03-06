<?php

/**
 * qrcode - QR Code for Pages plugin for Elgg 1.8
 * @license GNU Public License version 3
 * @author Vinu Felix <vinu.felix@gmail.com>
 * @package qrcode
 */



function qrcode_init() {
    $context = elgg_get_context();
    elgg_register_library('qrcode', dirname(__FILE__) . '/vendors/phpqrcode/qrlib.php');
    elgg_register_library('qrshared', dirname(__FILE__) . '/lib/qrcode.php');
    elgg_extend_view('css/admin', 'qrcode/admin', 1);


    if (($context != 'admin')&&($context != 'members')&&($context != 'co-creators')&&($context != 'suggested_friends')&&($context != 'suggested_friends_extended'))
    {
	elgg_extend_view('css/elgg', 'qrcode/css');
        elgg_extend_view('page/elements/sidebar','sidebar/qrcode',700);
        elgg_register_page_handler('qrcode','qrcode_page_handler');
    }
}
function qrcode_page_handler($page, $handler) {
    if (!isset($page[0]) || !isset($page[1])) 
    {
        register_error("Invalid QR Code Parameters Provided");
        return false; //Invalid Request
    };
    if ($page[0] == 'qrcode') {
        elgg_load_library('qrshared');
        elgg_load_library('qrcode');
        $qrcode_ECC = elgg_get_plugin_setting('qrcode_ECC', 'qrcode');
        $qrcode_Size = elgg_get_plugin_setting('qrcode_Size', 'qrcode');
        //set defaults
        $qrcode_ECC = ($qrcode_ECC == '') ? QR_ECLEVEL_M : $qrcode_ECC;
        $qrcode_Size = ($qrcode_Size == '') ? 5 : intval($qrcode_Size);
        $qurl = hexToStr($page[1]); //Decode URL Hex
        $pos = strrpos($qurl, elgg_get_site_url()); // Security fix. Prevent other domains from hotlinking
        if($pos === false) { //Hotlinked
            echo elgg_view('qrcode/png', array('qurl' => 'Hotlinking not allowed', 'qrcode_ECC' => $qrcode_ECC, 'qrcode_Size' => $qrcode_Size)); 
        } else {
            echo elgg_view('qrcode/png', array('qurl' => $qurl, 'qrcode_ECC' => $qrcode_ECC, 'qrcode_Size' => $qrcode_Size));
        }
    }else{
        return false;
    };
    return true;
}

// call init
elgg_register_event_handler('init','system','qrcode_init');

?>