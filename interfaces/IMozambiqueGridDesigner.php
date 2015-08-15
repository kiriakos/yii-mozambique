<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiqueGridDesigner {
    
    /**
     * Set the tiles with which the grid designer shall work
     * 
     * The Mozambique Extension mainly works with arrays of TMozambiqueTile
     * but the input functions up to the designer consume Typed Yii lists.
     * 
     * @param IMozambiqueTile[] $tiles
     */
    public function setTiles(CTypedList $tiles);
    
    /**
     * Order the IMozambiqueTiles
     */
    public function order();
    
    /**
     * Arange the collection of IMozambiqueTiles on a grid.
     */
    public function layout();
    
    /**
     * Replaces the legacy render call, now You should call render only on 
     * classes implementing IMozambiqueTile.
     */
    public function getGrid();
    
    /**
     * The edge criterion based on which one can generate followup pages.
     * 
     * The designer must be able to return a collection of attributes that
     * identify the attributes for the content of the next page. Since 
     * Mozambique is built to handle non homogenous content (varying types)
     * possibly even comming from different data providers each one type will
     * need to provide one pagination property for its type.
     * 
     * @return IMozambiquePagination
     */
    public function getPaginationCriterion();
}
