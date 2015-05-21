<?php

namespace CAP\Model;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Runner
 *
 * @author Eric
 */
class Runner {
    public $id;
    public $firstName;
    public $lastName;
    public $gender;
    public $birthDate;
    public $ffaId;
    public $picture;
    
    public function __construct($options = null)
    {
        if( is_array($options) )
            $this->exchangeArray($options);
    }
    
    public function exchangeArray($array)
    {
        $this->id = ( isset($array['id']) ) ? $array['id'] : null;
        $this->firstName = ( isset($array['firstName']) ) ? $array['firstName'] : null;
        $this->lastName = ( isset($array['lastName']) ) ? $array['lastName'] : null;
        $this->gender = ( isset($array['gender']) ) ? $array['gender'] : null;
        $this->birthDate = ( isset($array['birthDate']) ) ? ( ( $array['birthDate'] instanceof \DateTime ) ? $array['birthDate'] : new \DateTime($array['birthDate']) ) : null;
        $this->ffaId = ( isset($array['ffaId']) ) ? $array['ffaId'] : null;
        $this->picture = ( isset($array['picture']) ) ? $array['picture'] : null;
    }
}

?>
