<?php
/**
 * Defines an ActiveRecord instance that can be presented by Mozambique AR Tiles
 *
 * @author kiriakos
 */
interface IPresentableActiveRecord {
    /**
     * 
     * @param integer $width
     * @param integer $height
     * @param string[] $classes
     */
    function renderTile($width=1,$height=1, $classes=array());
    
    /**
     * Get an HTML displayable Title 
     */
    function getTitle();
    
    /**
     * Required for error throwing
     */
    function getUniqueTitle();
}
