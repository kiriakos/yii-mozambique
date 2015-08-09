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
}
