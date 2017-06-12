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
 * enthält das Datenmodell sowie die Datenbankzugriffe welche für die
 * Ermittlung der Rangliste (DWZ) aller Turniere einer Kategorie
 * (saisonübergreifend) erforderlich sind.
 */
class CLM_TurnierModelRangliste extends JModelLegacy {

	/**
	 * gemeinsame SQL WHERE Bedingung.
	 *
	 * @param object $query        	
	 * @param array $options        	
	 * @see getTurnierKategorieRangliste
	 */
	protected function _whereTurnierKategorie(&$query, &$options) {
		// TODO: alias als Parameter?
		$query->where($this->_db->quoteName('t1.published') . ' = 1');
		$query->andWhere($this->_db->quoteName('t1.catIdAllTime') . ' = ' . $options['catIdAllTime']);

		if (isset($options['dateBefore'])) {
			$query->andWhere($this->_db->quoteName('t1.dateStart') . ' < "' . $options['dateBefore'] . '"');
		}

		$query->andWhere($this->_db->quoteName('t1.params') . ' LIKE "%inofDWZ=1%"');
	}

	/**
	 * ermittelt das Datum des letzten ausgewerteten Turnieres.
	 *
	 * @param array $options        	
	 * @see getTurnierKategorieRangliste
	 * @return string Datum des Turnieres
	 */
	public function getTurnierKategorieMaxDateStart(&$options) {
		$maxDateStart = '';
		
		try {
			// Create a new query object
			$query = $this->_db->getQuery(true);
			
			$query->select('MAX(dateStart) maxDateStart');
			$query->from($this->_db->quoteName('#__clm_turniere', 't1'));
			$this->_whereTurnierKategorie($query, $options);
			
			$this->_db->setQuery($query);
			
			$value = $this->_db->loadObject();
			$maxDateStart = $value->maxDateStart;
		} catch (Exception $e) {
			$this->setError($e);
		}
		
		return $maxDateStart;
	}

	/**
	 * ermittelt die Rangliste (DWZ).
	 *
	 * Die Ranfliste wird als Array von Objekten gespeichert. Dabei wird
	 * folgendes Format verwendet:
	 * Array Key:
	 * - Spielername
	 * Object Values:
	 * - name: Spielername
	 * - titel: FIDE Titel
	 * - verein: Verein des Spielers
	 * - zps: ZPS des Vereines
	 * - start_dwz:
	 * - DWZ:
	 * - I0:
	 * - dateStart:
	 *
	 * @param array $options
	 *        	catIdAllTime ID der Turnier Kategorie (saisonübergreifend)
	 *        	dateBefore nur Turniere berücksichtigen, die vor dem Datum ausgerichtet wurden
	 * @return array Rangliste
	 */
	public function getTurnierKategorieRangliste(&$options) {
		$list = array();
		
		try {
			// Create a new query object
			$query = $this->_db->getQuery(true);
			$subQuery = $this->_db->getQuery(true);
			
			$subQuery->select($this->_db->quoteName(array(
					't2.name',
					't2.verein',
					't2.titel',
					't2.zps',
					't2.start_dwz',
					't2.DWZ',
					't2.I0',
					't1.dateStart'
			)));
			$subQuery->from($this->_db->quoteName('#__clm_turniere', 't1'));
			$subQuery->join('INNER', $this->_db->quoteName('#__clm_turniere_tlnr', 't2') . ' ON ' . $this->_db->quoteName('t2.turnier') . ' = ' . $this->_db->quoteName('t1.id'));
			$this->_whereTurnierKategorie($subQuery, $options);
			$subQuery->order($this->_db->quoteName('t1.dateStart') . ' DESC');
			
			$query->select('*');
			$query->from('(' . $subQuery . ') AS subquery');
			$query->group($this->_db->quoteName('name'));
			$query->order($this->_db->quoteName('DWZ') . ' DESC');
			$query->order($this->_db->quoteName('I0') . ' DESC');
			
			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectList('name');
		} catch (Exception $e) {
			$this->setError($e);
		}
		
		return $list;
	}
}
