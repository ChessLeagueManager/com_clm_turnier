<?php
/** 
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2016 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
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

if (count($this->rangliste) == 0) {
	echo CLMContent::clmWarning(JText::_('COM_CLM_TURNIER_RANGLISTE_NO'));
} else {
	// Menü / Layout Parameter
	$zps = $this->params->get('zps', '');
	$colVerein = $this->params->get('show_verein');
	$colDWZ = $this->params->get('col_dwz', 'DWZ');
	
	// Componentheading
	$date = getdate(strtotime($this->maxDateStart));
	echo CLMTurnierContent::componentheading($this->escape(strftime('%B %G', $date[0])));
	?>

<table <?php JHtml::_('thead.tableClass', ($config->fixth_ttab == "1")); ?> id="turnier_rangliste" cellpadding="0" cellspacing="0">

	<thead>
	<tr>
		<th class="rang">Nr.</th>
		<th class="titel">Titel</th>
		<th class="name">Name</th>
		<?php if ($colVerein) { ?>
		<th class="verein">Verein</th>
		<?php } ?>
		<th class="diff"></th>
		<th colspan=2 style="text-align:center"><?php echo $colDWZ; ?></th>
	</tr>
	</thead>

<?php
	
	// alle Spieler durchgehen
	$p = 0;
	foreach ($this->rangliste as $row) {
		if ($row->DWZ == 0)
			break;
		
		$p ++; // row count
		
		$zeilenr = (($p % 2) != 0) ? '"zeile1"' : '"zeile2"';
		$style = ($zps != '' && $zps == $row->zps) ? 'style="font-weight:bold"' : '';
		$diff = ($row->dateStart == $this->maxDateStart && $row->start_dwz != 0) ? ($row->DWZ - $row->start_dwz) : '';
		
		?>

	<tr class=<?php echo $zeilenr . ' ' . $style; ?>>
		<td class="rang">  <?php echo $p . '. '; ?>		</td>
		<td class="titel"> <?php echo $row->titel; ?> 	</td>
		<td class="name">  <?php echo $row->name; ?>	</td>
		<?php if ($colVerein) { ?>
		<td class="verein"> <?php echo $row->verein; ?>	</td>
		<?php } ?>
		<td class="diff"> <?php echo $diff; ?> 	</td>
		<td class="dwz"> <?php echo $row->DWZ . " "?> </td>
		<td class="i0">	<?php echo ' - ' . $row->I0 ?> </td>
	</tr>

<?php
	}
	
	echo '</table>';
}

// CLM-Container
echo '</div></div>';
echo '</div>';
