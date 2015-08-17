<?php

/**
 * Sort of a DAO for EKindMozambique
 *
 * @author kiriakos
 */
interface IMozambiqueFinder {
    
    /**
     * Find tiles Based on implementation logic.
     * 
     * Each implementation can decide on its own what selection criteria apply.
     * 
     * @param IMozambiquePagination $pagination How to paginate the results
     * @return IMozambiqueTileCollection
     */
    function findItems(\IMozambiquePagination $pagination = array());
}
