<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var string $autocomplete Autocomplete attribute for the field.
 * @var boolean $autofocus Is autofocus enabled?
 * @var string $class Classes for the input.
 * @var string $description Description of the field.
 * @var boolean $disabled Is this field disabled?
 * @var string $group Group the field belongs to. <fields> section in form XML.
 * @var boolean $hidden Is this field hidden in the form?
 * @var string $hint Placeholder for the field.
 * @var string $id DOM id of the field.
 * @var string $label Label of the field.
 * @var string $labelclass Classes to apply to the label.
 * @var boolean $multiple Does this field support multiple values?
 * @var string $name Name of the input field.
 * @var string $onchange Onchange attribute for the field.
 * @var string $onclick Onclick attribute for the field.
 * @var string $pattern Pattern (Reg Ex) of value of the form field.
 * @var boolean $readonly Is this field read only?
 * @var boolean $repeat Allows extensions to duplicate elements.
 * @var boolean $required Is this field required?
 * @var integer $size Size attribute of the input.
 * @var boolean $spellcheck Spellcheck state for the form field.
 * @var string $validate Validation rules to apply.
 * @var string $value Value attribute of the field.
 * @var array $options Options available for this field.
 * @var string $dataAttribute Miscellaneous data attributes preprocessed for HTML output
 * @var array $dataAttributes Miscellaneous data attribute for eg, data-*
 *     
 * @var array $data Selection List Data
 * @var string $saison
 */

$html = array ();
$attr = '';

// Initialize the field attributes.
$attr .= ! empty($size) ? ' size="' . $size . '"' : '';
$attr .= $multiple ? ' multiple' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= $dataAttribute;

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled) {
	$attr .= ' disabled="disabled"';
}

$attr2 = '';
$attr2 .= ! empty($class) ? ' class="' . $class . '"' : '';
$attr2 .= ' placeholder="' .
		$this->escape($hint ?: Text::_('JGLOBAL_TYPE_OR_SELECT_SOME_OPTIONS')) . '" ';
$attr2 .= 'id="' . $id . '" ';

if ($required) {
	$attr .= ' required class="required"';
	$attr2 .= ' required';
}

// Create a read-only list (no name) with hidden input(s) to store the value(s).
if ($readonly) {
	$html[] = HTMLHelper::_('select.genericlist', $data, '', trim($attr), 'id', 'name', $value);

	// E.g. form field type tag sends $this->value as array
	if ($multiple && is_array($value)) {
		if (! count($value)) {
			$value[] = '';
		}

		foreach ($value as $val) {
			$html[] = '<input type="hidden" name="' . $name . '" value="' .
					htmlspecialchars($val, ENT_COMPAT, 'UTF-8') . '">';
		}
	} else {
		$html[] = '<input type="hidden" name="' . $name . '" value="' .
				htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '">';
	}
} else {
// 	JHtml::_('script', 'com_clm_turnier/sonderranglisten.4.js', array('version' => 'auto', 'relative' => true));
	
	// Create a regular list.
	$html[] = HTMLHelper::_('select.genericlist', $data, $name, trim($attr), 'id', 'dname', $value);

// 	$html[] = '<div class="input-group">';
// 	$html[] = '<button type="button" class="btn btn-primary" data-bs-target="#sonderranglistenTypeModal" data-bs-toggle="modal"><span class="icon-list icon-white" aria-hidden="true"></span> Auswählen</button>';
// 	$html[] = '<button class="btn btn-danger" title="Löschen"><span class="icon-remove" aria-hidden="true"></span></button>';
// 	$html[] = '</div>';
	
// 	$link = Route::_('index.php?option=com_clm_turnier&view=sonderranglisten&layout=modal&tmpl=component&ismoo=0&field=' . $this->escape($id));
	
// 	$html[] = HTMLHelper::_(
// 			'bootstrap.renderModal',
// 			'sonderranglistenTypeModal',
// 			array (
// 				'url' => $link,
// 				'title' => Text::_('COM_CLM_TURNIER_FORM_FIELD_SONDERRANGLISTEN_DIALOG') 
// 					. ' (' . JText::_('SAISON') . ': ' . $this->escape($saison) . ')',
// 				'width' => '800px',
// 				'height' => '300px', 
// 				'modalWidth' => 80,
// 				'bodyHeight' => 70,
// 				'footer' => '<button type="button" class="btn btn-success button-save-selected" data-bs-dismiss="modal">' . Text::_('JSELECT') . '</button>'
// 					. '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . Text::_('JCANCEL') . '</button>' 
// 				)
// 			);
}

Text::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
Text::script('JGLOBAL_SELECT_PRESS_TO_SELECT');

Factory::getApplication()->getDocument()->getWebAssetManager()->usePreset('choicesjs')->useScript('webcomponent.field-fancy-select');

?>

<joomla-field-fancy-select <?php echo $attr2; ?>><?php	echo implode($html); ?></joomla-field-fancy-select>
