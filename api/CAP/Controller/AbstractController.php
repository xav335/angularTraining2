<?php
namespace CAP\Controller;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractController
 *
 * @author Eric
 */
class AbstractController {
    public function __construct(){
        header('Content-type:application/json');
    }
            
    
    protected function getParams(){
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                return $_GET;
            case 'POST':
                return $_POST;
            case 'DELETE': case 'PUT':
                
                $res = array();
                $body = file_get_contents('php://input');
                //echo $body;
                
                $body = explode('&',$body);
                foreach($body as $param){
                    //echo "<br />$param";
                    $parval = explode('=',$param);
                    //var_dump($parval);
                    $res[$parval[0]] = urldecode($parval[1]);
                }
                return $res;
                break;
            default:
                return array();
        }
    }
}

?>
