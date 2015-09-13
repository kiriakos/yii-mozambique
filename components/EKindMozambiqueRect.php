<?php
/**
 * A Rectangle defined by it's topleft and bottom right points
 * 
 * Reminder in Mozambiue the 0,0 Point of the grid is on the Top Left edge! Not
 * on the Kartesian standard Bottom Left.
 * The rect is inclusive so the Tiles on the edges are supposed to be included
 * and Width and Height calculations also reflect this.
 * 
 * @author kiriakos
 */
class EKindMozambiqueRect {
    
    private $tl;
    private $br;
    
    public function __construct(\EKindMozambiquePoint $tl, 
            \EKindMozambiquePoint $br) {
        
        if($tl->getX() > $br->getX()){
            throw new UnexpectedValueException("TopLeft point seems to be to"
                    . " the right of BottomRight. Mathematical imposibility for"
                    . " a natural rectangle.");
        }
        if($tl->getY() > $br->getY()){
            throw new UnexpectedValueException("TopLeft point seems to be under"
                    . " BottomRight. Mathematical imposibility for"
                    . " a natural rectangle.");
        }
        
        $this->tl = $tl;
        $this->br = $br;
    }
    
    function getTop(){
        return $this->tl->getY();
    }
    
    function getBottom(){
        return $this->br->getY();
    }
    
    function getLeft(){
        return $this->tl->getX();
    }
    
    function getRight(){
        return $this->br->getX();
    }
    
    function getWidth(){
        return $this->br->getX() - $this->tl->getX() + 1;
    }
    
    function getHeight(){
        return $this->br->getY() - $this->tl->getY() + 1;
    }
}
