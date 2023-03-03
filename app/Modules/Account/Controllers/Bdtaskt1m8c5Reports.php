<?php namespace App\Modules\Account\Controllers;
use CodeIgniter\Controller;
use App\Modules\Account\Models\Bdtaskt1m8AccountModel;
use App\Modules\Account\Models\Bdtaskt1m8ReportModel;
use App\Models\Bdtaskt1m1CommonModel;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Bdtaskt1m8c5Reports extends BaseController
{
    private $bdtaskt1m8c5_01_rptModel;
    private $bdtaskt1m8c5_02_CmModel;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bdtaskt1m8c5_01_rptModel = new Bdtaskt1m8ReportModel();
        $this->bdtaskt1m8c5_02_CmModel = new Bdtaskt1m1CommonModel();
    }

    /*--------------------------
    | create a receipt voucher 
    *--------------------------*/
    public function index()
    {
        $data['title']      = get_phrases(['voucher', 'reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "report/voucher_report_form";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | User Wise report form
    *--------------------------*/
    public function bdtaskt1m8c5_01_userReportForm()
    {
        $data['title']      = get_phrases(['user','reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "report/user_wise_report_form";
        return $this->base_01_template->layout($data);
    }



    /*--------------------------
    | Get package services by Id
    *--------------------------*/
    public function bdtaskt1m8c5_02_getVoucherList()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_01_getAllCashbookdata($postData);
        echo json_encode($data); 
    }

    /*--------------------------
    | Get cashbook report
    *--------------------------*/
    public function bdtask021_cash_book()
    {
        $cmbCode             = $this->bdtaskt1m8c5_01_rptModel->get_predefined_head('cashCode');  
        $dtpFromDate         = ($this->request->getVar('dtpFromDate')?$this->request->getVar('dtpFromDate'):'');
        $dtpToDate           = ($this->request->getVar('dtpToDate')?$this->request->getVar('dtpToDate'):''); 
        $HeadName            = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($cmbCode);       
        $pre_balance         = $this->bdtaskt1m8c5_01_rptModel->get_opening_balance($cmbCode,$dtpFromDate,$dtpToDate);
        $HeadName2           = $this->bdtaskt1m8c5_01_rptModel->get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate,1,0);
        $data['dtpFromDate'] = $dtpFromDate;
        $data['dtpToDate']   = $dtpToDate;
        $data['cmbCode']     = $cmbCode;
        $data['HeadName']    = $HeadName;
        $data['ledger']      = $HeadName;
        $data['HeadName2']   = $HeadName2;
        $data['prebalance']  = $pre_balance; 
        $data['setting']     = $this->bdtaskt1m8c5_01_rptModel->settings_data();   
        $data['title']       = get_phrases(['cash','book']);
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['module']      = "Account";
        $data['page']        = "report/cash_book";
        return $this->base_01_template->layout($data);
    }

     /*--------------------------
    | Get bankbook report
    *--------------------------*/
    public function bdtask022_bank_book()
    {
        $cmbCode             = $this->request->getVar('cmbCode');  
        $dtpFromDate         = $this->request->getVar('dtpFromDate');
        $dtpToDate           = $this->request->getVar('dtpToDate'); 
        if($cmbCode){
        $HeadName            = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($cmbCode);       
        $pre_balance         = $this->bdtaskt1m8c5_01_rptModel->get_opening_balance($cmbCode,$dtpFromDate,$dtpToDate);
        $HeadName2           = $this->bdtaskt1m8c5_01_rptModel->get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate,1,0);
        }else{
        $HeadName            = [];       
        $pre_balance         = [];
        $HeadName2           = [];
    }
        $data['banks']       = $this->bdtaskt1m8c5_01_rptModel->get_all_bank();
        $data['dtpFromDate'] = $dtpFromDate;
        $data['dtpToDate']   = $dtpToDate;
        $data['cmbCode']     = $cmbCode;
        $data['HeadName']    = $HeadName;
        $data['ledger']      = $HeadName;
        $data['HeadName2']   = $HeadName2;
        $data['prebalance']  =  $pre_balance;  
        $data['setting']     = $this->bdtaskt1m8c5_01_rptModel->settings_data();   
        $data['title']       = get_phrases(['bank','book']);
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['module']      = "Account";
        $data['page']        = "report/bank_book";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Get package services by Id
    *--------------------------*/
    public function bdtaskt1m8c5_03_detailsTransByVId($id)
    {
        $data = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_02_getTransByVId($id);
        $list = '';
        $debit = 0;
        $credit =0;
        if(!empty($data)){
            foreach ($data as  $value) {
                $debit +=$value->Debit;
                $credit +=$value->Credit;
                $list .= '<tr>
                            <td>'.$value->COAID.'</td>
                            <td>'.$value->HeadName.'</td>
                            <td>'.$value->Narration.'</td>
                            <td class="text-right">'.$value->Debit.'</td>
                            <td class="text-right">'.$value->Credit.'</td>
                            <td>'.date('d/m/Y H:i A', strtotime($value->CreateDate)).'</td>
                            <td>'.$value->short_name.'-'.$value->nameE.'</td>
                        </tr>';
            }
        }
        $response = array(
            'data'   => $list,
            'debit'  => number_format($debit, 2),
            'credit' => number_format($credit, 2)
        );
        echo json_encode($response); 
    }

    /*--------------------------
    | Get package services by Id
    *--------------------------*/
    public function bdtaskt1m8c5_04_getUserWiseReports()
    {
        $branch_id = $this->request->getVar('branch_id');
        $userId = $this->request->getVar('employee_id');
        $range = $this->request->getVar('date_range');
        $date   = explode('-', $range);
        $from   = date('Y-m-d', strtotime(trim($date[0])));
        $to     = date('Y-m-d', strtotime(trim($date[1])));
        $data['setting']    = $this->bdtaskt1m8c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['reports']    = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_03_getUserWiseReports($branch_id, $userId, $from,  $to);
        $data['dates']      = $range;
        $data['empId']     = $userId;
        $data['isDateTimes']= true;
        $data['title']      = get_phrases(['user', 'reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['page']       = "report/user_wise_reports";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Patient by report form
    *--------------------------*/
    public function bdtaskt1m8c5_05_patientReportForm()
    {
        $data['title']      = get_phrases(['patient', 'by', 'reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "report/patient_report_form";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Patient wise reports view
    *--------------------------*/
    public function bdtaskt1m8c5_06_patientByReports()
    {
        $branch_id = $this->request->getVar('branch_id');
        $patientId = $this->request->getVar('patient_id');
        $range     = $this->request->getVar('date_range');
        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['setting']    = $this->bdtaskt1m8c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['reports']    = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_04_getPntByReports($branch_id, $patientId, $from,  $to);
        $data['dates']      = $range;
        $data['pinfo']  = array('patient_id'=>$patientId, 'name'=>$this->request->getVar('pname'));
        $data['isDateTimes']= true;
        $data['title']      = get_phrases(['patient', 'by', 'reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['module']     = "Account";
        $data['page']       = "report/patient_by_reports";
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Trial balance report form 
    *--------------------------*/
    public function bdtaskt1m8c5_07_trialBalanceForm()
    {
        $data['title']      = get_phrases(['trial', 'balance', 'reports']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "report/trial_balance_report_form";
        return $this->base_01_template->layout($data);
    }

    public function bdtaskt1m8c5_08_trialBalanceReport()
      {
        $branch_id = !empty($this->request->getVar('branch_id'))?$this->request->getVar('branch_id'):session('branchId');
        $range     = $this->request->getVar('date_range');
        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $dtpFromDate     =  $from;
        $dtpToDate       =  $to;
        $chkWithOpening  =  1;//$this->request->getVar('chkIsTransction');
        $results         =  $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_05_trial_balance_report($dtpFromDate,$dtpToDate,$chkWithOpening);
        // echo "<pre>";
        // print_r($results);die();
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['oResultTr']   = $results['oResultTr'];
        $data['oResultInEx'] = $results['oResultInEx'];
        $data['dtpFromDate'] = $dtpFromDate;
        $data['dtpToDate']   = $dtpToDate;
        $data['dateRange']   = $range;
        $data['branch_id']   = $branch_id;
        $data['isDTables']   = true;
        $data['title']       = get_phrases(['trial','balance']);
        $data['module']      = "Account";
        if ($results['WithOpening'] == 1) {
            $data['page']    = "report/trial_balance_with_opening"; 
        }else{
            $data['page']    = "report/trial_balance_without_opening"; 
        }
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Balance sheet report form
    *--------------------------*/
    public function bdtaskt1m8c5_09_balanceSheetForm()
    {
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['title']       = get_phrases(['balance','sheet']);
        // $branch_id           = !empty($this->request->getVar('branch_id'))?$this->request->getVar('branch_id'):session('branchId');
        // $range               = $this->request->getVar('date_range');
        // $date                = explode('-', $range);
        // $from                = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:date('Y-m-d'))));
        // $to                  = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:date('Y-m-d'))));
        // $data['isDateTimes'] = true;
        // $data['from_date']   = $from;
        // $data['to_date']     = $to;
        // $data['dateRange']   = $range;
        // $data['branch_id']   = $this->request->getVar('branch_id');
        // $data['curr_Assets']      = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '12', 'CurrentAssets', $from, $to);
        // $data['fixed_assets']     = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '11', 'FixedAssets', $from, $to);
        // $data['curr_liabilities'] = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '22', 'CurrentLiabilities', $from, $to);
        // $data['fix_liabilities']  = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '21', 'FixedLiabilities', $from, $to);
        // $data['long_liabilities'] = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '24', 'Long-term liabilities', $from, $to);
        // $data['other_opponents']  = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '26', 'Other opponents', $from, $to);
        // $data['partner_rights']   = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '23', 'Partners rights', $from, $to);
        // $data['incomes']          = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '3', 'Incoms', $from, $to, true);
        // $data['expenses']         = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_23_getChildBalance($branch_id, '4', 'Expenses', $from, $to);
   $dtpFromDate            = (!empty($this->request->getVar('dtpFromDate'))?$this->request->getVar('dtpFromDate'):date('Y-m-01'));
    //$this->session->userdata('fyearEndDate')
   $dtpToDate              = (!empty($this->request->getVar('dtpToDate'))?$this->request->getVar('dtpToDate'):date('Y-m-d'));
   $data['dtpFromDate']    = $dtpFromDate;
   $data['dtpToDate']      = $dtpToDate;
   $numprefyear   = $this->bdtaskt1m8c5_01_rptModel->getnumPreviousFyear();
   $displaynumber = ($numprefyear >=3?3:$numprefyear);
   $data['count_previous_fyear'] = $displaynumber;
   $data['active_fyear']   = $this->bdtaskt1m8c5_01_rptModel->getActiveFinancialyear(); 
   $data['financialyears'] = $this->bdtaskt1m8c5_01_rptModel->get_previous_financial_year($displaynumber);
   $data['assets']         = $this->bdtaskt1m8c5_01_rptModel->get_balancedheet_summery('A','Assets',$dtpFromDate,$dtpToDate,$displaynumber);
   $data['liabilities']    = $this->bdtaskt1m8c5_01_rptModel->get_balancedheet_summery('L','Liabilities',$dtpFromDate,$dtpToDate,$displaynumber);
   $data['equitys']        = $this->bdtaskt1m8c5_01_rptModel->get_balancedheet_summery('L','Shareholder\'s Equity',$dtpFromDate,$dtpToDate,$displaynumber);

   $data['incomes'] = $this->bdtaskt1m8c5_01_rptModel->get_head_summery('I','Income',$dtpFromDate,$dtpToDate,0);
   $data['expenses'] = $this->bdtaskt1m8c5_01_rptModel->get_head_summery('E','Expenses',$dtpFromDate,$dtpToDate,0);
        $data['module']      = "Account";
        $data['page']        = "report/balance_sheet"; 
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Cash Flow Report Form
    *--------------------------*/
    public function bdtaskt1m8c5_10_cashFlowForm()
    {
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['title']       = get_phrases(['cash','flow', 'statement']);
        $data['isDateTimes']= true;
        $data['module']      = "Account";
        $data['page']        = "report/cash_flow_form"; 
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | Cash Flow Reports
    *--------------------------*/
    public function bdtaskt1m8c5_11_cashFlowResports()
    {
        $data['moduleTitle'] = get_phrases(['accounts']);
        $data['title']       = get_phrases(['cash','flow', 'statement']);
        $branch_id           = !empty($this->request->getVar('branch_id'))?$this->request->getVar('branch_id'):session('branchId');
        $range     = $this->request->getVar('date_range');
        $date      = explode('-', $range);
        $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:date('Y-m-d'))));
        $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:date('Y-m-d'))));
        $data['dtpFromDate']  = $from;
        $data['dtpToDate']    = $to;
        $data['dateRange']   = $range;
        $data['branch_id']   = $this->request->getVar('branch_id');
        $data['setting']    = $this->bdtaskt1m8c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        // echo "<pre>";
        // print_r($data['incomes']);die(); 
        $data['module']      = "Account";
        $data['page']        = "report/cash_flow_reports"; 
        return $this->base_01_template->layout($data);
    }

    /*--------------------------
    | View Transactions
    *--------------------------*/
    public function bdtaskt1m8c5_12_viewTransByIds()
    { 
        $ids      = explode('-', $this->request->getVar('ids'));
        $data['results']   = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_17_getTransByIds($ids);
        // echo "<pre>";
        // print_r($data['results']);die();
        $trans = view('App\Modules\Account\Views\report\view_transactions', $data);
        echo json_encode(array('data'=>$trans));
    }

    /*--------------------------
    | Trial balance report form
    *--------------------------*/
    public function bdtaskt1m8c5_13_GeneralLForm()
    {
        $data['title']      = get_phrases(['general', 'ledger', 'form']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['general_ledger']= $this->bdtaskt1m8c5_01_rptModel->get_general_ledger(); 
        $data['module']     = "Account";
        $data['page']       = "report/gl_report_form";
        return $this->base_01_template->layout($data);
    }
    /*--------------------------
    | General Ledger Report search
    *--------------------------*/

    public function accounts_general_ledgerreport_search(){      
            $cmbCode       = $this->request->getVar('cmbCode');
            $dtpFromDate   = $this->request->getVar('dtpFromDate');
            $dtpToDate     = $this->request->getVar('dtpToDate');          
            $HeadName      = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($cmbCode);       
            $pre_balance   = $this->bdtaskt1m8c5_01_rptModel->get_opening_balance($cmbCode,$dtpFromDate,$dtpToDate);
            $HeadName2     = $this->bdtaskt1m8c5_01_rptModel->get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate,1);
            $data['title']         = get_phrases(['general', 'ledger', 'report']);
            $data['moduleTitle']   = get_phrases(['accounts']);
            $data['dtpFromDate']   = $dtpFromDate;
            $data['dtpToDate']     = $dtpToDate;
            $data['cmbCode']       = $cmbCode;
            $data['HeadName']      = $HeadName;
            $data['ledger']        = $HeadName;
            $data['HeadName2']     = $HeadName2;
            $data['prebalance']    =  $pre_balance;
            $data['general_ledger']= $this->bdtaskt1m8c5_01_rptModel->get_general_ledger(); 
            $data['module']        = "Account";
            $data['page']          = "report/gl_new_report";
            return $this->base_01_template->layout($data);
    
        }

    public function bdtaskt1m8c5_14_GeneralLedgerReport()
      {
        $branch_id       = $this->request->getVar('branch_id');
        $range           = $this->request->getVar('date_range');
        $cmbGLCode       = $this->request->getVar('cmbGLCode');
        $cmbCode         = $this->request->getVar('cmbCode');
        $chkIsTransction = $this->request->getVar('chkIsTransction');
        if(!empty($cmbCode)){
            $head_code = $cmbCode;
        }else{
            $head_code = $cmbGLCode;
        }
        $rules = [
            'cmbGLCode'      => ['label' => get_phrases(['general','head']),'rules'     => 'required'],
            'date_range'     => ['label' => get_phrases(['date', 'range']),'rules'       => 'required'],
        ];

        if (! $this->validate($rules)) {
          $this->session->setFlashdata('exception', $this->validator->listErrors());
         return  redirect()->to('GeneralLForm');
        }else{
            $date      = explode('-', $range);
            $from      = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
            $to        = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
            $dtpFromDate     =  $from;
            $dtpToDate       =  $to;

            $HeadName2       = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname2($head_code,$dtpFromDate,$dtpToDate,$chkIsTransction, $branch_id);
            $pre_balance     = $this->bdtaskt1m8c5_01_rptModel->general_led_report_prebalance($head_code,$dtpFromDate, $branch_id);

            $data = array(
                'title'          => get_phrases(['general',' ledger','reports']),
                'dtpFromDate'    => $dtpFromDate,
                'dtpToDate'      => $dtpToDate,
                'HeadName2'      => $HeadName2,
                'prebalance'     => $pre_balance,
                'chkIsTransction'=> $chkIsTransction,
                'dateRange'      => $range
            );
            // echo "<pre>";
            // print_r($HeadName2);die();
            $data['ledger']  = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($head_code);
            // echo "<pre>";
            // print_r($results);die(); 
            $data['moduleTitle']  = get_phrases(['accounts']);
            $data['module']     = "Account";
            $data['page']    = "report/general_ledger_report"; 
            return $this->base_01_template->layout($data);
        }
    }
    
    /*--------------------------
    | Get Geanral ledger head
    *--------------------------*/
    public function bdtaskt1m8c5_15_getGLHeadList()
    {
        $data = $this->bdtaskt1m8c5_01_rptModel->get_general_ledger_head();
        echo json_encode($data); 
    }

    /*--------------------------
    | Get Geanral ledger Trans head
    *--------------------------*/
    public function bdtaskt1m8c5_16_getGLTransList($head)
    {
        $data = $this->bdtaskt1m8c5_01_rptModel->get_gl_trans_head($head);
        echo json_encode($data); 
    }

    /*--------------------------
    | Trial balance report form
    *--------------------------*/
    public function bdtaskt1m8c5_17_profitLossForm()
    {
        $data['title']      = get_phrases(['profit', 'loss', 'report']);
        $data['moduleTitle']= get_phrases(['accounts']);
        $data['isDateTimes']= true;
        $data['module']     = "Account";
        $data['page']       = "report/profit_loss_form";
        return $this->base_01_template->layout($data);
    }

    public function bdtaskt1m8c5_19_profitLossReoprtSearch()
    {
        $dtpFromDate = $this->request->getVar('dtpFromDate');
        $dtpToDate   = $this->request->getVar('dtpToDate');

        $data['incomes']           = $this->bdtaskt1m8c5_01_rptModel->get_head_summery('I','Income',$dtpFromDate,$dtpToDate,0);
        $data['expenses']          = $this->bdtaskt1m8c5_01_rptModel->get_head_summery('E','Expenses',$dtpFromDate,$dtpToDate,0);
        $get_profit                = $this->bdtaskt1m8c5_01_rptModel->profit_loss_serach();
        $data['oResultAsset']      = $get_profit['oResultAsset'];
        $data['oResultLiability']  = $get_profit['oResultLiability'];
        $data['dtpFromDate']       = $dtpFromDate;
        $data['dtpToDate']         = $dtpToDate;
        $data['title']             = get_phrases(['profit', 'loss', 'report']);
        $data['moduleTitle']       = get_phrases(['accounts']);
        $data['module']            = "Account";
        $data['page']              = "report/profit_loss_report";
        return $this->base_01_template->layout($data);
    }

    public function bdtaskt1m8c5_18_profitLossReoprt()
      {
        $branch_id          = $this->request->getVar('branch_id');
        $range              = $this->request->getVar('date_range');
        $date               = explode('-', $range);
        $from               = date('Y-m-d', strtotime(trim(!empty($date[0])?$date[0]:'')));
        $to                 = date('Y-m-d', strtotime(trim(!empty($date[1])?$date[1]:'')));
        $data['from_date']= $from;
        $data['to_date']  = $to;
        $data['dateRange']  = $range;
        //$data['setting']    = $this->bdtaskt1m8c5_02_CmModel->bdtaskt1m1_03_getRow('setting');
        $data['incomes']     = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_22_getIncomeChildInfo($branch_id, $from, $to);
        $data['expenses']    = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_21_getExpenseChildInfo($branch_id, $from, $to);
        
        $profitloss = view('App\Modules\Account\Views\report\profit_loss_report_new', $data);
        echo json_encode(array('data'=>$profitloss));
        exit();
    }

    /*--------------------------
    | Get Geanral ledger Trans head
    *--------------------------*/
    public function bdtaskt1m8c5_19_getTransHead()
    {
        $data = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_18_getTransHead();
        echo json_encode($data); 
    }

    /*--------------------------
    | View Transactions
    *--------------------------*/
    public function bdtaskt1m8c5_20_viewTrialBalanceDetails()
    { 
        $branch_id  = !empty($this->request->getVar('branch_id'))?$this->request->getVar('branch_id'):session('branchId');
        $from  = $this->request->getVar('from_date');
        $to  = $this->request->getVar('to_date');
        $parent_head  = $this->request->getVar('parent_head');
        $data['results']   = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_19_getTrialDetails($parent_head, $from, $to, $branch_id);
        // echo "<pre>";
        // print_r($data['results']);die();
        $trans = view('App\Modules\Account\Views\report\trial_balance_details', $data);
        echo json_encode(array('data'=>$trans));
    }

    /*--------------------------
    | Export trial balance
    *--------------------------*/
    public function bdtaskt1m8c5_21_exportTrialBalance()
    { 
        $branch_id  = !empty($this->request->getVar('branch_id'))?$this->request->getVar('branch_id'):session('branchId');
        $from  = $this->request->getVar('from_date');
        $to  = $this->request->getVar('to_date');
        $exportData   = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_20_exportTrialBalance($branch_id, $from, $to);
        // echo "<pre>";
        // print_r($exportData);die();
        // create file name
        $fileName = 'Trial-Balance-Reports-'.$from.'-to-'.$to.'.xlsx';  
        // load excel library
        $spreadsheet = new Spreadsheet();
 
        $sheet = $spreadsheet->getActiveSheet();
        // set Header
        $sheet->SetCellValue('A1', 'Account Number');
        $sheet->SetCellValue('B1', 'Account Name');
        $sheet->SetCellValue('C1', 'Opening Balance');
        $sheet->SetCellValue('D1', 'Debit');
        $sheet->SetCellValue('E1', 'Credit'); 
        $sheet->SetCellValue('F1', 'Closing');        

        $TotalOpening=0;
        $TotalCredit=0;
        $TotalDebit=0;  
        $TotalClosing=0;  
        // set Row
        $rowCount = 2;
        foreach ($exportData['AL'] as $value) 
        {
            $total = !empty($value->balance->total)?$value->balance->total:0;
            $TotalOpening += $total;
            if($value->resultAL->Debit > $value->resultAL->Credit){
                $debit = $value->resultAL->Debit - $value->resultAL->Credit;
                $credit = 0;
                $TotalDebit += $debit;
            }else{
                $debit = 0;
                $credit = $value->resultAL->Credit - $value->resultAL->Debit;
                $TotalCredit += $credit;
            }
            $closing = ($total + $value->resultAL->Debit) - $value->resultAL->Credit;
            $TotalClosing += $closing;
            
            $sheet->SetCellValue('A' . $rowCount, $value->HeadCode);
            $sheet->SetCellValue('B' . $rowCount, $value->HeadName);
            $sheet->SetCellValue('C' . $rowCount, $total);
            $sheet->SetCellValue('D' . $rowCount, $debit);
            $sheet->SetCellValue('E' . $rowCount, $credit);
            $sheet->SetCellValue('F' . $rowCount, $closing);
           
            $rowCount++;
        }

        $rowCount1 = $rowCount;
        foreach ($exportData['IE'] as $value) 
        {
            $total = !empty($value->balance->total)?$value->balance->total:0;
            $TotalOpening += $total;
            if($value->resultAL->Debit > $value->resultAL->Credit){
                $debit = $value->resultAL->Debit - $value->resultAL->Credit;
                $credit = 0;
                $TotalDebit += $debit;
            }else{
                $debit = 0;
                $credit = $value->resultAL->Credit - $value->resultAL->Debit;
                $TotalCredit += $credit;
            }
            $closing = ($total + $value->resultAL->Debit) - $value->resultAL->Credit;
            $TotalClosing += $closing;
            
            $sheet->SetCellValue('A' . $rowCount1, $value->HeadCode);
            $sheet->SetCellValue('B' . $rowCount1, $value->HeadName);
            $sheet->SetCellValue('C' . $rowCount1, $total);
            $sheet->SetCellValue('D' . $rowCount1, $debit);
            $sheet->SetCellValue('E' . $rowCount1, $credit);
            $sheet->SetCellValue('F' . $rowCount1, $closing);
           
            $rowCount1++;
        }
        $ProfitLoss=$TotalDebit-$TotalCredit;
        if($ProfitLoss < 0){
            $TotalDebit += $ProfitLoss;
            $sheet->SetCellValue('A' . $rowCount1, '');
            $sheet->SetCellValue('B' . $rowCount1, '');
            $sheet->SetCellValue('C' . $rowCount1, 'Profit-Loss');
            $sheet->SetCellValue('D' . $rowCount1, abs($ProfitLoss));
            $sheet->SetCellValue('E' . $rowCount1, '0.00');
            $sheet->SetCellValue('F' . $rowCount1, '');
        }else{
            $TotalCredit += $ProfitLoss;
            $sheet->SetCellValue('A' . $rowCount1, '');
            $sheet->SetCellValue('B' . $rowCount1, '');
            $sheet->SetCellValue('C' . $rowCount1, 'Profit-Loss');
            $sheet->SetCellValue('D' . $rowCount1, '0.00');
            $sheet->SetCellValue('E' . $rowCount1, abs($ProfitLoss));
            $sheet->SetCellValue('F' . $rowCount1, '');
        }
        $rowCount2 = $rowCount1+1;
        $sheet->SetCellValue('A' . $rowCount2, '');
        $sheet->SetCellValue('B' . $rowCount2, 'Total');
        $sheet->SetCellValue('C' . $rowCount2, $TotalOpening);
        $sheet->SetCellValue('D' . $rowCount2, $TotalDebit);
        $sheet->SetCellValue('E' . $rowCount2, $TotalCredit);
        $sheet->SetCellValue('F' . $rowCount2, $TotalClosing);
        
        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="'.$fileName.'"');

        // Write file to the browser
        $writer->save('php://output');
    }

    /*--------------------------
    | Get trial balance data
    *--------------------------*/
    public function bdtaskt1m8c5_22_trialBalanceData()
    {
        $postData = $this->request->getVar();
        $data = $this->bdtaskt1m8c5_01_rptModel->bdtaskt1m8_21_getTrialBalanceData($postData);
        //print_r($data);exit(); 
        echo json_encode($data); 
    }

    //new part
     public function general_ledger_non_transactional()
    {
     
        //$this->permission->check_label('non_transactional_general_ledger','read')->redirect(); 
        $cmbGLCode       = $this->request->getVar('cmbGLCode');
        $cmbCode         = $this->request->getVar('cmbCode');
        $dtpFromDate     = ($this->request->getVar('dtpFromDate')?$this->request->getVar('dtpFromDate'):'');
        $dtpToDate       = ($this->request->getVar('dtpToDate')?$this->request->getVar('dtpToDate'):'');
        $chkIsTransction = $this->request->getVar('chkIsTransction');
        if($cmbGLCode){
          $HeadName      = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($cmbGLCode);
       $HeadName2        = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname2_nontransactional($cmbGLCode,$dtpFromDate,$dtpToDate);
        $pre_balance     = $this->bdtaskt1m8c5_01_rptModel->general_led_report_prebalance_nontransactional($cmbGLCode,$dtpFromDate); 
      }else{
         $HeadName       = '';
        $HeadName2       = '';
        $pre_balance     = '';
      }
      
        $data = array(
            'dtpFromDate'    => $dtpFromDate,
            'dtpToDate'      => $dtpToDate,
            'HeadName'       => $HeadName,
            'HeadName2'      => $HeadName2,
            'prebalance'     => $pre_balance,
            'chkIsTransction'=> 1,
            'glcode'         => $cmbGLCode,

        );
    
        $data['general_ledger'] = $this->bdtaskt1m8c5_01_rptModel->non_transactional_head();
        $data['ledger']         = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($cmbGLCode);
        $data['moduleTitle']    = get_phrases(['accounts']);
        $data['title']          = get_phrases(['non','transactional','general','ledger']);
        $data['module']         = "Account";
        $data['page']           = "report/non_transactional_general_ledger_report"; 
        return $this->base_01_template->layout($data);
     
    }

    public function bdtask_124subLedger()
    {
        $general_ledger         = $this->bdtaskt1m8c5_01_rptModel->getsubTypeDatahasSubcode(); 
       
        $data['general_ledger'] = $general_ledger;
        $data['moduleTitle']    = get_phrases(['accounts']);
        $data['title']          = get_phrases(['sub','ledger']);
        $data['module']         = "Account";
        $data['page']           = "report/sub_ledger"; 
        return $this->base_01_template->layout($data);
    }

    public function getSubcode($id){
        $htm ='';     
           $subcodes = $this->bdtaskt1m8c5_01_rptModel->getSubcode($id);
           $htm .='<option value="all" >All</option>';
            if($subcodes) {
              foreach($subcodes as $sc) {
                 $htm .='<option value="'.$sc->id.'" >'.$sc->name.'</option>';
              }
            }            
         echo json_encode($htm);
     }

     public function getSubAccountHeads($id)
     {
        $htm ='';     
        $subcodes = $this->bdtaskt1m8c5_01_rptModel->get_account_head_by_subtype($id);
           if($subcodes) {
              foreach($subcodes as $sc) {
                 $htm .='<option value="'.$sc->HeadCode.'" >'.$sc->HeadName.'</option>';
              }
           }  
       echo json_encode($htm);
     }

     public function bdtask_125subLedger_report()
     {
        $subtype                = $this->request->getVar('subtype');
        $subcode                = $this->request->getVar('subcode');
        $accounthead            = $this->request->getVar('accounthead');
        $dtpFromDate            = $this->request->getVar('dtpFromDate');
        $dtpToDate              = $this->request->getVar('dtpToDate'); 
        $subLedger              = $this->bdtaskt1m8c5_01_rptModel->get_subcode_byid($subcode);
        $HeadName               = $this->bdtaskt1m8c5_01_rptModel->general_led_report_headname($accounthead);
        $pre_balance            = $this->bdtaskt1m8c5_01_rptModel->get_opening_balance_subtype($accounthead,$dtpFromDate,$dtpToDate,$subtype,$subcode);
        $HeadName2              = $this->bdtaskt1m8c5_01_rptModel->get_general_ledger_report_subhead($accounthead,$dtpFromDate,$dtpToDate,1,0,$subtype,$subcode);
        $data['dtpFromDate']    = $dtpFromDate;
        $data['dtpToDate']      = $dtpToDate;
        $data['subtype']        = $subtype;
        $data['subcode']        = $subcode;
        $data['accounthead']    = $accounthead;
        $data['ledger']         = $HeadName;
        $data['subLedger']      = $subLedger;
        $data['HeadName2']      = $HeadName2;
        $data['prebalance']     = $pre_balance;   
        $data['setting']        = $this->bdtaskt1m8c5_01_rptModel->settings_data();     
        $data['general_ledger'] = $this->bdtaskt1m8c5_01_rptModel->getsubTypeDatahasSubcode(); 
        $data['subcodes']       = $this->bdtaskt1m8c5_01_rptModel->get_subTypeItems($subtype);
        $data['acchead']        = $this->bdtaskt1m8c5_01_rptModel->get_account_head_by_subtype($subtype);
        $data['moduleTitle']    = get_phrases(['accounts']);
        $data['title']          = get_phrases(['sub','ledger']);
        $data['module']         = "Account";
        $data['page']           = "report/sub_ledger_report"; 
        return $this->base_01_template->layout($data);
     }
    
}