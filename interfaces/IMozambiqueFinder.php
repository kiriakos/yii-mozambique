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
     * @param array $timestampMap   Mozambique looks at the 
     * @return IMozambiqueTile[]              Ususally a CTypedList
     */
    function findItems($timestampMap = array());
}
