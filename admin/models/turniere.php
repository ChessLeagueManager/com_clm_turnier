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

/**
 * Grand Prix List Model
 */
class CLM_TurnierModelTurniere extends JModelList {

    /**
     * Constructor.
     *
     * @param array $config
     *            An optional associative array of configuration settings.
     *            
     * @see JControllerLegacy
     */
    function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'a.id',
                'published',
                'a.published',
                'name',
                'a.name',
                'sid',
                'a.sid',
                'typ',
                'a.typ'
            );
        }
        
        parent::__construct($config);
        
        // aktive Saison als default
        // TODO: ausgewählten Eintrag markieren
        $db	= JFactory::getDBO();
        $query = ' SELECT id FROM #__clm_saison
					WHERE published = 1 AND archiv = 0
					ORDER BY name DESC LIMIT 1;' ;
        $db->setQuery($query);
        $sid = $db->loadObject()->id;
        $this->setState('filter.sid', $sid);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return JDatabaseQuery
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        $query->select('a.*');
        $query->from($this->_db->quoteName('#__clm_turniere', 'a'));
        
        // Join the saison
        $query->select($db->quoteName('s.name', 'sname'))
            ->join('LEFT', $db->quoteName('#__clm_saison', 's') . ' ON ' . $db->quoteName('s.id') . ' = ' . $db->quoteName('a.sid'));
        
        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where($db->quoteName('a.published') . ' = ' . (int) $published);
        }
        
        // Filter by sid state
        $sid = $this->getState('filter.sid');
        if (is_numeric($sid)) {
            $query->where($db->quoteName('a.sid') . ' = ' . (int) $sid);
        }
        
        // Filter by type state
        $typ = $this->getState('filter.typ');
        if (is_numeric($typ)) {
            $query->where($db->quoteName('a.typ') . ' = ' . (int) $typ);
        }
        
        // Filter by search in name
        // TODO: SQL injection ? Auch bei Grand Prix !?
        $search = $this->getState('filter.search');
        if (! empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where($db->quoteName('a.name') . ' LIKE ' . $search);
            }
        }
        
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.name');
        $orderDirn = $this->state->get('list.direction', 'asc');
        
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
        
        // var_dump($query->dump());
        return $query;
    }

}
