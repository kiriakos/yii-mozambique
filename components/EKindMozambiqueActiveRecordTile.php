<?php
/**
 * Implementation of IMozambique Tile encapsulating (and hiding) an IPresentableActiveRecord.
 * 
 * 
 *
 * @author kiriakos
 */
class EKindMozambiqueActiveRecordTile 
extends EKindMozambiqueAbstractTile 
implements IMozambiqueTile {
    
    /**
     * These Tiles will limit transformation to following max dimensions
     */
    const MAX_WIDTH = 5;
    const MAX_HEIGHT = 4;
    const MAX_TILES = 12;
    
     /**
     *  Assoc Array of initial Dims in tiles for item primitives form: array(w,h)
     * @var array[]
     */
    private $classInitDims = array(
        'Article' => array(3, 1),
        'Image' => array(2, 3),
        'Gallery' => array(5, 1),
        'Project' => array(3, 2),
    );
    private $classMinDims = array(
        'Article' => array(2, 1),
        'Image' => array(1, 2),
        'Gallery' => array(2, 1),
        'Project' => array(2, 2),
    );
    private $classMaxTiles = array(
        'Article' => 6,
        'Image' => 6,
        'Gallery' => 12,
        'Project' => 9,
    );
    
    /**
     *  Assigned by KindFrontPageDesigner on construct
     * @var integer
     */
    private $id = NULL;
    
    /**
     *  An instance of an object that can be displayed on the front page
     * @var IPresentableActiveRecord
     */
    private $record;

    public function __construct(IPresentableActiveRecord $record, $width = null,
            $height= null){
        
        $this->record = $record;
        
        
        if($width && $width <= self::MAX_WIDTH){
            $effectiveWidth = $width;
        }
        else{
            $effectiveWidth = self::MAX_WIDTH;
        }
        
        if($height && $height <= self::MAX_HEIGHT){
            $effectiveHeight = $height;
        }
        else{
            $effectiveHeight = self::MAX_HEIGHT;
        }
        
        parent::__construct($effectiveWidth, $effectiveHeight);
    }

    public function canHeighten() {
        $maxOverflow = $this->getHeight() < self::MAX_HEIGHT;
        $projectedArea = $this->getWidth() * ($this->getHeight() +1);
        $areaOverflow =  $projectedArea <= self::MAX_TILES;
        
        return $maxOverflow && $areaOverflow;
    }

    public function canWiden() {
        $maxOverflow = $this->getWidth() < MAX_WIDTH;
        $projectedArea = $this->getHeight() * ($this->getWidth() + 1);
        $areaOverflow = $projectedArea <= self::MAX_TILES;
        
        return $maxOverflow && $areaOverflow;
    }

    /**
     * Get this Tile's desired Dimensions
     * 
     * @return array    A touple of integers (W,H)
     */
    public function getDesiredDimensions() {
        return $this->classInitDims[get_class($this->record)];
    }

    public function getId() {
        return $this->id;
    }

    public function getLastTimestamp() {
        return $this->record->getLastTimestamp();
    }


    public function render($return = TRUE) {
        return $this->record->renderTile($return);
    }

    public function heighten() {
        if($this->getHeight() < self::MAX_HEIGHT){
            $this->heighten();
        }
        else{
            $msg = "The height of ". $this->record->getUniqueTitle(). " exceded"
                    . " its maximum allowed value of ". self::MAX_HEIGHT. "!";
            throw new EKindMozambiqueSizeOutOfBoundsException($msg);
        }            
    }

    public function widen() {
        if($this->getWidth() < self::MAX_WIDTH){
            $this->widen();
        }
        else{
            $msg = "The width of ". $this->record->getUniqueTitle(). " exceded"
                    . " its maximum allowed value of ". self::MAX_WIDTH. "!";
            throw new EKindMozambiqueSizeOutOfBoundsException($msg);
        }
    }
    
    /**
     * Get the underlying record
     * 
     * @return \IPresentableActiveRecord
     */
    public function getRecord(){
        return $this->record;
    }

    public function getDimensions() {
        return array($this->getWidth(), $this->getHeight());
    }

}
