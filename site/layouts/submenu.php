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

$state = $displayData['state'];

if ($state->get('grand_prix.rids')) :

	JHtml::_('bootstrap.framework');
	JHtml::_('script', 'components/com_clm/javascript/submenu.js');
	JHTML::_('stylesheet', 'com_clm_turnier/submenu.css', array (
			'relative' => true ));

	if ($state->get('grand_prix.rid')) {
		$key = array_search($state->get('grand_prix.rid'), array_column($displayData['ranglisten'], 'id'));
		echo '<div class="componentheading">';
		echo $displayData['ranglisten'][$key]->name . ' ' .
				JText::_('TOURNAMENT_RANKING');
		echo '</div>';
	}
	?>

<div class="clm-navigator">
	<ul>
		<li onmouseout="clm_height_del(this)"
			onmouseover="clm_height_set(this)">
	<?php
	$link = Grand_PrixHelperRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), $state->get('grand_prix.rids'));
	echo JHtml::_('link', JRoute::_($link), JText::_('TOURNAMENT_RANKING'));
	?>
		<ul>
	<?php
	foreach ($displayData['ranglisten'] as $rangliste) {
		$link = Grand_PrixHelperRoute::getGrandPrixRoute($state->get('grand_prix.id'), $state->get('grand_prix.catidEdition'), $state->get('grand_prix.tids'), $state->get('grand_prix.filter'), $state->get('grand_prix.rids'), $rangliste->id);

		echo '<li>';
		echo JHtml::_('link', JRoute::_($link), $rangliste->name);
		echo '</li>';
	}
	?>
		</ul>
		</li>
	</ul>
</div>

<?php endif; ?>
