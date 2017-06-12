<?php
/** 
 * @copyright (C) 2016 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$n = count($this->playerlist);
?>

<div id="clm">
	<div class="clm">
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<!-- TODO: entfernt alignments -->
			<table class="table table-striped" id="itemList">
				<thead>
					<tr>
						<th><?php echo $n;?></th>
						<th>
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
						<th><?php echo JText::_('PLAYER_TITLE'); ?></th>
						<th><?php echo JText::_('PLAYER_NAME'); ?></th>
						<th><?php echo JText::_('CLUB'); ?></th>
						<th colspan=2 align="center"><?php echo JText::_('TWZ'); ?></th>
						<th colspan=2 align="center"><?php echo JText::_('COM_CLM_TURNIER_UPDATE_DWZ_TWZ_NEU'); ?></th>
						<th align="center">ID</th>
					</tr>
				</thead>
				<tbody>
		<?php
		$p = 0;
		foreach ($this->playerlist as $i => $value) {
			$row = &$value;
			$checked = JHtml::_('grid.checkedout', $row, $i);
			
			$p ++; // row count
			$zeilenr = (($p % 2) != 0) ? 'row0' : 'row1'?>
			<tr class="<?php echo $zeilenr; ?>">
						<td align="center"> <?php echo $p; ?>			</td>
						<td align="center"> <?php echo $checked; ?> 	</td>
						<td align="center"> <?php echo $row->titel; ?>	</td>
						<td align="left"> 	<?php echo $row->name; ?>	</td>
						<td align="left"> <?php echo $row->verein; ?></td>
						<td align="right"> <?php echo $row->start_dwz; ?></td>
						<td align="left"> <?php echo ' - ' . $row->start_I0; ?></td>
						<td align="right"><input class="inputbox" type="text"
							name="<?php echo 'new_dwz[' . $row->id .']';?>"
							id="<?php echo 'new_dwz[' . $row->id . ']';?>" maxlength="4"
							style='width: 4em' value="<?php echo $row->new_dwz; ?>" /></td>
						<td align="left">- <input class="inputbox" type="text"
							name="<?php echo 'new_I0[' . $row->id .']';?>"
							id="<?php echo 'new_I0[' . $row->id . ']';?>" maxlength="3"
							style='width: 3em' value="<?php echo $row->new_I0; ?>" /></td>
						<td align="center"><?php echo $row->id; ?></td>
					</tr>
			<?php
		}
		?>
		</tbody>
			</table>

			<input type="hidden" name="option" value="com_clm_turnier" /> <input
				type="hidden" name="view" value="update_dwz" /> <input type="hidden"
				name="task" value="" /> <input type="hidden" name="controller"
				value="update_dwz" /> <input type="hidden" name="id"
				value="<?php echo $this->turnier; ?>" />
	
	<?php echo JHtml::_( 'form.token' ); ?>
	
</form>

	</div>
</div>