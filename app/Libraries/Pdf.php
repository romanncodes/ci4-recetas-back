<?php 
namespace App\Libraries;

use TCPDF;

require_once APPPATH . '/libraries/tcpdf/tcpdf.php';
 
class Pdf extends TCPDF{
    function __construct()
    {
        parent::__construct();
    }
}
