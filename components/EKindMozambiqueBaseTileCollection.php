<?php
/**
 * Standard Implementation of IMozambiqueTileCollection
 *
 * Just a Value Object
 * 
 * @author kiriakos
 */
class EKindMozambiqueBaseTileCollection 
implements IMozambiqueTileCollection{
    
    private $tiles;
    private $pagination;
    
    public function __construct(\CTypedList $tiles, 
            \IMozambiquePagination $pagination){
     
        $this->tiles = $tiles;
        $this->pagination = $pagination;
    }
    
    public function getTiles() {
        return $this->tiles;
    }

    public function getPagination() {
        return $this->pagination;
    }

}
