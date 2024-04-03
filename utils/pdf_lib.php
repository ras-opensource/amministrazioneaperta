<?php
include_once "config.php";

Class AA_PDF_Page
{
    private $doc=null;
    public function SetDocument($doc=null)
    {
        $this->doc=$doc;
        $this->EnableHeader($doc->IsPageHeaderEnabled());
        $this->SetHeaderClass($doc->GetPageHeaderClass());
        $this->SetHeaderStyle($doc->GetPageHeaderStyle());
        $this->SetHeaderContent($doc->GetPageHeaderContent());

        $this->EnableFooter($doc->IsPageFooterEnabled());
        $this->SetFooterClass($doc->GetPageFooterClass());
        $this->SetFooterStyle($doc->GetPageFooterStyle());
        $this->SetFooterContent($doc->GetPageFooterContent());

        $this->EnableCorpo($doc->IsPageCorpoEnabled());
        $this->SetCorpoClass($doc->GetPageCorpoClass());
        $this->SetCorpoStyle($doc->GetPageCorpoStyle());
        $this->SetContent($doc->GetPageContent());
    }
    public function GetDocument()
    {
        return $this->doc;
    }

    private  $pageNumber=0;
    public function SetPageNumber($num=0)
    {
        if($num > 0) $this->pageNumber=$num;
    }

    public function GetPageNumber()
    {
        return $this->pageNumber;
    }
    private $bShowPageNumber=true;
    public function ShowPageNumber($bShow=true)
    {
        $this->bShowPageNumber=$bShow;
    }
    public function IsPageNumberVisible()
    {
        return $this->bShowPageNumber;
    }

    //Gestione dell'header
    private $sHeader='<div class="AA_PDF_Page_header_box %headerClass%" style="overflow: hidden;%headerStyle%">%content%</div>';
    private $sHeaderClass="";
    public function SetHeaderClass($var="")
    {
        $this->sHeaderClass=$var;
    }

    private $sHeaderStyle="";
    public function SetHeaderStyle($var="")
    {
        $this->sHeaderStyle=$var;
    }

    private $bHeader=true;
    public function EnableHeader($var=true)
    {
        $this->bHeader=$var;
    }

    private $sHeaderContent="";
    public function SetHeaderContent($var="")
    {
        $this->sHeaderContent=$var;
    }
    //----------------------------

    //Gestione del pie di pagina
    private $sFooter='<div class="AA_PDF_Page_footer_box %footerClass%" style="overflow: hidden;%footerStyle%">%content%<div class="AA_PDF_Page_number_box %page_number_box_class%" style="%page_number_box_style%">%page_number%</div></div>';
    private $sFooterClass="";
    public function SetFooterClass($var="")
    {
        $this->sFooterClass=$var;
    }

    private $sFooterStyle="";
    public function SetFooterStyle($var="")
    {
        $this->sFooterStyle=$var;
    }

    private $sPageNumberBoxClass="";
    public function SetPageNumberBoxClass($var="")
    {
        $this->sPageNumberBoxClass=$var;
    }

    private $sPageNumberBoxStyle="";
    public function SetPageNumberBoxStyle($var="")
    {
        $this->sPageNumberBoxStyle=$var;
    }

    private $bFooter=true;
    public function EnableFooter($var=true)
    {
        $this->bFooter=$var;
    }

    private $sFooterContent="";
    public function SetFooterContent($var="")
    {
        $this->sFooterContent=$var;
    }
    //----------------------------

    //Gestione del contenuto
    private $sCorpo='<div class="AA_PDF_Page_corpo_box %corpoClass%" style="overflow: hidden;%corpoStyle%">%content%</div>';
    private $sCorpoClass="";
    public function SetCorpoClass($var="")
    {
        $this->sCorpoClass=$var;
    }

    private $sCorpoStyle="";
    public function SetCorpoStyle($var="")
    {
        $this->sCorpoStyle=$var;
    }

    private $bCorpo=true;
    public function EnableCorpo($var=true)
    {
        $this->bCorpo=$var;
    }

    private $sCorpoContent="";
    public function SetContent($var="")
    {
        $this->sCorpoContent=$var;
    }
    public function AddContent($var="")
    {
        $this->sCorpoContent.=$var;
    }
    //----------------------------

    public function __construct($doc=null)
    {
        if($doc instanceof AA_PDF_Document)
        {
            $this->SetDocument($doc);
        } 
    }

    public function Render()
    {
        $page="<div class='AA_PDF_Page_box'>";
        if($this->bHeader)
        {
            $page.=str_replace(array("%headerClass%","%headerStyle%","%content%"),array($this->sHeaderClass,$this->sHeaderStyle,$this->sHeaderContent),$this->sHeader);
        }
        if($this->bCorpo)
        {
            $page.=str_replace(array("%corpoClass%","%corpoStyle%","%content%"),array($this->sCorpoClass,$this->sCorpoStyle,$this->sCorpoContent),$this->sCorpo);
        }
        if($this->bFooter)
        {
            if($this->IsPagenumberVisible()) $page.=str_replace(array("%footerClass%","%footerStyle%","%content%","%page_number%","%page_number_box_class%","%page_number_box_style%"),array($this->sFooterClass,$this->sFooterStyle,$this->sFooterContent,$this->pageNumber,$this->sPageNumberBoxClass,$this->sPageNumberBoxStyle),$this->sFooter);
            else $page.=str_replace(array("%footerClass%","%footerStyle%","%content%","%page_number%","%page_number_box_class%","%page_number_box_style%"),array($this->sFooterClass,$this->sFooterStyle,$this->sFooterContent,"",$this->sPageNumberBoxClass,"display: none;"),$this->sFooter);
        }
        $page.="</div>";

        //error_log(get_class()."->Render() - ".$this->sHeaderStyle);
        return $page;
    }

    public function __toString()
    {
        return "AA_PDF_Page";
    }
}

