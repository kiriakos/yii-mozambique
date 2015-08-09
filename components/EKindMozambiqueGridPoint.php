<?php

/**
 * A Touple representing a point on an IMozambiqueGrid
 *
 * @author kiriakos
 */
class EKindMozambiqueGridPoint 
extends EKindMozambiquePoint{
        
    /**
     *
     * @var IMozambiqueGrid
     */
    private $grid;
    
    function __construct($x, $y, IMozambiqueGrid $grid){
        parent::__construct($x, $y);
        $this->grid = $grid;
    }
    
    /**
     * 
     * @return IMozambiqueGrid
     */
    public function getGrid(){ return $this->grid; }
    
    public function getTile(){ return $this->grid->getTile($this); }
}
