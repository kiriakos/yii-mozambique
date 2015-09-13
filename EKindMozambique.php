<?php
/**
 * Appliction Component EKindMozambique.
 * 
 * Also serves as a facade to Mozambique configuration. All extensions or
 * integrations to mozambique should use the Facade facilities (get & generate).
 *
 * @author kiriakos
 */
final class EKindMozambique extends CApplicationComponent {
    
    public $finderAlias;
    
    ////////////////////////////////////////////////////////////////////////////
    // Components
    //
    // These can be configured via classical Yii Component configuration arrays
    public $widget = "ext.kindMozambique.components.EKindMozambiqueWidget";
    public $designer = "ext.kindMozambique.components.EKindMozambiqueGridDesigner";
    public $grid = "ext.kindMozambique.components.EKindMozambiqueGrid";
    public $sorter = "ext.kindMozambique.components.EKindMozambiquePropertyBasedSorter";
    public $paginationScraper = "ext.kindMozambique.components.EKindMozambiquePaginationScraper";
    public $pagination = "ext.kindMozambique.components.EKindMozambiqueSimplePagination";
    public $uuidGen = "ext.kindMozambique.components.EKindUuidGen";
    
    ////////////////////////////////////////////////////////////////////////////
    // Non Components
    //
    // These classes provide very specialized functionality and have speciffic 
    // instantiation requirements. Check the constructors on their interfaces 
    // or base implementations for more information.
    public $pointAlias = "ext.kindMozambique.components.EKindMozambiquePoint";
    public $gapAlias = "ext.kindMozambique.components.EKindMozambiqueGap";
    public $gapPatcherAlias = "ext.kindMozambique.components.EKindMozambiqueGapPatcher";
    public $tileCollectionAlias = "ext.kindMozambique.components.EKindMozambiqueBaseTileCollection";
    public $gridRendererAlias = "ext.kindMozambique.components.EKindMozambiqueGridRenderer";
    public $gridStylizerAlias = "ext.kindMozambique.components.EKindMozambiqueGridStylizer";
    public $tileAlias = "ext.kindMozambique.components.EKindMozambiqueActiveRecordTile";
    
    ////////////////////////////////////////////////////////////////////////////
    // The CSS Delivered with Mozambique provides for base grids up to width 5
    // if You want to provide bigger grids You will have to include your own CSS
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
        $this->uuidGen = Yii::createComponent($this->uuidGen);
        
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
     * @return \IMozambiqueFinder
     */
    public function getFinder(){
        return $this->finder;
    }
    
    public function getWidgetAlias(){
        return $this->widget;
    }
    
    /**
     * Render a preconfigured Grid Widget
     * 
     * @param IMozambiquePagination $pagination
     * @return string
     */
    public function renderWidget(\IMozambiquePagination $pagination = NULL){
        
        return Yii::app()->controller->widget($this->widget,array(
            'pagination' => $pagination
        ), TRUE);
    }
    
    /**
     * Render a preconfigured Grid Widget with a specific tile collection
     * 
     * @param IMozambiqueTileCollection $collection
     * @return string
     */
    public function renderWidgetWithItems(
            \IMozambiqueTileCollection $collection){
        
        return Yii::app()->controller->widget($this->widget, array(
            'tileCollection' => $collection
        ), TRUE);
    }
    
    /**
     * Generates an empty grid preconfigured for the given dimensions.
     * 
     * The type of grid produced can be manipulated via the grid property
     * 
     * @param int $width
     * @param int $height
     * @return IMozambiqueGrid
     */
    public function generateGrid($width = NULL, $height = NULL){
        
        if($width === NULL){
            $width = $this->defaultGridWidth;
        }
        
        if($height === NULL){
            $height = $this->defaultGridHeight;
        }
        
        return $this->instantiateNonComponent($this->grid, 
                array($width, $height));
    }
    
    /**
     * Generates a Grid Designer from the component configuration
     * 
     * @return IMozambiqueGridDesigner
     */
    public function generateDesigner(){
        return Yii::createComponent($this->designer);
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
        return Yii::createComponent($this->sorter);
    }
    
    /**
     * Generate a Tile collection to use with Mozambique Widgets
     * 
     * @param \CTypedList<IMozambiqueTile> $list
     * @param \IMozambiquePagination $pagination
     * @return IMozambiqueTileCollection
     * @throws CException
     */
    public function generateTileCollection(\CTypedList $list, 
            \IMozambiquePagination $pagination){
        if($list->count() > 0 && !$list->itemAt(0) instanceof IMozambiqueTile){
            throw new CException("generateTileCollection requires CTypedList of"
                    . " IMozambiqueTile instances!");
        }
        
        return $this->instantiateNonComponent($this->tileCollectionAlias, 
                array($list,$pagination));
    }
    
    /**
     * Generates a pagination instance
     * 
     * Override $this->pagination to include Your own pagination class. This 
     * class is globaly used to generate a pagination for a class.
     * 
     * @param string $class
     * @param mixed $args
     * @return CPagination
     */
    public function generatePagination($class, $args){
        $properties = array_merge(array($class), $args);
        return $this->instantiateNonComponent($this->pagination, $properties);
    }
    
    public function getPageScraper(){
        return Yii::createComponent($this->paginationScraper);
    }

    /**
     * 
     * @return IMozambiquePagination
     */
    public function getPagination(){
        return Yii::createComponent($this->pagination);
    }
    
    public function generateUuid(){
        return $this->uuidGen->v4();
    }
    
    public function generateGridRenderer(\IMozambiqueGrid $grid){
        return $this->instantiateNonComponent($this->gridRendererAlias, 
                array($grid));
    }
    
    public function generateGridStylizer(\IMozambiqueGrid $grid){
        return $this->instantiateNonComponent($this->gridStylizerAlias, 
                array($grid));
    }
    
    /**
     * Generate an instance of the default configured Tile.
     * 
     * @param \IMozambiqueRenderable $renderable
     * @param integer $width
     * @param integer $height
     * @return IMozambiqueTile
     */
    public function generateTile(\IMozambiqueRenderable $renderable, 
            $width = null, $height= null){
        
        return $this->instantiateNonComponent($this->tileAlias, 
                array($renderable, $width, $height));
    }
}