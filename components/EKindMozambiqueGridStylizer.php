<?php
/**
 * Takes care of EKindMozambiqueGrid::stylize()
 *
 * @author kiriakos
 */
class EKindMozambiqueGridStylizer {
    
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
    
    public function __construct(\IMozambiqueGrid $grid) {
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
     * This implementation traverses the Grid and calls a box model enforcing
     * method in on every Tile boundary of each row.
     */
    public function stylize(){
        
        $width = $this->grid->getWidth();

        foreach(array_keys($this->grid2D) as $rowId){            
            for($tileId=0; $tileId<$width-1; $tileId++){   
                
                $nextTile = $this->grid2D[$rowId][$tileId+1];
                $currentTile = $this->grid2D[$rowId][$tileId];
                
                if($nextTile && ($currentTile !== $nextTile )){
                    $this->ensureTileMarkupable($rowId, $tileId);
                }
            }
        }
    }
    
    /**
     * Ensures that the tile specified by the arguments is renderable
     * 
     * If the tile undert $rowId, $tile  is a gap (NULL, FALSE or an instance of 
     * EKindMozambiqueGap) a dummy item is generated.
     * 
     * In case the next tile... ToDo
     * 
     * @param int $rowId
     * @param int $tileId
     */
    private function ensureTileMarkupable($rowId, $tileId){
        $obstacleTile = $this->grid2D[$rowId][$tileId+1];
        $mergeTile = $this->grid2D[$rowId][$tileId];
                
        if( !$mergeTile || $mergeTile instanceof EKindMozambiqueGap){
            $placeholder = Image::model()->latest->visible->find();
            $mergeTile = Yii::app()->mozambique->generateTile($placeholder, 1,1);
            $mergeTile->setGridPosition(array($tileId, $rowId));
        }
        
        $mergePosition = $mergeTile->getGridPosition(); // 3,2
        $mergeRow = $mergePosition[1]; //2

        $obstaclePosition = $obstacleTile->getGridPosition();// 4,2
        $obstacleRow = $obstaclePosition[1];// 2

        $rowDiff = $mergeRow - $obstacleRow; //goes neg or zero  //2-2=0

        $oAllowHeight =$mergeTile->getHeight() + $rowDiff;//1+0=1
        
        if($obstacleTile->getHeight() > $oAllowHeight){//2>1 -> true
            $this->insertMergeTile($mergeTile, $obstacleTile, $tileId);
        }
    }
    
    private function insertMergeTile($merge, $obstacle, $tile){
        $mergePosition = $merge->getGridPosition(); // 3,2
        $mergeRow = $mergePosition[1]; //2
        
        $obstaclePosition = $obstacle->getGridPosition();// 4,2
        $obstacleRow = $obstaclePosition[1];// 2

        $mergeFromRow = $mergeRow; //2
        $mergeToRow = $obstacleRow + $obstacle->getHeight() -1; //-1 inclussive rows //2+2-1=3

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