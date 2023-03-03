<?php namespace App\Modules\FixedAssets\Models;
class Fixedasset_model
{
	
	 public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = db_connect();  
        helper(['form','url']);
        $this->request = \Config\Services::request();
    }

    public function findAll_assets()
    {
    $builder = $this->db->table('acc_coa');
	$builder->select("*");
    $builder->where("PHeadName",'FixedAssets');
        $query   = $builder->get(); 
		return $query->getResult();

      
    }

    public function singledata($id){
        $builder = $this->db->table('acc_coa')
                             ->select('*')
                             ->where('HeadCode', $id)
                             ->get()
                             ->getRow(); 
		return $builder;


    }

    public function save_assets($data=[]){
             $assets_name = $data['HeadName'];
             $parent_head = $data['PHeadName'];
            $CreateBy=$this->session->get('id');
            $createdate=date('Y-m-d H:i:s');
            $coa = ($parent_head?$this->childheadcode():$this->headcode());
        

           if($coa->HeadCode!=NULL){
                $headcode=$coa->HeadCode+1;
           }else{
                $headcode=($parent_head?110000100001:1100001);
            }
        // coa head create   
     $coa_info = [
             'HeadCode'         => $headcode,
             'HeadName'         => $assets_name,
             'PHeadName'        => ($parent_head?$parent_head:'FixedAssets'),
             'HeadLevel'        => ($parent_head?3:2),
             'IsActive'         => '1',
             'IsTransaction'    => '1',
             'IsGL'             => '0',
             'HeadType'         => 'A',
             'IsBudget'         => '1',
             'IsDepreciation'   => '1',
             'DepreciationRate' => '1',
             'CreateBy'         => $CreateBy,
             'CreateDate'       => $createdate,
        ];
       
       $acc_coa = $this->db->table('acc_coa');
               $add_assets = $acc_coa->insert($coa_info);
        if($add_assets){
              
           return true;
        }else{
            return false;
        }
    }

    public function check_exist($data=[]){
         $exitstdata = $this->db->table('acc_coa')
                             ->where('HeadName', $data['HeadName'])
                             ->countAllResults(); 
                            
               if($exitstdata > 0){
               
                return true;
               }else{
                return false;
               }              
    }

   

    public function update_assets($data=[]){
      $oldname = $this->request->getVar('old_name');
      $updata = array(
        'HeadName' =>  $data['HeadName'],
      );

      $coa = $this->db->table('acc_coa');   
      $coa->where('HeadName', $oldname);
      $coa->update($updata);
      return true;
     

    }

    public function delete_assets($id){
            $coadlt = $this->db->table('acc_coa');
            $coadlt->where('HeadCode', $id);
            $coadlt->delete();
         if ($this->db->affectedRows()) {
            return true;
        } else {
            return false;
        }

    }


     public function headcode(){
        $query=$this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='3' And HeadCode LIKE '1100%' ORDER BY CreateDate DESC");
        return $query->getRow();
    }

    public function childheadcode(){
        $query=$this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '110000100%' ORDER BY CreateDate DESC");
        return $query->getRow();
    }

   



