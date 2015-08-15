<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiqueGrid extends IMozambiqueTile{
        
    /**
     * Makes the grid self-arrange, eliminating holes in the process.
     * 
     * @return void
     */
    public function fillGaps();
    
    /**
     * Get the Tile on a specific grid coordinate
     * 
     * Zero based coordinates.
     * 
     * @param \IMozambiquePoint $point
     * @return \IMozambiqueTile
     */
    public function getTile(IMozambiquePoint $point);
    
    /**
     * Attempt to add a tile to the grid
     * 
     * @param \IMozambiqueTile $tile
     * @return boolean
     */
    public function addTile(\IMozambiqueTile $tile);
    
    /**
     * Remove the passed tile from the grid
     * 
     * @param \IMozambiqueTile $tile
     */
    public function removeTile(\IMozambiqueTile $tile);
}
