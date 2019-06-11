<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML Utility Klasse für Turnier Erweiterung
 *
 * @since 3.0.0
 */
abstract class JHtmlClm_Turnier {
	/**
	 *
	 * @var array Array containing information for loaded files
	 */
	protected static $loaded = array ();

	/**
	 * läd CSS Style Sheets
	 */
	public static function framework() {
		// Only load once
		if (! empty(static::$loaded[__METHOD__])) {
			return;
		}

		$config = clm_core::$db->config();
		if (! $config->template) {
			return;
		}


		// CLM Template Einstellungen
		// Lesehilfe
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('
	#clm .clm .zeile1 { background-color: #' . $config->zeile1 . '; }');
		$document->addStyleDeclaration('
	#clm .clm .zeile2 {	background-color: #' . $config->zeile2 . '; }');
		
		if ($config->lesehilfe == '1') {
			$document->addStyleDeclaration('
	#clm .clm tr.zeile1:hover td, #clm .clm tr.zeile2:hover td { background-color: #FFFFBB !important; }');
		}

		$document->addStyleDeclaration('
	#clm .clm table th { background-color: #' . $config->tableth . '; color: #' . $config->tableth_s1 . ' !important; }');
		$document->addStyleDeclaration('
	#clm .clm table th, #clm .clm table td {
		padding-top: ' . $config->cellin_top . '; 
		padding-left: ' . $config->cellin_left . ';
		padding-right: ' . $config->cellin_right . ';
		padding-bottom: ' . $config->cellin_bottom . ';
		border: ' .	$config->border_length . ' ' . $config->border_style . ' #' . $config->border_color . '; 
	}');

		$document->addStyleDeclaration('
	#clm .clm table .anfang, #clm .clm table .ende, #clm .clm .clm-navigator ul li, #clm .clm .clm-navigator ul li ul {
		background-color: #' .	$config->subth . ';
		color: #' . $config->tableth_s2 . ' !important;
	}');

		$document->addStyleDeclaration('
	#wrong, .wrong { background: #' . $config->wrong1 . '; border: ' . $config->wrong2_length . ' ' . $config->wrong2_style . ' #' . $config->wrong2_color . '; }');

		JHTML::_('stylesheet', 'com_clm_turnier/content.css', array (
				'version' => 'auto', 'relative' => true ));

		static::$loaded[__METHOD__] = true;

		return;
	}
}