Class AA_PDF_Document
{
    const AA_PDF_PAGE_FORMAT_A4_PORTRAIT=0;
    const AA_PDF_PAGE_FORMAT_A4_LANDSCAPE=1;

    public function __toString()
    {
        return "AA_PDF_Document";
    }

    private $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    private $templatePageClass="AA_PDF_Page";
    public function SetPageTemplateClass($pageTemplateClass="AA_PDF_Page")
    {
        error_log(get_class()."SetPageTemplateClass($pageTemplateClass)");
        
        if(class_exists($pageTemplateClass)) $this->templatePageClass=$pageTemplateClass;
        else error_log(get_class()."SetPageTemplateClass($pageTemplateClass) - ERRORE: la classe $pageTemplateClass non esiste.");
    }

    private $scripts=array();
    public function AddScript($script="")
    {
        if($script!="") $this->scripts[]=$script;
    }

    private $HeadContent="";
    public function SetHeadContent($var="")
    {
        $this->HeadContent=$var;
    }

    public function __construct($id="generic_doc", $page_format="AA_PDF_PAGE_FORMAT_A4_PORTRAIT")
    {
        //error_log(get_class()."->__construct($id)");
        
        if($id !="") $this->id=$id;
        $random=date("Ymdhis").rand( 0 , 10000);
        $this->working_dir="/tmp/".$random;
        $this->fileName=$id.".pdf";

        if(is_dir($this->working_dir))
        {
            //elimina il contenuto della directory di lavoro
            $this->PurgeWorkingDir(false);
        }
        else
        {
            //crea la directory di lavoro
            if(!mkdir($this->working_dir))
            {
                error_log(get_class()."->__construct($this->id) - ERRORE - impossibile creare la directory di lavoro: ".$this->working_dir);
            }
            else $this->bValid=true;
        }

        $this->SetPageFormat($page_format);
    }

    //flag di conservamento in cache
    private $bCached=false;
    public function EnableCache($enable=true)
    {
        $this->bCached=$enable;
    }

    //Tempo di conservazione in cache in minuti
    private $nCacheLiveTime=30;
    public function GetCacheLiveTime()
    {
        return $this->nCacheLiveTime;
    }
    public function SetCacheLiveTime($minutes=30)
    {
        if($minutes > 0) $this->nCacheLiveTime=$minutes;
    }

    //Verifica se il documento è disponibile per il download immediatamente (se è valido in cache)
    public function IsReady()
    {
        if(!$this->bCached || !$this->bValid) return false;

        //file già esistente (decidere se lasciare in cache o eliminare)
        if(file_exists($this->dest_dir."/".$this->id.".pdf"))
        {
            $filetime=filemtime($this->dest_dir."/".$this->id.".pdf");
            if((time()-$filetime) > $this->nCacheLiveTime*60) return false;
        }
        else return false;

        return true;
    }

    //Identificativo doc
    private $id="generic_doc";
    private $fileName="generic_doc.pdf";
    public function GetId()
    {
        return $this->id;
    }
    public function SetId($id="")
    {
        if($id !="") $this->id=$id;
        $this->fileName=$id.".pdf";
    }
    
    //directory di lavoro
    private $working_dir="/tmp";
    public function GetWorkingDir()
    {
        return $this->working_dir;
    }

    //directory del file finale
    private $dest_dir="/tmp";
    public function GetDestinationDir()
    {
        return $this->dest_dir;
    }
    public function SetDestinationDir($dir=null)
    {
        if(is_dir($dir)) $this->dest_dir=$dir;
    }

    //Numero di pagine
    private $Pages=array();
    public function GetNumPages()
    {
        return sizeof($this->Pages);
    }

    private function InitializeNewPage()
    {
        $pdf = new $this->templatePageClass($this);
        $this->Pages[]=$pdf;
        $pdf->SetPageNumber(sizeof($this->Pages));

        return $pdf;
    }

    private $oCurPage=null;
    public function GetCurPage()
    {
        if($this->oCurPage==null)
        {
            $this->oCurPage=$this->InitializeNewPage();
        }

        return $this->oCurPage;
    }

    public function AddPage()
    {
        $this->oCurPage=$this->InitializeNewPage();
        return $this->oCurPage;
    }

    public function GetPage($num=0)
    {
        if(isset($this->Pages[$num])) return $this->Pages[$num];
        else return null;
    }

    public function SetPage($page, $num="")
    {
        if($page instanceof AA_PDF_Page)
        {
            if($num >= 0 && isset($this->Pages[$num]))
            {
                $this->Pages[$num]=$page;
                return true;
            }
            if($this->oCurPage instanceof AA_PDF_Page)
            {
                $this->Pages[$this->oCurPage->GetPageNumber()]=$page;
                $this->oCurPage=$page;

                return true;
            }
        }
        
        return false;
    }

    public function Render($toBrowser=true)
    {
        if(!$this->bValid)
        {
            if($toBrowser) die(get_class()."->Render() - Documento non valido.");
            else return false;
        }

        if($this->bCached && $this->IsReady())
        {
            $this->PurgeWorkingDir();

            if($toBrowser)
            {
                //Restituisce il documento in cache
                header("Cache-Control: no-cache");
                header("Content-type:application/pdf");
                header("Content-Disposition:attachment;filename=".$this->id.".pdf");
                readfile($this->dest_dir."/".$this->id.".pdf");
                exit();
            }

            return true;
        }

        if($this->oCurPage==null)
        {
            if($toBrowser) die("Nessun contenuto da renderizzare.");
            else return false;
        } 

        //Crea il file da renderizzare
        $html_file='<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">';
        $html_file.='<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />';
        $html_file.='<link rel="preconnect" href="https://fonts.googleapis.com">';
        $html_file.='<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        $html_file.='<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">';

        $html_file.='<link href="'.AA_Config::AA_APP_FILESYSTEM_FOLDER.'/stili/system.css" rel="stylesheet" type="text/css" />';
        $html_file.='<link href="'.AA_Config::AA_APP_FILESYSTEM_FOLDER.'/stili/organismi.css" rel="stylesheet" type="text/css" />';
        $html_file.='
        <style>
            html *
            {
                font-family: "Roboto", "Dejavu",Verdana, Tahoma, sans-serif;
            }

            @page {size: %PAGE_WIDTH%%PDF_UNIT% %PAGE_HEIGHT%%PDF_UNIT%; margin: 0;}
            @media print
            {
                div.AA_PDF_Page_box {display: flex; flex-direction: column; align-items: stretch; page-break-after: always; width: %PAGEBOX_WIDTH%%PDF_UNIT%;height: %PAGEBOX_HEIGHT%%PDF_UNIT%; margin: %MARGIN_TOP%%PDF_UNIT% %MARGIN_LEFT%%PDF_UNIT% %MARGIN_BOTTOM%%PDF_UNIT% %MARGIN_RIGHT%%PDF_UNIT%;}
                div.AA_PDF_Page_footer_box {padding: 1mm; height: %FOOTER_HEIGHT%%PDF_UNIT%; min-height: %FOOTER_HEIGHT%%PDF_UNIT%;}
                div.AA_PDF_Page_header_box {padding: 1mm; margin-top: 5mm; height: %HEADER_HEIGHT%%PDF_UNIT%; min-height: %HEADER_HEIGHT%%PDF_UNIT%;}
                div.AA_PDF_Page_corpo_box {flex: 1; padding: 1mm; height: %CORPO_HEIGHT%%PDF_UNIT%; min-height: %HEADER_HEIGHT%%PDF_UNIT%;}
            }
        </style>
        %HEAD_CONTENT%
        </head><body style="%document_style%">';

        $html_file=str_replace(array("%HEAD_CONTENT%","%document_style%","%PAGEBOX_WIDTH%","%PAGEBOX_HEIGHT%","%PAGE_ORIENTATION%","%PDF_UNIT%","%PAGE_WIDTH%","%PAGE_HEIGHT%","%MARGIN_TOP%","%MARGIN_BOTTOM%","%MARGIN_LEFT%","%MARGIN_RIGHT%"," %FOOTER_HEIGHT%","%FOOTER_WIDTH%","%HEADER_HEIGHT%","%HEADER_WIDTH%","%CORPO_HEIGHT%"),
        array($this->HeadContent, $this->sDocumentStyle,$this->pagebox_width, $this->pagebox_height, $this->page_orientation,$this->pdf_unit,$this->page_width,$this->page_height,$this->margins[0],$this->margins[1],$this->margins[2],$this->margins[3],$this->footer_height,$this->footer_width,$this->header_height,$this->header_width,$this->corpo_height),$html_file);
        foreach($this->Pages as $num=>$page)
        {
            $html_file.=$page->Render();
        }
        $html_file.="</body></html>";
        
        //error_log($html_file);

        if(file_put_contents($this->working_dir."/".$this->id.".html",$html_file)===FALSE)
        {
            error_log(get_class()." - Errore nella generazione del documento: ".$this->working_dir."/".$this->id.".html");
            if($toBrowser)
            {
                die("ERRORE durante la generazione del documento pdf.");
            } 
            else return false;            
        }

        //Elimina il file già esistente
        if(file_exists($this->dest_dir."/".$this->id.".pdf"))
        {
            if(!unlink($this->dest_dir."/".$this->id.".pdf"))
            {
                error_log(get_class()." - Errore nella rimozione del documento: ".$this->dest_dir."/".$this->id.".pdf");
                if($toBrowser)
                {
                    
                    die("ERRORE durante la generazione del documento pdf.");
                } 
                else return false;
    
            } 
        }

       $cmd = "cd ".$this->working_dir."; LD_LIBRARY_PATH=/usr/lib:/lib  chromium-browser --headless --disable-gpu --no-sandbox --run-all-compositor-stages-before-draw --print-to-pdf ./".$this->id.".html --virtual-time-budget=20000";

        //error_log("eseguo: ".$cmd);
        $result = shell_exec($cmd);
        //error_log($result);

        //Errore durante la generazione del file pdf
        if(!is_file($this->working_dir."/output.pdf"))
        {
            error_log($result);  
            if($toBrowser)
            {
                die("ERRORE durante la generazione del documento pdf.");
            } 
            else return false;
        }

        //Copia il file nella destinazione finale
        if(!link($this->working_dir."/output.pdf",$this->dest_dir."/".$this->id.".pdf"))
        {
            error_log(get_class()."->Render($toBrowser) - ERRORE: impossibile copiare il file: ".$this->working_dir."/output.pdf"." -> ".$this->dest_dir."/".$this->id.".pdf");
            if($toBrowser) die("ERRORE durante la generazione del documento pdf.");
            else return false;
        }

        $this->PurgeWorkingDir();

        if(!is_file($this->dest_dir."/".$this->id.".pdf"))
        {
            error_log(get_class()."->Render($toBrowser) - ERRORE: file: ".$this->dest_dir."/".$this->id.".pdf non trovato.");
            if($toBrowser) die("ERRORE durante la generazione del documento pdf: ".$result);
            else return false;
        }

        if($toBrowser)
        {
            header("Cache-Control: no-cache");
            header("Content-type:application/pdf");
            //header("Content-Disposition:attachment;filename=".$this->id.".pdf");
            readfile($this->dest_dir."/".$this->id.".pdf");
            
            unlink($this->dest_dir."/".$this->id.".pdf");
            
            exit();
        }
        else return true;
    }
    
    public function GetFilePath()
    {
        if(is_file($this->dest_dir."/".$this->id.".pdf"))
        {
            return $this->dest_dir."/".$this->id.".pdf";
        }
        else return "";
    }

    private function PurgeWorkingDir($bRemoveDir=true)
    {
        if(is_dir($this->working_dir))
        {
            //elimina la directory di lavoro
            $files=scandir($this->working_dir);
            foreach($files as $file)
            {
                if(is_file($this->working_dir."/".$file))
                {
                    //error_log(get_class()."->PurgeWorkingDir() - Rimozione del file di lavoro: ".$this->working_dir."/".$file);
                    if(!unlink($this->working_dir."/".$file)) error_log(get_class()."->Render() - ERRORE durante la rimozione del file di lavoro: ".$this->working_dir."/".$file);
                }
            }
            
            if($bRemoveDir)
            {
                if(!rmdir($this->working_dir)) error_log(get_class()."->Render() - ERRORE durante la rimozione della directory di lavoro: ".$this->working_dir);
            } 
        }
    }

    private $author="Amministrazione Aperta - Regione autonoma della Sardegna";
    public function SetAuthor($author="Amministrazione Aperta - Regione autonoma della Sardegna")
    {
        $this->author;
    }

    private $creator="Amministrazione Aperta - Regione autonoma della Sardegna";
    public function SetCreator($creator="Amministrazione Aperta - Regione autonoma della Sardegna")
    {
        $this->creator=$creator;
    }

    private $title="";
    public function SetTitle($title="")
    {
        $this->title=$title;
    }
    public function GetTitle()
    {
        return $this->title;
    }

    private $logoImg="";
    public function SetLogoImage($logoImg="")
    {
        $this->logoImg=$logoImg;
    }
    public function GetLogoImage()
    {
        return $this->logoImg;
    }

    private $subject="";
    public function SetSubject($subject="")
    {
        $this->subject=$subject;
    }

    private $keywords="";
    public function SetKeywords($keywords="")
    {
        $this->keywords=$keywords;
    }

    private $page_orientation="portrait";
    public function SetPageOrientation($page_orientation="portrait")
    {
        $this->page_orientation=$page_orientation;
    }

    private $pdf_unit="mm";
    public function SetPdfUnit($pdf_unit="mm")
    {
        $this->pdf_unit=$pdf_unit;
    }

    private $page_format=self::AA_PDF_PAGE_FORMAT_A4_PORTRAIT;
    public function SetPageFormat($page_format=self::AA_PDF_PAGE_FORMAT_A4_PORTRAIT)
    {
        if($page_format==self::AA_PDF_PAGE_FORMAT_A4_PORTRAIT)
        {
            $this->page_height=297;
            $this->page_width=210;
            $this->margins=array(10,10,10,10);
            $this->nLeftOffset=2;
            $this->nTopOffset=3;
            $this->nBottomOffset=3;
            $this->nRightOffset=2;

            $this->SetPageBoxHeight();
            $this->SetPageBoxWidth();
            $this->SetHeaderHeight(15);
            $this->SetHeaderWidth();
            $this->SetFooterHeight(15);
            $this->SetFooterWidth();
            $this->SetCorpoHeight();

            $this->page_format=$page_format;
            return;
        }

        if($page_format==self::AA_PDF_PAGE_FORMAT_A4_LANDSCAPE)
        {
            $this->page_height=210;
            $this->page_width=297;
            $this->margins=array(10,10,10,10);
            $this->nLeftOffset=2;
            $this->nTopOffset=3;
            $this->nBottomOffset=3;
            $this->nRightOffset=2;

            $this->SetPageBoxHeight();
            $this->SetPageBoxWidth();
            $this->SetHeaderHeight(15);
            $this->SetHeaderWidth();
            $this->SetFooterHeight(15);
            $this->SetFooterWidth();
            $this->SetCorpoHeight();

            $this->page_format=$page_format;
            return;
        }

        //default to A4 portrait
        $this->page_height=297;
        $this->page_width=210;
        $this->margins=array(10,10,10,10);
        $this->nLeftOffset=2;
        $this->nTopOffset=3;
        $this->nBottomOffset=3;
        $this->nRightOffset=2;

        $this->SetPageBoxHeight();
        $this->SetPageBoxWidth();
        $this->SetHeaderHeight(15);
        $this->SetHeaderWidth();
        $this->SetFooterHeight(15);
        $this->SetFooterWidth();
        $this->SetCorpoHeight();

        $this->page_format=$page_format;
    }

    private $nLeftOffset=3;
    public function SetLeftOffSet($val=0)
    {
        if($val > 0)
        {
            $this->nLeftOffset=$val;
            $this->SetPageFormat($this->page_format);
        } 
    }

    private $nRightOffset=3;
    public function SetRightOffSet($val=0)
    {
        if($val > 0)
        {
            $this->nRightOffset=$val;
            $this->SetPageFormat($this->page_format);
        } 
    }

    private $nTopOffset=3;
    public function SetTopOffset($val=0)
    {
        if($val > 0)
        {
            $this->nTopOffset=$val;
            $this->SetPageFormat($this->page_format);
        } 
    }

    private $nBottomOffset=3;
    public function SetBottomOffSet($val=0)
    {
        if($val > 0)
        {
            $this->nBottomOffset=$val;
            $this->SetPageFormat($this->page_format);
        } 
    }

    private $margins=array(10, 10, 10,10);
    public function SetMargins($top=null,$left=null,$bottom=null,$right=null)
    {
        if($top != null) $this->margins[0]=$top;
        if($right != null) $this->margins[1]=$right;
        if($bottom != null) $this->margins[2]=$bottom;
        if($left != null) $this->margins[3]=$left;
        
    }

    private $page_height="297"; //mm
    public function SetPageHeight($value="297")
    {
        if($value != null) $this->page_height=$value;
    }

    private $page_width="210"; //mm
    public function SetPageWidth($value="210")
    {
        if($value != null) $this->page_width=$value;
    }

    private $sDocumentStyle="";
    public function SetDocumentStyle($style="")
    {
        $this->sDocumentStyle=$style;
    }
    public function GetDocumentStyle()
    {
        return $this->sDocumentStyle;
    }

    private $pagebox_height="270"; //mm
    public function SetPageBoxHeight($value="auto")
    {
        if($value=="auto")
        {
            $this->pagebox_height=$this->page_height-($this->margins[0]+$this->margins[2]+$this->nTopOffset+$this->nBottomOffset);
            return;
        } 
        $newValue=$value + $this->margins[0] +$this->margins[2] + 7;
        if($value <= $this->page_height) $this->pagebox_height=$value;
        else $this->pagebox_height=$this->page_height-($this->margins[0] +$this->margins[2]+$this->nTopOffset+$this->nBottomOffset);
    }

    private $pagebox_width="190"; //mm
    public function SetPageBoxWidth($value="auto")
    {

        if($value=="auto")
        {
            $this->pagebox_width=$this->page_width-($this->margins[1]+$this->margins[3]+$this->nLeftOffset+$this->nRightOffset);
            return;
        }
        $newValue=$value + $this->margins[1] +$this->margins[3]+$this->nLeftOffset+$this->nRightOffset;
        if($newValue <= $this->page_width) $this->pagebox_width=$value;
        else $this->pagebox_width=$this->page_width-($this->margins[1]+$this->margins[3]+$this->nLeftOffset+$this->nRightOffset);
    }

    //Gestione dell'header
    private $sPageHeaderClass="";
    public function SetPageHeaderClass($var="")
    {
        $this->sPageHeaderClass=$var;
    }
    public function GetPageHeaderClass()
    {
        return $this->sPageHeaderClass;
    }

    private $sPageHeaderStyle="";
    public function SetPageHeaderStyle($var="")
    {
        $this->sPageHeaderStyle=$var;
    }
    public function GetPageHeaderStyle()
    {
        return $this->sPageHeaderStyle;
    }

    private $bPageHeader=true;
    public function EnablePageHeader($var=true)
    {
        $this->bPageHeader=$var;
    }
    public function IsPageHeaderEnabled()
    {
        return $this->bPageHeader;
    }

    private $sPageHeaderContent="";
    public function SetPageHeaderContent($var="")
    {
        $this->sPageHeaderContent=$var;
    }
    public function GetPageHeaderContent()
    {
        return $this->sPageHeaderContent;
    }

    private $header_height="15"; //mm
    public function SetHeaderHeight($value="auto")
    {
        if($value=="auto")
        {
            $this->header_height=$this->pagebox_height-($this->footer_height+$this->corpo_height);
            return;
        }
        if($value <= ($this->pagebox_height-$this->footer_height))
        {
            $this->header_height=$value;
            $this->SetCorpoHeight();
        } 
    }

    private $header_width="192"; //mm
    public function SetHeaderWidth($value="auto")
    {
        if($value=="auto")
        {
            $this->header_width=$this->pagebox_width;
            return;
        }
        $newValue=$value;
        if($newValue <= $this->pagebox_width) $this->header_width=$value;
        else $this->header_width=$this->pagebox_width;
    }
    //----------------------------------

    //Gestione del pie di pagina
    private $sPageFooterClass="";
    public function SetPageFooterClass($var="")
    {
        $this->sPageFooterClass=$var;
    }
    public function GetPageFooterClass()
    {
        return $this->sPageFooterClass;
    }

    private $sPageFooterStyle="";
    public function SetPageFooterStyle($var="")
    {
        $this->sPageFooterStyle=$var;
    }
    public function GetPageFooterStyle()
    {
        return $this->sPageFooterStyle;
    }

    private $bPageFooter=true;
    public function EnablePageFooter($var=true)
    {
        $this->bPageFooter=$var;
    }
    public function IsPageFooterEnabled()
    {
        return $this->bPageFooter;
    }

    private $sPageFooterContent="";
    public function SetPageFooterContent($var="")
    {
        $this->sPageFooterContent=$var;
    }
    public function GetPageFooterContent()
    {
        return $this->sPageFooterContent;
    }

    private $footer_height="15"; //mm
    public function SetFooterHeight($value="auto")
    {
        if($value=="auto")
        {
            $this->footer_height=$this->pagebox_height-($this->header_height+$this->corpo_height);
            return;
        }
        if($value <= ($this->pagebox_height-$this->header_height))
        {
            $this->footer_height=$value;
            $this->SetCorpoHeight();
        }
    }

    private $footer_width="192"; //mm
    public function SetFooterWidth($value="192")
    {
        if($value=="auto")
        {
            $this->footer_width=$this->pagebox_width;
            return;
        }
        $newValue=$value;
        if($newValue <= $this->pagebox_width) $this->header_width=$value;
        else $this->footer_width=$this->pagebox_width;
    }
    //------------------------------------

    //Gestione del contenuto
    private $sPageCorpoClass="";
    public function SetPageCorpoClass($var="")
    {
        $this->sPageCorpoClass=$var;
    }
    public function GetPageCorpoClass()
    {
        return $this->sPageCorpoClass;
    }

    private $sPageCorpoStyle="";
    public function SetPageCorpoStyle($var="")
    {
        $this->sPageCorpoStyle=$var;
    }
    public function GetPageCorpoStyle()
    {
        return $this->sPageCorpoStyle;
    }

    private $bPageCorpo=true;
    public function EnablePageCorpo($var=true)
    {
        $this->bPageCorpo=$var;
    }
    public function IsPageCorpoEnabled()
    {
        return $this->bPageCorpo;
    }

    private $sPageContent="";
    public function SetPageContent($var="")
    {
        $this->sPageContent=$var;
    }
    public function GetPageContent()
    {
        return $this->sPageContent;
    }

    private $corpo_height="240"; //mm
    public function SetCorpoHeight($value="auto")
    {
        $this->corpo_height=$this->pagebox_height-($this->header_height+$this->footer_height);
    }
    //----------------------------
}

