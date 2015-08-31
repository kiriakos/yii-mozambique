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
 *  - IMozambiqueTile::getLastTimestamp()
 *  - IMozambiqueTile::getDesiredDimensions()
 *  
 * @author kiriakos
 */
abstract class EKindMozambiqueAbstractTile
implements IMozambiqueTile{
    
    public function __construct($width = 1, $height = 1) {
        $this->width = $width;
        $this->height = $height;
    }
    
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
}
