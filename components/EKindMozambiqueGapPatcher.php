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
    
    private function getNeighbor(\EKindMozambiqueGap $gap, 
            \EKindMozambiquePoint $rel, 
            \IMozambiqueGrid $grid){
        $point = new EKindMozambiquePoint($gap->getX() + $rel->getX(), 
                $gap->getY() + $rel->getY());
        return $grid->getTile($point);
    }
    
    public function fillGap(\EKindMozambiqueGap $gap, \IMozambiqueGrid $grid) {
        foreach($this->neighborRels as $rel){
            $relPoint = new EKindMozambiquePoint($rel[0], $rel[1]);
            $tile = $this->getNeighbor($gap, $relPoint, $grid);
            
            if($tile instanceof IMozambiqueTile
                    && $this->tileCanExpandToGap($tile, $gap, $grid)){
                return $this->expandToGap($tile, $gap, $grid);      
            }
        }
    }
    
    
    private function tileCanExpandToGap(\IMozambiqueTile $tile,
            \EKindMozambiqueGap $gap, \IMozambiqueGrid $grid){
        if($gap->sameX($tile->getGridPosition())
                && $gap->get)
    }
    
    

    public function forceFillGap(\EKindMozambiqueGap $gap, \IMozambiqueGrid $grid) {
        
    }

}
