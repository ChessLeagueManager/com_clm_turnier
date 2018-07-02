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
 * Utility class for THead JavaScript behaviors 
 * floatThead
 * 
 * @since 2.1
 * @see https://github.com/mkoryak/floatThead
 */
abstract class JHtmlThead {

    /**
     * @var    array  Array containing information for loaded files
     */
    protected static $loaded = array();
    
    /**
     * Method to load the jQuery JavaScript framework into the document head
     */
    public static function framework() {
        // Only load once
        if (!empty(static::$loaded[__METHOD__])) {
            return;
        }

        // load jQuery JavaScript framework
        JHtml::_('jquery.framework');
        
        // load floatThead JavaScript framework
        JHtml::_('script', 'https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.1.2/jquery.floatThead.js');

        //
        $document = JFactory::getDocument();
        $document->addScriptDeclaration('
            if (typeof jQuery == "function") {
                jQuery(document).ready(function() {        
                    jQuery(".theadFloatingHeader").floatThead({position: "absolute"});
                });
            } else if (typeof $ == "function") {
                $(document).ready(function() {        
                    $(".theadFloatingHeader").floatThead({position: "absolute"});
                });
            }
        ');
        
        static::$loaded[__METHOD__] = true;
        
        return;
    }
    
    /**
     * 
     * @param boolean $active
     */
    public static function tableClass($active = true) {
        if ($active == true)
            echo 'class="theadFloatingHeader"';
    }
}

?>
