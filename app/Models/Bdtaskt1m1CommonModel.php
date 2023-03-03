<?php

namespace App\Models;

use CodeIgniter\Model;

class Bdtaskt1m1CommonModel extends Model
{

  public function __construct()
  {
    $this->db = db_connect();
    //$this->db->simpleQuery("SET GLOBAL time_zone = '+3:00'");
  }

  //====>$table=string, $data=array(), $where = array()<=== 

  /*--------------------------
    | Inserted data return last Id
    *--------------------------*/
  public function bdtaskt1m1_01_Insert($table, $data = [])
  {
    if (!empty($table) && !empty($data)) {

      if ($table == 'acc_transaction' && !empty($data['IsAppove'])) {
        $data['opening_balance'] = $this->bdtaskt1m1_24_getCurrentBalance($data['COAID'], $data['BranchID']);
        $this->updateCoaBalance($data);
      }
      $query = $this->db->table($table)->insert($data);

      if ($this->db->affectedRows() > 0) {
        return $this->db->insertId();
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  // update coa balance
  public function updateCoaBalance($data)
  {
    $COAID = $data['COAID'];
    $Credit = $data['Credit'];
    $Debit = $data['Debit'];
    $BranchID = $data['BranchID'];

    $where = array('headCode' => $COAID, 'branch_id' => $BranchID);

    if ($Debit > 0) {
      // balance debited
      $this->db->table('acc_coa_balance')->set('balance', 'balance-' . $Debit, FALSE)->set('total_debit', 'total_debit+' . $Debit, FALSE)->where($where)->update();
    }
    if ($Credit > 0) {
      // balance credited
      $this->db->table('acc_coa_balance')->set('balance', 'balance+' . $Credit, FALSE)->set('total_credit', 'total_credit+' . $Credit, FALSE)->where($where)->update();
    }
    if ($this->db->affectedRows() == 0) {
      $data2 = array(
        'branch_id'    => $BranchID,
        'headCode'     => $COAID,
        'total_debit'  => $Debit,
        'total_credit' => $Credit,
        'balance'      => $Credit - $Debit,
      );
      $this->db->table('acc_coa_balance')->insert($data2);
    }
  }

  /*--------------------------
    | Updated data 
    *--------------------------*/
  public function bdtaskt1m1_02_Update($table, $data = [], $where = [])
  {
    if (!empty($table) && !empty($data)) {
      //branch checking
      // if( session('branchId') =='' || session('branchId') == 0 ){
      //   return false;
      // }
      // if ($this->db->fieldExists('branch_id', $table) && empty($data['branch_id'])) {
      //   $data['branch_id'] = session('branchId');
      // }
      //end
      return $this->db->table($table)->where($where)->update($data);
    } else {
      return false;
    }
  }

  public function bdtaskt1m1_02_UpdateSet($table, $column, $value, $where = [])
  {
    if (!empty($table) && !empty($column)) {
      return $this->db->table($table)->set($column, $column . '+' . $value, FALSE)->where($where)->update();
    } else {
      return false;
    }
  }

  /* Batch Insert of data */
  public function bdtaskt1m1_01_Insert_Batch($table, $data = [])
  {
    if (!empty($table) && !empty($data)) {
      //branch checking
      // if( session('branchId') =='' || session('branchId') == 0 ){
      //   return false;
      // }
      // if( $this->db->fieldExists('branch_id', $table) ){
      //   foreach($data as $key => $value){
      //     $data[$key]['branch_id'] = (empty($data[$key]['branch_id']))?session('branchId'):$data[$key]['branch_id'];            
      //   }
      // }
      //end
      return $this->db->table($table)->insertBatch($data);
    } else {
      return false;
    }
  }

  /* Batch update of data */
  public function bdtaskt1m1_02_Update_Batch($table, $data = [], $column)
  {
    if (!empty($table) && !empty($data)) {
      //branch checking
      // if( session('branchId') =='' || session('branchId') == 0 ){
      //   return false;
      // }
      // if( $this->db->fieldExists('branch_id', $table) ){
      //   foreach($data as $key => $value){
      //     $data[$key]['branch_id'] = (empty($data[$key]['branch_id']))?session('branchId'):$data[$key]['branch_id'];      
      //   }
      // }
      //end
      return $this->db->table($table)->updateBatch($data, $column);
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get single row data
    *--------------------------*/
  public function bdtaskt1m1_03_getRow($table, $where = [])
  {
    if (!empty($table)) {
      if (!empty($where)) {
        $builder = $this->db->table($table);
        $builder->where($where);
        return $builder->get()->getRow();
      } else {
        return $this->db->table($table)->get()->getRow();
      }
    } else {
      return false;
    }
  }

  public function bdtaskt1m1_03_getRowArray($table, $where = [])
  {
    if (!empty($table)) {
      if (!empty($where)) {
        $builder = $this->db->table($table);
        $builder->where($where);
        return $builder->get()->getRowArray();
      } else {
        return $this->db->table($table)->get()->getRowArray();
      }
    } else {
      return false;
    }
  }

  public function bdtaskt1m1_03_getSumRow($table, $select, $where = [])
  {
    if (!empty($table)) {
      if (!empty($where)) {
        $builder = $this->db->table($table);
        $builder->select('SUM(' . $select . ') as ' . $select . '');
        $builder->where($where);
        return $builder->get()->getRow();
      } else {
        return $this->db->table($table)->get()->getRow();
      }
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get all rows data
    *--------------------------*/
  public function bdtaskt1m1_04_getResult($table)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Inserted data
    *--------------------------*/
  public function bdtaskt1m1_05_getResultWhere($table, $where = null, $where_in = null, $where_not_in = null, $orderBy = null)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);

      if ($where != null) {
        $builder->where($where);
      }
      if ($where_in != null) {
        $builder->whereIn($where_in['field'], $where_in['values']);
      }
      if ($where_not_in != null) {
        $builder->whereNotIn($where_not_in['field'], $where_not_in['values']);
      }
      if ($orderBy != null) {
        // ->orderBy('id', 'desc')
        $builder->orderBy($orderBy[0], $orderBy[1]);
      }

      $result = $builder->get()->getResult();

      return $result;
    } else {
      return false;
    }
  }

  /*--------------------------
    | get data with where() and orWhere()
    *--------------------------*/
  public function bdtaskt1m1_21_getResultWhereOR($table, $where, $orWhere)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);
      $builder->groupStart();
      $builder->where($where);
      $builder->groupEnd();
      $builder->orGroupStart();
      $builder->where($orWhere);
      $builder->groupEnd();
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /* get result with where and like */

  public function bdtaskt1m1_25_getResultWhereLike($table, $where, $like)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);
      $builder->where($where);
      $builder->like($like);
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Deleted data
    *--------------------------*/
  public function bdtaskt1m1_06_Deleted($table, $where)
  {
    if (!empty($table)) {
      //end
      return $this->db->table($table)->where($where)->delete();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get Select2 data
    *--------------------------*/
  public function bdtaskt1m1_07_getSelect2Data($table, $where, $column, $find_in_set = null)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);
      $builder->select($column);
      if ($where) {
        $builder->where($where);
      }
      if (!empty($find_in_set)) {
        $builder->where($find_in_set);
      }
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search doctor data
    *--------------------------*/
  public function bdtaskt1m1_08_searchDoctor($text, $langColumn)
  {
    if (!empty($text)) {
      $query = $this->db->table('employees');
      $query->select("emp_id as id, CONCAT_WS(' ', short_name, '-', $langColumn) as text");
      $query->where('job_title_id', 14);
      $query->groupStart();
      $query->like('nameE', $text);
      $query->orLike('nameA', $text);
      $query->orLike('mobile', $text);
      $query->orLike('short_name', $text);
      $query->groupEnd();
      return $query->get()->getResult();
      //echo get_last_query();exit;
    } else {
      return false;
    }
  }

  /*--------------------------
    | search employee data
    *--------------------------*/
  public function bdtaskt1m1_09_searchEmployee($text, $langColumn=null)
  {
    if (!empty($text)) {
      $query = $this->db->table('hrm_employees');
      $query->select("employee_id as id, CONCAT_WS(' ',  first_name,last_name) as text");
      $query->where('status', 1);
      $query->groupStart();
      $query->like('first_name', $text);
      $query->orLike('last_name', $text);
      $query->orLike('phone', $text);
      $query->groupEnd();
      return $query->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search patient data
    *--------------------------*/
  public function  bdtaskt1m1_10_searchPatient($text, $langColumn)
  {
    if (!empty($text)) {
      $builder = $this->db->table('patient p')->select("p.patient_id as id, CONCAT_WS(' ', p.patient_id, '-', p.$langColumn, IF(p.mobile IS NULL OR p.mobile='', '', CONCAT('M-', p.mobile)), IF(f.patient_id IS NULL, '', CONCAT('F-', f.file_no)) ) as text");
      $builder->join("patient_file f", "f.patient_id=p.patient_id", "left");
      $builder->where('p.status', 1);
      $builder->groupStart();
      $builder->like('p.nameE', $text);
      $builder->orLike('p.nameA', $text);
      if (strlen($text) > 5 && is_numeric($text)) {
        $builder->orLike('p.mobile', $text);
      }
      if (is_numeric($text)) {
        $builder->orWhere('f.file_no', $text);
      }
      $builder->groupEnd();
      $query = $builder->get();

      return $query->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search services data
    *--------------------------*/
  public function bdtaskt1m1_11_searchDocServices($text, $docId, $langColumn)
  {
    if (!empty($text) && !empty($docId)) {
      $builder = $this->db->table('doctor_services ds')
        ->select("s.id, CONCAT(s.code_no, ' - ', s.$langColumn) as text")
        ->join("services s", "s.id=ds.service_id", "left")
        ->where('ds.doctor_id', $docId)
        ->groupStart()
        ->like('s.nameE', $text)
        ->orLike('s.nameA', $text)
        ->orLike('s.code_no', $text)
        ->groupEnd();
      $data = $builder->get()->getResult();
      //echo get_last_query();exit;
      return $data;
    } else {
      return false;
    }
  }

  /*--------------------------
    | search medicine data
    *--------------------------*/
  public function bdtaskt1m1_12_searchMedicine($text, $langColumn)
  {
    if (!empty($text)) {
      return $this->db->table('ph_items')->select("id, CONCAT_WS(' ', id, '-', $langColumn) as text")->like('nameE', $text)->orLike('nameA', $text)->orLike('item_code', $text)->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search appointment data
    *--------------------------*/
  public function bdtaskt1m1_12_searchAppoint($text, $langColumn)
  {
    if (!empty($text)) {
      $builder = $this->db->table('appointment app');
      $builder->select("app.appoint_id as id, CONCAT(app.appoint_id, ' - ', file.file_no, ' ', p.$langColumn,' ', p.mobile) as text");
      $builder->join("patient p", "p.patient_id=app.patient_id", "left");
      $builder->join("patient_file file", "file.patient_id=app.patient_id", "left");
      $builder->like('app.appoint_id', $text);
      $builder->orLike('file.file_no', $text);
      $builder->orLike('app.patient_id', $text);
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get Select2 data from list_data table
    *--------------------------*/
  public function bdtaskt1m1_13_getListData($table, $column, $ids)
  {
    if (!empty($table)) {
      $Id = $this->db->table('lists')->select('id')->where('table_name', $table)->get()->getRow();
      if (!empty($Id)) {
        if (!empty($ids)) {
          return $this->db->table('list_data')->select($column)->where('list_id', $Id->id)->where('status', 1)->whereNotIn('id', $ids)->get()->getResult();
        } else {
          return $this->db->table('list_data')->select($column)->where('list_id', $Id->id)->where('status', 1)->get()->getResult();
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /*--------------------------
    | search patient data
    *--------------------------*/
  public function bdtaskt1m1_14_searchPntWithFile($text, $langColumn)
  {
    if (!empty($text)) {
      return $this->db->table('patient_file file')
        ->select("file.patient_id as id, CONCAT_WS(' ', file.file_no, '-', p.$langColumn) as text")
        ->join("patient p", "p.patient_id=file.patient_id", "left")
        ->where('p.status', 1)
        ->groupStart()
        ->like('file.file_no', $text)
        ->orLike('p.patient_id', $text)
        ->orLike('p.nameE', $text)
        ->orLike('p.nameA', $text)
        ->orLike('p.mobile', $text)
        ->groupEnd()
        ->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search patient data
    *--------------------------*/
  public function bdtaskt1m1_15_searchRV($text)
  {
    if (!empty($text)) {
      $query = $this->db->table('vouchers')->select("id, CONCAT(id, ' | ', voucher_date, ' | ', receipt) as text");
      $query->where('isPV', 0);
      $query->where('isClosed', 0);
      $query->where('status', 1);
      return $query->like('id', $text)->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search invoice no 
    *--------------------------*/
  public function bdtaskt1m1_16_searchInvoiceNo($text)
  {
    if (!empty($text)) {
      $query = $this->db->table('service_invoices')->select("id, CONCAT(id, ' | ', invoice_date) as text");
      $query->where('status', 1);
      $query->where('isClosed', 0);
      return $query->like('id', $text)->get()->getResult();
    } else {
      return false;
    }
  }

  /* Update and return affected rows*/
  public function bdtaskt1m1_17_Update_getAffectedRows($table, $data = [], $where = [])
  {
    if (!empty($table) && !empty($data)) {
      //branch checking
      //end
      $this->db->table($table)->where($where)->update($data);
      return $this->db->affectedRows();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get MAX by after like
    *--------------------------*/
  public function bdtaskt1m1_18_getLikeMaxData($table, $like, $column)
  {
    if (!empty($table)) {
      return $this->db->table($table)->selectMax($column)->like($column, $like, 'after')->get()->getRow()->$column;
    } else {
      return false;
    }
  }
  /*--------------------------
    | Get MAX by after like
    *--------------------------*/
  public function bdtaskt1m1_18_getLikeMaxDataWhere($table, $like, $column, $where = [])
  {
    if (!empty($table)) {
      return $this->db->table($table)->selectMax($column)->like($column, $like, 'after')->where($where)->get()->getRow()->$column;
    } else {
      return false;
    }
  }

  /*--------------------------
    | search patient data
    *--------------------------*/
  public function bdtaskt1m1_19_patientList($langColumn)
  {
    return $this->db->table('patient_file file')
      ->select("file.patient_id as id, CONCAT_WS(' ', file.file_no, '-', p.$langColumn) as text")
      ->join("patient p", "p.patient_id=file.patient_id", "left")
      ->get()->getResult();
  }

  /*--------------------------
    | Get Table Max Column Id
    *--------------------------*/
  public function bdtaskt1m1_20_getTableMaxId($table, $column)
  {
    if ($this->db->tableExists($table)) {
      if ($this->db->fieldExists($column, $table)) {
        $row = $this->db->table($table)
          ->selectMax($column)
          ->get()
          ->getRow()->$column;
        if (!empty($row)) {
          return $row + 1;
        } else {
          return 1;
        }
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }

  /*--------------------------
    | Account Transactions 
    *--------------------------*/
  public function bdtaskt1m1_21_AccTrans($VNo, $Vtype, $COAID, $details = null, $debit = 0, $credit = 0, $patient = 0, $doctor = 0, $isApproved = 1, $branch = null, $date = null, $created_date = null, $created_by = null)
  {
    $data = array(
      'VNo'         => $VNo,
      'Vtype'       => $Vtype,
      'VDate'       => $date == null ? date('Y-m-d') : $date,
      'COAID'       => $COAID,
      'Narration'   => $details,
      'Debit'       => $debit,
      'Credit'      => $credit,
      'PatientID'   => $patient,
      'BranchID'     => $branch == null ? session('branchId') : $branch,
      'IsPosted'    => 1,
      'CreateBy'    => $created_by == null ? session('id') : $created_by,
      'CreateDate'  => $created_date == null ? date('Y-m-d H:i:s') : $created_date,
      'IsAppove'    => $isApproved
    );
    $this->bdtaskt1m1_01_Insert('acc_transaction', $data);
  }

  public function bdtaskt1m1_22_addActivityLog($type, $action_name, $id, $table, $status = 0, $data = null)
  {
    $postData = (empty($_POST)) ? array() : $_POST;
    $actionData = array(
      'emp_id'    => session('id'),
      'branch_id' => session('branchId'),
      'type'      => $type,
      'action'    => $action_name,
      'action_id' => $id,
      'table_name' => $table,
      'slug'      => uri_string(),
      'form_data' => ($data == null) ? json_encode($postData) : $data,
      'status'    => $status
    );
    $this->bdtaskt1m1_01_Insert('activity_logs', $actionData);
  }

  /*--------------------------
    | search user data
    *--------------------------*/
  public function bdtaskt1m1_23_searchUserName($text)
  {
    if (!empty($text)) {
      $query = $this->db->table('user');
      $query->select("user.emp_id as id, CONCAT_WS(' ', emp.short_name, '-', emp.nameE, emp.mobile) as text");
      $query->join("employees emp", "emp.emp_id=user.emp_id", "left");
      $query->groupStart();
      $query->like('emp.nameE', $text);
      $query->orLike('emp.nameA', $text);
      $query->orLike('emp.mobile', $text);
      $query->orLike('emp.short_name', $text);
      $query->groupEnd();
      return $query->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get current balance
    *--------------------------*/
  public function bdtaskt1m1_24_getCurrentBalance($head, $branch_id)
  {
    if (empty($branch_id)) {
      return 0;
    }
    $query = $this->db->table('acc_coa_balance');
    $query->select('SUM(balance) as balance');
    $query->where('headCode', $head);
    $query->where('branch_id', $branch_id);
    $result = $query->get()->getRow();
    if (!empty($result)) {
      $balance = ($result->balance == '' || $result->balance == NULL) ? 0 : $result->balance;
    } else {
      $balance = 0;
    }
    return $balance;
  }

  public function bdtaskt1m1_25_updatePatientBLDebit($amount, $where = [])
  {
    $result = $this->db->table('patient')->set('balance', 'balance-' . $amount, FALSE)->where($where)->update();
    return $this->db->affectedRows();
  }

  public function bdtaskt1m1_26_updatePatientBLCredit($amount, $where = [])
  {
    $result = $this->db->table('patient')->set('balance', 'balance+' . $amount, FALSE)->where($where)->update();
    return $this->db->affectedRows();
  }

  public function bdtaskt1m1_27_updateSupplierBLDebit($amount, $where = [])
  {
    $result = $this->db->table('supplier_information')->set('balance', 'balance-' . $amount, FALSE)->where($where)->update();
    return $this->db->affectedRows();
  }

  public function bdtaskt1m1_28_updateSupplierBLCredit($amount, $where = [])
  {
    $result = $this->db->table('supplier_information')->set('balance', 'balance+' . $amount, FALSE)->where($where)->update();
    return $this->db->affectedRows();
  }

  /*--------------------------
    | search pharmacy item
    *--------------------------*/
  public function bdtaskt1m1_25_searchPharmacyCustomer($text, $langColumn)
  {
    if (!empty($text)) {
      return $this->db->table('ph_customer_information')->select("id, $langColumn as text")->orLike('nameE', $text)->orLike('nameA', $text)->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | search patient data
    *--------------------------*/
  public function bdtaskt1m1_26_searchAllPatient($text, $langColumn)
  {
    if (!empty($text)) {
      return $this->db->table('patient p')
        ->select("p.patient_id as id, CONCAT_WS(' ', IF(file.file_no='', p.patient_id, file.file_no), '-', p.$langColumn, '-', p.mobile) as text")
        ->join("patient_file file", "file.patient_id=p.patient_id", "left")
        ->where('p.status', 1)
        ->groupStart()
        ->like('file.file_no', $text)
        ->orLike('p.patient_id', $text)
        ->orLike('p.nameE', $text)
        ->orLike('p.nameA', $text)
        ->orLike('p.mobile', $text)
        ->orLike('CONCAT("0", p.mobile)', $text)
        ->orLike('CONCAT("966", p.mobile)', $text)
        ->groupEnd()
        ->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get assign doctor list data
    *--------------------------*/
  public function bdtaskt1m1_27_getAssignDoctorList($type, $id, $langColumn)
  {
    if ($type == 'nurse') {
      $query = $this->db->table('doctor_assistant das')
        ->select("emp.emp_id as id, CONCAT(emp.short_name, ' - ', emp.$langColumn) as text")
        ->join("employees emp", "emp.emp_id=das.doctor_id", "left")
        ->where("FIND_IN_SET($id, das.nurse_id)", null, false)
        ->get()->getResult();
    } else {
      $query = $this->db->table('doctor_assistant das')
        ->select("emp.emp_id as id, CONCAT(emp.short_name, ' - ', emp.$langColumn) as text")
        ->join("employees emp", "emp.emp_id=das.doctor_id", "left")
        ->where("FIND_IN_SET($id, das.coordinator_id)", null, false)
        ->get()->getResult();
    }
    return $query;
  }

  public function bdtaskt1m1_28_updateBalance($amount, $where = [], $action)
  {
    if ($action == 'debit') {
      $this->db->table('acc_coa')->set('balance', 'balance+' . $amount, FALSE)->where($where)->update();
    } else {
      // balance credited
      $this->db->table('acc_coa')->set('balance', 'balance-' . $amount, FALSE)->where($where)->update();
    }
    return $this->db->affectedRows();
  }

  public function bdtaskt1m1_29_getBalance($table, $where = [], $column)
  {
    return $this->db->table($table)->select($column)->where($where)->get()->getRow();
  }

  // Update amount column
  public function bdtaskt1m1_30_updateColumnAmount($table, $where = [], $column, $value, $flag)
  {
    if ($flag === true) {
      $query = $this->db->table($table)->set($column, $column . '+' . $value, FALSE)->where($where)->update();
    } else {
      $query = $this->db->table($table)->set($column, $column . '-' . $value, FALSE)->where($where)->update();
    }
    return $query;
  }

  /*--------------------------
    | Get Select2 data
    *--------------------------*/
  public function bdtaskt1m1_31_getSelect2DataLike($table, $column, $where, $field, $like)
  {
    if (!empty($table)) {
      $builder = $this->db->table($table);
      $builder->select($column);
      $builder->where($where);
      $builder->like($field, $like);
      return $builder->get()->getResult();
    } else {
      return false;
    }
  }

  /*--------------------------
    | Get assign branch list data
    *--------------------------*/
  public function bdtaskt1m1_32_getAssignBranchList($branchs, $langColumn)
  {
    $query = $this->db->table('branch');
    $query->select("id, $langColumn as text");
    $data[] = array('id' => '', 'text' => 'Select Branch');
    if (session('isAdmin') === false) {
      $query->whereIn("id", $branchs);
    } else {
      $data[] = array(
        'id' => '0',
        'text' => 'All Branch'
      );
    }
    $query->where("status", 1);
    $result = $query->get()->getResult();
    foreach ($result as $row) {
      $data[] = array(
        'id' => $row->id,
        'text' => $row->text
      );
    }
    return $data;
  }

  /*--------------------------
    | search doctor data
    *--------------------------*/
  public function bdtaskt1m1_33_getDoctorList($table, $where, $column)
  {
    if (!empty($table)) {
      $query = $this->db->table($table);
      $query->select($column);
      $query->where($where);
      return $query->get()->getResult();
      //echo get_last_query();exit;
    } else {
      return false;
    }
  }

  public function getActiveFiscalyear()
  {
    $fiscalyear = $this->db->table('financial_year')->select('*')->where('status', 1)->get()->getRow();
    return ($fiscalyear ? $fiscalyear->id : 0);
  }

  public function getcoaPredefineHead()
  {
    $predefinehead = $this->db->table('acc_predefine_account')->select('*')->get()->getRow();
    return ($predefinehead ? $predefinehead : false);
  }

  public function getReferSubcode($subType, $refcode)
  {
    $subcode = $this->db->table('acc_subcode')->select('*')->where('subTypeId', $subType)->where('referenceNo', $refcode)->get()->getRow();
    return ($subcode ? $subcode->id : false);
  }

  public function bdtaskt1m8_07_getMaxvoucherno($type)
  {
    $result = $this->db->table('acc_vaucher')
      ->select("VNo")
      ->where('Vtype', $type)
      ->orderBy('id', 'desc')
      ->get()
      ->getRow();

    $typed =  $this->db->table('acc_voucher_type')
      ->select("*")
      ->where('typen', $type)
      ->get()
      ->getRow();
    $vno = ($result ? explode('-', $result->VNo) : 0);
    $vn = ($result ? $vno[1] + 1 : 1);
    return $voucher = $typed->prefix_code . ($vn);
  }


  public function bank_heads()
  {
    $builder = $this->db->table('acc_coa');
    $builder->select('*');
    $builder->where('isBankNature', '1');
    $query = $builder->get();
    $data = $query->getResult();

    return $data;
  }

  public function bankLC_heads()
  {
    $builder = $this->db->table('acc_coa');
    $builder->select('*');
    $builder->where('isLC', '1');
    $query = $builder->get();
    $data = $query->getResult();

    // $list = array(' ' => get_phrases(['select', 'bank']));
    // if (!empty($data)) {
    //   foreach ($data as $value) {
    //     $list[$value->HeadCode] = $value->HeadName;
    //   }
    // }
    return $data;
  }
}
