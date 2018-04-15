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

    // Menü Titel
    protected $titel;

    // Gesamtwertung
    protected $gesamtwertung;

    protected $grand_prix;

    function display($tpl = null) {
        $options = array();
        
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
        $this->anzahlTurniere = $this->get('AnzahlTurniere');
        
        // tableWithFloatingHeader
        $document = JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
        $document->addScript(JURI::base() . 'components/com_clm/javascript/updateTableHeaders.js');
        
        // Display the view
        parent::display($tpl);
    }
}

?>