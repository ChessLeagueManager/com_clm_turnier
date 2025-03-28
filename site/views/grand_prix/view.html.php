<?php
use Joomla\CMS\Mail\Mail;

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
	protected $offset;

	// Grand Prix Wertung
	protected $grand_prix;
	
	/**
	 * Display the view.
	 *
	 * @param string $tpl
	 *        	The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return mixed A string if successful, otherwise an Error object.
	 */
	function display($tpl = null) {
		$app = JFactory::getApplication();

		$this->state = $this->get('State');
		$this->grand_prix = $this->get('Item');
		$this->gesamtwertung = $this->get('GesamtWertung');
		$this->turniere = $this->get('TurnierListe');
		$this->anzahlTurniere = $this->get('AnzahlTurniere');
		$this->offset = $this->get('TurnierOffset');
		
		$this->print = $app->input->getBool('print');

		// merge params
		$this->params = $this->state->get('params');
		if ($this->params->get('show_filter_icon')) {
			$this->params->set('show_filter_icon', $this->get('minTournaments'));
		}

		$menu = $app->getMenu();
		if (! (isset($menu) && $menu->getActive() != null)) {
			$this->params->set('show_title', 0);
			$this->params->set('show_print_icon', 0);
			$this->params->set('show_email_icon', 0);
		}

		// Tables with floating headers
		JHtml::_('thead.framework');

		// Display the view
		parent::display($tpl);
	}

	/**
	 * ermittelt Turnier Index innerhalb der Gesamtwertung.
	 *
	 * @param integer $ii
	 *        	Schleifenzähler
	 * @return integer Turnier Index
	 */
	protected function getTurnierIndex($ii) {
		if ($this->grand_prix->col_header && $this->offset > 0) {
			$ii += $this->offset;
			$ii = ($ii > 12) ? ($ii - 12) : $ii;
		}

		return $ii;
	}
}

?>