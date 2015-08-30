<?php
/**
 * Simple widget orianetd implementation of EKindMozambiqueSimplePagination
 *
 * @author kiriakos
 */
class EKindMozambiqueSimplePagination 
extends CComponent
Implements IMozambiquePagination{

    /**
     * @var CPagination[]
     */
    private $pagination = array();
    
    private $name;
    
    /**
     * Guard function to provide functions with constant argument type for class
     * arguments.
     * 
     * Argument pre processing function. This is called by the public funcitons
     * to preprocess the class argument.
     * 
     * @param mixed $arg    String or Object
     * @return string       The string class name.
     * @throws UnexpectedValueException
     */
    private function getClass($arg){
        if(is_string($arg)){
            return $arg;
        }
        elseif(is_object($arg)){
            return get_class($arg);
        }
        else{
            throw new UnexpectedValueException(get_class(). " class arguments"
                    . " can either be a string or an object!");
        }
    }
    
    public function getPaginationFor($class) {
        return $this->pagination[$this->getClass($class)];
    }

    public function getPaginationQueryString() {
        $strs = array();
        
        foreach($this->pagination as $class => $pagination){
            $size = $pagination->getSize();
            $page = $pagination->getPage();
            $strs[] = rawurlencode("$class=$size:$page");
        }
        
        return join("&",$strs);
    }

    /**
     * Pull in pagination configuration from the request.
     * 
     * Tries to read the following url encoded string:
     * Class1=size:page&Class2=size:page...
     * out of a predefined GET variable (the pagination name @see setPaginationName)
     * 
     * @return boolean Whether request data existed or not
     */
    public function scrapePagination() {
        
        $input =filter_input(INPUT_GET, get_class());
            
        if($input === NULL){
            // Variable not in __GET[]
            return FALSE;
        }
        else{
            $pages = explode("&", rawurldecode($input));
            foreach ($pages as $page){
                
                $set = explode("=", $page);
                $class = $set[0];
                $props = explode(":", $set[1]);
                
                $pgn = new CPagination();
                $pgn->setPageSize($props[0]);
                $pgn->setCurrentPage($props[1]);
                $this->setPaginationFor($class, $pgn);
            }
        }
        
        return TRUE;
    }

    
    /**
     * Strict implementation of Interface
     * 
     * @param mixed $class
     * @return boolean
     * @throws UnexpectedValueException
     */
    public function hasPaginationFor($class) {
        return array_key_exists($this->getClass($class), $this->pagination);
    }

    public function setPaginationName($name) {
        $this->name = $name;
    }
    
    public function setPaginationFor($class, \CPagination $pagination) {
        $this->pagination[$this->getClass($class)] = $pagination;
    }
}