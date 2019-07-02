<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// TODO: defines und "bootstrap" trennen, evtl. auch Site und Admin

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Directory Separator (backward compatibility)
if (! defined("DS")) {
	define('DS', DIRECTORY_SEPARATOR);
}

// CLM Turnier Component Path
if (! defined('JPATH_CLM_TURNIER_COMPONENT')) {
	define('JPATH_CLM_TURNIER_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR .
			'components' . DIRECTORY_SEPARATOR . 'com_clm_turnier');
}

// CLM Turnier Component Administrator Path
if (! defined('JPATH_ADMIN_CLM_TURNIER_COMPONENT')) {
	define('JPATH_ADMIN_CLM_TURNIER_COMPONENT', JPATH_ADMINISTRATOR .
			DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR .
			'com_clm_turnier');
}

// CLM Component Path
if (! defined('JPATH_CLM_COMPONENT')) {
	define('JPATH_CLM_COMPONENT', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' .
			DIRECTORY_SEPARATOR . 'com_clm');
}

// CLM Component Administrator Path
if (! defined('JPATH_ADMIN_CLM_COMPONENT')) {
	define('JPATH_ADMIN_CLM_COMPONENT', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR .
			'components' . DIRECTORY_SEPARATOR . 'com_clm');
}

jimport('joomla.filesystem.folder');

// lädt alle Klassen - quasi autoload
// ---- Chess League Manager
if (! jimport('clm/index', JPATH_CLM_COMPONENT)) {
	throw new Exception(JText::_('COM_CLM_TURNIER_ERROR'), '404');
}

$classpath = JPATH_CLM_COMPONENT . DIRECTORY_SEPARATOR . 'classes';
foreach (JFolder::files($classpath) as $file) {
	JLoader::register(str_replace('.class.php', '', $file), $classpath . DIRECTORY_SEPARATOR . $file);
}

// ---- Turniererweiterung
$classpath = JPATH_CLM_TURNIER_COMPONENT . DIRECTORY_SEPARATOR . 'classes';
foreach (JFolder::files($classpath) as $file) {
	JLoader::register(str_replace('.class.php', '', $file), $classpath . DIRECTORY_SEPARATOR . $file);
}

JLoader::register('Grand_PrixHelperRoute', JPATH_CLM_TURNIER_COMPONENT . '/helpers/route.php');

// Add include path for ...
JHtml::addIncludePath(JPATH_CLM_TURNIER_COMPONENT . '/helpers/html');
JTable::addIncludePath(JPATH_ADMIN_CLM_TURNIER_COMPONENT . '/tables');
