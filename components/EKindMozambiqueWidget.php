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
    
    /**
     * Pagination information. This is collected from the Request by default.
     * @var IMozambiquePagination
     */
    public $pagination = NULL;

    /**
     *
     * @var IMozambiqueGridDesigner 
     */
    private $designer = NULL;
    
    /**
     * Initializes all attributes
     */
    public function init(){
        
        $this->htmlId =$this->id.'_KindGrid_TimeStamp';
        $this->collectRequestParams();

        if(!$this->pagination){
            $this->pagination = $this->collectRequestParams();
        }
        elseif(!$this->pagination instanceof IMozambiquePagination){
            throw new EKindMozambiqueTypeException($this->pagination, "IMozambiquePagination");
        }
        
        if(!$this->tiles){
            $this->tiles = Yii::app()->mozambique->getFinder()
                    ->findItems($this->pagination);
        }
        
        $this->designer = Yii::app()->mozambique->generateDesigner();
        $this->designer->setTiles($this->tiles);
        $this->designer->order();
        $this->designer->layout();
    }

    /**
     * Produces the Grid based on the configuration and outputs it.
     */
    public function run(){
        
        $this->designer->getGrid()->render(FALSE);
        $this->renderNav();
        $this->renderInfiniScroll();
    }
    
    /**
     * Finds init parameters in the request.
     */
    private function collectRequestParams(){
        
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
                Yii::t('phrases', 'older items'),
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
            'loading'=>array(
               'finishedMsg'=> 'No more items!',
               'img'=> '/images/system/loading.gif',
               'msg'=> null,
               'msgText'=> 'Loading...',
            ),
            'navSelector'=>'div.tiles.navigation.centered',
            'nextSelector'=>'div.tiles.navigation.centered a.next',

            'pages' => $pages,
        ));
    }
}