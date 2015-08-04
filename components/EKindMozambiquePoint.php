
<?php
/**
 * A Touple representing a point on a Cartesian Surface
 * 
 * @author kiriakos
 */
class EKindMozambiquePoint {
    
    /**
     *
     * @var integer
     */
    private $x;
    
    /**
     *
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
    public function getX(){ return $this->x; }
    
    /**
     * 
     * @return integer
     */
    public function getY(){ return $this->y; }
    
}
