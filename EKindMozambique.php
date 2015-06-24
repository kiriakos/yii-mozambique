<?php

/**
 * Appliction Component EKindMozambique
 *
 * @author kiriakos
 */
final class EKindMozambique extends CApplicationComponent {
    
    public $finderAlias;
    public $widgetAlias = "ext.kindMozambique.components.EKindMozambiqueWidget";
    public $designerAlias = "ext.kindMozambique.components.EKindMozambiqueGridDesigner";
    public $gridAlias = "ext.kindMozambique.components.EKindMozambiqueGrid";
    public $defaultGridHeight = 6;
    public $defaultGridWidth = 5;
    
    /**
     *
     * @var IMozambiqueFinder
     */
    private $finder;
    
    
    public function init() {
        parent::init();
        
        Yii::import("ext.kindMozambique.interfaces.*");
        Yii::import("ext.kindMozambique.components.*");
        Yii::import("ext.kindMozambique.exceptions.*");
        
        Yii::import($this->finderAlias);
        $class = array_pop(explode(".", $this->finderAlias));
        
        $this->finder = new $class;
        
        if(! $this->finder instanceof \IMozambiqueFinder){
            throw new \CException("Mozambique requires the configuration"
                    . " property finderClass to point to an implementation of"
                    . " IItemFinder!");
        }
    }
    
    /**
     * 
     * @return interfaces\IItemFinder
     */
    public function getFinder(){
        return $this->finder;
    }
    
    public function getWidgetAlias(){
        return $this->widgetAlias;
    }
    
    /**
     * 
     * @param array $timestampMap
     * @return string
     */
    public function renderWidget($timestampMap = null){
        
        return Yii::app()->controller->widget($this->getWidgetAlias(),array(
            'timestampMap' => $timestampMap
        ), TRUE);
    }
    
    /**
     * 
     * @param IMozambiqueTile[] $tiles
     * @return string
     */
    public function renderWidgetWithItems($tiles){
        
        return Yii::app()->controller->widget($this->getWidgetAlias(),array(
            'tiles' => $tiles
        ), TRUE);
    }
    
    /**
     * Produces an empty grid preconfigured for the given dimensions.
     * 
     * The type of grid produced can be manipulated via the gridAlias property
     * 
     * @param int $width
     * @param int $height
     * @return IGrid
     */
    public function generateGrid($width = NULL,$height = NULL){
        if($width === NULL){
            $width = $this->defaultGridWidth;
        }
        if($height === NULL){
            $height = $this->defaultGridHeight;
        }
        
        $grid = Yii::createComponent($this->gridAlias);
        
        
        return $grid;
    }
    
    public function generateDesigner(){
        return Yii::createComponent($this->designerAlias);
    }
}