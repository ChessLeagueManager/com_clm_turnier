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

// Component definitions
file_exists(__DIR__ . '/includes/defines.php') or die();
include_once __DIR__ . '/includes/defines.php';

// TODO: intelligenten CLMLoader ...
jimport('joomla.filesystem.folder');

// lädt alle CLM-Klassen - quasi autoload
$classpath = JPATH_CLM_COMPONENT . DS . 'classes';
foreach (JFolder::files($classpath) as $file) {
    JLoader::register(str_replace('.class.php', '', $file), $classpath . DS . $file);
}

$classpath = JPATH_SITE . DS . 'components' . DS . 'com_clm_turnier' . DS . 'classes';
foreach (JFolder::files($classpath) as $file) {
    JLoader::register(str_replace('.class.php', '', $file), $classpath . DS . $file);
}

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm_turnier' . DS . 'tables');

// set current locale for date and time formatting with strftime()
setlocale(LC_TIME, JFactory::getLanguage()->getLocale());

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('CLM_Turnier');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
