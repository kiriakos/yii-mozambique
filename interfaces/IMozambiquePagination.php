<?php
/**
 * A Typed pagination collection.
 * 
 * This is the base pagination structure consumed by Mozambique. Consumers can 
 * either pull the CPagination objects they need or just pass on the collection.
 *
 * In the spirit of being versatile class arguments should bew consumable both
 * as Class instances and Strings containing the class name.
 * 
 * @author kiriakos
 */
interface IMozambiquePagination {
    
    /**
     * Returns a URL Query string for the next page.
     * 
     * The URL Query string must be able to uniquely identify the speciffic set.
     * There are usecases where more than one paginations can be active on
     * a single page.
     * 
     * One aproach is to collect all pagination properties (next page and size)
     * of all classes and encode them. We call this the Pagination Value
     * 
     * This method should produce output in the form of 
     * "PaginationName=PaginationValue"
     * Note the absence of the querystring separator "?" or concatenators "&".
     * This is done by design since the pagination should not assume any 
     * external (request) conditions.
     * 
     * Typically the pagination implementation will read the expected pagiantion
     * name via setPaginationName($prefix).
     * 
     * @return string
     */
    public function getPaginationQueryString();
    
    /**
     * This will identify the Name of the Query string fragement.
     * 
     *  (portion before "=")
     * 
     * Logic dictates that if this method is not invoked before 
     * IMozambiquePagination::getPaginationQueryString() the PaginationName part
     * should amount to the name of the Implementing class.
     * 
     * @param string $name  The name to be used.
     */
    public function setPaginationName($name);
    
    /**
     * Get a pagination object for a class
     * 
     * @param mixed $class
     * @return CPagination
     */
    public function getPaginationFor($class);
    
    /**
     * Check if pagination is provided for a specific class.
     * 
     * @param mixed $class String or Object
     * @return boolean
     */
    public function hasPaginationFor($class);
    
    /**
     * Set the pagination for a class
     * 
     * @param mixed $class
     * @param CPagination $pagination
     */
    public function setPaginationFor($class, CPagination $pagination);
    
    
    /**
     * Scrape the pagination out of request parameters
     * 
     * @return IMozambiquePagination
     */
    public function scrapePagination();
}