Class AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE extends AA_PDF_Page
{
    public function __construct($doc=null)
    {
        parent::__construct($doc);

        if($doc instanceof AA_PDF_Document)
        {
            $title=$doc->GetTitle();
            $logoImg=$doc->GetLogoImage();
        }
        else
        {
            $title="";
            $logoImg="";
        }
        
        if($logoImg != "")
        {
            $header_content='<a href="https:///www.regione.sardegna.it"><img src="file://'.AA_Const::AA_APP_FILESYSTEM_FOLDER.'/immagini/'.$logoImg.'" style="height: 20mm;" title="logo" alt="logo"/></a><br/>';
        }
        $header_content.='<span style="font-size: 3mm; font-weight: bold;">'.$title.'</span>';
        $this->SetHeaderContent($header_content);
        $this->SetHeaderStyle("border-bottom: .2mm solid black; text-align: center");
        $this->SetFooterStyle("display: flex; flex-direction: column; justify-content: flex-end; text-align: center");
        $this->SetPageNumberBoxStyle("border-top:.2mm solid black; overflow: hidden; font-size: 3mm; padding-top: .5mm;");

        //error_log(get_class()." - ".$this->sHeaderContent);
    }
}

Class AA_PDF_PAGE_GENERIC_DEFAULT_TEMPLATE extends AA_PDF_Page
{
    public function __construct($doc=null)
    {
        parent::__construct($doc);

        if($doc instanceof AA_PDF_Document)
        {
            $title=$doc->GetTitle();
            $logoImg=$doc->GetLogoImage();
        }
        else
        {
            $title="";
            $logoImg="";
        }
        
        if($logoImg != "")
        {
            $header_content='<a href="https:///www.regione.sardegna.it"><img src="file://'.AA_Const::AA_APP_FILESYSTEM_FOLDER.'/immagini/'.$logoImg.'" style="height: 20mm;" title="logo" alt="logo"/></a><br/>';
        }
        $header_content.='<span style="font-size: 3mm; font-weight: bold;">'.$title.'</span>';
        $this->SetHeaderContent($header_content);
        $this->SetHeaderStyle("border-bottom: .2mm solid black; text-align: center");
        $this->SetFooterStyle("display: flex; flex-direction: column; justify-content: flex-end; text-align: center");
        $this->SetPageNumberBoxStyle("border-top:.2mm solid black; overflow: hidden; font-size: 3mm; padding-top: .5mm;");

        //error_log(get_class()." - ".$this->sHeaderContent);
    }
}

