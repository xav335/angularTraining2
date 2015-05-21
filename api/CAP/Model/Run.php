<?php
namespace CAP\Model;

/**
 * Description of Run
 *
 * @author Eric
 */
class Run {
    public $id;
    public $eventId;
    public $startTime;
    public $name;
    public $distance;
    
    
    public function __construct( $options = null )
    {
        if(null !== $options)
            $this->exchangeArray($options);
    }
    
    public function exchangeArray($array)
    {
        $this->id = ( isset($array['id']) ) ? $array['id'] : null ;
        $this->eventId = ( isset($array['eventId']) ) ? $array['eventId'] : null ;
        $this->name = ( isset($array['name']) ) ? $array['name'] : null ;
        $this->startTime = ( isset($array['startTime']) ) ? ( ($array['startTime'] instanceof \DateTime) ? $array['startTime'] : new \DateTime($array['startTime']) ) : null ;
        $this->distance = ( isset($array['distance']) ) ? $array['distance'] : null ;
    }
}

?>
