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
    
    private $isMainGrid = TRUE;
    
    private $renderer;
    private $stylizer;
    
    private $id;
    
    public function __construct($width = 1, $height = 1) {
        
        parent::__construct($width, $height);
        
        $grid = array_fill(0, $height, FALSE);
        for($i = 0; $i < $height; $i++){
            $grid[$i] = array_fill(0, $width, FALSE);
        }
        
        $this->grid = $grid;
        $this->id = Yii::app()->mozambique->generateUuid();
        $this->renderer = Yii::app()->mozambique->generateGridRenderer($this);
        $this->stylizer = Yii::app()->mozambique->getGridStylizer();
        
        $point = Yii::app()->mozambique->generatePoint(0, 0);
        $this->setGridPosition($point);
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
                $gap = Yii::app()->mozambique->generateGap($x, $y);
                $tile = $this->getTile($gap);
                
                if($tile === FALSE || $tile instanceof EKindMozambiqueGap){
                    $gaps[] = $gap;
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
        $row = array_fill(0, $this->getWidth(), FALSE);
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
            $this->assignTile($tile, $position);
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Sets the values of the affected grid cels to be the tile.
     * 
     * @param \IMozambiqueTile $tile
     * @param \EKindMozambiquePoint $position
     * @return void
     */
    private function assignTile(\IMozambiqueTile $tile, 
            \EKindMozambiquePoint $position){
        
        $rowStart = $position->getY();
        $rowEnd = $rowStart + $tile->getHeight(); //exclusive
        
        $colStart = $position->getX();
        $colEnd = $colStart + $tile->getWidth(); //exclusive
        
        for($row=$rowStart; $row<$rowEnd; $row++){
            for($col=$colStart; $col<$colEnd; $col++){
                $this->grid[$row][$col] = $tile;
            }
        }
        
        $tile->setGridPosition($position);
    }
        
    /**
     * Get a free patch in the grid based on passed dimensions.
     * 
     * iterates over the grid to identify empty points
     * 
     * @param integer[] $dims
     * @return \IMozambiquePoint
     */
    private function getSpace($dims){
        
        foreach ($this->grid as $rowId => $row){
            foreach (array_keys($row) as $tileId){
                if ($this->tileIsEmpty($this->grid[$rowId][$tileId])
                        && $this->tilesAreEmpty(array($tileId, $rowId), $dims)){
                    return Yii::app()->mozambique->generatePoint(
                            $tileId, $rowId);
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
     * @param integer[] $position   XxY Col*Row
     * @param integer[] $dims       WxH
     * @return boolean
     */
    private function tilesAreEmpty($position,$dims)
    {
        $colStart = $position[0];
        $rowStart = $position[1];
        
        $colEnd = $colStart + $dims[0]; // Exclusive
        $rowEnd = $rowStart + $dims[1]; // Exclusive
                
        if($colEnd > $this->getWidth()
                || $rowEnd > $this->getHeight()){
            return FALSE;
        }
        
        for($row=$rowStart; $row<$rowEnd; $row++){
            for($col=$colStart; $col<$colEnd; $col++){
                if (!$this->tileIsEmpty($this->grid[$row][$col])){
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }
    
    /**
     * 
     * @param \IMozambiqueTile[] $tiles
     */
    public function removeTiles($tiles){
        foreach($this->grid as $r=>$row){
            foreach($row as $t=>$tile){
                if(in_array($tile, $tiles)){
                    $this->grid[$r][$t] = FALSE;
                }
            }
        }
        
        foreach($tiles as $tile){
            $tile->unsetGridPosition();
        }
    }
    
    public function removeTile(\IMozambiqueTile $tile){
        
        foreach($this->grid as $r=>$row){
            foreach($row as $t=>$content){
                if($content && $tile === $content){
                    $this->grid[$r][$t] = FALSE;
                }
            }
        }
        
        $tile->unsetGridPosition();
    }

    public function fillGaps() {
        $gapPatcher = Yii::app()->mozambique->generateGapPatcher();
        $gaps = $this->getGaps();
        while($gaps){
            if(!$gapPatcher->fillGap($gaps[0], $this)){
                $gapPatcher->forceFillGapp($gap[0], $this);
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
     * Produces a visual representation of the grid;
     * 
     * @return String[][]
     */
    public function getDebugGrid(){
        $grid = array();
        
        foreach($this->grid as $row){
            $rowa = array();
            
            foreach($row as $tile){
                
                if($tile instanceof IMozambiqueTile){
                    $rowa[] = $tile->getId();
                }
                else{
                    $rowa[] = $tile;
                }
                    
            }
            $grid[] = $rowa;
        }
        
        return $grid;
    }
    
    public function stylize(){
        $this->stylizer->stylize($this);
    }

    public function isMainGrid() {
        return $this->isMainGrid;
    }

    public function setMainGrid($boolean) {
        $this->isMainGrid = $boolean;
    }

}