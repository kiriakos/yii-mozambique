<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiqueTile {
    
    /**
     * Used by Mozambique to order the passed items and to pass page parameters
     * 
     * A unix timestamp.
     * 
     * Kind Mozambique will take a look on all passed ITile object's 
     * getLatestTimestamp() return value. These values arenused to order the 
     * tiles (presenting newer ones first) and to collect pagin informtaion
     * that is passed to infinitescroll to load followup datasets.
     * 
     * @return int
     */
    function getLastTimestamp();
    
    /**
     * @return array    A touple. The first item represents the desired width.
     *                  The second item represents the desired height.
     */
    function getDesiredDimensions();
    
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
}
