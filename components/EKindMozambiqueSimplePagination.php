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

    public function getPaginationQueryString($shift = 0) {
        $strs = array();
        
        foreach($this->pagination as $pagination){
            
            $page = $pagination->getCurrentPage() + $shift + 1;
            $strs[] = $pagination->pageVar. "=". $page;
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
     * TODO: This should only be allowed to run once.
     * 
     * @return boolean Whether request data existed or not
     */
    public function scrapePagination($classes = array()) {
        
        $this->prepareRequestEnvironment($classes);
        
        foreach ($classes as $class=>$pageSize){

            $pgn = $this->createClassPagination($class, $pageSize);
            $this->setPaginationFor($class, $pgn);
        }
        return TRUE;
    }
    
    /**
     * Prepares the runtime's rewuest environment
     * 
     * Due to limitations in Yii's CHtml::link EKindMozambiqueSimplePagination
     * renders all it's page parameters down to one URL query parameter. This
     * method undoes this and sets $_GET entries as they will be awaited by the
     * CPagination instances for each class.
     * 
     * @return boolean
     */
    private function prepareRequestEnvironment($classes){
        
        if($this->prepared){
            return TRUE;
        }
        
        $inputRaw = filter_input(INPUT_GET, $this->getPaginationName());

        if(!$inputRaw){
            foreach( array_keys($classes) as $class){
                $_GET[$this->getPaginationName()."-".$class] = 1;
            }
        }
        else
        {
            $input = explode("&", rawurldecode($inputRaw));
            
            foreach( $input as $pages){
                $set = explode("=", $pages);
                $_GET[$set[0]] = $set[1];
            }
        }   

        return $this->prepared = TRUE;
    }
    private $prepared = FALSE;
    
    /**
     * 
     * @param type $class
     * @param type $pageSize
     * @return \CPagination
     */
    private function createClassPagination($class, $pageSize){
        $pgn = new CPagination();
        $pgn->validateCurrentPage = FALSE;
        $pgn->pageVar = $this->getPaginationName()."-".$class;        
        $pgn->setPageSize($pageSize);
        
        return $pgn;
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
        
        if(isset($this->pagination[$this->getClass($class)])){
            throw new CException("Now What?");
        }
        else{
            $this->pagination[$this->getClass($class)] = $pagination;
        }
    }

    public function getPaginationName() {
        return $this->name;
    }

}