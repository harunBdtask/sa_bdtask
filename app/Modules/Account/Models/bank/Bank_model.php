<?php
class Bank_model extends CI_Model
{
   private $_table = 'bank_names';

   public function __construct()
   {
      parent::__construct();
   }

   /*-----------------------------*
    | get all bank by ajax call  
    *------------------------------*/
   public function getBankList($postData = null)
   {

      $response = array();

      ## Read value
      @$draw            = $postData['draw'];
      @$start           = $postData['start'];
      @$rowperpage      = $postData['length']; // Rows display per page
      @$columnIndex     = $postData['order'][0]['column']; // Column index
      @$columnName      = $postData['columns'][$columnIndex]['data']; // Column name
      @$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
      @$searchValue     = $postData['search']['value']; // Search value

      ## Search 
      $searchQuery = "";
      if ($searchValue != '') {
         $searchQuery = "(name like '%" . $searchValue . "%') ";
      }

      ## Total number of records without filtering
      $this->db->select('count(*) as allcount');
      if ($searchValue != '')
         $this->db->where($searchQuery);
      $records = $this->db->get($this->_table)->result();
      $totalRecords = $records[0]->allcount;

      ## Total number of record with filtering
      $this->db->select('count(*) as allcount');
      $this->db->from($this->_table);
      if ($searchValue != '')
         $this->db->where($searchQuery);
      $records = $this->db->get()->result();
      $totalRecordwithFilter = $records[0]->allcount;

      ## Fetch records
      $this->db->select("*");
      $this->db->from($this->_table);
      if ($searchValue != '')
         $this->db->where($searchQuery);
      $this->db->order_by($columnName, $columnSortOrder);
      $this->db->limit($rowperpage, $start);
      $records = $this->db->get()->result();

      $data = array();
      foreach ($records as $record) {
         $button = '';

         $button .= '<a href="#" class="btn btn-xs btn-success m-r-2 editAction" data-id="' . $record->id . '"><i class="fa fa-edit text-white"></i></a>';
         $button .= '<a href="#" class="btn btn-xs btn-danger deleteAction" data-id="' . $record->id . '"><i class="fa fa-trash text-white"></i></a>';

         $data[] = array(
            "id"           => $record->id,
            "bname"        => $record->name,
            "button"       => $button,
         );
      }

      ## Response
      $response = array(
         "draw" => intval($draw),
         "iTotalRecords" => $totalRecordwithFilter,
         "iTotalDisplayRecords" => $totalRecords,
         "aaData" => $data
      );

      return $response;
   }

   /*-----------------------------*
    | get Bank by id 
    *------------------------------*/
   public function getBankById($id)
   {
      return $this->db->get_where("$this->_table", array("id" => $id))->row();
   }

   /*-----------------------------*
    | delete bank name
    *------------------------------*/
   public function delete_bank($id)
   {
      $this->db->where('id', $id)
         ->delete($this->_table);

      if ($this->db->affected_rows()) {
         return true;
      } else {
         return false;
      }
   }
}
