<?php
/**
 * Description of EKindMozambiqueTypeException
 *
 * @author kiriakos
 */
class EKindMozambiqueTypeException 
extends CException{
    
    /**
     * EKindMozambiqueTypeException regards the first consturcor argument as the 
     * actual value and the second as the expected type
     * 
     * @param mixed $message
     * @param string $code
     * @param mixed $previous
     */
    public function __construct($message, $code, $previous) {
        $smg = "Expected instance of $code but got ". get_class($message);
        parent::__construct($msg, 109144000001, $previous);
    }
}
