<?php
/**
 * WARNING: Incomplete, does not work. use EKindMozambiqueAbsoluteGridStylizer!
 * 
 * Modifies a grid that is aranged by floating the tiles so that it can 
 * correctly be displayed
 * 
 * Mozambique Tiles are floated to the left in CSS. This Stylizer makes sure 
 * that all tiles can float where they belong to.
 *
 * @author kiriakos
 */
class EKindMozambiqueFloatedGridStylizer 
extends CComponent
implements IMozambiqueGridStylizer{
    
    /**
     * Reference to the Grid's 2D representation
     * 
     * This is usually a 2D array. assigned via forced referencing.
     * Any Changes to this will also propagate to the actual grid.
     * 
     * @var \IMozambiqueTile[][]
     */
    private $grid2D;
    
    /**
     *  The grid to be rendered
     * @var \IMozambiqueGrid
     */
    private $grid;
    
    private function setGrid(\IMozambiqueGrid $grid) {
        $this->grid2D = &$grid->get2d();
        $this->grid = $grid;
    }
    
    
    /**
     * Makes sure the Grid's contents can be rendered with the used box model.
     *  
     * Create merge groups, assign classes to each tile item. In some cases
     * a tile may not be renderable because the active box model would render it
     * in some other position. In this case subgrids are created to remedy the
     * situation.
     * 
     * This implementation traverses the Grid in reverse (bottom right to top 
     * left) and spawns subgrids every time it encounters a right tile with a
     * bottom value lower than its left counterpart.
     * 
     * @return void
     */
    public function stylize(\IMozambiqueGrid $grid){
        $this->setGrid($grid);
        
        $width = $this->grid->getWidth();
        $height = $this->grid->getHeight();
        
        //This is a naive Inspection, maybe there is a better algo
        for($rowId=$height-1; $rowId>=0; $rowId--){
            for($tileId=$width-1; $tileId>0; $tileId--){   

                $this->patchMissingTiles($rowId, $tileId);
                
                $left = $this->grid2D[$rowId][$tileId-1];
                $right = $this->grid2D[$rowId][$tileId];
                
                if($right !== $left 
                        && $right->getBottom() > $left->getBottom()){
                    $this->insertMergeTile($leftTile, $rightTile);
                }
            }
        }
    }
    
    /**
     * Makes sure that the tiles around the seam are Mozambique Tiles
     * 
     * A seam is the edge between two tiles. Seams are zero based dimenstions.
     * Between tile two and three of the same grid row the seam takes the value
     * of Tile3->getGridPosition()->getX().
     * 
     * @param integer $row
     * @param integer $seam
     */
    private function patchMissingTiles($row, $seam){
        $left = $this->grid2D[$row][$seam-1];
        $right = $this->grid2D[$row][$seam];

        if( !$left || $left instanceof EKindMozambiqueGap){
            $placeholder = Image::model()->latest->visible->find();
            $left = Yii::app()->mozambique->generateTile($placeholder, 1,1);
            $left->setGridPosition(array($seam-1, $row));
            $this->grid2D[$row][$seam-1] = $left;
        }

        if( !$right || $right instanceof EKindMozambiqueGap){
            $placeholder = Image::model()->latest->visible->find();
            $right = Yii::app()->mozambique->generateTile($placeholder, 1,1);
            $right->setGridPosition(array($seam, $row));
            $this->grid2D[$row][$seam] = $right;
        }
    }
        
    /**
     * Spawns a box model enabling subgrid.
     * 
     * @param integer $row
     * @param integer $seam
     */
    private function insertMergeTile($row,$seam){
        $gridHeight=1;
        
        $mergePosition = $left->getGridPosition(); // 3,2
        $mergeRow = $mergePosition[1]; //2
        
        $obstaclePosition = $right->getGridPosition();// 4,2
        $obstacleRow = $obstaclePosition[1];// 2

        $mergeFromRow = $mergeRow; //2
        $mergeToRow = $obstacleRow + $right->getHeight() -1; //-1 inclussive rows //2+2-1=3

        $subGrid = $this->merge( array(
            0,      //$left
            $mergeFromRow, //$top =2
            $tile,     //$right =3
            $mergeToRow //$bottom inclusive coords =3
        ));
        $subGrid->fillGaps();
        $subGrid->stylizeGrid();

    }
            

    /**
     *  Create a subgrid to merge small tile cluster into bigger ones
     *
     * @param integer[] $rect       (left,top,right,bottom)
     * @return null
     */
    private function merge($rect){ //0,2,3,3
        
        $width = $rect[2]-$rect[0]+1; //+1 because the rect is inclusive //3-0+1=4
        $height= $rect[3]-$rect[1]+1; // 3-2+1=2

        $items = $this->getItems($rect); //0,2,3,3
        $this->grid->removeTiles($items);
        $subg = Yii::app()->mozambique->generateGrid($width, $height);
                // new KindFrontPageGrid($width, $height);
        $subg->setIsSubGrid(true);

        foreach ($items as $item){
            $subg->addItem($item); //ToDo: this methodology of merging seems flawed
        }

        $this->setItem(array($rect[0],$rect[1]), $subg);

        return $subg;
    }
    
    /**
     *  Create a subgrid to merge small tile cluster into bigger ones
     *
     * @param integer[] $rect       (left,top,right,bottom)
     * @return null
     */
    private function mergeRect(\EKindMozambiqueRect $rect){

        $items = $this->getItemsRect($rect);
        $this->grid->removeTiles($items);
        $subg = Yii::app()->mozambique->generateGrid($rect->getWidth(), 
                $rect->getHeight());
        $subg->setIsSubGrid(true);

        foreach ($items as $item){
            $subg->addItem($item); //ToDo: this methodology of merging seems flawed
        }

        $this->setItem(array($rect[0],$rect[1]), $subg);

        return $subg;
    }
    
    /**
     * Get the items defined by the rect $left, $top, $right, $bottom
     *
     * the dims are given inclusive, getItems(0,0,1,2) will return max 6 items
     * (0.0, 0.1, 0.2, 1.0, 1.1, 1.2) items that consume multiple positions are
     * returned only once
     *
     * @param EKindMozambiqueRect $rect
     * @return \IMozambiqueTile[]
     */
    private function getItemsRect(EKindMozambiqueRect $rect) {
        
        $items = array();

        for($row=$rect->getTop(); $row<=$rect->getBottom(); $row++){
            
            //precaution for an ill computed merge grid
            if($row == $this->grid->getHeight()){
                return $items;
            }
                
            for($tile=$rect->getLeft(); $tile<=$rect->getRight(); $tile++){ 
                
                //added check for False condition on if
                if($this->grid2D[$row][$tile] 
                        && !in_array($this->grid2D[$row][$tile], $items, true)){
                    $items[]=$this->grid2D[$row][$tile];
                }
            }
        }

        return $items;
    }
}