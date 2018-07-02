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
    ?>

<table <?php JHtml::_('thead.tableClass', ($config->fixth_ttab == "1")); ?> id="turnier_kategorie_gesamtwertung" cellpadding="0" cellspacing="0" ?>

	<thead>
	<tr>
		<th class="rang">Nr.</th>
		<th class="titel">Titel</th>
		<th class="name">Name</th>		
		<?php

		for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
        ?>
		<th class="erg">
		<?php
    		if (is_object($this->grand_prix) && $this->grand_prix->col_header) {
                echo strftime("%b", mktime(0, 0, 0, $ii));
            } else {
                echo $ii;
            }
        ?>
		</th>
		<?php } ?>
		<th class="gesamt">Gesamt</th>
	</tr>
	</thead>

	<tbody>
<?php
    // alle Spieler durchgehen
    $p = 0;
    $gb = 0;
    foreach ($this->gesamtwertung as $row) {
        $p ++; // row count (entspricht Platzierung)
        
        $zeilenr = (($p % 2) != 0) ? '"zeile1"' : '"zeile2"';
        $style = '';
        ?>
		
	<tr class=<?php echo $zeilenr; ?>>
		<td class="rang">  <?php echo ($row->gesamt <> $gb) ? $p . '. ' : ''; ?>			</td>
		<td class="titel"> <?php echo $row->titel; ?> 	</td>
		<td class="name">  <?php echo $row->name; ?> 	</td>		
		<?php
        for ($ii = 1; $ii <= $this->anzahlTurniere; $ii ++) {
            $style = '';
            $ergebnis = '';
            if (isset($row->ergebnis[$ii]) && $row->ergebnis[$ii] != 0) {
                $ergebnis = $row->ergebnis[$ii];
                if ($ergebnis < 0) {
                    $ergebnis *= - 1;
                    $style = ' style=" background-color: yellow;"';
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
