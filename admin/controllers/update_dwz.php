<?php
/** 
 * @copyright (C) 2016 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//
jimport('clm.index', JPATH_CLM_COMPONENT);
jimport('clm.api.db_tournament_delDWZ', JPATH_CLM_COMPONENT);

/**
 * DWZ Update Controller
 */
class CLM_TurnierControllerUpdate_Dwz extends JControllerLegacy {
    // The default view for the display method.
    protected $default_view = 'update_dwz';
    
	protected $jinput;
	
	// Request Parameter
	protected $param;
	//
	protected $adminLink;

	function __construct($config = array()) {
		parent::__construct($config);

		JLoader::register('AdminLink', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm' . DS . 'classes' . DS . 'AdminLink.class.php');
		
		// The default view for the display method.
		$this->default_view = 'update_dwz';
		
		$this->_getParameters();
		
		// Register Extra tasks
		$this->registerTask('apply', 'save');
		
		$this->adminLink = new AdminLink('com_clm_turnier');
		$this->adminLink->view = 'turniere';
		$this->adminLink->more = array(
				'id' => $this->param['id']
		);
	}

	/**
	 * Method to display a view.
	 *
	 * @param boolean $cachable
	 *            If true, the view output will be cached
	 * @param array $urlparams
	 *            An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return ContactController This object to support chaining.
	 */
	function display($cachable = false, $urlparams = array()) {
	    $view = $this->input->get('view', $this->default_view);
	    $layout = $this->input->get('layout', 'default');
	    $id = $this->input->getInt('id');
	    
	    parent::display();
	}
	
	/**
	 * ermittelt alle vorhandenen Request Parameter
	 */
	protected function _getParameters() {
		$this->jinput = JFactory::getApplication()->input;
		
		// Turnier ID ermitteln
		$this->param['id'] = $this->jinput->getInt('id');		
		$this->param['task'] = $this->jinput->getCmd('task');
	}

	/**
	 */
	public function save() {
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');
		
		$cid = $this->jinput->get('cid');
		if (count($cid) && $this->_doSave($cid)) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('DWZ_UPDATE_TURNIER_SAVE') . '(' . count($cid) . ')');
		}
		// sonst Fehlermeldung schon geschrieben
		
		// je nach Task: Message und Weiterleitung
		switch ($this->param['task']) {
			case 'save' :
				break;
			case 'apply' :
			default :
				$this->adminLink->view = "update_dwz"; // WL in Liste
				break;
		}
		
		$this->adminLink->makeURL();
		$this->setRedirect($this->adminLink->url);
	}

	/**
	 * übernimmt das eigendtliche Speichern
	 *
	 * @return boolen
	 */
	private function _doSave($cid) {
		$new_dwz = JRequest::getVar('new_dwz', array(), '', 'array');
		JArrayHelper::toInteger($new_dwz, array());
		
		$new_I0 = JRequest::getVar('new_I0', array(), '', 'array');
		JArrayHelper::toInteger($new_I0, array());
		
		// Set the table directory
		JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_clm' . DS . 'tables');
		$row = JTable::getInstance('turnier_teilnehmer', 'TableCLM');
		
		foreach ($cid as $playerid) {
			$row->load($playerid); // Daten zu dieser ID laden
			                       // Spieler existent?
			if (! $row->id) {
				JError::raiseWarning(500, CLMText::errorText('PLAYER', 'NOTEXISTING'));
				return false;
			} else {
				$row->start_dwz = $new_dwz[$playerid];
				$row->twz = $new_dwz[$playerid];
				$row->FIDEelo = 0;
				$row->start_I0 = $new_I0[$playerid];
				
				if (! $row->store()) {
					JError::raiseError(500, $row->getError());
					return false;
				}
			}
		}
		
		// DWZ Auswertung entfernen
		clm_api_db_tournament_delDWZ($this->param['id'], false);
		
		return true;
	}

	/**
	 */
	public function cancel() {
		$this->adminLink->makeURL();
		$this->setRedirect($this->adminLink->url);
	}
}
