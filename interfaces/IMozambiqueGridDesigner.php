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
    
}
