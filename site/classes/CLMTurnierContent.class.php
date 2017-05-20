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
 * CLMTurnierContent
 *
 * Klassenbibliothek für content-bezogene, modulare, Funktionalitäten
 * keine Printausgabe, immer nur String-Rückgabe
 */
class CLMTurnierContent extends CLMContent {

    /**
     * Componenten Titel, entspricht einem Beitragstitel
     *
     * @param string $text            
     * @return string
     */
    public static function componentTitle($text) {
        $string = '<div class="page-header">';
        $string .= '<h2 itemprop="name">';
        $string .= $text;
        $string .= '</h2>';
        $string .= '</div>';
        $string .= '<p> </p>';
        
        return $string;
    }
}
?>