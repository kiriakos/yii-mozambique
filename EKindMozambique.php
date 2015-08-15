<?php

/**
 * Appliction Component EKindMozambique
 *
 * @author kiriakos
 */
final class EKindMozambique extends CApplicationComponent {
    
    public $finderAlias;
    
    ////////////////////////////////////////////////////////////////////////////
    // Components
    //
    // These can be configured via classical Yii Component configuration arrays
    public $widgetAlias = "ext.kindMozambique.components.EKindMozambiqueWidget";
    public $designerAlias = "ext.kindMozambique.components.EKindMozambiqueGridDesigner";
    public $gridAlias = "ext.kindMozambique.components.EKindMozambiqueGrid";
    public $defaultSorter = "ext.kindMozambique.components.EKindMozambiquePropertyBasedSorter";
    
    ////////////////////////////////////////////////////////////////////////////
    // Non Components
    //
    // These classes provide very specialized functionality and have speciffic 
    // instantiation requirements. Chack the constructors on their interfaces 
    // for more information
    public $pointAlias = "ext.kindMozambique.components.EKindMozambiquePoint";
    public $gapAlias = "ext.kindMozambique.components.EKindMozambiqueGap";
    public $gapPatcherAlias = "ext.kindMozambique.components.EKindMozambiqueGapPatcher";
    
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
                    . " IMozambiqueFinder!");
        }
    }
    
    /**
     * Instantiate a non Yii/CComponent Class
     * 
     * @param string $alias
     * @param array $args
     */
    private function instantiateNonComponent($alias, $args = array()){
        Yii::import($alias);
        $class = array_pop(explode(".", $alias));
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($args);
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
     * 
     * @param IMozambiquePagination $pagination
     * @return string
     */
    public function renderWidget(IMozambiquePagination $pagination = null){
        
        return Yii::app()->controller->widget($this->getWidgetAlias(),array(
            'pagination' => $pagination
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
     * Generates an empty grid preconfigured for the given dimensions.
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
    
    /**
     * Generates a Grid Designer from the component configuration
     * 
     * @return IMozambiqueGridDesigner
     */
    public function generateDesigner(){
        return Yii::createComponent($this->designerAlias);
    }
    
    /**
     * Generates a Gap Patcher based on the configuration
     * 
     * @return IMozambiqueGapPatcher
     */
    public function generateGapPatcher(){
        return $this->instantiateNonComponent($this->gapPatcherAlias);
    }
    
    /**
     * Generates a Gap based on the component's configuration
     * 
     * @return IMozambiqueGapPatcher
     */
    public function generateGap($x,$y){
        return $this->instantiateNonComponent($this->gapAlias,array($x, $y));
    }
    
    /**
     * Generates a Point based on the component's configuration
     * 
     * @return IMozambiqueGapPatcher
     */
    public function generatePoint($x, $y){
        return $this->instantiateNonComponent($this->pointAlias, array($x, $y));
    }
    
    public function generateSorter(){
        return Yii::createComponent($this->defaultSorter);
    }
}