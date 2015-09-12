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
    private $grid;
    
    /**
     *  The grid to be rendered
     * @var \IMozambiqueGrid
     */
    private $gridObject;
    
    public function __construct(\IMozambiqueGrid $grid) {
        $this->grid = &$grid->get2d();
        $this->gridObject = $grid;
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
        
        $width = $this->gridObject->getWidth();

        foreach(array_keys($this->grid) as $rowId){            
            for($tile=0; $tile<$width-1; $tile++){   
                
                $nextTile = $this->grid[$rowId][$tile+1];
                $currentTile = $this->grid[$rowId][$tile];
                
                if($nextTile && ($currentTile !== $nextTile )){
                    $this->ensureTileMarkupable($rowId, $tile);
                }
            }
        }
    }
    
    /**
     * Ensures that the tile specified by the arguments is renderable
     * 
     * In case the next tile... ToDo
     * 
     * @param int $rowId
     * @param int $tile
     */
    private function ensureTileMarkupable($rowId, $tile){
        $nextTile = $this->grid[$rowId][$tile+1];
        $currentTile = $this->grid[$rowId][$tile];
                
        if(!$currentTile){
            $merge = new KindFrontPageItem (($rowId+1)*($tile+1), Image::model(), 1, 1);//Art 1x1 @ 3,2
            $merge->setGridPosition(array($tile, $rowId));
        }else{
            $merge = $currentTile; //Art 1x1 @ 3,2
        }
        
        $mergePosition = $merge->getGridPosition(); // 3,2
        $mergeRow = $mergePosition[1]; //2

        $obstacle = $nextTile; //Gal 1x2 @ 4,2
        $obstaclePosition = $obstacle->getGridPosition();// 4,2
        $obstacleRow = $obstaclePosition[1];// 2

        $rowDiff = $mergeRow - $obstacleRow; //goes neg or zero  //2-2=0

        $oAllowHeight =$merge->getHeight() + $rowDiff;//1+0=1
        
        if($obstacle->getHeight() > $oAllowHeight){//2>1 -> true
            $this->insertMergeTile($merge, $obstacle, $tile);
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
        $this->removeItemsFromGrid($items);
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
     * Get the items defined by the rect $left, $top, $right, $bottom
     *
     * the dims are given inclusive, getItems(0,0,1,2) will return max 6 items
     * (0.0, 0.1, 0.2, 1.0, 1.1, 1.2) items that consume multiple positions are
     * returned only once
     *
     * @param integer[] $rect       (left,top,right,bottom)
     * @return \IMozambiqueTile[]
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
            if($row == $this->height)//precaution for an ill computed merge grid
                    return $items;
            for($tile=$left;$tile<=$right;$tile++){ //added check for False condition on if
                if($this->grid[$row][$tile] && !in_array($this->grid[$row][$tile], $items, true))
                        $items[]=$this->grid[$row][$tile];
            }
        }

        return $items;
    }
    
    /**
     *  Sets the tiles on which $items are found to false
     *
     * @param \IMozambiqueTile[] $items
     */
    private function removeItemsFromGrid($items){
        
        foreach ($items as $item)
            foreach($this->grid as $r=>$row){
                foreach($row as $t=>$tile){
                    if($tile && $tile === $item){
                        $this->setTile (array($t,$r));
                    }
                }
            }
        }
        
    }
}