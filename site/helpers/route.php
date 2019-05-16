<?php
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2018 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Utility Klasse für Links
 *
 * @since 2.1
 *       
 */
abstract class Grand_PrixHelperRoute {

	/**
	 * erstellt einen "Link" zu einer Grand Prix Gesamtwertung.
	 *
	 * @param number $id
	 *        	Grand Prix ID
	 * @param number $catidEdition
	 *        	Turnier Kategorie ID
	 * @param array $tids
	 *        	Turnier ID's
	 * @param array $filter
	 *        	Filter (optional)
	 * @param array $rids
	 *        	Ranglisten ID's (optional)
	 * @param number $rid
	 *        	Rangliste ID (optional)
	 * @return string Link zur Grand Prix Gesamtwertung
	 */
	public static function getGrandPrixRoute($id, $catidEdition = 0, $tids = array(), $filter = array(), $rids = array(), $rid = 0) {
		// Link erstellen
		$link = 'index.php?option=com_clm_turnier&view=grand_prix';

		$link .= '&grand_prix=' . $id;

		$link .= '&kategorie=' . $catidEdition;

		if (isset($tids)) {
			foreach ($tids as $key => $value) {
				if (! empty($value)) {
					$link .= '&turniere[' . $key . ']=' . $value;
				}
			}
		}

		if (isset($filter)) {
			foreach ($filter as $key => $value) {
				if (! empty($value)) {
					$link .= '&filter[' . $key . ']=' . $value;
				}
			}
		}

		if (isset($rids) && is_array($rids)) {
			foreach ($rids as $key => $value) {
				if (! empty($value)) {
					$link .= '&ranglisten[' . $key . ']=' . $value;
				}
			}
		}

		if ($rid > 0) {
			$link .= '&rid=' . (int) $rid;
		}

		return $link;
	}

	/**
	 * erstellt einen "Link" zu einer Turnier Rangliste
	 *
	 * @param number $id
	 *        	Turnier ID
	 * @param string $orderBy
	 *        	Sortierung der Teilnehmer (optional)
	 * @return string Link zur Turnier Rangliste
	 */
	public static function getTurnierRanglisteRoute($id, $orderBy = 'pos') {
		// Link erstellen
		$link = 'index.php?option=com_clm&view=turnier_rangliste';

		$link .= '&turnier=' . (int) $id;
		$link .= '&orderBy=' . $orderBy;

		return $link;
	}
}

?>