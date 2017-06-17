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

// Chess League Manager installiert ?
if (! JComponentHelper::isInstalled('com_clm')) {
    JError::raiseError('404', JText::_('COM_CLM_TURNIER_REQ_COM_CLM'));
    return;
}

// Component definitions
if (! file_exists(JPATH_SITE . '/components/com_clm_turnier/includes/defines.php')) {
    JError::raiseError('404', JText::_('COM_CLM_TURNIER_ERROR'));
    return;
}
include_once JPATH_SITE . '/components/com_clm_turnier/includes/defines.php';

echo '<div id="clm"><div class="clm">';

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('clm_turnier');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();

echo "</div></div>";
