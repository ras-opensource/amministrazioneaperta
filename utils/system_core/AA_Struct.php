<?php
class AA_Struct
{
    //Assessorato
    protected $nID_Assessorato = '0';
    protected $sAssessorato = 'Qualunque';

    //Direzione
    protected $nID_Direzione = '0';
    protected $sDirezione = 'Qualunque';

    //Servizio
    protected $nID_Servizio = '0';
    protected $sServizio = 'Qualunque';

    //Tipo di struttura
    protected $nTipo = -1;

    //Flag di validità
    protected $bIsValid = false;
    public function IsValid()
    {
        return $this->bIsValid;
    }

    //Albero della struttura
    protected $aTree = array();

    public function __construct()
    {

    }

    static public function GetStruct($id_assessorato = '', $id_direzione = '', $id_servizio = '', $type = '')
    {
        //AA_Log::Log(get_class() . "GetStruct($id_assessorato,$id_direzione,$id_servizio,$type)");

        $db = new AA_AccountsDatabase();
        $struct = new AA_Struct();

        if ($type != '') $struct->nTipo = $type;

        $now = Date("Y-m-d");

        //Servizio impostato
        if ($id_servizio != '' && $id_servizio > 0) 
        {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from servizi inner join direzioni on servizi.id_direzione = direzioni.id inner join assessorati on direzioni.id_assessorato=assessorati.id where servizi.id='$id_servizio'");
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                //Direzione
                $struct->nID_Direzione = $rs[0]["id_direzione"];
                $struct->sDirezione = $rs[0]["direzione"];

                //Servizio
                $struct->nID_Servizio = $rs[0]["id_servizio"];
                $struct->sServizio = $rs[0]["servizio"];

                $soppresso_servizio = 0;
                if ($rs[0]["data_soppressione_servizio"] < $now) $soppresso_servizio = 1;
                $soppresso = 0;
                if ($rs[0]["data_soppressione_direzione"] < $now) $soppresso = 1;

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array($rs[0]["id_direzione"] => array('descrizione' => $rs[0]["direzione"], 'data_soppressione' => $rs[0]["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array($rs[0]["id_servizio"] => array("descrizione" => $rs[0]["servizio"], "data_soppressione" => $rs[0]["data_soppressione_servizio"], "soppresso" => $soppresso_servizio)))));

                return $struct;
            }
        }

        //Direzione impostata
        if ($id_direzione != '' && $id_direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione from direzioni inner join assessorati on direzioni.id_assessorato=assessorati.id where direzioni.id='$id_direzione'";
            $db->Query($query);
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                //Direzione
                $struct->nID_Direzione = $rs[0]["id_direzione"];
                $struct->sDirezione = $rs[0]["direzione"];

                $soppresso = 0;
                if ($rs[0]["data_soppressione_direzione"] < $now) $soppresso = 1;

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array());
                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]]['direzioni'][$rs[0]["id_direzione"]] = array('descrizione' => $rs[0]["direzione"], "data_soppressione" => $rs[0]['data_soppressione_direzione'], "soppresso" => $soppresso, 'servizi' => array());

                return $struct;
            }
        }

        //Assessorato impostato
        if ($id_assessorato != '' && $id_assessorato > 0) 
        {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo from assessorati where assessorati.id='$id_assessorato'");
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array());

                return $struct;
            }
        }

        return $struct;
    }

    //Restituisce l'albero della struttura
    public function GetStructTree()
    {
        //if(!$this->bIsValid) return $this->aTree

        $now = Date("Y-m-d");

        //Servizio impostato
        if ($this->nID_Servizio != '' && $this->nID_Servizio > 0) {
            return $this->aTree;
        }

        $db = new AA_AccountsDatabase();

        //Direzione impostata
        if ($this->nID_Direzione != '' && $this->nID_Direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where direzioni.id='$this->nID_Direzione' order by assessorati.descrizione, direzioni.descrizione, servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore: " . $db->GetErrorMessage(), 100);
                return $this->aTree;
            }

            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                if ($curRow["id_direzione"] != "") {
                    
                        //AA_Log::Log(get_class()."->GetStructTree() ".print_r($this->aTree,TRUE),100,true,true);
                        $soppresso = 0;
                        if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                        if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curRow["id_direzione"]]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
                }
            }

            return $this->aTree;
        }

        //Assessorato impostato
        if ($this->nID_Assessorato != '' && $this->nID_Assessorato > 0) 
        {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where assessorati.id='$this->nID_Assessorato' order by assessorati.descrizione, direzioni.descrizione,servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            //AA_Log::Log(get_class()."->GetStructTree() - query: ".$query,100,true,true);

            $curDirezione = 0;
            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                if ($curRow["id_direzione"] != $curDirezione && $curRow["id_direzione"] != "") 
                {
                    $soppresso = 0;
                    if ($curRow["data_soppressione_direzione"]<= $now) $soppresso = 1;
                    $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curRow["id_direzione"]] = array('descrizione' => $curRow["direzione"], "data_soppressione" => $curRow["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array());
                    $curDirezione = $curRow["id_direzione"];
                }

                $soppresso = 0;
                if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curDirezione]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
            }

            return $this->aTree;
        }

        //Tutte le strutture del reame 'type'
        if ($this->nID_Assessorato == '' || $this->nID_Assessorato == 0) {
            if ($this->nTipo < 0) $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id order by assessorati.descrizione,direzioni.descrizione,servizi.descrizione";
            else $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where assessorati.tipo='" . $this->nTipo . "' order by assessorati.descrizione,direzioni.descrizione,servizi.descrizione";

            $curAssessorato = 0;
            $curDirezione = 0;
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            //AA_Log::Log(get_class()."->GetStructTree(nTipo: ".$this->nTipo.") - query: ".$query,100,true,true);

            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                
                if ($curAssessorato != $curRow["id_assessorato"]) {
                    $this->aTree['assessorati'][$curRow["id_assessorato"]] = array('descrizione' => $curRow["assessorato"], 'tipo' => $curRow["tipo"], 'direzioni' => array());
                    $curAssessorato = $curRow["id_assessorato"];
                }

                if ($curRow["id_direzione"] != $curDirezione && $curRow["id_direzione"] != "") {
                    $soppresso = 0;
                    if ($curRow["data_soppressione_direzione"] <= $now) $soppresso = 1;
                    $this->aTree['assessorati'][$curAssessorato]['direzioni'][$curRow["id_direzione"]] = array('descrizione' => $curRow["direzione"], "data_soppressione" => $curRow["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array());
                    $curDirezione = $curRow["id_direzione"];
                }

                $soppresso = 0;
                if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curAssessorato]['direzioni'][$curDirezione]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
            }
        }

        return $this->aTree;
    }

    //Restituisce l'id o la descrizione dell'assessorato
    public function GetAssessorato($getID = false)
    {
        if ($getID) return $this->nID_Assessorato;
        else return $this->sAssessorato;
    }

    //Restituisce il tipo di struttura
    public function GetTipo()
    {
        return $this->nTipo;
    }

    //Restituisce l'id o la descrizione della direzione
    public function GetDirezione($getID = false)
    {
        if ($getID) return $this->nID_Direzione;
        else return $this->sDirezione;
    }

    //Restituisce l'id o la descrizone del servizio
    public function GetServizio($getID = false)
    {
        if ($getID) return $this->nID_Servizio;
        else return $this->sServizio;
    }

    //Stampa la struttura in formato xml
    public function toXML()
    {
        AA_Log::Log(get_class() . "->toXML()");

        $this->aTree = $this->GetStructTree();

        $result = "<struttura tipo='" . $this->GetTipo() . "'>";
        foreach ($this->aTree['assessorati'] as $id_ass => $ass) {
            $result .= '<assessorato id="' . $id_ass . '" tipo="' . $ass['tipo'] . '"><descrizione>' . $ass['descrizione'] . "</descrizione>";
            foreach ($ass['direzioni'] as $id_dir => $dir) {
                $result .= '<direzione id="' . $id_dir . '"><descrizione>' . $dir['descrizione'] . "</descrizione>";
                foreach ($dir['servizi'] as $id_ser => $ser) {
                    $result .= '<servizio id="' . $id_ser . '">' . $ser['descrizione'] . "</servizio>";
                }
                $result .= '</direzione>';
            }
            $result .= '</assessorato>';
        }
        $result .= "</struttura>";

        return $result;
    }

    //Restituisce la struttura in formato JSON
    public function toJSON($bEncode = false)
    {
        AA_Log::Log(get_class() . "->toJSON()");

        if ($bEncode) return base64_encode(json_encode($this->toArray()));
        else return json_encode($this->toArray());
    }

    //Restituisce la struttura in formato JSON
    public function toArray($params = array())
    {
        //AA_Log::Log(get_class() . "->toArray()");

        $this->aTree = $this->GetStructTree();

        $root = "root";
        $assessorato_num = 1;
        $direzione_num = 1;
        $servizio_num = 1;
        $result = array(array("id" => $root, "value" => "Strutture", "open" => true,"ops"=>"","parent"=>0, "data" => array()));
        foreach ($this->aTree['assessorati'] as $id_ass => $ass) {
            if (sizeof($ass['direzioni']) > 0 && (!isset($params['hideDirs']) || $params['hideDirs'] !=1)) $curAssessorato = array("id" => $id_ass, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0,"parent"=>$root, "tipo" => $ass['tipo'], "value" => $ass['descrizione'], "soppresso" => 0, "data" => array());
            else $curAssessorato = array("id" => $id_ass, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0, "tipo" => $ass['tipo'],"parent"=>$root, "value" => $ass['descrizione'], "soppresso" => 0);

            //AA_Log::Log(__METHOD__." - curAssessorato: ".print_r($curAssessorato,true),100);

            if ((!isset($params['hideDirs']) || $params['hideDirs'] !=1)) {
                foreach ($ass['direzioni'] as $id_dir => $dir) {
                    //AA_Log::Log(get_class()."->toArray() - direzione: ".$dir['descrizione'],100);
                    if(empty($params['bHideSuppressed'])  || (empty($dir['soppresso']) && !empty($params['bHideSuppressed'])))
                    {
                        if (sizeof($dir['servizi']) > 0 && (!isset($params['hideServices']) || $params['hideServices'] !=1)) $curDirezione = array("id" => $id_ass . "." . $id_dir, "parent"=>$id_ass, "id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso'], "data" => array());
                        else $curDirezione = array("id" => $id_ass . "." . $id_dir, "parent"=>$id_ass,"id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso']);
                        if ((!isset($params['hideServices']) || $params['hideServices'] !=1)) 
                        {
                            foreach ($dir['servizi'] as $id_ser => $ser) {
                                if(empty($params['bHideSuppressed'])  || (empty($ser['soppresso']) && !empty($params['bHideSuppressed'])))
                                {
                                    $curDirezione['data'][] = array("id" => $id_ass . "." . $id_dir . "." . $id_ser, "parent"=>$id_ass . "." . $id_dir,"id_servizio" => $id_ser, "id_assessorato" => $id_ass, "id_direzione" => $id_dir, "data_soppressione" => $ser['data_soppressione'], "soppresso" => $ser['soppresso'], "value" => $ser['descrizione']);
                                    $servizio_num++;
                                }
                            }
                        }
                        $direzione_num++;
                        $curAssessorato['data'][] = $curDirezione;
                    }

                }
            }
            $assessorato_num++;
            $result[0]['data'][] = $curAssessorato;
        }

        //AA_Log::Log(__METHOD__." - ".print_r($result,true),100);
        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        AA_Log::Log(get_class() . "__toString()");

        return $this->toXML();
    }
}