public function employee_list()
{
        $builder = $this->db->table('hrm_employees');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => 'Select Employee');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->id]=$value->first_name.' '.$value->last_name;
            }
        }
        return $list;  
}







   public function coa_info(){

        $builder = $this->db->table('acc_coa');
        $builder->select('*');
        $builder->where("PHeadName",'FixedAssets');
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => 'Select Assets');
        if(!empty($data)){
            foreach ($data as $value){
             $childs =  $this->db->table('acc_coa')
                        ->select('*')
                        ->where("PHeadName",$value->HeadName)
                        ->get()
                        ->getResult();   
                $list[$value->HeadCode]=$value->HeadName;
                  if(!empty($data)){
            foreach ($childs as $child){
            $list[$child->HeadCode]=$child->HeadName;
            }
        }
            }
        }
        return $list;                   
     }


     public function bank_list()
     {
        $builder = $this->db->table('ah_bank');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => 'Select Bank');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->acc_head]=$value->bank_name;
            }
        }
        return $list;
     }


          public function save_purchase($data = []){
            $voucher_no   =  date('Ymdhis');
            $Vtype        =  "Asset Expense";
            $expense_type =  $this->request->getVar('expense_type');
            $pay_type     =  $this->request->getVar('paytype');
             
            $IsPosted   = 1;
            $IsAppove   = 1;
            $CreateBy   = $this->session->get('id');
            $createdate = date('Y-m-d H:i:s');
            $coinfo = $this->db->table('acc_coa')
                             ->select('HeadName')
                             ->where('HeadCode',$data['asset_code'])
                             ->get()
                             ->getRow();

            $supplier_hcode =   $this->db->table('acc_coa')
                             ->select('HeadCode')
                             ->where('supplier_id',$this->request->getVar('supplier_id'))
                             ->get()
                             ->getRow();               
                            
           $bank_id =  $this->request->getVar('bank_id');
           if($bank_id){
                               
         $coaid = $this->db->table('acc_coa')
                           ->select('HeadCode')
                           ->where('bank_id',$bank_id)
                           ->get()
                           ->getRow()->HeadCode;
  
            }else{
              $coaid = '';
              $bankname ='';
            }
 
         // expense type credit  
     $asset_acc = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  $data['asset_code'],
      'Narration'      =>  $coinfo->HeadName.' Purchase Expense',
      'Debit'          =>  $data['amount'],
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
      $supplier_cr = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  $supplier_hcode->HeadCode,
      'Narration'      =>  $coinfo->HeadName.' Purchase Expense',
      'Debit'          =>  0,
      'Credit'         =>  $data['amount'],
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

       $supplier_dr = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  $supplier_hcode->HeadCode,
      'Narration'      =>  $coinfo->HeadName.' Purchase Expense',
      'Debit'          =>  $data['paid_amount'],
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

      $expense_acc = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  405,
      'Narration'      =>  $coinfo->HeadName.' Purchase ',
      'Debit'          =>  $data['amount'],
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    );
     // bank credit
      $bankexpense = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  $coaid,
      'Narration'      =>  $coinfo->HeadName.' purchase Expense ',
      'Debit'          =>  0,
      'Credit'         =>  $data['paid_amount'],
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    );
      // cash in hand credit
           $cashinhand = array(
      'VNo'            =>  $voucher_no,
      'Vtype'          =>  $Vtype,
      'VDate'          =>  $data['date'],
      'COAID'          =>  1020101,
      'Narration'      =>  $coinfo->HeadName.' Purchase Expense',
      'Debit'          =>  0,
      'Credit'         =>  $data['paid_amount'],
      'IsPosted'       =>  1,
      'CreateBy'       =>  $CreateBy,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

   
          
          $assetpurchase = $this->db->table('assets_purchase_record');
          $assetpurchase->insert($data);

          $assetinsert = $this->db->table('acc_transaction');
          $assetinsert->insert($asset_acc);
           $assetinsert->insert($supplier_cr);
          $transactiontbl = $this->db->table('acc_transaction');
          $transactiontbl->insert($expense_acc);
     
        if($pay_type == 1){
          if($data['paid_amount'] > 0){
            $transactiontbl->insert($cashinhand); 
            $transactiontbl->insert($supplier_dr);  
          }
         
      }else{
        if($data['paid_amount'] > 0){
        $transactiontbl->insert($bankexpense);
         $transactiontbl->insert($supplier_dr); 
         }  
      }
               
    return true;
   }


   public function purchase_list()
   {
    return $list =  $this->db->table('assets_purchase_record a')
                        ->select('a.*,b.HeadName,c.bank_name')
                        ->join('acc_coa b','b.HeadCode = a.asset_code','left')
                        ->join('bank_information c','c.bank_id =a.bank_id','left')
                        ->get()
                        ->getResult();
   }

   public function supplier_list()
   {
     $builder = $this->db->table('wh_supplier_information');
        $builder->select('*');
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => 'Select Supplier');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->acc_head]=$value->nameE;
            }
        }
        return $list;
   }

   public function assets_drowdown()
   {
        $builder = $this->db->table('acc_coa');
        $builder->select('*');
        $builder->where('PHeadName','FixedAssets');
        $query=$builder->get();
        $data=$query->getResult();
        
       $list = array('' => 'Select Assets');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->HeadName]=$value->HeadName;
            }
        }
        return $list;
   }


    public function findAll_childassets($phead)
    {
    $builder = $this->db->table('acc_coa');
    $builder->select("*");
    $builder->where("PHeadName",$phead);
        $query   = $builder->get(); 
        return $query->getResult();

      
    }
}