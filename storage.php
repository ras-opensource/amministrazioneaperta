<?php
session_start();
include_once "utils/config.php";
include_once "utils/system_lib.php";

if($_REQUEST['object'] =="")
{
    die("oggetto non impostato.");
}

$storage=AA_Storage::GetInstance();
if(!$storage->IsValid())
{
    die("storage non inizializzato.");
}

$file=$storage->GetFileByHash($_REQUEST['object']);

//file inesistente
if(!$file->IsValid())
{
    die("file non trovato.");
}

//file non pubblico
if(!$file->IsPublic())
{
    die("file non accessibile.");
}

header("Cache-control: private");
header("Content-type: ".$file->GetMimeType());
header("Content-Length: ".$file->GetFileSize());
//header('Content-Disposition: attachment; filename="'.$this->tipo."_".$this->id.'.pdf"');
$fd = fopen ($file->GetFilePath(), "rb");
echo fread ($fd, filesize ($file->GetFilePath()));
fclose ($fd);
exit();
