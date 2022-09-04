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

jimport('clm.classes.params', JPATH_CLM_COMPONENT);

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

    public static function getDatum($dateStart = null, $dateEnd = null) {
        $value = JText::_('COM_CLM_TURNIER_UNKNOWN_DATE');
        if (! JHtmlTurnier::isDefaultDate($dateStart)) {
            $value = JHtml::_('date', $dateStart, JText::_('DATE_FORMAT_CLM'));
        }
        
        if (! JHtmlTurnier::isDefaultDate($dateEnd)) {
            $value = $value . ' ' . JText::_('TOURNAMENT_UNTIL') . ' ' . JHtml::_('date', $dateEnd, JText::_('DATE_FORMAT_CLM'));
        }
        return $value;
    }

    public static function getInofDWZ($params = null, $i = 0) {
        $turParams = new clm_class_params($params);
        $published = $turParams->get('inofDWZ');
        // TODO HELP TEXT ??
        return JHtml::_('jgrid.published', $published, $i, 'turniere.dwz_');
    }

    // TODO: auslagern und mit Joomla / CLM abgeleichen
    public static function isDefaultDate($value) {
        if ($value == null || $value == '0000-00-00' || $value == '1970-01-01') {
            return true;
        }
        return false;
    }
}
?>