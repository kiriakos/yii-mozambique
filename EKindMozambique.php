<?php
/**
 * Layouting widget that allows content to be aranged in interesting tiles.
 *
 * @author kiriakos
 */
class EKindMozambique extends CWidget{
    
    /**
     * Map of Types to timestamps
     *
     * @var array
     */
    private $timestamps = false;
    
    /**
     * The HTML id attribute of the produced markup
     * @var string
     */
    private $htmlId = null;

    /**
     * Grid Items to use
     * @var type 
     */
    public $items = false;

    /**
     * Initializes all attributes
     */
    public function init(){
        
        $this->htmlId =$this->id.'_KindGrid_TimeStamp';
        $this->collectRequestPatrams();

        
        $this->designer = new KindFrontPageDesigner($this->items);
        
        //$this->designer->populate($this->timestamp);
        //$this->designer->order();
        //$this->designer->layout();
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
     */
    private function collectRequestPatrams(){
        
        if(isset($_GET[$this->htmlId]) && is_numeric($_GET[$this->htmlId])){
            $this->timestamps = $_GET[$this->htmlId];
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