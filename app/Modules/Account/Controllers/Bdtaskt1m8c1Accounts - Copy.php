<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8AccountModel;
use App\Models\Bdtaskt1m1CommonModel;
use App\Libraries\Permission;
class Bdtaskt1m8c1Accounts extends BaseController
{
    private $bdtaskt1m8c1_01_servInModel;
    private $bdtaskt1m8c1_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c1_01_accModel = new Bdtaskt1m8AccountModel();
        $this->bdtaskt1m8c1_02_CmModel = new Bdtaskt1m1CommonModel();
        $this->permission = new Permission();
    }

    /*--------------------------
    | Chart of Accounts
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['chart','of', 'account']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['userList']   = $this->bdtaskt1m8c1_01_accModel->get_userlist();
        $data['role_reult'] = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadCode'=>1));
        $data['page']       = "treeview";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | predefine account
    *--------------------------*/
    public function bdtaskt1m8c1_11_prdefine_accounts()
    {
      $filednames = $this->bdtaskt1m8c1_01_accModel->getPredefineCode();
      $fvalues    = $this->bdtaskt1m8c1_01_accModel->getPredefineCodeValues();
      $MesTitle   = 'PredefineCode';
      if ($this->request->getMethod() == 'post') {
        foreach($filednames as $fields){
          if($fields != 'id'){
            $rules[$fields] = 'max_length[20]';
           }
        }

        if (!$this->validate($rules)) {
          $this->session->setFlashdata(array('exception'=>$this->validator->listErrors()));
          return redirect()->to($_SERVER['HTTP_REFERER']); 
      } else {
        foreach($filednames as $fields){
          if($fields != 'id'){
            $definedata[$fields] = $this->request->getVar($fields);
          }
        }
         
        $updata = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_02_Update('acc_predefine_account',$definedata, array('id'=> $fvalues->id));
        if($updata){
          $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['updated']), 'PredefineCode', 'acc_predefine_account', 1);
          $this->session->setFlashdata(array('message'=> get_phrases(['successfully','updated'])));
          return redirect()->to($_SERVER['HTTP_REFERER']);
        }else{
          $this->session->setFlashdata(array('exception'=> 'Please try Again'));
        return redirect()->to($_SERVER['HTTP_REFERER']);
        }
        
      }
        
    }else{
        $data['title']       = get_phrases(['predefine', 'accounts']);
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['module']      = "Account";
        $data['fieldNames']  = $filednames;
        $data['filedvalues'] = $fvalues;
        $data['allheads']    = $this->bdtaskt1m8c1_01_accModel->getCoaHeads();
        $data['page']        = "predefine_accounts";
        return $this->base_01_template->layout($data);
    }
    }

    /*--------------------------
    | Show selected account
    *--------------------------*/
    public function bdtaskt1m8c1_01_selectedForm($id){

        $role_reult = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadCode'=>$id));

        $baseurl = base_url('account/accounts/insertCoa');
         $html = '';
        if ($role_reult){
            $html = form_open_multipart('account/accounts/insertCoa', 'class="ajaxForm" novalidate="" id="saveTree"');
            $html .= "<div id=\"newData\">
      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
      <tr>
        <td>Head Code</td>
        <td><input type=\"text\" name=\"txtHeadCode\" id=\"txtHeadCode\" class=\"form-control\"  value=\"".$role_reult->HeadCode."\" readonly=\"readonly\"/>
        <input type=\"hidden\" name=\"txtHeadLevel\" id=\"txtHeadLevel\" class=\"form-control\"  value=\"".$role_reult->HeadLevel."\" readonly/>
        <input type=\"hidden\" name=\"txtHeadType\" id=\"txtHeadType\" class=\"form-control\"  value=\"".$role_reult->HeadType."\"/>
        </td>
      </tr>
      <tr>
        <td>Head Name</td>
        <td><input type=\"text\" name=\"txtHeadName\" id=\"txtHeadName\" class=\"form-control\" value=\"".$role_reult->HeadName."\" />
        <input type=\"hidden\" name=\"HeadName\" id=\"HeadName\" class=\"form-control\" value=\"".$role_reult->HeadName."\"/><label id=\"nameLabel\" class=\"errore\"></label>
        </td>
      </tr>
      <tr>
        <td>Parent Head</td>
        <td><input type=\"text\" name=\"txtPHead\" id=\"txtPHead\" class=\"form-control\" readonly=\"readonly\" value=\"".$role_reult->PHeadName."\"/></td>
      </tr> 
      <tr>
        <td>Parent HeadCode</td>
        <td><input type=\"text\" name=\"txtPHeadCode\" id=\"txtPHeadCode\" class=\"form-control\" readonly=\"readonly\" value=\"".$role_reult->PHeadCode."\"/></td>
      </tr>";
      // <tr>

      //   <td>Head Level</td>
      //   <td>
      //   </td>
      // </tr>
      //  <tr>
      //   <td>Head Type</td>
      //   <td></td>
      // </tr>";
      if($role_reult->HeadLevel > 3 ) {
        $html .= "<tr>
          <td>Note No</td>
          <td><input type=\"text\" name=\"noteNo\" id=\"noteNo\" class=\"form-control\"  value=\"".$role_reult->noteNo."\"/></td>
         </tr>";
       }

       $html .= "<tr>
         <td>&nbsp;</td>
         <td id=\"innerCheck\">"; 
            $html .= "<input type=\"checkbox\" value=\"1\" name=\"IsActive\" id=\"IsActive\" size=\"28\"";
            if($role_reult->IsActive==1){ $html .="checked";}
            $html .= "/><label for=\"IsActive\">&nbsp;IsActive</label> &nbsp;&nbsp; ";           

            if($role_reult->HeadLevel > 3 && ($role_reult->HeadType =="A" || $role_reult->HeadType =="L") ) {
             $html .= "<input type=\"checkbox\" name=\"isFixedAssetSch\" value=\"1\" id=\"isFixedAssetSch\" size=\"28\"  onchange=\"isFixedAssetSch_change('isFixedAssetSch','".$role_reult->HeadType."')\"";
             if($role_reult->isFixedAssetSch==1){ $html .="checked";}
              $html .= "/><label for=\"isFixedAssetSch\">&nbsp;isFixedAssetSchedule</label> &nbsp;&nbsp; ";
            } 
          
            if($role_reult->HeadLevel > 3 && $role_reult->HeadType =="L") {
              $html .= "<input type=\"checkbox\" name=\"isLC\" value=\"1\" id=\"isLC\" size=\"28\"";
              if($role_reult->isLC==1){ $html .="checked";}
               $html .= "/><label for=\"isLC\">&nbsp;isLC</label> &nbsp;&nbsp; ";
               }  
                     
            if($role_reult->HeadLevel > 3 ) {
              if($role_reult->HeadType =="A") {
                $html .= "<input type=\"checkbox\" name=\"isStock\" value=\"1\" id=\isStock\" size=\"28\"  onchange=\"isStock_change()\"";
                if($role_reult->isStock==1){ $html .="checked";}
                $html .= "/><label for=\isStock\">&nbsp;isStock</label> &nbsp;&nbsp; ";
                 $html .= "<br/><input type=\"checkbox\" name=\"isCashNature\" value=\"1\" id=\"isCashNature\" size=\"28\"  onchange=\"isCashNature_change()\"";
             if($role_reult->isCashNature==1){ $html .="checked";}
              $html .= "/><label for=\"isCashNature\">&nbsp;isCashNature</label> &nbsp;&nbsp; ";

              $html .= "<input type=\"checkbox\" name=\"isBankNature\" value=\"1\" id=\"isBankNature\" size=\"28\"  onchange=\"isBankNature_change()\"";
             if($role_reult->isBankNature==1){ $html .="checked";}
              $html .= "/><label for=\"isBankNature\">&nbsp;isBankNature</label> &nbsp;&nbsp; ";
              }

              
              
             $html .= "<input type=\"checkbox\" name=\"isSubType\" value=\"1\" id=\"isSubType\" size=\"28\"  onchange=\"isSubType_change('isSubType')\"";
             if($role_reult->isSubType==1){ $html .="checked";}
              $html .= "/><label for=\"isSubType\">&nbsp;isSubType</label> &nbsp;&nbsp; ";

              
             }
            //  if($role_reult->HeadLevel >= 3 && ($role_reult->PHeadName == "Cash & Cash Equivalent" || $role_reult->PHeadName == "Cash At Bank")) {
             
            // }
            // if($role_reult->HeadLevel >= 3 && ($role_reult->PHeadName == "Cash & Cash Equivalent" || $role_reult->PHeadName == "Cash At Bank")) {
              
            // }

          $html .= "</tr>";  
            if($role_reult->isFixedAssetSch==1){
              if($role_reult->HeadLevel > 0 && $role_reult->HeadType =="A" ) {
               $html .= "<tr id=\"fixedassetCode\">"; 
                $html .= "<td>Fixed Asset Code</td><td><input type=\"text\" name=\"assetCode\" id=\"assetCode\" class=\"form-control\" value=\"".$role_reult->assetCode."\"/></td>";
               $html .= "</tr>";
             } else if($role_reult->HeadLevel > 0 &&  $role_reult->HeadType =="L" ) {
               $html .= "<tr id=\"depreciationCode\"> <td>Depraciation Code</td><td><input type=\"text\" name=\"depCode\" id=\"depCode\" class=\"form-control\" value=\"".$role_reult->depCode."\"/></td></tr>";
             }
            }  else {
               $html .= "<tr id=\"fixedassetCode\"> </tr>";
               $html .= "<tr id=\"depreciationCode\"> </tr>";
            } 
            if($role_reult->isSubType==1){
               $html .= "<tr id=\"subtypeContent\">"; 
               $subdata = $this->bdtaskt1m8c1_01_accModel->getsubTypeData();      
                if($subdata) {
                  $html .="<td>Subtype</td>
                    <td><select  name=\"subtype\" id=\"subtype\" class=\"form-control select2\" />";
                  foreach($subdata as $sub) {
                    $scheck = $sub->id == $role_reult->subType ? 'selected':'';
                    $html .="<option value=\"".$sub->id."\" ". $scheck." >".$sub->subtypeName."</option>";
                  }
                  $html .="</select><br/></td>"; 
               } 
               $html .="</tr>";

            }  else {
               $html .= "<tr id=\"subtypeContent\"> </tr>";
          }            
          $html .= "<tr> <td>&nbsp;</td><td>";
          if( $this->permission->method('chart_of_account','create')->access()):
            if($role_reult->HeadLevel >= 0 ):
              $html .="<input type=\"button\" name=\"btnNew\" id=\"btnNew\" value=\"New\" onClick=\"newdata(".$role_reult->HeadCode.")\" class=\"btn btn-primary\" />
               <input type=\"submit\" name=\"btnSave\" class=\"btn btn-success treesubmit\" id=\"btnSave\" value=\"Save\" disabled=\"disabled\"/>";
            endif;
          endif;
          if($this->permission->method('chart_of_account','update')->access()):
            if($role_reult->HeadLevel >= 0 ):
              $html .=" <input type=\"submit\" name=\"btnUpdate\" id=\"btnUpdate\" value=\"Update\" class=\"btn btn-info treesubmit\"/>";
            endif;
          endif;
          if($this->permission->method('chart_of_account','update')->access()):
            if($role_reult->HeadLevel >= 1 ):
              $html .=" <input type=\"button\" name=\"btnDelete\" id=\"btnDelete\" value=\"Delete\" class=\"btn btn-danger\" onClick=\"deleteCoa(".$role_reult->HeadCode.")\"/>";
            endif;
          endif;
          $html .= "</tr></table>";
          $html .= form_close();
        }
        echo json_encode($html);
    }

    /*--------------------------
    | Add child account code
    *--------------------------*/
    public function bdtaskt1m8c1_03_newForm($id){
        $newdata    = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadCode'=>$id));
        $newidsinfo = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_03_getParentCById($id, $newdata->HeadName);

        if(!empty($newidsinfo->hc)){
            $nid  = $newidsinfo->hc;
            $n =$nid + 1;
            $HeadCode = $n;
        }else{
            $HeadCode = $id . "001";
        }
        $info['headcode']  =  $HeadCode;
        $info['rowdata']   =  $newdata;
        $info['headlabel'] =  $newdata->HeadLevel+1;
        echo json_encode($info);
    }

    /*--------------------------
    | Insert COA
    *--------------------------*/
    public function bdtaskt1m8c1_04_insertCoa(){
        $isact           = $this->request->getVar('IsActive');
        $IsActive        = (!empty($isact)?$isact:0);
        $trns            = $this->request->getVar('IsTransaction');
        $IsTransaction   = 1;//(!empty($trns)?$trns:0);
        $isgl            = $this->request->getVar('IsGL');
        $IsGL            = (!empty($isgl)?$isgl:0);
        $headcode        = $this->request->getVar('txtHeadCode');
        $HeadName        = $this->request->getVar('txtHeadName');
        $PHeadName       = $this->request->getVar('txtPHead');
        $PHeadCode       = $this->request->getVar('txtPHeadCode');
        $HeadLevel       = $this->request->getVar('txtHeadLevel');
        $txtHeadType     = $this->request->getVar('txtHeadType');
        $isact           = $this->request->getVar('IsActive');
        $isLC            = ( $this->request->getVar('isLC')? $this->request->getVar('isLC'):0);
        $IsActive        = (!empty($isact)?$isact:0);
        $stock           = $this->request->getVar('isStock');
        $isStock         = (!empty($stock)?$stock:0); 
        $cashnature      = $this->request->getVar('isCashNature');
        $isCashNature    = (!empty($cashnature)?$cashnature:0);
        $banknature      = $this->request->getVar('isBankNature');
        $isBankNature    = (!empty($banknature)?$banknature:0);
        $fixedassets     = $this->request->getVar('isFixedAssetSch');
        $isFixedAssetSch = (!empty($fixedassets)?$fixedassets:0);
        $isstype         = $this->request->getVar('isSubType');
        $isSubType       = (!empty($isstype)?$isstype:0);
        $createby        = session('id');
        $createdate      = date('Y-m-d H:i:s');
        if($isFixedAssetSch == 1) {
         $assetCode      = $this->request->getVar('assetCode');
         $depCode        = $this->request->getVar('depCode');
        } else {
          $assetCode     = null;
          $depCode       = null;
        }
         if($isSubType == 1) {
         $subtype   = $this->request->getVar('subtype');
        
        } else {
          $subtype   = 1;      
        }
         $noteNo   = (!empty($this->request->getVar('noteNo'))?$this->request->getVar('noteNo'):null);
        
        $postData = array(
            'HeadCode'       => $headcode,
            'HeadName'       => $HeadName,
            'PHeadName'      => $PHeadName,
            'PHeadCode'      => $PHeadCode,
            'HeadLevel'      => $HeadLevel,
            'IsActive'       => $IsActive,
            'isStock'        => $isStock,
            'isLC'           => $isLC,
            'IsTransaction'  => $IsTransaction,
            'isSubType'      => $isSubType,
            'HeadType'       => $txtHeadType,
            'IsBudget'       => 0,     
            'isCashNature'   => $isCashNature,
            'isBankNature'   => $isBankNature,
            'isFixedAssetSch'=> $isFixedAssetSch,
            'assetCode'      => $assetCode,
            'depCode'        => $depCode,
            'subType'        => $subtype,
            'noteNo'         => $noteNo,      
            'CreateBy'       => $createby,
            'CreateDate'     => $createdate,
        ); 

        if(empty($this->request->getVar('txtHeadName'))){
            $this->session->setFlashdata('exception', get_phrases(['please', 'add', 'headname'])); 
            return redirect()->to($_SERVER['HTTP_REFERER']); 
        }
     
        $upinfo    = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_10_checkheadcode($postData['HeadCode']);
        //$checkHead = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadName'=>$postData['HeadName']));
        //if(!empty($checkHead)){
            //$this->session->setFlashdata('exception', get_phrases(['head', 'name', 'already','exists']));
        //}else{
            if($upinfo == 0){
              $check_head  = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_10_checkhead($postData['HeadCode']);
              if($check_head > 0){
                   
                  $info['status']    =  0;
                  $info['message']   =  get_phrases(['headname', 'already', 'exists']);
              
              }
                $ID = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_01_Insert('acc_coa',$postData);
               // print_r($ID);
                // store activity log
                $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['account']), get_phrases(['created']), $ID, 'acc_coa', 1);

                $info['status']    =  1;
                $info['id']        =  $postData['HeadCode'];
                $info['type']      =  1;
                $info['level']     =  $postData['HeadLevel'] + 1;
                $info['HeadName']  =  $postData['HeadName'];
                $info['PHeadCode']  =  $postData['PHeadCode'];
                $info['message']   =  get_phrases(['account', 'created', 'successfully']);
                
            }else{
                $hname =$this->request->getVar('HeadName');
                $updata = array(
                    'PHeadName'      =>  $postData['PHeadName'],
                    'IsActive'       =>  $IsActive,
                    'IsTransaction'  =>  $IsTransaction,
                    'IsGL'           =>  $IsGL
                );
                $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$postData, array('HeadCode'=>$postData['HeadCode']));        
                $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_02_Update('acc_coa',$updata, array('PHeadName'=>$hname)); 
                // store activity log
                $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_22_addActivityLog(get_phrases(['account']), get_phrases(['updated']), $postData['HeadCode'], 'acc_coa', 2);
                $info['status']    =  1;
                $info['id']        =  $postData['HeadCode'];
                $info['type']      =  2;
                $info['level']     =  $postData['HeadLevel'] + 1;
                $info['PHeadCode']  =  $postData['PHeadCode'];
                $info['HeadName']  =  $postData['HeadName'];
                $info['message']   =  get_phrases(['account', 'updated', 'successfully']);

                    
            }

            echo json_encode($info);
          //}
        //return redirect()->to($_SERVER['HTTP_REFERER']);
    }

    /*--------------------------
    | Get patient info by Id
    *--------------------------*/
    public function bdtaskt1m8c1_05_patientInfoById($pid)
    {
        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_05_getPntById($pid);
        echo json_encode($data);
    }

    /*--------------------------
    | Get voucher number by type prefix like DV-, CV- ...
    *--------------------------*/
    public function bdtaskt1m8c1_06_getMaxVoucher($like)
    {
        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_06_getMaxVoucher($like);
        if(!empty($data)){
             $voucher = explode('-', $data->VNo);
            $new = ((int)$voucher[1]+1);
        }else{
            $new = 1;
        }
        echo json_encode(array('id'=>$new));
    }

    /*--------------------------
    | Get credit or debit account headCode
    *--------------------------*/
    public function bdtaskt1m8c1_07_getCreditOrDebitAcc()
    {
        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_07_getCreditOrDebitAcc();
        echo json_encode($data);
    }

    /*--------------------------
    | Get all account headCode
    *--------------------------*/
    public function bdtaskt1m8c1_08_getAllAccount()
    {
        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_08_getAllAccount();
        echo json_encode($data);
    }

     /*--------------------------
    | Get all opening Head headCode
    *--------------------------*/
    public function bdtaskt1m8c1_09_getAllOpeningAccount()
    {

        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_09_geAllopeningHeads();
        echo json_encode($data);
    }

    /*--------------------------
    | Journal voucher form
    *--------------------------*/
    public function bdtaskt1m8c1_09_addOpeningBalance()
    {
        $data['title']      = get_phrases(['add','opening', 'balance']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['fiscalyears']= $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_10_financialyearList();
        $data['isDTables']  = true;
        $data['module']     = "Account";
        $data['page']       = "opening_balance_form";
        return $this->base_01_template->layout($data);
    }

      /*--------------------------
    | Get opening balance list 
    *--------------------------*/
    public function bdtaskt1m8c9_13_getOpeningBalanceList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_09_getOpeningBalanceList($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Save Journal voucher
    *--------------------------*/
    public function bdtaskt1m8c1_10_saveOpeningBalance()
    { 
      $voucher_date     = $this->request->getVar('voucher_date', FILTER_SANITIZE_STRING);
      $account_name     = $this->request->getVar('account_name', FILTER_SANITIZE_STRING);
      $head_code        = $this->request->getVar('head_code', FILTER_SANITIZE_STRING);
      $debit            = $this->request->getVar('debit', FILTER_SANITIZE_STRING);
      $credit           = $this->request->getVar('credit', FILTER_SANITIZE_STRING);
      $sub_type         = $this->request->getVar('sub_type', FILTER_SANITIZE_STRING);
      $sub_code         = $this->request->getVar('sub_code', FILTER_SANITIZE_STRING);
      $fisyearid        = $this->request->getVar('fiscalyear', FILTER_SANITIZE_STRING);
      //$fisyearid        = $this->bdtaskt1m8c1_01_accModel->getActiveFiscalyear();
        $MesTitle = get_phrases(['opening', 'balance']);
        // $list = array(
        //     'VNo'         => $voucher_no,
        //     'Vtype'       => 'OPBL',
        //     'VDate'       => $voucher_date,
        //     'COAID'       => $head_code,
        //     'Narration'   => $remarks,
        //     'Debit'       => $debit,
        //     'Credit'      => $credit,
        //     'BranchID'    => session('branchId'),
        //     'IsPosted'    => 1,
        //     'CreateBy'    => session('id'),
        //     'CreateDate'  => date('Y-m-d H:i:s'),
        //     'IsAppove'    => 0
        // );
        
        // $result = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_01_Insert('acc_transaction',$list);

        $list = array();
        for ($i=0; $i < sizeof($account_name); $i++) { 
            $subcodecount = ($sub_code?sizeof($sub_code):0);
            $subcode      = ($i < $subcodecount?$sub_code[$i]:'');
            $list[$i] = array(
                'fyear'           => $fisyearid,
                'openDate'        => $voucher_date,
                'COAID'           => $head_code[$i],
                'Debit'           => $debit[$i],
                'Credit'          => $credit[$i],
                'subType'         => ($sub_type[$i]?$sub_type[$i]:1),
                'subCode'         => $subcode,
                'CreateBy'        => session('id'),
                'CreateDate'      => date('Y-m-d H:i:s'),
            );
        }
        $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_01_Insert_Batch('acc_opening_balance',$list);
        // Store log data
        $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_22_addActivityLog($MesTitle, get_phrases(['created']), 'openingBalance', 'acc_opening_balance', 1);
        $result = 'success';
        if(!empty($result)){
           
            $response = array(
                'success'  => true,
                'message'  => get_phrases(['created', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  => false,
                'message'  => get_phrases(['please_try_again']),
                'title'    => $MesTitle
            );
        }
        
        echo json_encode($response);
    }

    /*--------------------------
    | Search user name
    *--------------------------*/
    public function bdtaskt1m8c1_11_updatePatientBalance($head, $amount)
    { 
        $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_09_updateBalance($head, $amount);
    }

    /*--------------------------
    | Search user name
    *--------------------------*/
    public function bdtaskt1m8c1_12_test()
    { 
        $this->db = db_connect();
        $userList = $this->db->table('acc_coa')->select('*')->where('IsActive',1)->where('HeadLevel',0)->orderBy('HeadName')->get()->getResult();
        $list[0]['id']    = 1;
        $list[0]['state'] = array('children'=>array('id'=>1, 'opened' => true, 'selected' => false ));
        $list[0]['text']  = 'Chart Of Accounts';
        $i=1;
        foreach ($userList as $value) {
            $list[$i]['id'] = $value->HeadCode;
            $list[$i]['state'] = array('children'=>array('id'=>1, 'opened' => true, 'selected' => false ));
            $list[$i]['text'] = $value->HeadName;
            $i++;
        }

        // echo "<pre>";
        // print_r(json_encode($list));die();

        $test = '[{"id":1,"text":"Root node","children":[{"id":2,"text":"Child node 1","children":[{"state" : {"id":3, "opened" : true, "selected" : true },"text":"Note 3"}]},{"id":4,"text":"Child node 2"},{"id":5,"text":"Child node 5"},{"id":6,"text":"<span>Child node 3</span>"}]}]';
        return $test;exit();
        return json_encode($list);
    }

    public function bdtaskt1m8c1_13_dealer_received_form()
    {
        $data['title']      = get_phrases(['dealer','receive', 'form']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['dealer_list']= $this->bdtaskt1m8c1_01_accModel->dealer_list();
        $data['module']     = "Account";
        $data['page']       = "dealer_receive_form";
        return $this->base_01_template->layout($data);
    }

    public function getsubtype($id=null) {
      $subdata = $this->bdtaskt1m8c1_01_accModel->getsubTypeData($id);
      $html="";
      if($subdata) {
        $html .="<td>Subtype</td>
          <td><select  name=\"subtype\" id=\"subtype\" class=\"form_select\" />";
        foreach($subdata as $sub) {
          $html .="<option value=\"".$sub->id."\">".$sub->subtypeName."</option>";
        }
        $html .="</select><br/></td>";
      }
      echo json_encode($html);
  }

  public function bdtaskt1m8c1_04_deleteCoa($id)
  {
    $MesTitle = get_phrases(['coa', 'record']);


        $info = $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_03_getRow('acc_coa', array('HeadCode'=>$id));

        if(!empty($info)){

            $this->bdtaskt1m8c1_02_CmModel->bdtaskt1m1_06_Deleted('acc_coa', array('HeadCode'=>$id));

            $response = array(
                'success'  =>true,
                'message'  => get_phrases(['deleted', 'successfully']),
                'title'    => $MesTitle
            );
        }else{
            $response = array(
                'success'  =>false,
                'message'  => get_phrases(['something', 'went', 'wrong']),
                'title'    => $MesTitle
            );
        }

        echo json_encode($response);
  }

  public function bdtaskt1m8c1_11_getOpeningDetails($id)
  {
       $data['results']      = $this->bdtaskt1m8c1_01_accModel->bdtaskt1m8_04_getOpenigDetails($id);
       $data['settings_info']= $this->bdtaskt1m8c1_01_accModel->setting_info();
       $details = view('App\Modules\Account\Views\opening_details', $data);
       echo json_encode(array('data'=>$details));
  }

  public function bdtaskt1m8c1_13_checkinfoform()
  {
    $data['title']      = get_phrases(['check', 'info']);
    $data['moduleTitle']= get_phrases(['accounts']);
    $data['module']     = "Account";
    $data['page']       = "checkinfoform";
    return $this->base_01_template->layout($data);
  }

  public function bdtaskt1m8c1_12_checkform()
  {
    $data['title']      = get_phrases(['check', 'print']);
    $data['date']       = date('Ymd', strtotime(($this->request->getVar('date', FILTER_SANITIZE_STRING)?$this->request->getVar('date', FILTER_SANITIZE_STRING):date('Y-m-d'))));
    $data['payto']      = $this->request->getVar('payto', FILTER_SANITIZE_STRING);
    $data['amount']     = $this->request->getVar('amounts', FILTER_SANITIZE_STRING);
    $data['moduleTitle']= get_phrases(['accounts']);
    $data['module']     = "Account";
    $data['page']       = "checkprint";
    return $this->base_01_template->layout($data);
  }



}
