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

if (substr(JVERSION, 0, 1) > 2) {
    $GLOBALS["clm"]["grid.checkall"] = JHtml::_('grid.checkall');
} else {
    $GLOBALS["clm"]["grid.checkall"] = '<input type="checkbox" name="toggle" value="" onclick="checkAll(this);" />';
}

// Bei Standalone Verbindung wird das Backend Login verwendet
$_GET["clm_backend"] = 1;

// Component definitions
file_exists(JPATH_SITE . '/components/com_clm_turnier/includes/defines.php') or die();
include_once JPATH_SITE . '/components/com_clm_turnier/includes/defines.php';

// erstellt DS und kümmert sich um die Rechteverwaltung
jimport('clm.index', JPATH_CLM_COMPONENT);
// lädt Funktion zum sichern vor SQL-Injektion
jimport('includes.escape', JPATH_CLM_COMPONENT);

jimport('joomla.filesystem.folder');

// lädt CLM Klassen
$classpath = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm' . DS . 'classes';
foreach (array(
    'AdminLink.class.php',
    'CLMText.class.php'
) as $file) {
    JLoader::register(str_replace('.class.php', '', $file), $classpath . DS . $file);
}

$app = JFactory::getApplication();
$template = $app->getTemplate('template')->template;
$config = clm_core::$db->config();

if ($config->isis_remove_sidebar > 0 && ($config->isis_remove_sidebar == 2 || $template == "isis")) {
    clm_core::$load->load_css("isis_fix");
}
if ($config->isis > 0 && ($config->isis == 2 || $template == "isis")) {
    $document = JFactory::getDocument();
    $document->addStyleSheet("../components/com_clm/includes/clm_isis.css");
}

// läd CLM Sprachen Dateien
$lang = JFactory::getLanguage();
$lang->load('com_clm', JPATH_ADMINISTRATOR);

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm' . DS . 'tables');
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm_turnier' . DS . 'tables');

echo '<div id="clm"><div class="clm">';

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('clm_turnier');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();

echo "</div></div>";
