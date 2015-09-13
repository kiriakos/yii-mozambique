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

    /**
     * Remove the passed tile from the grid
     * 
     * @param \IMozambiqueTile[] $tiles
     */
    public function removeTiles($tiles);

    /**
     * Get a 2D representation of the grid.
     * 
     * The First array level represents rows starting from the top of the grid.
     * 
     * @return \IMozambiqueTile[][]
     */
    public function get2d();
        
    /**
     * Configure Tile classes for rendering
     * 
     * This function is called before render is called on a master grid.
     * It isn't necessary to add a stylize step to Your grid since in many cases
     * this kind of functionality can also be provided by reactively resolving 
     * style information of tiles when needed or by eagerly configuring it in 
     * the grid's layout phase. Since those two alternatives might come with 
     * performance penalties in large sets this is just a chance for 
     * optimizations.
     * 
     * @return void
     */
    public function stylize();
    
    /**
     * Whether this grid is a main grid
     * 
     * @return boolean
     */
    public function isMainGrid();
    
    /**
     * Demarcate the grid as either a main Grid or a Subgrid
     * 
     * Main grids are the root elements of a Mozambique Layout. They are the 
     * first grid a designer has created.
     * 
     * @param boolean $boolean
     */
    public function setMainGrid($boolean);
}
