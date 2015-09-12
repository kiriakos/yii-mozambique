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
    
    /**
     *  A 2D array of tiles
     * @var IMozambiqueTile[][]
     */
    private $grid;
    
    private $renderer;
    
    private $id;
    
    public function __construct($width = 1, $height = 1) {
        
        parent::__construct($width, $height);
        
        $row = array_fill(0, $width, NULL);
        $grid = array_fill(0, $height, NULL);
        for($i = 0; $i < $height; $i++){
            $grid[$i] = array_values($row);
        }
        
        $this->grid = $grid;
        $this->id = Yii::app()->mozambique->generateUuid();
        $this->renderer = Yii::app()->mozambique->generateGridRenderer($this);
    }
            
    public function getDesiredDimensions() {
        throw new CException("getDesiredDimensions() is not Implemented!");
    }

    public function getId() {
        return $this->id;
    }

    public function render($return = TRUE) {
        return $this->renderer->render($return);
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
        
        if( $x < 0 || $y < 0 ){
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
        while(($added = $this->tryAdding($tile)) === FALSE && --$counter){
            if($tile->canUnwiden()){
                $tile->unWiden();
            }
            elseif($tile->canUnHeighten()){
                $tile->unHeighten();
            }
            elseif($this->canHeighten()){
                $this->heighten();
            }
        }

        return $added;
    }
    
    public function heighten() {
        $row = array_fill(0, $this->getWidth(), NULL);
        $this->grid[] = $row;
        
        parent::heighten();
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
    
    
    /**
     * Get a free patch in the grid based on passed dimensions.
     * 
     * iterates over the grid to identify empty points
     * 
     * @param integer[] $dims
     * @return \IMozambiquePoint
     */
    private function getSpace($dims)
    {
        foreach ($this->grid as $rowId => $row){
            foreach ($row as $tileId => $tile){
                if ($this->tileIsEmpty($tile) 
                        && $this->tilesAreEmpty(array($tileId, $rowId), $dims)){
                    return Yii::app()->mozambique->generatePoint(
                            $tileId,$rowId);
                }
            }
        }

        return FALSE;
    }
    
    private function tileIsEmpty($tile){
        return $tile === FALSE || $tile instanceof EKindMozambiqueGap;
    }
    
    /**
     *
     * @param integer[] $position hxw
     * @param integer[] $dims   wxh
     * @return boolean
     */
    private function tilesAreEmpty($position,$dims)
    {
        //for each row of the grid
        for($i=$position[1]; $i<$position[1]+$dims[1]; $i++){
            //for each tile in row
            for($j=$position[0]; $j<$position[0]+$dims[0]; $j++){
                
                if ( $i > $this->height-1 || $j > $this->width-1 || 
                        !$this->tileIsEmpty($this->grid[$i][$j])){
                    return FALSE;
                }
            }
        }

        return TRUE;
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

    public function get2d() {
        return $this->grid;
    }
    
    /**
     * Get the items defined by the rect $left, $top, $right, $bottom
     *
     * the dims are given inclusive, getItems(0,0,1,2) will return max 6 items
     * (0.0, 0.1, 0.2, 1.0, 1.1, 1.2) items that consume multiple positions are
     * returned only once
     *
     * @param integer[] $rect       (left,top,right,bottom)
     * @return IMozambiqueTile[]
     */
    public function getItems($rect=false) {
        if($rect){
            $left   = $rect[0];
            $top    = $rect[1];
            $right  = $rect[2];
            $bottom = $rect[3];
        }else{
            $left   = 0;
            $top    = 0;
            $right  = $this->width-1;
            $bottom = $this->height-1;
        }

        $items = array();

        for($row=$top;$row<=$bottom;$row++){
            //precaution for an ill computed merge grid
            if($row == $this->height){
                return $items;
            }
            for($tile=$left;$tile<=$right;$tile++){ 
                //added check for False condition on if
                if($this->grid[$row][$tile] 
                        && !in_array($this->grid[$row][$tile], $items, true)){
                    $items[]=$this->grid[$row][$tile];
                }
            }
        }

        return $items;
    }
    
    public function stylize(){
        $this->renderer->stylize();
    }
}