Class AA_PDF_RAS_TEMPLATE_A4_PORTRAIT extends AA_PDF_Document
{
    public function __construct($id="generic_ras_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_PORTRAIT);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("logo_ras.gif");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}

Class AA_PDF_RAS_TEMPLATE_A4_LANDSCAPE extends AA_PDF_Document
{
    public function __construct($id="generic_ras_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_LANDSCAPE);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("logo_ras.gif");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}

//Modelli ASPAL
Class AA_PDF_ASPAL_TEMPLATE_A4_LANDSCAPE extends AA_PDF_Document
{
    public function __construct($id="generic_aspal_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_LANDSCAPE);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("27_logo.jpg");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}

Class AA_PDF_ASPAL_TEMPLATE_A4_PORTRAIT extends AA_PDF_Document
{
    public function __construct($id="generic_aspal_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_PORTRAIT);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("27_logo.jpg");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}
//-------------------------

//Modelli AREA
Class AA_PDF_AREA_TEMPLATE_A4_LANDSCAPE extends AA_PDF_Document
{
    public function __construct($id="generic_area_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_LANDSCAPE);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("22_logo.jpg");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}

Class AA_PDF_AREA_TEMPLATE_A4_PORTRAIT extends AA_PDF_Document
{
    public function __construct($id="generic_area_doc")
    {
        parent::__construct($id,AA_PDF_Document::AA_PDF_PAGE_FORMAT_A4_PORTRAIT);
        $this->SetHeaderHeight(23);
        $this->SetLogoImage("22_logo.jpg");
        $this->SetDocumentStyle("font-family: sans-serif;");
        $this->SetPageTemplateClass("AA_PDF_PAGE_RAS_DEFAULT_TEMPLATE");
    }
}
//-------------------------
?>