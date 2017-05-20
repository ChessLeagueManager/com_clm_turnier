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
 * Grand Prix Model Class
 */
class CLM_TurnierModelGrand_Prix extends JModelLegacy {

    // Grand Prix Wertung
    protected $grandPrix = null;

    // Grand Prix Gesamtwertung
    protected $gesamtergebnis = array();

    // Anzahl der gewerteten Turniere
    protected $anzahlTurniere = 0;

    /**
     * ermittelt für den Modus 'absolut' die Verteilung der Punkte für die
     * jeweilige Platzierung innerhalb eines Turnieres.
     * Bei gleicher Platzierung werden die Punkte auf die Spieler gleichmäßig
     * verteilt.
     *
     * @param int $pk
     *            des Turnieres
     *            
     * @return array Verteilung der Punkte
     */
    protected function _getPunkteVerteilung($pk, $wertung) {
        $query = $this->_db->getQuery(true);
        
        $query->select('t1.sum_punkte, count(*) AS anzahl');
        $query->from($this->_db->quoteName('#__clm_turniere_tlnr', 't1'));
        $query->where($this->_db->quoteName('t1.turnier') . ' = ' . $pk);
        $query->group($this->_db->quoteName('t1.sum_punkte') . ' DESC');
        
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();
        
        $ii = 0;
        $verteilung = array();
        foreach ($list as $row) {
            $sum = 0;
            for ($ik = 0; $ik < $row->anzahl; $ik ++) {
                if (isset($wertung[$ii]))
                    $sum += (int) $wertung[$ii ++];
            }
            
            if ($sum == 0)
                break;
            $sum = round($sum / $row->anzahl, 1);
            
            for ($ik = 0; $ik < $row->anzahl; $ik ++) {
                array_push($verteilung, $sum);
            }
        }
        
        return $verteilung;
    }

    /**
     * ermittelt die Rangliste eines Turnieres.
     *
     * @param int $pk
     *            Id des Turnieres
     *            
     * @return array Rangliste
     */
    protected function _loadTurnierErgebnis($pk) {
        $query = $this->_db->getQuery(true);
        $query->select($this->_db->quoteName(explode(',', 't2.name,t2.verein,t2.titel,t2.sum_punkte,t2.rankingPos,t2.sumTiebr1,t2.sumTiebr2,t2.sumTiebr3,t1.dateStart,t1.runden,t1.dg')));
        $query->from($this->_db->quoteName('#__clm_turniere', 't1'));
        $query->join('INNER', $this->_db->quoteName('#__clm_turniere_tlnr', 't2') . ' ON ' . $this->_db->quoteName('t2.turnier') . ' = ' . $this->_db->quoteName('t1.id'));
        $query->where($this->_db->quoteName('t1.id') . ' = ' . $pk);
        $query->order($this->_db->quoteName('t2.rankingPos') . ' ASC');
        $query->order($this->_db->quoteName('t2.sumTiebr1') . ' DESC');
        $query->order($this->_db->quoteName('t2.sumTiebr2') . ' DESC');
        $query->order($this->_db->quoteName('t2.sumTiebr3') . ' DESC');
        
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();
        return $list;
    }

    /**
     * speichert das Turnierergebnis eines Spielers.
     *
     * @param integer $ii
     *            Index im Gesamtergebnis
     * @param unknown $row            
     * @param float $punkte            
     */
    protected function _setErgebnis($ii, $row, $punkte) {
        if (isset($this->gesamtergebnis[$row->name])) {
            $spieler = $this->gesamtergebnis[$row->name];
        } else {
            $spieler = new stdClass();
            $spieler->name = $row->name;
            $spieler->titel = $row->titel;
            $spieler->gesamt = 0;
            $spieler->ergebnis = array();
        }
        
        $spieler->ergebnis[$ii] = $punkte;
        $this->gesamtergebnis[$row->name] = $spieler;
    }

