<?php

/**
 * Layouting widget that allows content to be aranged in interesting tiles.
 *
 * 
 * 
 * @author kiriakos
 */
class EKindMozambiqueWidget extends CWidget{
    
    /**
     * The HTML id attribute of the produced markup
     * @var string
     */
    private $htmlId = NULL;

    /**
     * Grid Items to use
     * @var IMozambiqueTile[]
     */
    public $tiles = FALSE;
    
    public $timestampMap = NULL;

    private $designer = NULL;
    
    /**
     * Initializes all attributes
     */
    public function init(){
        
        $this->htmlId =$this->id.'_KindGrid_TimeStamp';
        $this->collectRequestPatrams();

        if(!$this->timestampMap){
            $this->timestampMap = $this->collectRequestPatrams();
        }
        
        if(!$this->tiles){
            $this->tiles = Yii::app()->mozambique->getFinder()->findItems();
        }
        
        $this->designer = Yii::app()->mozambique->generateDesigner();
        $this->designer->setTiles($this->tiles);
        $this->designer->order();
        $this->designer->layout();
    }

    /**
     * Renders a Grid
     */
    public function run(){
        
        $this->designer->render();
        $this->renderNav();
        $this->renderInfiniScroll();
    }
    
    /**
     * Find init parameters in the request
     *
     */
    private function collectRequestPatrams(){
        
        $input =filter_input(INPUT_GET, $this->htmlId);
        if(is_array($input)){
            return $input;
        }
        else{
            return array();
        }
            
    }
    
    /**
     * Render procedure to echo the html of the nav unit
     */
    private function renderNav()
    {
        echo '<hr class="hidden" />';
        echo '<div class="tiles navigation centered"><div>';
        echo CHtml::link(
                Yii::t( 'phrases', 'older items'),
                array('', $this->htmlId=>$this->designer->getOldestTimestamp()),
                array('class'=>'button navigation next')
        );
        echo '</div></div>';

    }

    private function renderInfiniScroll()
    {
        $pages = new CPagination(10);
        $pages->pageSize= 3;

        $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
            'itemSelector' => '#main div.tiles',
            'contentSelector' => '#main',
            'loading'=>array(  //infiscroll 2.0.xxx
               'finishedMsg'=> '', //"Συγχαρητήρια, φτάσατε στο τέλος της λίστας",
               'img'=> '/images/system/loading.gif',
               'msg'=> null,
               'msgText'=> '', //"<em>φορτώνονται δεδομένα...</em>",
            ),
            'navSelector'=>'div.tiles.navigation.centered',
            'nextSelector'=>'div.tiles.navigation.centered a.next',

            'pages' => $pages,
        ));
    }

}