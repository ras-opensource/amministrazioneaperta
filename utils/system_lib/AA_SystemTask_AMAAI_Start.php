<?php
class AA_SystemTask_AMAAI_Start extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("AMAAI_Start", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

        $module = AA_AMAAI::GetInstance();

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $template = $module->TemplateLayout();
        $template->SetWidth(720);
        $template->SetHeight(580);

        $template->AddView($module->TemplateStart());

        $content = $template->toString();
        $this->sTaskLog .= base64_encode($content);
        $this->sTaskLog .= "</content>";
        return true;
    }
}
