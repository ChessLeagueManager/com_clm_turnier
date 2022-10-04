<?php 
/**
 * Chess League Manager Turnier Erweiterungen
 *
 * @copyright (C) 2017 Andreas Hrubesch
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('grandprix.tooltip');

$user = JFactory::getUser();
$userId = $user->get('id');
$canChange = clm_core::$access->access('BE_tournament_create');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.id';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_clm_turnier&task=turniere.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'turniere_list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$n = count($this->items);
?>

<form 
	action="<?php echo JRoute::_('index.php?option=com_clm_turnier&view=turniere');?>"
	method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
		<table class="table table-striped" id="turnier_list">
			<thead>
				<tr>
					<th class="center" width="10"><?php echo $n;?></th>
					<th class="center" width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('searchtools.sort', 'TOURNAMENT_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th class="center" width="10">
						<?php echo JHtml::_('searchtools.sort', 'SEASON', 'a.sid', $listDirn, $listOrder);?>
					</th>
					<th class="center" width="10"><?php echo JText::_('TERMINE_DATUM');?></th>
					<th class="title">
						<?php echo JHtml::_('searchtools.sort', 'MODUS', 'a.typ', $listDirn, $listOrder); ?>
					</th>
					<th class="center"><?php echo JText::_('CATEGORY_NAME');?>
					</th>
					<th class="center" width="10"><?php echo JText::_('RATING');?></th>
					<th class="center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th class="center" nowrap="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>				
			</thead>
			<tfoot>
				<tr>
					<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
			<?php
                $k = 0;
                foreach ($this->items as $i => $row) {
                    $k++;
            ?>
            	<tr>
					<td class="center"><?php echo $k; ?></td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $row->id); ?>
					</td>
					<td class="title"><?php echo $row->name;?></td>
					<td class="center"><?php echo $row->sname;?></td>
					<td class="center"><?php echo JHtml::_('turnier.getDatum', $row->dateStart, $row->dateEnd);?></td>
					<td class="title"><?php echo JHtml::_('turnier.getTurnierModus', $row->typ);?></td>
					<td class="center"><?php echo $row->cname;?></td>
					<td class="center"><?php echo JHtml::_('turnier.getInofDWZ', $row->params, $i, 'tick.png', 'publish_x.png', 'turniere.' )?></td>
					<td class="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'turniere.', $canChange); ?></td>
					<td class="center"><?php echo $row->id; ?></td>
            	</tr>
            <?php } ?>
			</tbody>			
		</table>
		<?php endif; ?>
					
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" /> 
		<?php echo JHtml::_( 'form.token' ); ?>
	
	</div>	<!-- j-main-container -->	
</form>