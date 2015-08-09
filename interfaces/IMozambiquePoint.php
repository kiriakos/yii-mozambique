<?php

/**
 * A Basic Cartesian Point can be used to compare two dimensions, etc.
 * 
 * Created as a way to avoid handling dimension logic with PHP arrays. Points
 * are supposed to be imutable
 *
 * @author kiriakos
 */
interface IMozambiquePoint {
    
    /**
     * Base constructor.
     * 
     * This should be the only way to instantiate a basic point. 
     * 
     * @param type $x
     * @param type $y
     * @throws EKindMozambiqueDimensionOutOfBoundsException
     */
    public function __construct($x, $y);
    
    /**
     * The X dimension
     * 
     * @return integer
     */
    public function getX();
    
    /**
     * The Y dimension
     * 
     * @return integer
     */
    public function getY();
    
    /**
     * Whether the point is on the same X plane as the passed point
     * 
     * @param \IMozambiquePoint $point
     * @return boolean
     */
    public function sameX(\IMozambiquePoint $point);
    
    /**
     * Whether the point is on the same Y plane with the passed point
     * 
     * @param \IMozambiquePoint $point
     * @return boolean
     */
    public function sameY(\IMozambiquePoint $point);
}
