<?php
/**
 *
 * @author kiriakos
 */
interface IMozambiquePagination {
    
    /**
     * Returns a URL Query string for the next page.
     * 
     * This is the main funcitonality of this method
     * 
     * @return string
     */
    public function getPaginationQueryString();
    
    /**
     * Get a pagination object for a class
     * 
     * @param string $class
     * @return CPagination
     */
    public function getPaginationFor($class);     
    
    /**
     * Set the pagination for a class
     * 
     * @param string $class
     * @param CPagination $pagination
     */
    public function setPaginationFor($class, CPagination $pagination);    
}
