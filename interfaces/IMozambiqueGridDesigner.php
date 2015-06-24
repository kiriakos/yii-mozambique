<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiqueGridDesigner {
    
    /**
     * Set the tiles with which the grid designer shall work
     * 
     * @param ITile[] $tiles
     */
    public function setTiles(CTypedList $tiles);
    
    /**
     * Order the Itiles
     */
    public function order();
    
    /**
     * Arange the collection of ITiles on a grid.
     */
    public function layout();
    
    /**
     * Replaces the lagacy rener call, now You should cal rener only on classes
     * implementing ITile.
     */
    public function getGrid();
}