    /**
     * berechnet das Turnierergebnis für den Modus 'Summe'.
     *
     * <p>
     * Die Grand Prix Gesamtwertung berechnet sich aus den Punkten der einzelnen
     * Turniere.
     *
     * Punkte := erzielten Punkte
     * </p>
     *
     * @param integer $pk
     *            Id des Turnieres
     * @param integer $ii
     *            Index im Gesamtergebnis
     */
    protected function _getTurnierErgebnisSumme($pk, $ii) {
        $list = $this->_loadTurnierErgebnis($pk);
        foreach ($list as $row) {
            $this->_setErgebnis($ii, $row, $row->sum_punkte);
        }
    }

    /**
     * berechnet das Turnierergebnis für den Modus 'prozentual'.
     *
     * <p>
     * Die Grand Prix Gesamtwertung berechnet sich aus dem Prozentwert der
     * Punkte der einzelnen Turniere.
     *
     * Punkte := erzielte Punkte / Anzahl Runden * 100
     * </p>
     *
     * @param integer $pk
     *            Id des Turnieres
     * @param integer $ii
     *            Index im Gesamtergebnis
     */
    protected function _getTurnierErgebnisProzentual($pk, $ii) {
        $list = $this->_loadTurnierErgebnis($pk);
        $count = $list[0]->runden * $list[0]->dg;
        foreach ($list as $row) {
            $this->_setErgebnis($ii, $row, round($row->sum_punkte / $count * 100));
        }
    }

    /**
     * berechnet das Turnierergebnis für den Modus 'absolut'.
     *
     * <p>
     * Die Grand Prix Gesamtwertung berechnet sich nach folgendem Schema:
     *
     * <ol>
     * <li>Anhand einer vorgegebenen Punkteverteilung (Feld typ_calculation).</li>
     *
     * <li>Anhand der Teilnehmeranzahl des Turnieres. Die Punkteverteilung
     * berechnet sich dabei nach folgender Formel:
     *
     * Punkte = Anzahl Teilnehmer – Platzierung + 1
     * </li>
     * </ol>
     *
     * Bleibt die Feinwertung unberücksichtigt, so erfolgt eine Punkteteilung
     * bei Teilnehmern mit gleichem Turnierergebnis.
     * </p>
     *
     * @param integer $pk
     *            Id des Turnieres
     * @param integer $ii
     *            Index im Gesamtergebnis
     */
    protected function _getTurnierErgebnisAbsolut($pk, $ii) {
        $list = $this->_loadTurnierErgebnis($pk);
        
        if ($this->grandPrix->typ_calculation == null || trim($this->grandPrix->typ_calculation == '')) {
            $wertung = array();
            for ($ik = count($list); $ik > 0; $ik --) {
                array_push($wertung, $ik);
            }
        } else {
            $wertung = explode(' ', $this->grandPrix->typ_calculation);
        }
        
        $punkte = ($this->grandPrix->use_tiebreak) ? $wertung : $this->_getPunkteVerteilung($pk, $wertung);
        
        foreach ($list as $row) {
            if ($row->rankingPos > 0 && $row->rankingPos <= count($punkte)) {
                $this->_setErgebnis($ii, $row, $punkte[$row->rankingPos - 1]);
            }
        }
    }

    /**
     * ermittelt die veröffentlichten Turniere einer Kategorie (Veranstaltung).
     *
     * @return array veröffentlichte Turniere
     */
    protected function _loadTurnierListe() {
        $catidEdition = (int) $this->getState('grand_prix.catidEdition');
        
        // Create a new query object
        $query = $this->_db->getQuery(true);
        $query->select($this->_db->quoteName(explode(',', 't1.id,t1.dateStart')));
        $query->from($this->_db->quoteName('#__clm_turniere', 't1'));
        $query->where($this->_db->quoteName('t1.published') . ' = 1');
        $query->andWhere($this->_db->quoteName('t1.catidEdition') . ' = ' . $catidEdition);
        
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();
        
        $this->anzahlTurniere = ($this->grandPrix->col_header) ? 12 : count($list);
        
        return $list;
    }

