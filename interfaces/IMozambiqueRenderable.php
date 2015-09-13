<?php
/**
 * Defines an object that can produce a representation (rendering) of something.
 *  
 * This can be an object of the pattern of "Model and View" or just an
 * ActiveRecord instance that can be presented by Mozambique AR Tiles.
 *
 * 
 * @author kiriakos
 */
interface IMozambiqueRenderable {
    
    
    /**
     * The base render call
     * 
     * @param integer $width
     * @param integer $height
     * @param string[] $classes
     */
    function renderTile($width=1,$height=1, $classes=array(), $return = FALSE);
    
    /**
     * Get an HTML displayable Title 
     */
    function getTitle();
    
    /**
     * Required for error throwing
     */
    function getUniqueTitle();
}
