<?php
/** 
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2016 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * enthält Methoden zur Darstellung des DWZ Updates
 */
class CLM_TurnierViewUpdate_Dwz extends JViewLegacy {

	var $turnier = null;
	var $playerlist = null;
	
	function display($tpl = null) {
		// Turnier ID ermitteln
		$id = JFactory::getApplication()->input->getInt('id');
		if (empty($id)) {
		    JError::raiseError(500, JText::_('COM_CLM_TURNIER_UPDATE_DWZ_TURNIER_NO'));
		    return false;
		}
		    
		// Daten an Template übergeben
		$this->turnier = $id;
		$this->playerlist = $this->getModel()->getTurnierTeilnehmer();

		// CLM Sprachen Dateien
		$lang = JFactory::getLanguage()->load('com_clm.turnier', JPATH_ADMINISTRATOR);

		$this->addToolbar();
		
		// Display the view
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */
	protected function addToolbar() {
	    JToolBarHelper::title(JText::_('COM_CLM_TURNIER_UPDATE_DWZ_TITLE') . ': ' . $this->getModel()->getTurnierName());
	    
	    JToolBarHelper::apply('update_dwz.apply');
	    JToolBarHelper::save('update_dwz.save');
	    JToolBarHelper::cancel('update_dwz.cancel');
	}	
}
