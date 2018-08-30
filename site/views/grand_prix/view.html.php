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
    protected $filter;

    // Menü Parameter
    protected $params;

    // Menü Titel
    protected $titel;

    // Gesamtwertung
    protected $gesamtwertung;
    
    // Liste der gewerteten Turniere 
    protected $turniere;
    protected $anzahlTurniere;
    
    // Grand Prix Wertung
    protected $grand_prix;

    function display($tpl = null) {
        $app = JFactory::getApplication();
        
        $this->params = $app->getParams();
        $menu = $app->getMenu();
        if (isset($menu) && $menu->getActive() != null) {
            $this->titel = $menu->getActive()->title;
        } else {
            $this->params->set('show_title', 0);
        }
        
        $this->state = $this->get('State');
        $this->grand_prix = $this->get('Item');
        $this->gesamtwertung = $this->get('GesamtWertung');
        $this->turniere = $this->get('TurnierListe');
        $this->anzahlTurniere = $this->get('AnzahlTurniere');
        
        // Tables with floating headers
        JHtml::_('thead.framework');
      
        // Filter
        $this->filter = $this->state->get('grand_prix.filter');

        // Display the view
        parent::display($tpl);
    }
}

?>