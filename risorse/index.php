<?php
session_start();
include_once "../utils/config.php";
include_once "system_lib.php";

if($_REQUEST['risorsa'] =="")
{
    die("risorsa non indicata.");
}

$storage=AA_Storage::GetInstance();
if(!$storage->IsValid())
{
    die("storage non inizializzato.");
}

$risorsa=AA_Risorse::LoadFromUrlName($_REQUEST['risorsa']);
if(!$risorsa)
{
    die("risorsa non trovata (".$_REQUEST['risorsa'].")");
}

$fileInfo=$risorsa->GetFileInfo();

$file=$storage->GetFileByHash($fileInfo['hash']);

//file inesistente
if(!$file->IsValid())
{
    die("file non trovato.");
}

header("Cache-control: private");
header("Content-type: ".$file->GetMimeType());
header("Content-Length: ".$file->GetFileSize());
//if(isset($_REQUEST['filename']) || isset($_REQUEST['attachment'])) header('Content-Disposition: attachment; filename="'.$file->GetName().'"');
$fd = fopen ($file->GetFilePath(), "rb");
echo fread ($fd, filesize ($file->GetFilePath()));
fclose ($fd);
exit();