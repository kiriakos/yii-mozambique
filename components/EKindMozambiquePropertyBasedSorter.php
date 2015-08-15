<?php
/**
 * Sorting callback
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
     * 
     * @param object $a
     * @param object $b
     */
    public function sort($a, $b) {
        
    }
}
