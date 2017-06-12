<?php
/** 
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

/** 
 * TODO
 * Kategorie(en) anzeigen
 * Reihenfolge anzeigen
 * DWZ aktivieren ?!
 * 
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * View Klasse zur Darstellung der Turnier Liste.
 */
class CLM_TurnierViewTurniere extends JViewLegacy {

    /**
     * An array of items
     *
     * @var array
     */
    protected $items;

    /**
     * The pagination object
     *
     * @var JPagination
     */
    protected $pagination;

    /**
     * The model state
     *
     * @var object
     */
    protected $state;

    /**
     * Form object for search filters
     *
     * @var JForm
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var array
     */
    public $activeFilters;

    /**
     * Display the view.
     *
     * @param string $tpl
     *            The name of the template file to parse; automatically searches through the template paths.
     *            
     * @return mixed A string if successful, otherwise an Error object.
     */
    public function display($tpl = null) {
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        
        // CLM Sprachen Dateien
        $lang = JFactory::getLanguage()->load('com_clm.turnier', JPATH_ADMINISTRATOR);

        $this->addToolbar();
        
        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return void
     */
    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_CLM_TURNIER_TURNIER_TITLE'), 'clm_table_image_tournament');
        JToolBarHelper::custom('turniere.update_dwz', 'edit', '', JText::_('COM_CLM_TURNIER_BUTTON_UPDATE_DWZ'));
    }

    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return array Array containing the field name to sort by as the key and display text as value
     */
    protected function getSortFields() {
        return array(
            'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.published' => JText::_('JSTATUS'),
            'a.name' => JText::_('TOURNAMENT_NAME'),
            'a.sid' => JText::_('SEASON'),
            'a.typ' => JText::_('MODUS')
        );
    }
}