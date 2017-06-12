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

//
jimport('models.rangliste', JPATH_CLM_TURNIER_COMPONENT);

/**
 * DWZ Update Model
 */
class CLM_TurnierModelUpdate_Dwz extends CLM_TurnierModelRangliste {
	// Request Parameter
	protected $param;
	
	// Turnier Daten (eingeschränkt)
	protected $turnier;
	
	// Turnierteilnehmer
	protected $players;

	function __construct() {
		parent::__construct();
		
		// get parameters
		$this->_getParameters();
		
		// get all data
		$this->_getData();
	}

	/**
	 * ermittelt alle vorhandenen Request Parameter
	 */
	protected function _getParameters() {
		$jinput = JFactory::getApplication()->input;
		
		// Turnier ID ermitteln
		$this->param['id'] = $jinput->getInt('id');
	}

	/**
	 * ermittelt die Turnier und Spielerdaten
	 */
	protected function _getData() {
		// Turnierdaten ermitteln
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array(
				't1.name',
				't1.published',
				't1.catIdAllTime',
				't1.dateStart',
				't1.name'
		)));
		$query->from($this->_db->quoteName('#__clm_turniere', 't1'));
		$query->where($this->_db->quoteName('t1.id') . ' = ' . $this->param['id']);
		$this->_db->setQuery($query);
		$this->turnier = $this->_db->loadObject();
		
		// Teilnehmer des Turnieres ermitteln
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_db->quoteName('#__clm_turniere_tlnr', 't2'));
		$query->where($this->_db->quoteName('t2.turnier') . ' = ' . $this->param['id']);
		$this->_db->setQuery($query);
		$this->players = $this->_db->loadObjectList();
		
		// aktuelle Rangliste ermiteln
		$options = array();
		$options['catIdAllTime'] = $this->turnier->catIdAllTime;
		$options['dateBefore'] = $this->turnier->dateStart;
		
		$rangliste = $this->getTurnierKategorieRangliste($options);
		
		// Rangliste "kopieren"
		foreach ($this->players as $row) {
			if (isset($rangliste[$row->name])) {
				$row->new_dwz = $rangliste[$row->name]->DWZ;
				$row->new_I0 = $rangliste[$row->name]->I0;
			} else {
				$row->new_dwz = 0;
				$row->new_I0 = 0;
			}
		}
	}

	/**
	 *
	 * @return array Liste der Turnierteilnehmer
	 */
	public function getTurnierTeilnehmer() {
		return $this->players;
	}

	/**
	 *
	 * @return string Turniernamen
	 */
	public function getTurnierName() {
		return $this->turnier->name;
	}
}