    /**
     * berechnet die Grand Prix Gesamtwertung der veröffentlichten Turniere
     * einer Kategorie (Veranstaltung).
     *
     * <p>
     * Die Gesamtwertung wird als Array von Objekten gespeichert. Dabei wird
     * folgendes Format verwendet:
     * </p>
     * <p>Array Key:
     * <ul>
     * <li>Spielername</li>
     * </ul>
     * </p>
     * <p>Object Values:
     * <ul>
     * <li>name: Spielername</li>
     * <li>titel: FIDE Titel</li>
     * <li>gesamt: Gesamtpunktzahl aus allen Turnieren</li>
     * <li>ergebnis: Array mit den Einzelergebnissen</li>
     * <ul>
     * </p>
     * <p>
     * Ein negatives Einzelergbnis bedeutet, dass diese in der Gesamtwertung
     * nicht berücksichtigt ist.
     * </p>
     */
    protected function _getGesamtwertung() {
        // veröffentlichte Turniere ermitteln
        $list = $this->_loadTurnierListe();
        
        // Tunrierergebnisse berechnen
        $ii = 0;
        foreach ($list as $row) {
            $ii ++;
            if ($this->grandPrix->col_header) {
                $date = getdate(strtotime($row->dateStart));
                $ii = $date["mon"];
            }
            
            switch ($this->grandPrix->typ) {
                case 3:
                    $this->_getTurnierErgebnisSumme($row->id, $ii);
                    break;
                case 2:
                    $this->_getTurnierErgebnisProzentual($row->id, $ii);
                    break;
                default:
                    $this->_getTurnierErgebnisAbsolut($row->id, $ii);
                    break;
            }
        }
        
        // Einzelergebnisse aufaddieren
        foreach ($this->gesamtergebnis as $spieler) {
            $ergebnis = $spieler->ergebnis;
            rsort($ergebnis, SORT_NUMERIC);
            for ($ii = 0; $ii < count($ergebnis); $ii ++) {
                if ($this->grandPrix->best_of == 0 || $ii < $this->grandPrix->best_of) {
                    $spieler->gesamt += $ergebnis[$ii];
                } else {
                    $key = array_search($ergebnis[$ii], $spieler->ergebnis);
                    $spieler->ergebnis[$key] *= - 1;
                }
            }
        }
        
        // Gesamtwertung sortieren
        usort($this->gesamtergebnis, function ($a, $b) {
            return $a->gesamt < $b->gesamt;
        });
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return void
     */
    protected function populateState() {
        $app = JFactory::getApplication('site');
        
        // Load state from the request.
        $pk = $app->input->getInt('grand_prix');
        $this->setState('grand_prix.id', $pk);
        
        $catidEdition = $app->input->getInt('kategorie');
        $this->setState('grand_prix.catidEdition', $catidEdition);
    }

    /**
     * berechnet die Grand Prix Geamtwertung.
     *
     * @param integer $pk
     *            Id der Grand Prix Wertung
     *            
     * @return mixed Grand Prix Object, false im Fehlerfall
     */
    public function getItem($pk = null) {
        $pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
        if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
            
            // Grand Prix Wertung ermitteln
            try {
                $result = JTable::getInstance('turnier_grand_prix', 'TableCLM');
                if (! $result->load($pk)) {
                    return false;
                }
                $this->grandPrix = $result;
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
            
            // Grand Prix Gesamtwertung berechnen
            try {
                $this->_getGesamtwertung();
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
        }
        
        return $this->grandPrix;
    }

    /**
     * Grand Prix Gesamtwertung.
     *
     * @param integer $pk
     *            Id der Grand Prix Wertung
     *            
     * @return Ambigous <multitype:, stdClass>
     */
    public function getGesamtWertung($pk = null) {
        $pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
        if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
            $this->getItem($pk);
        }
        
        return $this->gesamtergebnis;
    }

    /**
     * Anzahl der gewerteten Turniere.
     *
     * @param integer $pk
     *            Id der Grand Prix Wertung
     *            
     * @return integer Anzahl der Turniere
     */
    public function getAnzahlTurniere($pk = null) {
        $pk = (! empty($pk)) ? $pk : (int) $this->getState('grand_prix.id');
        if ($this->grandPrix === null || $this->grandPrix->id != $pk) {
            $this->getItem($pk);
        }
        
        return $this->anzahlTurniere;
    }
}

?>