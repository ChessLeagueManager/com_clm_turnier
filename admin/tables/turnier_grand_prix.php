<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Grand Prix Datenbank Tabelle
 */
class TableCLMTurnier_Grand_Prix extends JTable {

    var $id = null;

    var $published = 0;

    var $checked_out = 0;

    var $checked_out_time = 0;

    // Name der Grand-Prix Wertung
    var $name = '';

    // Modus der Grand-Prix Wertung
    var $typ = 0;

    // Punktevergabe
    var $typ_calculation = '';

    // Feinwertung eines Turniers bei der Berechnung berücksichtigen
    var $use_tiebreak = 0;

    // Anzahl der zu berücksichtigenden Turniere für die Gesdamtwertung
    // 0 = alle Turniere werden berücksichtigt
    var $best_of = 0;

    // 1 = monatliche Turniere
    var $col_header = 0;

    // Einleitungstext, dieser wird vor der Tabelle ausgegeben
    var $introduction = null;

    function __construct(&$_db) {
        parent::__construct('#__clm_turniere_grand_prix', 'id', $_db);
    }

    /**
     * Overloaded check function
     *
     * @access public
     * @return boolean
     * @see JTable::check
     *
     * @return boolean
     */
    function check() {
        // Name vorhanden
        if (trim($this->name) == '') {
            $this->setError(CLMText::errorText('NAME', 'MISSING'));
            return false;
        }
        
        // Grand Prix Modus gewählt
        if ($this->typ == 0) {
            $this->published = 0;
        }
        
        return true;
    }
}

?>