<?php

namespace CAP\Model;

/**
 * Description of Courses
 *
 * @author Eric
 */
class Event {
    public $name;
    public $id;
    public $date;
    public $location;
    public $description;
    public $runs;
    
    public function __construct($options = null){
        if (is_array($options)){
            $this->exchangeArray($options);
        }
    }
    
    public function exchangeArray($array){
        $this->id = ( isset($array['id']) ) ? $array['id'] : null;
        $this->name = ( isset($array['name']) ) ? $array['name'] : null;
        $this->location = ( isset($array['location']) ) ? $array['location'] : null;
        $this->date = ( isset($array['date']) ) ? ( ($array['date'] instanceof \DateTime)?$array['date'] : new \DateTime($array['date']) ) : null;
        $this->description = ( isset($array['description']) ) ? $array['description'] : null;
    }
    
    
}

