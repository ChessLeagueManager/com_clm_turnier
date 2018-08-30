<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('JPATH_CLM_TURNIER_COMPONENT') or die('Restricted access');

echo "<div class='clm'>";

// Stylesheet laden
jimport('includes.css_path', JPATH_CLM_TURNIER_COMPONENT);

// CLM Config
$config = clm_core::$db->config();

// CLM-Container
echo "<div id='clm'><div id='turnier_rangliste'>";

// Page Header
if ($this->params->get('show_title')) {
    echo CLMTurnierContent::componentTitle($this->escape($this->titel));
}

// Einleitungstext
if (is_object($this->grand_prix) && $this->grand_prix->introduction != null) {
    echo '<p>';
    echo $this->grand_prix->introduction;
    echo '</p>';
}

if (count($this->gesamtwertung) == 0) {
    echo CLMContent::clmWarning(JText::_('COM_CLM_TURNIER_KATEGORIE_GESAMTWERTUNG_NO'));
} else {
    $min_tournaments = 0;
    if ($this->grand_prix->min_tournaments > 0 &&
            count($this->turniere) >= $this->grand_prix->min_tournaments) {
        $min_tournaments = $this->grand_prix->min_tournaments;
        
        $uri = JUri::getInstance();
        $uri->setVar('filter', array('tlnr' => !$this->filter['tlnr']));

        if ($this->filter['tlnr']) {
            $icon_class = 'icon-plus';
            $title = JText::_('COM_CLM_TURNIER_FILTER_TLNR_PLUS');
        } else {
            $icon_class = 'icon-minus';
            $title = JText::_('COM_CLM_TURNIER_FILTER_TLNR_MINUS');
        }
        $title = JHtml::_('tooltipText', $title, '', 0);
?>
        
        <div style="float: right; padding: 2px">
        	<a class="btn btn-micro active hasTooltip" 
        		href="<?php echo $uri; ?>" title="<?php echo $title; ?>"
        	>
        		<span class="<?php echo $icon_class; ?>"></span>
        	</a>        
        </div>
        
<?php 
    }
?>

<table <?php JHtml::_('thead.tableClass', ($config->fixth_ttab == "1")); ?> id="turnier_kategorie_gesamtwertung" cellpadding="0" cellspacing="0">

	<thead>
	<tr>
		<th class="rang"><?php echo JText::_('COM_CLM_TURNIER_COL_RANG') ?></th>
		<?php if ($this->params->get('show_player_title')) { ?>
			<th class="titel"><?php echo JText::_('COM_CLM_TURNIER_COL_PLAYER_TITLE') ?></th>
		<?php } ?>
		<th class="name"><?php echo JText::_('COM_CLM_TURNIER_COL_NAME') ?></th>
		<?php if ($this->params->get('show_verein')) { ?>
			<th class="verein"><?php echo JText::_('COM_CLM_TURNIER_COL_VEREIN') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_dwz') && $this->params->get('show_elo')) { ?>
			<th class="dwz"><?php echo JText::_('COM_CLM_TURNIER_COL_TWZ') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_dwz')) { ?>
			<th class="dwz"><?php echo JText::_('COM_CLM_TURNIER_COL_DWZ') ?></th>
		<?php } ?>
		<?php if ($this->params->get('show_elo')) { ?>
			<th class="dwz"><?php echo JText::_('COM_CLM_TURNIER_COL_ELO') ?></th>
		<?php } ?>
		
		<?php
		for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
        ?>
		<th class="erg">
		<?php
    		if (is_object($this->grand_prix) && $this->grand_prix->col_header) {
		        $colTitle = strftime("%b", mktime(0, 0, 0, $ii));
            } else {
		        $colTitle = $ii;
		    }
		
		    // Turnier gewertet
		    if (isset($this->turniere[$ii])) {
		        $url = JRoute::_('index.php?option=com_clm&view=turnier_rangliste'
		            . '&turnier=' . $this->turniere[$ii]->id
		            . '&orderby=pos');
		        $attribs = 'class="active_link"' .
		  		        ' title="' . $this->turniere[$ii]->name . '"';
		  		        
                $colTitle = JHtml::_('link', $url, $colTitle, $attribs);
            }

		    // Spaltenüberschrift ausgeben
		    echo $colTitle;
        ?>
		</th>
		<?php } ?>
		<th class="gesamt"><?php echo JText::_('COM_CLM_TURNIER_COL_GESATM') ?></th>
	</tr>
	</thead>

	<tbody>
<?php
    // alle Spieler durchgehen
    $p = 0;
    $gb = 0;
    foreach ($this->gesamtwertung as $row) {
        $style = '';
        if (count($row->ergebnis) < $min_tournaments) {
            if ($this->filter['tlnr']) {
                continue;
            }
            $style = 'style="color: red;"';
        }
        
        $p ++; // row count (entspricht Platzierung)
        
        $zeilenr = (($p % 2) != 0) ? '"zeile1"' : '"zeile2"';
        ?>
		
	<tr class=<?php echo $zeilenr . ' ' . $style; ?>>
		<td class="rang">  <?php echo ($row->gesamt <> $gb) ? $p . '. ' : ''; ?>			</td>
		<?php if ($this->params->get('show_player_title')) { ?>
			<td class="titel"> <?php echo $row->titel; ?> 	</td>
		<?php } ?>
		<td class="name">  <?php echo $row->name; ?> 	</td>
		<?php if ($this->params->get('show_verein')) { ?>
			<td class="verein"><?php echo $row->verein; ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_dwz') && $this->params->get('show_elo')) { ?>
			<td class="dwz"><?php echo $row->twz; ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_dwz')) { ?>
			<td class="dwz"><?php echo $row->dwz; ?></td>
		<?php } ?>
		<?php if ($this->params->get('show_elo')) { ?>
			<td class="dwz"><?php echo $row->elo; ?></td>
		<?php } ?>
		<?php
        for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
            $style = '';
            $ergebnis = '';
            if (isset($row->ergebnis[$ii])) {
                $ergebnis = $row->ergebnis[$ii];
                if ($ergebnis < 0 || strcmp(strval($ergebnis), '-0') == 0) {
                    $ergebnis *= - 1;
                    $style = ' style="background-color: yellow;"';
                }
            }
            ?>
		<td class="erg" <?php echo $style; ?>> <?php echo $ergebnis; ?></td>
		<?php
        
        }
        $gb = $row->gesamt;
        ?>
		<td class="gesamt"> <?php echo $row->gesamt; ?>	</td>
	</tr>

<?php 
    }
?>

    </tbody>
</table>


<?php 
} 

// CLM-Container
echo '</div></div>';
echo '</div>';

?>
