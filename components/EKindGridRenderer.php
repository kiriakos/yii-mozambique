<?php
/**
 * Utility class that renders Grids
 *
 * @author kiriakos
 */
class EKindGridRenderer 
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
        
        $this->addItemPositionalModifyerClasses();

        $result = $this->renderOpenHtml( !$this->grid->isSubGrid(), $return);

        foreach($this->grid->getItems() as $item){
            if($item){
                $result .= $item->render($return);
            }
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
     * Scans the grid and adds the `last' htmlClass to items of KindFrontPageItem
     *
     * as well as addign the htmlClass `first' to items of type KindFrontPageGrid
     * @return null
     */
    private function addItemPositionalModifyerClasses(){
        //add the last and first classes
        foreach($this->grid as $row){
            if ($row[count($row)-1] instanceof KindFrontPageItem){
                $row[count($row)-1]->classes[] = 'last';
            }
            if($row[0] instanceof KindFrontPageGrid){
                $row[0]->classes[]= 'first';
            }
        }
    }
    
    private function renderOpenHTML($mainGrid, $return){
        $divType= ($mainGrid)?'tiles':'merge';
        $wClass = (!$mainGrid)?'wide-'.$this->getWidth():'';
        $hClass = (!$mainGrid)?'high-'.$this->getHeight():"";
        $endClasses = join(' ', $this->classes);

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
    
    public function stylize(){
        
    }
}