<?php
namespace gr\kindstudios\widget\mozambique\interfaces;

/**
 * Sort of a DAO for EKindMozambique
 *
 * @author kiriakos
 */
interface IKindMozambiqueItemFinder {
    
    /**
     * Find tiles Based on implementation logic.
     * 
     * Each implementation can decide on its own what selection criteria apply.
     * 
     * @return IKindMozambiqueTile[]
     */
    function findItems($timestampMap = array());
}
