<?php
/**
 * A basic OO Sorting callback Implementation
 * 
 * This property based sort is very Strict. It does no input validation.
 * This means that the Integrator will need to be sure he provides only Objects
 * to the sort that can resolve the access to the configured property ("date_u"
 * by default).
 *
 * @author kiriakos
 */
class EKindMozambiquePropertyBasedSorter 
extends CComponent
implements IMozambiqueSorter{
    
    /**
     * The property to call.
     *
     * @var string
     */
    public $property = "date_u";
    
    
    /**
     * An array of calls that will happen on a and b before properties are compared
     *
     * @var string[]
     */
    public $drilldown = array("getRecord");
    
    /**
     * The sorting function
     * 
     * @param object $a
     * @param object $b
     */
    public function sort($a, $b) {
        
        $prop= $this->property;
        
        foreach($this->drilldown as $call){
            $a = call_user_func(array($a, $call));
            $b = call_user_func(array($b, $call));
        }
        
        if($a->$prop == $b->$prop){
            return 0;
        }
        else{
            return $a->$prop > $b->$prop ? 1 : -1;
        }
    }
}
