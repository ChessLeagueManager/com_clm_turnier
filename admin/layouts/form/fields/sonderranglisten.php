<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2019 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 *
 * @var   array    $data            Selection List Data
 */
extract($displayData);

// TODO: implement "readonly"
if (! $readonly) {
	JHtml::_('script', 'com_clm_turnier/sonderranglisten.js', array('version' => 'auto', 'relative' => true));
}

$uri = new JUri('index.php?option=com_clm_turnier&view=sonderranglisten&layout=modal&tmpl=component&ismoo=0');
$uri->setVar('field', $this->escape($id));


// TODO: Attribute füllen / definieren
$attribs = array();
$attribs['id'] = $id;
$attribs['name'] = $name;
$attribs['multiple'] = '';
$attribs['class'] = 'field-sonderranglisten-input-name';
?>

<div class="field-sonderranglisten-wrapper"
	data-url="<?php echo (string)$uri; ?>"
	data-modal=".modal"
	data-modal-width="100%"
	data-modal-height="400px"
	data-input=".field-sonderranglisten-input"
	data-input-name="<?php echo '.' . $attribs['class']?>"
	data-button-select=".button-select"
	data-button-save-selected=".button-save-selected"
	>
	<div class="input-append">
		<?php echo JHTML::_('select.genericlist', $data, $name, $attribs, 'id', 'name', $value); ?>

		<a class="btn button-select" title="<?php echo JText::_('JSELECT'); ?>">
			<?php echo JText::_('JSELECT'); ?>
		</a>
		<a class="btn hasTooltip button-clear" 
			title="<?php echo JText::_('JCLEAR'); ?>" 
			>
			<span class="icon-remove" aria-hidden="true"></span>
		</a>

		<?php echo JHtml::_(
			'bootstrap.renderModal',
			'sonderranglistenModal_' . $id,
			array(
				'title'  => JText::_('COM_CLM_TURNIER_FORM_FIELD_SONDERRANGLISTEN_DIALOG'),
				'closeButton' => true,
					'footer' => '<a type="button" class="btn button-save-selected" data-dismiss="modal">' . JText::_('JGLOBAL_FIELD_ADD') . '</a>' 
					. '<a type="button" class="btn" data-dismiss="modal">' . JText::_('JCANCEL') . '</a>'
			)
		); ?>
	</div>
</div>
