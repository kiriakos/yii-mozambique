<?php
/**
 *  Interface describing a class that tries to fill up gaps in a grid with tiles
 * 
 * @author kiriakos
 */
interface IMozambiqueGapPatcher {
    /**
     * Tries to fill a gap on the grid non destructively
     * 
     * This method will honor tile prefrences if  they exists (canHeighten etc.)
     *  
     * @param \EKindMozambiqueGap $gap
     * @param \IMozambiqueGrid $grid
     * @return boolean
     */
    public function fillGap(\EKindMozambiqueGap $gap, \IMozambiqueGrid $grid);
    
    /**
     * Tries to fill a gap on the grid even if that means creating new ones
     * 
     * This method will ignore tile prefrences
     * 
     * @param \EKindMozambiqueGap $gap
     * @param \IMozambiqueGrid $grid
     * @return boolean
     */
    public function forceFillGap(\EKindMozambiqueGap $gap, \IMozambiqueGrid $grid);
}
