<?php
/**
 * Base implementation of a grid
 * 
 * Tries to encapsulate as much as possible. Does not allow for manual adding 
 * or removing of tiles, provides the indirect addTile and removeTile methods.
 *
 * @author kiriakos
 */
class EKindMozambiqueGrid 
extends EKindMozambiqueAbstractTile 
implements IMozambiqueGrid{
    
    private $grid;
    
    public function __construct($width = 1, $height = 1) {
        
        parent::__construct($width, $height);
        
        $row = array_fill(0, $width, NULL);
        $grid = array_fill(0, $height, NULL);
        for($i = 0; $i < $height; $i++){
            $grid[$i] = array_values($row);
        }
        
        $this->grid = $grid;
    }
            
    public function getDesiredDimensions() {
        
    }

    public function getId() {
        
    }

    public function getLastTimestamp() {
        
    }

    public function render($return = TRUE) {
        
    }    
    
    /**
     * Gets the gaps that exist in the current layout
     * 
     * @return EKindMozambiqueGap[] An array of Cartesian Points
     */
    private function getGaps(){
        
        $gaps = array();
        for ($y = 0; $y < $this->getHeight(); $y++) {
            for ($x = 0; $x < $this->getWidth(); $x++) {
                $point = new EKindMozambiquePoint($x, $y);
                $tile = $this->getTile($point);
                
                if($tile === FALSE){
                    $gaps[] = $point;
                }
            }
        }

        return $gaps;
    }

    
    /**
     * Get the Tile on a specific grid coordinate
     * 
     * Zero based coordinates.
     * 
     * @param \IMozambiquePoint $point
     * @return \IMozambiqueTile
     */
    public function getTile(\IMozambiquePoint $point) {
        
        $x = $point->getX();
        $y = $point->getY();
        
        if($x < 0 || $y < 0){
            throw new EKindMozambiqueSizeOutOfBoundsException("X and Y must"
                    . " be non negative integers!");
        }
        elseif ($this->getWidth() > $x && $this->getHeight() > $y) {
            return $this->grid[$y][$x];
        }
        else {
            throw new EKindMozambiqueSizeOutOfBoundsException("Grid Size"
                    . " {$this->getWidth()} x {$this->getHeight()}. Item"
                    . "requested, $x x $y");
        }
    }

    public function addTile(\IMozambiqueTile $tile) {
        
        $counter = 10;
        while(($added = $this->tryAdding($tile)) === FLASE && --$counter){
            $tile->unWiden() || $tile->unHeighten() || $this->heighten();
        }

        return $added;
    }
    
    /**
     * Tries Adding a Tile to the grid.
     * 
     * Will return FALSE if any issues arise.
     * 
     * @return boolean
     */
    private function tryAdding(\IMozambiqueTile $tile){
        $dims = $tile->getDimensions();
        $position = $this->getSpace($dims);
        
        if ($position){
            return $this->setItem($position, $tile);
        }
        
        return FALSE;
    }
    
    public function removeTile(\IMozambiqueTile $tile){
        foreach($this->grid as $r=>$row){
            foreach($row as $t=>$content){
                if($content && $tile === $content){
                    $this->
                    $this->setTile(array($t,$r));
                }
            }
        }
    }

    public function fillGaps() {
        $gapPatcher = Yii::app()->mozambique->generateGapPatcher();
        $gaps = $this->getGaps();
        while($gaps){
            if(!$gapPatcher->fillGap($gap, $this)){
                $gapPatcher->forceFillGapp($gap, $this);
            }
            
            $gaps = $this->getGaps();
        }
    }

    public function getDimensions() {
        return array($this->getWidth(), $this->getHeight());
    }

}