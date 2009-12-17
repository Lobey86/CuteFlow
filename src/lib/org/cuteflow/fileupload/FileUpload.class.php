<?php



class FileUpload {


    public function uploadFile(array $file, $versionid, $templateid) {
        if($file['name'] != '') {
            $this->checkFolder($versionid, $templateid);
            $hashFileArray = explode('.', $file['name']);
            $hashString = time() . md5($hashFileArray[0]) . '.' . $hashFileArray[count($hashFileArray)-1];
            move_uploaded_file($file['tmp_name'], sfConfig::get('sf_upload_dir') . '/' . $templateid . '/' . $versionid . '/' . $hashString);
            $newFile = new WorkflowVersionAttachment();
            $newFile->setWorkflowversionId($versionid);
            $newFile->setWorkflowtemplateId($templateid);
            $newFile->setFilename($file['name']);
            $newFile->setHashname($hashString);
            $newFile->save();
            
            return true;
        }
        return false;
    }


    public function checkFolder($versionid, $templateid) {
        if(is_dir(sfConfig::get('sf_upload_dir') . '/' . $templateid) == false) {
            mkdir(sfConfig::get('sf_upload_dir') . '/' . $templateid);
        }

        if(is_dir(sfConfig::get('sf_upload_dir') . '/' . $templateid . '/' . $versionid) == false) {
            mkdir(sfConfig::get('sf_upload_dir') . '/' . $templateid . '/' . $versionid);
        }
        return true;
    }


    public function uploadFormFile(array $file, $field_id, $versionid, $templateid) {
        $this->checkFolder($versionid, $templateid);
        $hashFileArray = explode('.', $file['name']);
        $hashString = time() . md5($hashFileArray[0]) . '.' . $hashFileArray[count($hashFileArray)-1];
        move_uploaded_file($file['tmp_name'], sfConfig::get('sf_upload_dir') . '/' . $templateid . '/' . $versionid . '/' . $hashString);
        $wfs = new WorkflowSlotFieldFile();
        $wfs->setWorkflowslotfieldId($field_id);
        $wfs->setFilename($file['name']);
        $wfs->setHashname($hashString);
        $wfs->save();
    }




}
?>