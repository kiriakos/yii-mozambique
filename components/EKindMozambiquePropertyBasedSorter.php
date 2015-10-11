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
     * Sort in descending values
     */
    const ORDER_DESC = 1;
    
    /**
     * Sort in ascending values
     */
    const ORDER_ASC = 2;
    
    
    /**
     * The property to call.
     *
     * @var string
     */
    public $property = "date_u";
    public $order = self::ORDER_DESC;
    
    /**
     * An array of calls that will happen on a and b before properties are compared
     *
     * @var string[]
     */
    public $drilldown = array("getRecord");
    
    /**
     * The sorting function
     * 
     * Sort direction is being governed by the $order property.
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
        elseif($this->order === self::ORDER_ASC){
            return $a->$prop > $b->$prop ? 1 : -1;
        }
        elseif($this->order === self::ORDER_DESC){
            return $a->$prop < $b->$prop ? 1 : -1;
        }
        else{
            throw new BadMethodCallException("The order attribute of "
                    . get_class(). " must be a value from it's ORDER_XXX"
                    . " constants!");
        }
    }
}
