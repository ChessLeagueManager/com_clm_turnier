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

use Joomla\Utilities\ArrayHelper;

// Import CLM Classes
jimport('clm.index', JPATH_CLM_COMPONENT);

/**
 * Turnier List Controller
 */
class CLM_TurnierControllerTurniere extends JControllerAdmin {

    /**
     * Constructor.
     *
     * @param array $config
     *            An optional associative array of configuration settings.
     *            
     * @see JControllerLegacy
     */
    function __construct($config = array()) {
        parent::__construct($config);
        
        //
        $this->text_prefix = 'COM_CLM_TURNIER_UPDATE_DWZ';
        
        // Define standard task mappings.
        $this->registerTask('update_dwz', 'update_dwz');
        $this->registerTask('dwz_publish', 'publish');
        $this->registerTask('dwz_unpublish', 'publish');
    }
    
    /**
     * 
	 * @return  void
     */
    public function update_dwz() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        
        $cid = $this->input->get('cid');
        if (count($cid)) {
            $this->setRedirect(JRoute::_('index.php?option=com_clm_turnier&view=update_dwz&id=' . $cid[0], false));
        } else {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
    }

    /**
	 * Method to publish a list of items
	 *
	 * @return  void
     */
    public function publish() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        
        // Get items to publish from the request.
        $cid = $this->input->get('cid', array(), 'array');
        $data = array(
            'publish' => 1,
            'unpublish' => 0,
            'dwz_publish' => 1,
            'dwz_unpublish' => 0
        );
        $task = $this->getTask();
        $value = ArrayHelper::getValue($data, $task, 0, 'int');
        $function = preg_replace(array(
            '/un/',
            '/_p/'
        ), array(
            '',
            'P'
        ), $task);
        $text_prefix = $this->text_prefix;
        if (preg_match('/^dwz_/', $task) === 1) {
            $text_prefix = $text_prefix . '_DWZ';
        }
        
        if (empty($cid)) {
            JLog::add(JText::_($text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        } else {
            // Get the table CLM_TurnierModelTurniere
            $model = $this->getModel('Turniere', 'CLM_TurnierModel');
            
            // Make sure the item ids are integers
            $cid = ArrayHelper::toInteger($cid);
            
            // Publish the items.
            try {
                $model->$function($cid, $value);
                $ntext = null;
                if ($value == 1) {
                    if ($errors) {
                        JFactory::getApplication()->enqueueMessage(JText::plural($text_prefix . '_N_ITEMS_FAILED_PUBLISHING', count($cid)), 'error');
                    } else {
                        $ntext = $text_prefix . '_N_ITEMS_PUBLISHED';
                    }
                } else {
                    $ntext = $text_prefix . '_N_ITEMS_UNPUBLISHED';
                }
                
                if ($ntext !== null) {
                    $this->setMessage(JText::plural($ntext, count($cid)));
                }
            } catch (Exception $e) {
                $this->setMessage($e->getMessage(), 'error');
            }
        }
        
        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }
}

?>
