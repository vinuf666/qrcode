<?php

/**
 * QR code Image in PNG format
 * Part of view qrcode/bitmap
 */

header('Content-type:image/png');
elgg_load_library('qrcode');
QRcode::png($vars['url'],false,$vars['qrcode_ECC'],$vars['qrcode_Size']);
