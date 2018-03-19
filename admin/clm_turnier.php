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

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_clm_turnier')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Component definitions
if (! file_exists(JPATH_SITE . '/components/com_clm_turnier/includes/defines.php')) {
    throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}
include_once JPATH_SITE . '/components/com_clm_turnier/includes/defines.php';

// Chess League Manager installiert ?
if (! JComponentHelper::isInstalled('com_clm') 
        || ! file_exists(JPATH_CLM_COMPONENT . '/clm/index.php')) {
    throw new Exception(JText::_('COM_CLM_TURNIER_REQ_COM_CLM'), '404');
}
include_once JPATH_CLM_COMPONENT . '/clm/index.php';


echo '<div id="clm"><div class="clm">';

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('clm_turnier');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();

echo "</div></div>";
