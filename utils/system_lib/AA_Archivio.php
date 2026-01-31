<?php
class AA_Archivio
{
    //Archivia un oggetto
    static public function Snapshot($date = "", $id_object = 0, $object_type = 0, $content = "", $user = null)
    {
        AA_Log::Log(__METHOD__ . "()");

        //Costruisce il contenuto
        if ($date == "") $date = date("Y-m-d H:i:s");
        if ($user == null) $user = AA_User::GetCurrentUser();
        if (!$user->IsValid() && !$user->isCurrentUser()) {
            AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
            return false;
        }

        if ($content == "") {
            AA_Log::Log(__METHOD__ . " - contenuto non presente: ", 100, true, true);
            return false;
        }

        $db = new Database();
        $query = "INSERT into archivio set data='" . $date . "', object_type='" . $object_type . "', id_object='" . $id_object . "', content='" . addslashes($content) . "', user='" . addslashes($user->toXml()) . "'";
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - errore nella query: " . htmlspecialchars($query), 100, true, true);
            return false;
        }

        return true;
    }

    //Recupera la rappresentazione di un oggetto dall'archivio
    static public function Resume($date = "", $id_object = 0, $object_type = 0)
    {
        AA_Log::Log(__METHOD__ . "()");

        $objects = AA_Archivio::ResumeMulti($id_object, $object_type, $date, 1);

        if (sizeof($objects) > 0) return array_pop($objects);

        return null;
    }

    //Recupera le prime n rappresentazioni dell'oggetto dall'archivio
    static public function ResumeMulti($id_object = 0, $object_type = 0, $date = "", $num = 1)
    {
        AA_Log::Log(__METHOD__ . "()");

        $return = array();

        if ($date == "") $date = date("Y-m-d H:i:s");
        if ($num <= 0) $num = 1;
        if ($num > 50) $num = 50;
        $db = new Database();
        $query = "SELECT * from archivio WHERE data <= '" . $date . "'";
        if ($id_object > 0) $query .= " AND id_object='" . $id_object . "'";
        if ($object_type > 0) $query .= " AND object_type='" . $object_type . "'";
        $query .= " ORDER by data DESC,id DESC LIMIT " . $num;

        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - errore nella query: " . $query, 100, true, true);
            return $return;
        }

        $rs = $db->GetRecordSet();
        if ($rs->GetCount()) {
            do {
                $xml = new AA_XML_FEED_ARCHIVIO();
                $xml->SetContent("<data>" . $rs->Get('content') . $rs->Get('user') . "</data>");
                $return[$rs->Get('id')] = $xml;
            } while ($rs->MoveNext());
        }

        return $return;
    }
}
