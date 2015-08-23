<?php
/**
 * Description of EKindMozambiqueSimplePagination
 *
 * @author kiriakos
 */
class EKindMozambiqueSimplePagination 
extends CComponent
Implements IMozambiquePagination{

    private $pagination = array();
    
    public function getPaginationFor($class) {       
        return $this->pagination[$class];
    }

    public function getPaginationQueryString() {
        $strs = array();
        
        foreach($this->pagination as $class => $pagination){
            $strs[] = "$class=$pagination";
        }
        
        return join("&",$strs);
    }

    public function scrapePagination() {
        foreach ($_GET as $class=>$paginationArgs){
            if(class_exists($class)){
                
            }
        }
    }

    public function setPaginationFor($class, \CPagination $pagination) {
        
    }
}
