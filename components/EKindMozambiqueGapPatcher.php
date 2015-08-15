<?php
/**
 * Implementation of a gap patcher
 *
 * @author kiriakos
 */
class EKindMozambiqueGapPatcher 
implements IMozambiqueGapPatcher
{
    /**
     * Relative positions of the neigbors.
     * 
     * Array of touples.
     *
     * @var int[][] Toupes are encoded in X,Y format.
     */
    private $neighborRels = array(array(0,1),array(1,0),array(0,-1),array(-1,0));
    
    /**
     * A Convenience function to get $gap's neighbor based on a relative vector.
     * 
     * @param \EKindMozambiqueGap $gap      The gap anchoring the vector.
     * @param \EKindMozambiquePoint $vector The relative vector.
     * @param \IMozambiqueGrid $grid        The grid context.
     * @return \IMozambiqueTile
     */
    private function getNeighbor(
            \EKindMozambiqueGap $gap, 
            \EKindMozambiquePoint $vector, 
            \IMozambiqueGrid $grid){
        
        $point = Yii::app()->mozambique->generatePoint(
                $gap->getX() + $vector->getX(), 
                $gap->getY() + $vector->getY());
        
        try{
            return $grid->getTile($point);
        }
        catch(EKindMozambiqueSizeOutOfBoundsException $e){
            return FALSE;
        }
    }
    
    public function fillGap(\EKindMozambiqueGap $gap, \IMozambiqueGrid $grid) {
        
        foreach($this->neighborRels as $rel){
            $vector = Yii::app()->mozambique->generatePoint($rel[0], $rel[1]);
            $tile = $this->getNeighbor($gap, $vector, $grid);
            
            if($tile instanceof IMozambiqueTile
                    && $this->tileCanExpandToGap($tile, $gap, $grid)){
                return $this->expandToGap($tile, $gap, $grid);      
            }
        }
    }
    
    /**
     * Tests whether a tile can expand into a gap without incidents
     * 
     * @param \IMozambiqueTile $tile
     * @param \EKindMozambiqueGap $gap
     * @param \IMozambiqueGrid $grid
     * @return boolean
     */
    private function tileCanExpandToGap(\IMozambiqueTile $tile,
            \EKindMozambiqueGap $gap, \IMozambiqueGrid $grid){
        
        if($gap->sameX($tile->getGridPosition())
                && $tile->getWidth() == 1
                && $tile->canHeighten()){            
            return TRUE;
        }
        elseif($gap->sameY($tile->getGridPosition())
                && $tile->getHeight() == 1
                && $tile->canWiden()){
            return TRUE;
        }
        else{
            return FALSE;
        }    
    }
    
    /**
     * Expands a tile into a gap
     * 
     * @param \IMozambiqueTile $tile
     * @param \EKindMozambiqueGap $gap
     * @param \IMozambiqueGrid $grid
     * @return boolean
     */
    private function expandToGap(\IMozambiqueTile $tile,
            \EKindMozambiqueGap $gap, \IMozambiqueGrid $grid){
        
        if($gap->sameX($tile->getGridPosition())
                && $tile->canHeighten()){
            
            $grid->removeTile($tile);
            $tile->heighten();
            $grid->addTile($tile);
            return TRUE;
        }
        elseif($gap->sameY($tile->getGridPosition())
                && $tile->canWiden()){
            
            $grid->removeTile($tile);
            $tile->widen();
            $grid->addTile($tile);
            return TRUE;
        }
        else{
            return FALSE;
        }    
    }

    public function forceFillGap(\EKindMozambiqueGap $gap, 
            \IMozambiqueGrid $grid) {
        $rels = array_values($this->neighborRels);
        shuffle($rels);
        
        foreach($rels as $rel){
            $vector = new EKindMozambiquePoint($rel[0], $rel[1]);
            $tile = $this->getNeighbor($gap, $vector, $grid);
            
            if($tile instanceof IMozambiqueTile){
                return $this->forceExpandToGap($tile, $gap, $grid);      
            }
        }        
    }
    
    /**
     * Forcefully Expands a tile into a gap
     * 
     * This will forcefully expand the tile into the space occupied by the gap.
     * This function does not honor canXXX type methods and might even generate
     * more gaps.
     * 
     * @param \IMozambiqueTile $tile
     * @param \EKindMozambiqueGap $gap
     * @param \IMozambiqueGrid $grid
     * @return boolean
     */
    private function forceExpandToGap(\IMozambiqueTile $tile,
            \EKindMozambiqueGap $gap, \IMozambiqueGrid $grid){
        $x = $gap->getX() - $tile->getGridPosition()->getX();
        $y = $gap->getY() - $tile->getGridPosition()->getY();
        
        if($x < 0 || $x == $tile->getWidth()){
            $yStart = $tile->getGridPosition()->getY();
            $yEnd = $yStart + $tile->getHeight() - 1;
            $col = $gap->getX();
            $points = $this->genPointsColumn($col, $yStart, $yEnd);
            $function = "widen";
        }
        elseif($y < 0 || $y == $tile->getHeight()){
            $xStart = $tile->getGridPosition()->getX();
            $xEnd = $xStart + $tile->getWidth() - 1;
            $row = $gap->getY();
            $points = $this->genPointsRow($row, $xStart, $xEnd);
            $function = "heighten";
        }
        
        $grid->eradicate($points);
        $grid->
        call_user_func(array($tile, $function));
        
    }
    
    /**
     * Generates a list of points for a column
     * 
     * @param integer $column   The X dimension. (remains constant)
     * @param integer $yStart
     * @param integer $yEnd
     * @return \IMozambiquePoint[]
     */
    private function genPointsColumn($column, $yStart, $yEnd){
        $points = array();
        for($y = $yStart; $y <= $yEnd; $y++){
            $points[] = Yii::app()->mozambique->generatePoint($column, $y);
        }
        
        return $points;
    }
    
    /**
     * Returns a list of points in a row.
     * 
     * @param integer $row      The Y dimension. (remains constant)
     * @param integer $xStart
     * @param integer $xEnd
     * @return \IMozambiquePoint[]
     */
    private function genPointsRow($row, $xStart, $xEnd){
        $points = array();
        for($x = $xStart; $x <= $xEnd; $x++){
            $points[] = Yii::app()->mozambique->generatePoint($x, $row);
        }
        
        return $points;
    }
 
}
