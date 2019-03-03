<?php
/**
 * Chess League Manager Turnier Erweiterungen 
 *  
 * @copyright (C) 2017 Andreas Hrubesch; All rights reserved
 * @license GNU General Public License; see https://www.gnu.org/licenses/gpl.html
 * @author Andreas Hrubesch
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

/**
 * Script file of CLM Turnier component.
 *
 * The name of this class is dependent on the component being installed.
 * The class name should have the component's name, directly followed by
 * the text InstallerScript (ex:. com_helloWorldInstallerScript).
 *
 * This class will be called by Joomla!'s installer, if specified in your component's
 * manifest file, and is used for custom automation actions in its installation process.
 *
 * In order to use this automation script, you should reference it in your component's
 * manifest file as follows:
 * <scriptfile>install.php</scriptfile>
 *
 * @copyright Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
class com_clm_turnierInstallerScript
{

    // the version we are updating from
    protected $fromVersion = null;

    /**
     * This method is executed before any Joomla uninstall action, such as file
     * removal or database changes.
     *
     * @param \stdClass $parent
     *            - Parent object calling this method.
     *            
     * @return void
     */
    public function uninstall($parent)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>:';

        // TODO: DB fehler (sql-mode)
        $removeTables = false;
        if (file_exists(JPATH_SITE . '/components/com_clm/clm/index.php')) {
            require_once JPATH_SITE . '/components/com_clm/clm/index.php';
            $removeTables = ! clm_core::$db->config()->database_safe;
        } else {
            try {
                $db = JFactory::getDBO();
                $columns = $db->getTableColumns('#__clm_config');
            } catch (RuntimeException $r) {
                $removeTables = true;
            }
        }

        if ($removeTables == true) {
            $element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/uninstall.sql</file></sql>');
            $result = $parent->getParent()->parseSQLFiles($element);

            $this->enqueueMessage(JText::_('COM_CLM_TURNIER_DELETE_TABLES'), 'notice');
        }
    }

    /**
     * This method is called after a component is updated.
     *
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function update($parent)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>:';

        // remove depricated language files
        $this->deleteUnexistingFiles();
    }

    /**
     * Runs just before any installation action is preformed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param string $type
     *            - Type of PreFlight action. Possible values are:
     *            - * install
     *            - * update
     *            - * discover_install
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function preflight($type, $parent)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>:';

        // Chess League Manager installiert ?
        if (! JComponentHelper::isInstalled('com_clm')) {
            $this->enqueueMessage(JText::_('COM_CLM_TURNIER_REQ_COM_CLM'), 'warning');
        }

        if ($type === 'update') {
            if (! empty($parent->extension->manifest_cache)) {
                $manifestValues = json_decode($parent->extension->manifest_cache, true);
                if ((array_key_exists('version', $manifestValues))) {
                    $this->fromVersion = $manifestValues['version'];
                }
            }
        }
    }

    /**
     * Runs right after any installation action is preformed on the component.
     *
     * @param string $type
     *            - Type of PostFlight action. Possible values are:
     *            - * install
     *            - * update
     *            - * discover_install
     * @param \stdClass $parent
     *            - Parent object calling object.
     *            
     * @return void
     */
    public function postflight($type, $parent)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>:';

        $notice = array();

        // Beispieldaten importieren
        $query = 'SELECT id FROM #__clm_turniere_grand_prix';
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->execute();
        $num_rows = $db->getNumRows();
        if ($num_rows == 0) {
            $element = new SimpleXMLElement('<sql><file driver="mysql" charset="utf8">sql/samples.sql</file></sql>');
            $result = $parent->getParent()->parseSQLFiles($element);

            $notice[] = JText::_('COM_CLM_TURNIER_INSTALL_SAMPLES');
        }

        $this->loadConfigXml($parent);

        if (! empty($notice)) {
            $this->enqueueMessage(implode("\n", $notice), 'notice');
        }
    }

    /**
     * Enqueue a system message.
     *
     * @param string $msg
     *            The message to enqueue.
     * @param string $type
     *            The message type. Default is message.
     *            
     * @return void
     */
    private function enqueueMessage($msg, $type = 'message')
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>:';

        // Don't add empty messages.
        if (! strlen(trim($msg))) {
            return;
        }

        // Enqueue the message.
        JFactory::getApplication()->enqueueMessage('<h3>' . JText::_('COM_CLM_TURNIER_DESC') . '</h3>' . '<pre style="line-height: 1.6em;">' . $msg . '</pre>', $type);
    }

    /**
     * Delete files that should not exist
     *
     * @return void
     *
     * @see joomla-cms/administrator/components/com_admin/script.php
     */
    private function deleteUnexistingFiles()
    {
        $files = array(
            // Release 2.0
            '/administrator/language/de-DE/de-DE.com_clm_turnier.sys.ini',
            '/administrator/language/en-GB/en-GB.com_clm_turnier.sys.ini'
        );

        $folders = array();

        jimport('joomla.filesystem.file');
        foreach ($files as $file) {
            if (JFile::exists(JPATH_ROOT . $file) && ! JFile::delete(JPATH_ROOT . $file)) {
                $this->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file), 'warning');
            }
        }

        jimport('joomla.filesystem.folder');
        foreach ($folders as $folder) {
            if (JFolder::exists(JPATH_ROOT . $folder) && ! JFolder::delete(JPATH_ROOT . $folder)) {
                $this->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder), 'warning');
            }
        }
    }

    /**
     * Method to load and merge the ''config.xml'' file with the extens params.
     *
     * @param \stdClass $parent
     *            - Parent object calling object.
     * @return void
     */
    private function loadConfigXml($parent)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '</b>';

        $file = $parent->getParent()->getPath('extension_administrator') . '/config.xml';
        $defaults = $this->parseConfigFile($file);
        if ($defaults === '{}') {
            return;
        }

        $manifest = $parent->getParent()->getManifest();
        $type = $manifest->attributes()->type;

        try {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true)
                ->select($db->quoteName(array(
                'extension_id',
                'params'
            )))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                ->where($db->quoteName('element') . ' = ' . $db->quote($parent->getElement()))
                ->where($db->quoteName('name') . ' = ' . $db->quote($parent->getName()));
            $db->setQuery($query);
            $row = $db->loadObject();
            if (! isset($row)) {
                $this->enqueueMessage(JText::_('COM_CLM_TURNIER_ERROR_CONFIG_LOAD'), 'warning');
                return;
            }

            $params = json_decode($row->params, true);
            if (json_last_error() == JSON_ERROR_NONE && is_array($params)) {
                $result = array_merge(json_decode($defaults, true), $params);
                $defaults = json_encode($result);
            }
        } catch (Exception $e) {
            $this->enqueueMessage($e->getMessage(), 'warning');
            return;
        }

        try {
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__extensions'));
            $query->set($db->quoteName('params') . ' = ' . $db->quote($defaults));
            $query->where($db->quoteName('extension_id') . ' = ' . $db->quote($row->extension_id));

            $db->setQuery($query);
            $db->execute();
        } catch (Exception $e) {
            $this->enqueueMessage($e->getMessage(), 'warning');
        }
    }

    /**
     * Method to parse the ''config.xml'' of an extension, build the JSON
     * string for its default parameters, and return the JSON string.
     *
     * @param string $file
     *
     * @return string JSON string of parameter values
     *        
     * @note This method must always return a JSON compliant string
     * @see joomla-cms/libraries/cms/installer/installer.php
     */
    private function parseConfigFile($file)
    {
        echo '<br><b>' . __FILE__ . ' ' . __FUNCTION__ . '(' . $file . ')</b>';
        if (! file_exists($file)) {
            return '{}';
        }

        $xml = simplexml_load_file($file);
        if (! ($xml instanceof SimpleXMLElement)) {
            return '{}';
        }

        if (! isset($xml->fieldset)) {
            return '{}';
        }

        // Getting the fieldset tags
        $fieldsets = $xml->fieldset;

        // Creating the data collection variable:
        $ini = array();

        // Iterating through the fieldsets:
        foreach ($fieldsets as $fieldset) {
            if (! count($fieldset->children())) {
                // Either the tag does not exist or has no children therefore we return zero files processed.
                return '{}';
            }

            // Iterating through the fields and collecting the name/default values:
            foreach ($fieldset as $field) {
                // Check against the null value since otherwise default values like "0"
                // cause entire parameters to be skipped.

                if (($name = $field->attributes()->name) === null) {
                    continue;
                }

                if (($value = $field->attributes()->default) === null) {
                    continue;
                }

                $ini[(string) $name] = (string) $value;
            }
        }

        return json_encode($ini);
    }
}