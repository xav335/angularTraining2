<?php
namespace CAP\Model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Entrant
 *
 * @author Eric
 */
class Entrant {
    public $id;
    public $runId;
    public $runnerId;
    public $bibNumber;
    public $runTime;
    public $didFinish;
    
    public function __construct( $options = null )
    {
        if (null !== $options)
            $this->exchangeArray ($options);
    }
    
    public function exchangeArray($array)
    {
        $this->id = isset($array['id']) ? $array['id'] : null ;
        $this->runId = isset($array['runId']) ? $array['runId'] : null ;
        $this->runnerId = isset($array['runnerId']) ? $array['runnerId'] : null ;
        $this->bibNumber = isset($array['bibNumber']) ? $array['bibNumber'] : null ;        
        $this->didFinish = isset($array['didFinish']) ? $array['didFinish'] : null ;
        if( isset($array['runTime']) ){
            if( $array['runTime'] instanceof \DateInterval ){
                $this->runTime = $array['runTime'];
            }else{
                $duration = explode(':',$array['runTime']);
                $this->runTime =   new \DateInterval("PT$duration[0]H$duration[1]M$duration[2]S");
            }
        }else{
            $this->runTime = null; 
        }
    }
}

?>
