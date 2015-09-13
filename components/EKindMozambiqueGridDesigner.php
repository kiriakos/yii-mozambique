<?php
/**
 * Designs grids for mozambique
 * 
 * This is the responsibilty holder for Aranging tiles in a grid
 *
 * @author kiriakos
 */
class EKindMozambiqueGridDesigner 
extends CComponent 
implements IMozambiqueGridDesigner{
    
    /**
     *
     * @var IMozambiqueGrid
     */
    private $grid;
    
    /**
     *
     * @var IMozambiqueTile[]
     */
    private $tiles;
    
    public function __construct() {
        $this->grid = Yii::app()->mozambique->generateGrid();
    }

    public function getGrid() {
        return $this->grid;
    }

    public function layout() {
        
        foreach($this->tiles as $tile)
        {
            $success = $this->grid->addTile($tile);

            // addItem returns false if grid is full and can't be modified 
            // anymore. Should this happen the grid population should finish
            // and proceed with the required items.
            if($success == FALSE && $this->grid->heighten() == FALSE){
                break;
            }
        }

        $this->grid->fillGaps();
        $this->grid->stylize();
    }

    public function setTiles(CTypedList $tiles) {
        $this->tiles = $tiles->toArray();
    }

    public function order() {
        $sorter = Yii::app()->mozambique->generateSorter();
        usort($this->tiles, array($sorter,"sort"));
    }
}