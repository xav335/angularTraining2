<?php
namespace CAP\Controller;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Err404Controller
 *
 * @author Eric
 */
class Err404Controller {
    public function indexAction(){
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        exit;
    }
}

?>
