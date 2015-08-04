<?php
/**
 * A gap in the grid
 * 
 * partially simulates a tile without inheriting from one
 *
 * @author kiriakos
 */
class EKindMozambiqueGap 
extends EKindMozambiquePoint{
    
    /**
     * Get the Tiles located Over, Right, Down and Left of the gap
     * 
     * @param \IMozambiqueGrid $grid
     * @return \IMozambiqueTile[]
     */
    public function getAdjoiningTiles(\IMozambiqueGrid $grid){
        $neighbors = array();
        $coordinates = $this->calculateValidNeighborCoordinates($grid);
        
        foreach($coordinates as $point){
            $neighbors[] = $grid->getTile($point);
        }
            
        $tiles = new CTypedList("ITile");
        
        foreach($neighbors as $neighbor){
            if($neighbor instanceof IMozambiqueTile){
                $tiles->add($neighbor);
            }
        }
        
        return $tiles;
    }
    
    /**
     * Return an array of points that represent valid points on the passed grid
     * 
     * @param \IMozambiqueGrid $grid
     * @return \EKindMozambiquePoint[]
     */
    private function calculateValidNeighborCoordinates(\IMozambiqueGrid $grid){
        $x = $this->getX();
        $y = $this->getY();
        
        $coords = [
            new EKindMozambiquePoint($x - 1, $y),
            new EKindMozambiquePoint($x + 1, $y),
            new EKindMozambiquePoint($x, $y - 1),
            new EKindMozambiquePoint($x, $y + 1),
        ];
        
        foreach($coords as $index => $point){
            $px = $point->getX();
            $py = $point->getY();
            if ($px < 0 || $py < 0 
                    || $px >= $grid->getWidth()
                    || $py >= $grid->getHeight()){
                unset($coords[$index]);
            }
        }
        
        return array_values($coords);
    }
}
