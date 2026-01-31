<?php
class AA_SessionFileUpload
{
    protected $id = "file";
    public function GetId()
    {
        return $this->id;
    }

    protected $value = "";
    public function GetValue()
    {
        return $this->value;
    }

    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    protected function __construct($id = "", $value = "")
    {
        $this->value = $value;
        $this->id = $id;

        if (is_array($value)) {
            if (is_file($value['tmp_name'])) {
                $this->bValid = true;

                //AA_Log::Log(__METHOD__." file: ".$curFile['tmp_name']);
            }
        }
    }

    static public function Get($id = "")
    {
        if (isset($_SESSION['SessionFiles']) && $id != "") {
            $files = unserialize($_SESSION['SessionFiles']);

            //AA_Log::Log(__METHOD__." - SessionFiles: ".$_SESSION['SessionFiles'],100);

            if (isset($files[$id])) return new AA_SessionFileUpload($id, $files[$id]);
        }

        return new AA_SessionFileUpload();
    }

    static public function Add($id = "", $value = "")
    {
        if ($id == "" || !is_array($value)) return false;

        $sessionFiles = unserialize($_SESSION['SessionFiles']);

        if (!is_array($sessionFiles)) {
            $sessionFiles = array();
        }

        if (is_file($value['tmp_name'])) {
            $dir = sys_get_temp_dir(). DIRECTORY_SEPARATOR . "session_files";
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            $filename = $dir . DIRECTORY_SEPARATOR . session_id() . "_" . Date("Ymdhis");

            if (move_uploaded_file($value['tmp_name'], $filename)) {
                $value['tmp_name'] = $filename;
                $sessionFiles[$id] = $value;

                $_SESSION['SessionFiles'] = serialize($sessionFiles);

                //AA_Log::Log(__METHOD__." - SessionFiles: ".$_SESSION['SessionFiles'],100);

                return $value;
            } else return false;
        }

        return false;
    }
}
