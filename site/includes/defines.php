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

// Directory Separator (backward compatibility)
if (! defined("DS")) {
    define('DS', DIRECTORY_SEPARATOR);
}

// CLM Turnier Component Path
if (! defined('JPATH_CLM_TURNIER_COMPONENT')) {
    define('JPATH_CLM_TURNIER_COMPONENT', JPATH_SITE . DS . 'components' . DS . 'com_clm_turnier');
}

// CLM Component Path
if (! defined('JPATH_CLM_COMPONENT')) {
    define('JPATH_CLM_COMPONENT', JPATH_SITE . DS . 'components' . DS . 'com_clm');
}
