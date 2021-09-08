<?php

namespace app\common\model;

class UploadHandler
{

    protected $policyId;

    protected $userId;

    protected $policyContent;

    public function __construct($id,$uid){
        $this->policyId = $id;
        $this->userId = $uid;

        $this->policyContent = Policys::where('id',$id)->find();

    }

    public function getToken(){
        switch ($this->policyContent['policy_type']) {
            case 'local':
                return $this->getLocalToken();
                break;

            case 'remote':
                return $this->getRemoteToken();
                break;
            default:
                return '';
                break;
        }
    }

    protected function getLocalToken(){

    }

}