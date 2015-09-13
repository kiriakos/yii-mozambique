<?php
/**
 * Utility class that renders Grids
 *
 * @author kiriakos
 */
class EKindMozambiqueGridRenderer 
extends CComponent{
    
    /**
     *  The grid to be rendered
     * @var \IMozambiqueGrid
     */
    private $grid;
    
    public function __construct(\IMozambiqueGrid $grid) {
        $this->grid = $grid;
    }
    
    public function render($return = TRUE){
        
        $this->addEdgeClassesToTiles();

        $result = $this->renderOpenHtml($return);

        foreach($this->getTiles() as $tile){
            $result .= $tile->render($return);
        }

        $result .= $this->renderCloseHtml($return);
        
        if($return){
            return $result;
        }
        else{
            echo $result;
        }

    }
    
    /**
     * Get all Tile instances paritcipating in the Grid.
     * 
     * Falsy values are excluded from the returned result. This function will
     * return the Tiles in order of first appearance on the grid. This might 
     * differ from the actual apearance on HTML since stylize will also wrap
     * some tiles in subgrids.
     * 
     * @return \IMozambiqueTile
     */
    private function getTiles(){
        $items = array();
        
        foreach($this->grid->get2d() as $row){
            foreach($row as $tile){
                if($tile && !in_array($tile, $items)){
                    $items[] = $tile;
                }
            }
        }
        return $items;
    }
    
    /**
     * Adds the classes `first` and `last` to the left and right edge tiles.
     * 
     * Scans the grid and adds the `last' htmlClass to items of KindFrontPageItem
     *as well as addign the htmlClass `first' to items of type KindFrontPageGrid
     * @return null
     */
    private function addEdgeClassesToTiles(){
        
        //add the last and first classes
        foreach($this->grid->get2d() as $row){
            
            if ($row[count($row)-1] instanceof IMozambiqueTile){
                $row[count($row)-1]->addClass("last");
            }
            if($row[0] instanceof IMozambiqueGrid){
                $row[0]->addClass("first");
            }
        }
    }
    
    private function renderOpenHTML($return){
        $main = $this->grid->isMainGrid();
        $divType= ($main)?'tiles':'merge';
        $wClass = (!$main)?'wide-'.$this->grid->getWidth():'';
        $hClass = (!$main)?'high-'.$this->grid->getHeight():"";
        $endClasses = join(' ', $this->grid->getClasses());

        $result = "<div class=\"$divType $wClass $hClass $endClasses \">";
        
        if($return){
            return $result;
        }
        else{
            echo $result;
        }
    }

    private function renderCloseHTML($return)
    {
        $result = "</div>";
        
        if($return){
            return $result;
        }
        else{
            echo $result;
        }
    }
}