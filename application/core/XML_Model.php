<?php

/**
 * XML-persisted collection.
 *
 * ------------------------------------------------------------------------
 */
class XML_Model extends Memory_Model
{
//---------------------------------------------------------------------------
//  Housekeeping methods
//---------------------------------------------------------------------------

    /**
     * Constructor.
     * @param string $origin Filename of the XML file
     * @param string $keyfield  Name of the primary key field
     * @param string $entity    Entity name meaningful to the persistence
     */
    function __construct($origin = null, $keyfield = 'id', $entity = null)
    {
        parent::__construct();

        // guess at persistent name if not specified
        if ($origin == null)
            $this->_origin = get_class($this);
        else
            $this->_origin = $origin;

        // remember the other constructor fields
        $this->_keyfield = $keyfield;
        $this->_entity = $entity;

        // start with an empty collection
        $this->_data = array(); // an array of objects
        $this->fields = array(); // an array of strings
        // and populate the collection
        $this->load();
    }

    /**
     * Load the collection state appropriately, depending on persistence choice.
     * OVER-RIDE THIS METHOD in persistence choice implementations
     */
    protected function load()
    {
        //---------------------
        if (($xml = simplexml_load_file($this->_origin)) !== FALSE)
        {
            $record = new stdClass();
            $this->loadChildElements($xml, $record);
        }
        // --------------------
        // rebuild the keys table
        $this->reindex();
    }

    /**
     * A function to recursively go through all the child elements in an objects
     * created from XML.  If there are no child elements, it returns FALSE,
     * otherwise it returns TRUE.
     */
    private function loadChildElements($parent, &$item) {
        $hasChildren = FALSE;
        foreach($parent as $key => $value) {
            $hasChildren = TRUE;
            $record = new stdClass();
            if($this->loadChildElements($value, $record) === FALSE) {
                $item->$key = (string) $value;
            } else {
                $key = $record->{$this->_keyfield};
                $this->_data[$key] = $record;
            }
        }
        return $hasChildren;
    }
}
