<?php
/** 
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('JPATH_CLM_TURNIER_COMPONENT') or die('Restricted access');

/**
 * enthält Methoden zur Darstellung der Rangliste (DWZ) einer Turnier 
 * Kategorie (saisonübergreifend).
 */
class CLM_TurnierViewRangliste extends JViewLegacy {
	// Menü Parameter
	protected $params;
	// Menü Titel
	protected $titel;
	// DWZ Rangliste
	protected $rangliste;
	// Datum des letzten ausgewerteten Turnieres
	protected $maxDateStart;

	function display($tpl = null) {
		$options = array();
		
		$app = JFactory::getApplication();
		if (isset($app)) {
			$this->params = $app->getParams();
			$menu = $app->getMenu();
			if (isset($menu) && $menu->getActive() != null) {
				$this->titel = $menu->getActive()->title;
			} else {
				$this->params->set('show_title', 0);
				$this->params->set('show_verein', $app->input->getUint('show_verein', 0));
				$this->params->set('col_dwz', $app->input->getString('col_dwz', 'DWZ'));
			}
		}
		
		$options['catIdAllTime'] = $app->input->getUint('kategorie');
		$options['dateBefore'] = $app->input->get('date_before');
		
		$model = $this->getModel();
		$this->rangliste = $model->getTurnierKategorieRangliste($options);
		$this->maxDateStart = $model->getTurnierKategorieMaxDateStart($options);
		
		// Tables with floating headers
		JHtml::_('thead.framework');

		// Display the view
		parent::display($tpl);
	}
}