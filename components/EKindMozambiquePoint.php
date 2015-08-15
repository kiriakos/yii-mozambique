
<?php
/**
 * A Touple representing a point on a Cartesian Surface
 * 
 * This is the base implementation of IMozambiqueGrid and does not include any 
 * specialized logic.
 * 
 * @author kiriakos
 */
class EKindMozambiquePoint 
implements IMozambiquePoint{
    
    /**
     *
     * @immutable
     * @var integer
     */
    private $x;
    
    /**
     * 
     * @immutable
     * @var integer
     */
    private $y;
    
    function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }
    
    /**
     * 
     * @return integer
     */
    public final function getX(){ return $this->x; }
    
    /**
     * 
     * @return integer
     */
    public final function getY(){ return $this->y; }
    
    /**
     * Whether the point is on the same X plane as the passed point
     * 
     * @param \IMozambiquePoint $point
     * @return boolean
     */
    public function sameX(\IMozambiquePoint $point){
        return $this->getX() == $point->getX();
    }
    
    /**
     * Whether the point is on the same Y plane with the passed point
     * 
     * @param \IMozambiquePoint $point
     * @return type
     */
    public function sameY(\IMozambiquePoint $point){
        return $this->getY() == $point->getY();
    }
}