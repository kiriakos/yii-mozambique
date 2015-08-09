<?php
/**
 * Is thrown by a an ITile when its size get out of its Hard boundaries
 * 
 * EG: in an EKindMozambiqueActiveRecordTile this happens when the handler 
 * (IGridDesigner) tries to decrease width or height to 0 or tries to exceed 
 * MAX_WIDTH.
 *
 * @author kiriakos
 */
class EKindMozambiqueDimensionOutOfBoundsException extends CException{

}
