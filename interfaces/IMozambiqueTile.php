<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiqueTile {
    
    /**
     * @return array    A touple. The first item represents the desired width.
     *                  The second item represents the desired height.
     */
    function getDesiredDimensions();
    
    /**
     * @return array    The current dimensions Width followed by height.
     */
    function getDimensions();
    
    /**
     * @return boolean  Success or not.
     */
    public function heighten();
    
    /**
     * @return boolean  Success or not.
     */
    public function widen();
    
    /**
     * @return boolean  Success or not.
     */
    public function unWiden();
    
    /**
     * @return boolean  Success or not.
     */
    public function unHeighten();
    
    /**
     * @return boolean  If the respective command will succeed.
     */
    public function canHeighten();
    
    /**
     * @return boolean  If the respective command will succeed.
     */
    public function canUnHeighten();
    
    /**
     * @return boolean  If the respective command will succeed.
     */
    public function canWiden(); 
    
    /**
     * @return boolean  If the respective command will succeed.
     */
    public function canUnwiden();
    
    /**
     * Produce the HTML representing this tile.
     * 
     * This function should be biased towards returning the rendered markup as a
     * String instead of directly outpuiting to StdOut since this allows for
     * more flexibility in the long run.
     * 
     * @return mixed By default a string is returned.
     */
    public function render($return = TRUE);
    
    public function getId();
    
    public function getHeight();
    public function getWidth();
    
    /**
     * Return the Left Top corner of the tile's position on the grid
     * 
     * @returns \IMozambiquePoint
     */
    public function getGridPosition();
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getTop();
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getBottom();
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getRight();
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getLeft();

    /**
     * Set the grid position of the tile's Left Top corner
     * 
     * @param \IMozambiquePoint $position
     * @return void
     */
    public function setGridPosition(\IMozambiquePoint $position);
    
    /**
     * Facility to unset the position. (When a tile is removed from a grid)
     */
    public function unsetGridPosition();
    
    /**
     * @return string[]
     */
    public function getClasses();
    
    /**
     * 
     * @param string[] $classes
     */
    public function setClasses($classes);
    
    /**
     * 
     * @param string $class
     */
    public function addClass($class);
    
    /**
     * 
     * @param string $class
     */
    public function removeClass($class);
}