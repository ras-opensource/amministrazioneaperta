<?php
class AA_JSON_Template_Generic
{
    //Restituisce la reppresentazione dell'oggetto come una una stringa
    public function __toString()
    {
        //Restituisce l'oggetto come stringa
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toBase64()
    {
        return base64_encode($this->__toString());
    }

    public function toArray()
    {
        $result = array();

        //Gestori eventi
        if (sizeof($this->aEventHandlers) > 0) {
            $result["on"] = array();
            $result['eventHandlers'] = $this->aEventHandlers;
            foreach ($this->aEventHandlers as $event => $curHandler) {
                $result["on"][$event] = "AA_MainApp.utils.getEventHandler('" . $curHandler['handler'] . "','" . $curHandler['module_id'] . "')";
            }
        }

        //Infopopup
        if ((isset($this->props['label']) || isset($this->props['bottomLabel'])) && isset($this->props['infoPopup']) && is_array($this->props['infoPopup'])) {
            $script = "AA_MainApp.utils.callHandler(\"dlg\", {task:\"infoPopup\", params: [{id: \"" . $this->props['infoPopup']['id'] . "\"}]},\"" . $this->props['infoPopup']['id_module'] . "\")";
            if (isset($this->props['bottomLabel']))
                $this->props['bottomLabel'] .= "<a href='#' onclick='" . $script . "'><span class='mdi mdi-help-circle'></span></a>";
            else
                $this->props['label'] .= "&nbsp;<a href='#' onclick='" . $script . "' title='fai click per ricevere ulteriori informazioni.'><span class='mdi mdi-help-circle'></span></a>";
        }

        //Proprietà
        foreach ($this->props as $key => $prop) {
            if ($prop instanceof AA_JSON_Template_Generic)
                $result[$key] = $prop->toArray();
            else
                $result[$key] = $prop;
        }

        //rows
        if (is_array($this->rows)) {
            $result['rows'] = array();
            foreach ($this->rows as $curRow) {
                $result['rows'][] = $curRow->toArray();
            }
        }

        //cols
        if (is_array($this->cols)) {
            $result['cols'] = array();
            foreach ($this->cols as $curCol) {
                $result['cols'][] = $curCol->toArray();
            }
        }
        if (isset($result['view']) && $result['view'] == "layout" && isset($result['rows']) && !is_array($result['rows']) && isset($result['cols']) && !is_array($result['cols']))
            $result['rows'] = array(array("view" => "spacer"));

        //cells
        if (is_array($this->cells)) {
            $result['cells'] = array();
            foreach ($this->cells as $curCell) {
                $result['cells'][] = $curCell->toArray();
            }
        }
        if (isset($result['view']) && $result['view'] == "multiview" && isset($result['cells']) && !is_array($result['cells']))
            $result['cells'] = array(array("view" => "spacer"));

        //elements
        if (is_array($this->elements)) {
            $result['elements'] = array();
            foreach ($this->elements as $curCell) {
                $result['elements'][] = $curCell->toArray();
            }
        }
        if (isset($result['view']) && $result['view'] == "toolbar" && isset($result['elements']) && !is_array($result['elements']))
            $result['elements'] = array(array("view" => "spacer"));

        //bodyRows
        if (is_array($this->bodyRows) || is_array($this->bodyCols)) {
            $result['body'] = array();
            if (is_array($this->bodyRows)) {
                foreach ($this->bodyRows as $curBodyRow) {
                    if (!is_array($result['body']['rows']))
                        $result['body']['rows'] = array();
                    $result['body']['rows'][] = $curBodyRow->toArray();
                }
            }

            if (is_array($this->bodyCols)) {
                foreach ($this->bodyCols as $curBodyCol) {
                    if (!is_array($result['body']['cols']))
                        $result['body']['cols'] = array();
                    $result['body']['cols'][] = $curBodyCol->toArray();
                }
            }
        }

        //Restituisce l'oggetto come array
        return $result;
    }

    protected $props = array();
    public function SetProp($prop = "", $value = "")
    {
        $this->props[$prop] = $value;
    }
    public function GetProp($prop)
    {
        if (isset($this->props[$prop]))
            return $this->props[$prop];
        else
            return "";
    }

    //Aggiunta righe
    protected $rows = null;
    public function addRow($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->rows))
                $this->rows = array();
            $this->rows[] = $row;
        }
    }

    public function GetRowsCount()
    { 
        if(is_array($this->rows)) return sizeof($this->rows);
        else return 0;
    }

    //Aggiunta row al body
    protected $bodyRows = null;
    public function addRowToBody($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyRows))
                $this->bodyRows = array();
            $this->bodyRows[] = $row;
        }
    }

    public function GetBodyRowsCount()
    { 
        if(is_array($this->bodyRows)) return sizeof($this->bodyRows);
        else return 0;
    }

    //Aggiunta col al body
    protected $bodyCols = null;
    public function addColToBody($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyCols))
                $this->bodyCols = array();
            $this->bodyCols[] = $col;
        }
    }

    public function GetBodyColsCount()
    { 
        if(is_array($this->bodyCols)) return sizeof($this->bodyCols);
        else return 0;
    }

    //Aggiunta colonne
    protected $cols = null;
    public function addCol($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cols))
                $this->cols = array();
            $this->cols[] = $col;
        }
    }

    public function GetColsCount()
    { 
        if(is_array($this->cols)) return sizeof($this->cols);
        else return 0;
    }

    //gestori degli eventi
    protected $aEventHandlers = array();
    public function AddEventHandler($event = "", $handler = "", $handlerParams = null, $module_id = "")
    {
        try {
            if ($event != "" && $handler != "")
                $this->aEventHandlers[$event] = array("handler" => $handler, "params" => $handlerParams, "module_id" => $module_id);
        } catch (Exception $e) {
            AA_Log::Log(__METHOD__ . " - " . $e->getMessage(), 100);
        }
    }
    public function DelEventHandler($event = "")
    {
        if ($event != "" && isset($aEventHandlers[$event]))
            unset($aEventHandlers[$event]);
    }

    //Aggiunta celle
    protected $cells = null;
    public function addCell($cell = null, $bFromHead = false)
    {
        if ($cell instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cells))
                $this->cells = array();
            if (!$bFromHead)
                $this->cells[] = $cell;
            else
                array_unshift($this->cells, $cell);
        }
    }

    //Aggiunta elementi
    protected $elements = null;
    public function addElement($obj = null)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->elements))
                $this->elements = array();
            $this->elements[] = $obj;
        }
    }
    public function __construct($id = "", $props = null)
    {
        if ($id != "")
            $this->props["id"] = $id;
        else
            $this->props["id"] = "AA_JSON_TEMPLATE_GENERIC_" . uniqid(time());

        if (is_array($props)) {
            foreach ($props as $key => $value) {
                if ($key != "eventHandlers")
                    $this->props[$key] = $value;
            }

            if (isset($props['fixedRowHeight']) && !$props['fixedRowHeight']) {
                if (!isset($props['eventHandlers'])) {
                    $props['eventHandlers'] = array("onresize" => array("handler" => "adjustRowHeight", "module_id" => ""));
                } else {
                    if (!isset($props['eventHandlers']['onresize'])) {
                        $props['eventHandlers']['onresize'] = array("handler" => "adjustRowHeight", "module_id" => "");
                    }
                }
            }
        }

        if (isset($props['eventHandlers']) && is_array($props['eventHandlers'])) {
            $this->aEventHandlers = $props['eventHandlers'];
        }
    }

    public function GetId()
    {
        return $this->props['id'];
    }
}
