<?php
/**
 * Defines an ActiveRecord instance that can be presented by Mozambiques AR Tile
 *
 * @author kiriakos
 */
interface IPresentableActiveRecord {
    function getLastTimeStamp();
    function renderTile($return = TRUE);
    function getTitle();
    
    /**
     * Required for error throwing
     */
    function getUniqueTitle();
}
