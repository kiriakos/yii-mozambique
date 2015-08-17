<?php
/**
 * A collection of tiles. Product of IMozambiqueFinder
 *
 * @author kiriakos
 */
interface IMozambiqueTileCollection {
    
    /**
     * Get a CTypedList of IMozambiqueTile instances
     * 
     * @return CTypedList<IMozambiqueTile>
     */
    public function getTiles();
    
    /**
     * The edge criterion based on which one can generate followup pages.
     * 
     * The designer must be able to return a collection of attributes that
     * identify the attributes for the content of the next page. Since 
     * Mozambique is built to handle non homogenous content (varying types)
     * possibly even comming from different data providers each one type will
     * need to provide one pagination property for its type.
     * 
     * @return IMozambiquePagination
     */
    public function getPagination();
}