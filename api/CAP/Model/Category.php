<?php
namespace CAP\Model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Category
 *
 * @author Eric
 */
class Category {
    public $id;
    public $name;
    public $ageLimit;
    
    public function __construct($options = null )
    {
        if( null !== $options)
            $this->exchangeArray ($options);
    }
    
    public function exchangeArray($array){
        $this->id = ( isset($array['id']) ) ? $array['id'] : null ;
        $this->name = ( isset($array['name']) ) ? $array['name'] : null ;
        $this->ageLimit = ( isset($array['ageLimit']) ) ? $array['ageLimit'] : null ;
    }
    
}

?>
