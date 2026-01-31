<?php
class AA_GalleryDlg extends AA_GenericWindowTemplate
{
    protected $target="";
    public function GetTarget(){return $this->target;}
    public function SetTarget($target=''){$this->target=$target;}

    protected $list=null;

    public function __construct($id='',$title="Galleria immagini",$target="",$user=null)
    {
        parent::__construct($id,$title);

        $modify=false;
        if(!($user instanceof AA_User)) $user=AA_User::GetCurrentUser();
        if($user->IsSuperUser()) $modify=true;

        $id_list="AA_SystemGallery_".uniqid();

        $itemTemplate="<div style='display:flex; flex-direction:column; justify-content: end; align-items: center; height: 100%; font-size: smaller'><div style='display:flex; flex-direction:column; justify-content: center; align-items: center; height: 150px; width:150px; background-image: url(#img_url#); background-size: cover;background-repeat: no-repeat; background-position: center'>&nbsp;</div><div style='text-align: center;'>#url#</div>";
        $itemTemplate.="<div style='width: 90%; display: flex; justify-content: space-evenly; align-items: center'><a class='AA_DataTable_Ops_Button' title='Scarica' onclick='window.open(\"#img_url#\",\"_blank\");'><span class='mdi mdi-download'></span></a>";
        $itemTemplate.="<a class='AA_DataTable_Ops_Button' title='Copia negli appunti' onclick='navigator.clipboard.writeText(\"#img_url#\");'><span class='mdi mdi-content-copy'></span></a>";
        if($modify)
        {
            $action="AA_MainApp.utils.callHandler('dlg', {task: 'GetGalleryTrashDlg', postParams: {id:'#id#',refresh: 1, refresh_obj_id:'".$id_list."'}, taskManager: AA_MainApp.taskManager, module: '" . $this->module. "'},'".$this->module."')";
            $itemTemplate.="<a class='AA_DataTable_Ops_Button AA_DataTable_Ops_Button_Red' title='Elimina' onclick=\"".$action."\";'><span class='mdi mdi-trash-can'></span></a></div></div>";
        }
        else $itemTemplate.="</div></div>";

        $this->list=new AA_JSON_Template_Generic($id_list,array(
            "view" => "dataview",
            "filtered"=>true,
            "xCount"=>6,
            "type" =>
            array(
                    "type" => "tiles",
                    "height" => "auto",
                    "width" => "auto",
                    "css" => "AA_DataView_item"
            ),
            "target"=>$target,
            "template" => $itemTemplate,
            "wnd_id"=>$this->GetWndId(),
            "url" => AA_Const::AA_WWW_ROOT."/".AA_Const::AA_PUBLIC_LIB_PATH."/system_ops.php?task=GetGalleryData"
        ));    
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_ToolbarOC",array("css"=>array("border-bottom"=>"1px solid #dedede !important")));
        $toolbar->addCol(new AA_JSON_Template_Generic());
        if($modify)
        {
              //carica nuova
            $action="AA_MainApp.utils.callHandler('UploadToGallery', {task: 'GetGalleryAddNew', postParams: {refresh: 1, refresh_obj_id:'".$id_list."'}, taskManager: AA_MainApp.taskManager, module: '" . $this->module. "'},'".$this->module."')";
            $btn=new AA_JSON_Template_Generic($id."_Manuale_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-image-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "inputWidth"=>120,
                "click"=>$action,
                "tooltip"=>"Aggiungi una nuova immagine"
            ));

            $toolbar->AddCol($btn);
        }
        
        $this->AddView($toolbar);

        $this->target=$target;
    }

    protected function Update()
    {
        if($this->list)
        {
            if($this->target) 
            {
                $this->list->SetProp("target",$this->target);
                $onDblClickEvent = "try{AA_MainApp.utils.getEventHandler('OnDblClickItemGallery','" . $this->module . "','" . $this->list->GetProp("id") . "')}catch(msg){console.error(msg)}";
                $this->list->SetProp("on",array("onItemDblClick" => $onDblClickEvent));
            }

            $this->Addview($this->list);
        }
        else
        {
            $this->AddView(new AA_JSON_Template_Template("",array("borderless"=>true,"template"=>"<div style='display:flex; justify-content: center; align-items: center:width: 100%;heignt:100%'>La galleria e' vuota.</div>")));
        }

        return parent::Update();
    }
}
