<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML helper class
 */
abstract class JHtmlTurnier {

    /**
     *
     * @param string $id            
     * @return Ambigous <NULL>
     */
    public static function getTurnierModus($id = null) {
        $modus = array();
        
        $modus[0] = JText::_('SELECT_MODUS');
        $modus[1] = JText::_('MODUS_TYP_1');
        $modus[2] = JText::_('MODUS_TYP_2');
        $modus[3] = JText::_('MODUS_TYP_3');
        $modus[5] = JText::_('MODUS_TYP_5');
        $modus[6] = JText::_('MODUS_TYP_6');
        
        if ($id != null && $id >= 0 and $id <= count($modus)) {
            return $modus[$id];
        }
        
        return $modus[0];
    }
    
    public static  function  getDatum($dateStart = null, $dateEnd = null) {
        $value = 'unbestimmt'; // TODO
        if (! JHtmlTurnier::isDefaultDate($dateStart)) {
            $value = $dateStart; // TODO Formatieren
        }
        
        if (! JHtmlTurnier::isDefaultDate($dateEnd)) {
            $value = $value . ' bis ' . $dateEnd;
        }
        return $value;
    }
    
    public static function getInofDWZ($params = null, $i) {
        $published = 0;
         return JHtml::_('grid.published', $published, $i, 'tick.png', 'publish_x.png', 'grand_prix.' );
    }
    
    // TODO: auslagern und mit Joomla abgeleichen
    public static function isDefaultDate($value) {
        if ($value == null || 
            $value == '0000-00-00' || $value == '1970-01-01') {
                return true;
            }
        return false;
    }
}
?>