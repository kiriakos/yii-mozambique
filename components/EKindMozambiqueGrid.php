<?php
/**
 * Description of EKindMozambiqueGrid
 *
 * @author kiriakos
 */
class EKindMozambiqueGrid 
extends EKindMozambiqueAbstractTile 
implements IMozambiqueGrid{
    
    public function getDesiredDimensions() {
        
    }

    public function getId() {
        
    }

    public function getLastTimestamp() {
        
    }

    public function render($return = TRUE) {
        
    }
    
    private function callcualteHypoDims(\IMozambiqueTile $tile){
        
        $hypoDims = array();
        $dim = $tile->getDimensions();
                
        if ($tile->canHeighten()){
            $hypoDims[] = array($dim[0],$dim[1]+1);
        }
        
        if ($tile->canWiden()){
            $hypoDims[] = array($dim[0]+1,$dim[1]);
        }
        
        if ($tile->canUnHeighten()){
            $hypoDims[] = array($dim[0],$dim[1]-1);
        }
        
        if ($tile->canUnWiden()){
            $hypoDims[] = array($dim[0]-1,$dim[1]);
        }

        return $hypoDims;
    }
    
    
    /**
     * Tries to fill all gaps
     */
    public function fillGaps(){
        
        while ( ($gaps = $this->getGaps())) {
            foreach ($gaps as $gap) {
                $filled = $this->fillGap($gap);
                
                if(!$filled){
                    $this->forceFillGap($gap);
                }
            }
        }
    }
    
    /**
     * Tries to fill a gap without creating new ones
     * 
     * @param \EKindMozambiqueGap $gap
     * @return boolean
     */
    private function fillGap (\EKindMozambiqueGap $gap) {
        $tiles = $gap->getAdjoiningTiles($this);
        
        foreach ($tiles as $tile){
            if($tile->expandTo($gap, $this)){
                return TRUE;
            }
        }
        
        return FALSE;
    }

    /**
     * Fills a gap possibly creating new ones
     * 
     * @param \EKindMozambiqueGap $gap
     * @return boolean
     */
    private function forceFillGap (\EKindMozambiqueGap $gap) {
        $tiles = $gap->getAdjoiningTiles($this);
        shuffle($tiles);
        
        $tile = $tiles[0];
        return $tile->forceExpandTo($gap, $this);
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
                $tile = $this->getItem($point);
                
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
     * @param \EKindMozambiquePoint $point
     * @return \IMozambiqueTile
     */
    public function getTile(\EKindMozambiquePoint $point) {
        
        $x = $point->getX();
        $y = $point->getY();
        
        if($x < 0 || $y < 0){
            throw new EKindMozambiqueSizeOutOfBoundsException("X and Y must"
                    . " be non negative integers!");
        }
        elseif ($this->getWidth() > $x && $this->getHeight() > $y) {
            return $this->grid[$point->getY()][$point->getX()];
        }
        else {
            throw new EKindMozambiqueSizeOutOfBoundsException("Grid Size"
                    . " {$this->getWidth()} x {$this->getHeight()}. Item"
                    . "requested, $x x $y");
        }
    }
    
    

}