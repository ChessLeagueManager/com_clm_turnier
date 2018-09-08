<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('JPATH_CLM_TURNIER_COMPONENT') or die('Restricted access');

/**
 * HTML Grand Prix View Class
 */
class CLM_TurnierViewGrand_Prix extends JViewLegacy {

	/**
	 * The item model state
	 *
	 * @var \Joomla\Registry\Registry
	 * @since 1.6
	 */
	protected $state;

	// Menü Parameter
	protected $params;
	protected $print;

	// Gesamtwertung
	protected $gesamtwertung;

	// Liste der gewerteten Turniere
	protected $turniere;
	protected $anzahlTurniere;

	// Grand Prix Wertung
	protected $grand_prix;

	function display($tpl = null) {
		$this->state = $this->get('State');
		$this->grand_prix = $this->get('Item');
		$this->gesamtwertung = $this->get('GesamtWertung');
		$this->turniere = $this->get('TurnierListe');
		$this->anzahlTurniere = $this->get('AnzahlTurniere');

		// TODO
		$app = JFactory::getApplication();
		$this->print = $app->input->getBool('print');

		$this->mergeParams();

		// Tables with floating headers
		JHtml::_('thead.framework');

		// Display the view
		parent::display($tpl);
	}

	private function mergeParams() {
		$app = JFactory::getApplication();

		$this->params = $app->getParams();
		$this->params->set('show_icons', 1);

		$menu = $app->getMenu();
		if ((isset($menu) && $menu->getActive() != null)) {
			$this->params->set('show_print_icon', 1);
		} else {
			$this->params->set('show_title', 0);
			$this->params->set('show_print_icon', 0);
		}

		$this->params->set('show_filter_icon', $this->get('minTournaments'));
	}
}

?>