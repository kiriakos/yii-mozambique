<?php
/**
 * A simple tile that will conform to resize commands
 * 
 * The only exception scenarios are trying to reduce one of its dimensions to
 * zero.
 * 
 * This tile misses following functions: 
 *  - IMozambiqueTile::render()
 *  - IMozambiqueTile::getId()
 *  - IMozambiqueTile::getDesiredDimensions()
 *  
 * @author kiriakos
 */
abstract class EKindMozambiqueAbstractTile
implements IMozambiqueTile{
        
    /**
     * A naive hash set. The structure is key=>value wehere value is always null
     * 
     * Sadly PHP does not support a Hash set for native types like strings.
     * 
     * @var array
     */
    private $classes = array();
    
    /**
     *  The height (in tiles) the object will be rendered in
     * @var integer
     */
    private $height;

    /**
     *  The width (in tiles) the object will be rendered in
     * @var integer
     */
    private $width;
    
    /**
     *  The position of the tile's Left Top corner
     * @var \IMozambiquePoint
     */
    private $gridPosition;

        
    public function __construct($width = 1, $height = 1) {
        $this->width = $width;
        $this->height = $height;       
    }
    
    public function canHeighten() {
        return TRUE;
    }
    
    public function canUnHeighten() {
        return $this->height > 1;
    }

    public function canUnwiden() {
        return $this->width > 1;
    }

    public function canWiden() {
        return TRUE;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getWidth() {
        return $this->width;
    }
    
    public function heighten() {
        $this->height += 1;
    }
    
    public function widen() {
        $this->width += 1;
    }    
    
    public function unHeighten() {
        if($this->getHeight() - 1){
            $this->height -= 1;
        }
        else{
            $msg = "The height of ". $this->getRecord()->getUniqueTitle(). " was"
                    . " reduced to 0!";
            throw new EKindMozambiqueSizeOutOfBoundsException($msg);
        }            
    }
    
    public function unWiden() {
        if($this->getWidth() - 1){
            $this->width -= 1;
        }
        else{
            $msg = "The width of ". $this->getRecord()->getUniqueTitle(). " was"
                    . " reduced to 0!";
            throw new EKindMozambiqueSizeOutOfBoundsException($msg);
        }      
    }
    
    /**
     * Return the Left Top corner of the tile's position on the grid
     * 
     * @returns \
     */
    public function getGridPosition() {
        return $this->gridPosition;
    }

    /**
     * Set the grid position of the tile's Left Top corner
     *
     * @param \IMozambiquePoint $position
     */
    public function setGridPosition(\IMozambiquePoint $position) {
        $this->gridPosition = $position;
    }
    
    public function unsetGridPosition() {
        $this->gridPosition = NULL;
    }
    
    public function addClass($class) {
        
        if(!is_string($class)){
            throw new UnexpectedValueException("The Class argument in"
                    . " IMozambiqueTile::addClass() should be a string!");
        }
        $this->classes[$class] = NULL;
    }

    public function getClasses() {
        return array_keys($this->classes);
    }

    public function removeClass($class) {
        unset($this->classes[$class]);
    }

    /**
     * Sets the classes to the passed array, ignoring any existing ones.
     * 
     * This method expects a classic list of strings, not the fancy struct that 
     * EKindMozambiqueAbstractTile uses internaly.
     * 
     * @param string[] $classes
     * @throws UnexpectedValueException
     */
    public function setClasses($classes) {
        
        if(!is_array($classes)){
            throw new UnexpectedValueException("The Classes argument in"
                    . " IMozambiqueTile::setClasses() should be an array of strings!");
        }
        
        $this->classes = array_flip($classes);        
    }
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getTop(){
        return $this->gridPosition->getY();
    }
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getBottom(){
        return $this->gridPosition->getY() + $this->height;
    }
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getRight(){
        return $this->gridPosition->getX() + $this->width;
    }
    
    /**
     * Get the seam coordinate relative to the grid position.
     *  
     * @return integer
     */
    public function getLeft(){
        return $this->gridPosition->getX();
    }

}