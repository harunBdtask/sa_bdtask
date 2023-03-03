<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}
/*** Route for Human Resources ***/
$routes->group('human_resources', ['namespace' => 'App\Modules\Human_resource\Controllers'], function($subroutes){

    /*** Route for departments ***/
    $subroutes->group('departments', function($subroutes)
    {   
        $subroutes->add('department_list', 'Bdtaskt1m9c1Employees::bdtask1c1_01_departmentList');
        $subroutes->add('get_department_list', 'Bdtaskt1m9c1Employees::bdtask1c1_02_getDepartmentList');
        $subroutes->add('addDepartment', 'Bdtaskt1m9c1Employees::bdtask1c1_03_addDepartment');
        $subroutes->add('getdepartmentById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_04_getdepartmentById/$1');
        $subroutes->add('deleteDepartmentById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_05_deleteDepartmentById/$1');

        $subroutes->add('departmentHeadAlreadyAssigned/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_20_departmentHeadAlreadyAssigned/$1');

    });

	/*** Route for Employees ***/
    $subroutes->group('employees', function($subroutes)
    {
        $subroutes->add('employee_type', 'Bdtaskt1m9c1Employees::bdtask1c1_06_employeeTypesList');
        $subroutes->add('get_employee_type_list', 'Bdtaskt1m9c1Employees::bdtask1c1_07_getEmployeeTypesList');
        $subroutes->add('addEmployeeType', 'Bdtaskt1m9c1Employees::bdtask1c1_08_addEmpployeeType');
        $subroutes->add('getEmpTypeById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_09_getEmpTypeById/$1');
        $subroutes->add('deleteEmpTypeById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_10_deleteEmpTypeById/$1');

        $subroutes->add('employee_list', 'Bdtaskt1m9c1Employees::bdtask1c1_11_employeeList');
        $subroutes->add('get_employee_list', 'Bdtaskt1m9c1Employees::bdtask1c1_12_getEmployeeList');
        $subroutes->add('add_employee', 'Bdtaskt1m9c1Employees::bdtask1c1_13_createEmployee');
        $subroutes->add('create_employee', 'Bdtaskt1m9c1Employees::bdtask1c1_14_addEmpployee');
        $subroutes->add('update_employee/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_15_updateEmployeeForm/$1');
        $subroutes->add('save_update_employee/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_16_updateEmpployee/$1');
        $subroutes->add('delete_employee/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_17_deleteEmployee/$1');

        $subroutes->add('bulkImportEmployees', 'Bdtaskt1m9c1Employees::bdtask1c1_18_bulkImportEmployees');
        $subroutes->add('checkEmpRoster', 'Bdtaskt1m9c1Employees::bdtaskt1c1_19_checkEmpRoster');

        $subroutes->add('employee_profile/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_20_employeeProfile/$1');
        $subroutes->add('employee_details_print/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_21_employeeDetailsPrint/$1');

        $subroutes->add('best_employee_list', 'Bdtaskt1m9c1Employees::bdtask1c1_22_best_employee_list');
        $subroutes->add('get_best_employee_list', 'Bdtaskt1m9c1Employees::bdtask1c1_23_get_best_employee_list');
        $subroutes->add('add_best_employee', 'Bdtaskt1m9c1Employees::bdtask1c1_24_add_best_employee');
        $subroutes->add('get_best_employee_by_id/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_25_get_best_employee_by_id/$1');
        $subroutes->add('delete_best_employee_by_id/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_26_delete_best_employee_by_id/$1');

    });

    /*** Route for designations ***/
    $subroutes->group('designations', function($subroutes)
    {
        $subroutes->add('designations_list', 'Bdtaskt1m9c1Employees::bdtask1c1_21_designationList');
        $subroutes->add('get_designations_list', 'Bdtaskt1m9c1Employees::bdtask1c1_22_getDesignationsList');
        $subroutes->add('addDesignation', 'Bdtaskt1m9c1Employees::bdtask1c1_23_addDesignation');
        $subroutes->add('getdesignationById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_24_getdesignationById/$1');
        $subroutes->add('deleteDesignationById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_25_deleteDesignationById/$1');
    });

    /*** Route for branch ***/
    $subroutes->group('branch', function($subroutes)
    {
        $subroutes->add('branch_list', 'Bdtaskt1m9c1Employees::bdtask1c1_26_branchList');
        $subroutes->add('get_branch_list', 'Bdtaskt1m9c1Employees::bdtask1c1_27_getBranchList');
        $subroutes->add('addBranch', 'Bdtaskt1m9c1Employees::bdtask1c1_28_addBranch');
        $subroutes->add('getbranchById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_29_getbranchById/$1');
        $subroutes->add('deleteBranchById/(:num)', 'Bdtaskt1m9c1Employees::bdtask1c1_30_deleteBranchById/$1');
    });

    /*** Route for basic_salary_setup ***/
    $subroutes->group('basic_salary_setup', function($subroutes)
    {

        $subroutes->add('basic_salary_setup_list', 'Bdtaskt1m9c2Setup::bdtask1c2_01_basicSalaryList');
        $subroutes->add('get_basic_salary_setup_list', 'Bdtaskt1m9c2Setup::bdtask1c2_02_getBasicSalaryList');
        $subroutes->add('basic_salary_create', 'Bdtaskt1m9c2Setup::bdtask1c2_03_basicSalaryCreate');
        $subroutes->add('basic_salary_by_id/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_04_basicSalaryById/$1');
        $subroutes->add('update_basic_salary/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_05_updateBasicSalary/$1');
        $subroutes->add('delete_basic_salary/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_06_deleteBasicSalary/$1');

    });

    /*** Route for allowance_setup ***/
    $subroutes->group('allowance_setup', function($subroutes)
    {

        $subroutes->add('allowance_setup_list', 'Bdtaskt1m9c2Setup::bdtask1c2_07_allowanceList');
        $subroutes->add('get_allowance_setup_list', 'Bdtaskt1m9c2Setup::bdtask1c2_08_getallowanceList');
        $subroutes->add('allowance_create', 'Bdtaskt1m9c2Setup::bdtask1c2_09_allowanceCreate');
        $subroutes->add('allowance_by_id/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_10_allowanceById/$1');
        $subroutes->add('update_allowance/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_11_updateAllowance/$1');
        $subroutes->add('delete_allowance/(:num)', 'Bdtaskt1m9c2Setup::bdtask1c2_12_deleteAllowance/$1');

    });

    /*** Route for Attendance ***/
    $subroutes->group('attendances', function($subroutes)
    {
        $subroutes->add('attendance_form', 'Bdtaskt1m9c2Attendances::index');
        $subroutes->add('getEmployees', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_01_empList');
        $subroutes->add('attendance_create', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_02_attn_create');
        $subroutes->add('attendance_log', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_03_attendance_log');
        $subroutes->add('user_attendanc_details/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_04_user_attendanc_details/$1');
        $subroutes->add('attendanc_edit/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_05_user_attendanc_edit_page/$1');
        $subroutes->add('attendance_update', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_06_attn_update');
        $subroutes->add('atten_delete/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_07_atten_delete/$1');
        $subroutes->add('atten_log_search', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_08_atten_log_search');
        $subroutes->get('searched_atten_log', 'Bdtaskt1m9c2Attendances::bdtaskt1c2_09_searched_atten_log');

        $subroutes->add('bulkImportAttenLogs', 'Bdtaskt1m9c2Attendances::bdtask1c1_20_bulkImportEmployees');
        $subroutes->add('punchTimeUnderTwoRemainingTime', 'Bdtaskt1m9c2Attendances::bdtask1c1_21_checkPunchTimeUnderTwoRemainingTime');
        $subroutes->add('upPunchTimeUnderTwoRemainingTime', 'Bdtaskt1m9c2Attendances::bdtask1c1_22_upPunchTimeUnderTwoRemainingTime');

        //Attendance in time setup
        $subroutes->add('attendance_setup', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_10_attendance_setup');
        $subroutes->add('add_attendanceSetup', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_11_add_attendanceSetup');
        $subroutes->add('attendanceSetupList', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_12_attendanceSetupList');
        $subroutes->add('attendanceSetupById/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_13_attendanceSetupById/$1');
        $subroutes->add('deleteAttendanceSetupById/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_14_deleteAttendanceSetupById/$1');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Attendnace Machine Map for Zkt machine id with employee... branch wise
        // $subroutes->add('atten_machine_map', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_15_atten_machine_map');
        // $subroutes->add('add_atten_machine_map', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_16_add_atten_machine_map');
        // $subroutes->add('add_atten_machine_map_list', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_17_add_atten_machine_map_list');
        // $subroutes->add('atten_machine_map_by_id/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_18_atten_machine_map_by_id/$1');
        // $subroutes->add('del_atten_machine_map_by_id/(:num)', 'Bdtaskt1m9c2Attendances::bdtaskt1c7_19_del_atten_machine_map_by_id/$1');

    });

    /*** Route for holidays ***/
    $subroutes->group('holidays', function($subroutes)
    {

        $subroutes->add('weekends', 'Bdtaskt1m9c4Holidays::bdtask1c4_01_weekendsList');
        $subroutes->add('weekends_list', 'Bdtaskt1m9c4Holidays::bdtask1c4_02_getWeekendsList');
        $subroutes->add('add_weekEndDays', 'Bdtaskt1m9c4Holidays::bdtask1c4_03_add_weekEndDays');
        $subroutes->add('getWeekEndDaysById/(:num)', 'Bdtaskt1m9c4Holidays::bdtask1c4_04_getWeekEndDaysById/$1');

        $subroutes->add('govt_holidays', 'Bdtaskt1m9c4Holidays::bdtask1c4_05_govt_holidays');
        $subroutes->add('govt_holidays_list', 'Bdtaskt1m9c4Holidays::bdtask1c4_06_govt_holidays_list');
        $subroutes->add('addGovtHoliday', 'Bdtaskt1m9c4Holidays::bdtask1c4_07_addGovtHoliday');
        $subroutes->add('getNoOfGovtHolidays', 'Bdtaskt1m9c4Holidays::bdtask1c4_08_getNoOfGovtHolidays');
        $subroutes->add('getGovtHolidayById/(:num)', 'Bdtaskt1m9c4Holidays::bdtask1c4_09_getGovtHolidayById/$1');
        $subroutes->add('deleteGovtHoliday/(:num)', 'Bdtaskt1m9c4Holidays::bdtask1c4_10_deleteGovtHoliday/$1');

    });

    /*** Route for holidays ***/
    $subroutes->group('leave_management', function($subroutes)
    {
        //Leave types setup
        $subroutes->add('leave_type', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_01_leave_type');
        $subroutes->add('leave_types_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_02_leave_types_list');
        $subroutes->add('add_leave_type', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_03_add_leave_type');
        $subroutes->add('get_leave_type_by_Id/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_04_leave_type_by_Id/$1');
        $subroutes->add('delete_leave_type/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_05_delete_leave_type/$1');

        //CPL Leaves for employees
        $subroutes->add('cpl_leave', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_06_cpl_leave');
        $subroutes->add('cpl_leave_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_07_cpl_leave_list');
        $subroutes->add('cpl_leave_by_Id/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_08_cpl_leave_by_Id/$1');

        //Earned Leave for employees
        $subroutes->add('earned_leave', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_09_earned_leave');
        $subroutes->add('earned_leave_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_10_earned_leave_list');
        $subroutes->add('generate_earned_leave', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_11_generate_earned_leave');
        $subroutes->add('earned_leave_by_Id/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_12_earned_leave_by_Id/$1');

        //Leave application for employees
        $subroutes->add('leave_approval', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_13_leave_approval');
        $subroutes->add('leave_application_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_14_leave_application_list');
        $subroutes->add('create_leave_approval', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_15_create_leave_approval');
        $subroutes->add('leave_application_by_Id/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_16_leave_application_by_Id/$1');
        $subroutes->add('delete_leave_application/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_17_delete_leave_application/$1');
        $subroutes->add('calculateDays', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_18_calculateDays');
        $subroutes->add('calculateDaysOthers', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_19_calculateDaysOthers');

        $subroutes->add('leave_application', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_20_leave_application');
        $subroutes->add('emp_leave_application_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_21_emp_leave_application_list');
        $subroutes->add('create_leave_application', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_22_create_leave_application');

        $subroutes->add('superior_approval', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_23_superior_approval');
        $subroutes->add('superior_leave_application_list', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_24_superior_leave_application_list');
        $subroutes->add('update_leave_application_superior', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_25_update_leave_application_superior');

        $subroutes->add('del_lve_apli_by_final_approv/(:num)', 'Bdtaskt1m9c5LeaveManagement::bdtask1c5_26_del_lve_apli_by_final_approv/$1');


    });

    /*** Duty Roster ***/
    $subroutes->group('duty_roster', function($subroutes)
    {
        // Shift Manage
        $subroutes->add('shift', 'Bdtaskt1m9c6DutyRoster::index');
        $subroutes->add('create_shift', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_01_createShift');
        $subroutes->add('check_inshift', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_02_checkInshift');
        $subroutes->add('chkduplicateshift', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_03_chkduplicateshift');
        $subroutes->add('shiftDataList', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_04_shiftDataList');
        $subroutes->add('getShiftById/(:num)', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_05_getShiftById/$1');
        $subroutes->add('deleteShiftById/(:num)', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_06_deleteShiftById/$1');

        // Roster Manage
        $subroutes->add('roster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_07_rosterList');
        $subroutes->add('shift_list', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_08_getShiftList');
        $subroutes->add('checkshift_data1', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_08_checkshift_data1');
        $subroutes->add('checkshift_data2', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_09_checkshift_data2');
        $subroutes->add('create_roster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_10_createRoster');
        $subroutes->add('rosterDataList', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_11_rosterDataList');
        $subroutes->add('getRosterById/(:num)', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_12_getRosterById/$1');
        $subroutes->add('deleteRosterByDate', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_13_deleteRosterByDate');
        $subroutes->add('getShiftsByDepartmentId/(:num)', 'Bdtaskt1m9c6DutyRoster::get_department_shifts/$1');

        // Roster Assign
        $subroutes->add('roster_assign', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_14_rosterAssignList');
        $subroutes->add('roster_shift_assign', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_15_rosterShiftAssignAdd'); // It will take to roster assign page where assign roster to employees

        $subroutes->add('create_shift_assign', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_16_createShiftAssign');
        $subroutes->add('empdatashow', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_17_empdatashow');
        $subroutes->add('rosteDateTimedata', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_18_rosteDateTimedata');
        $subroutes->add('delete_shiftassign', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_19_deleteShiftassign');
        $subroutes->add('shiftRosterList', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_20_shiftRosterList');
        $subroutes->add('update_shiftAssignForm/(:num)', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_21_update_shiftAssignForm/$1');
        $subroutes->add('update_addSingleEmpRoster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_22_update_addSingleEmpRoster');
        $subroutes->add('update_romoveSingleEmpRoster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_23_update_romoveSingleEmpRoster');
        $subroutes->add('roster_emp_view', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_24_rosterEmpView');
        $subroutes->add('delete_assigned_roster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_25_delete_assigned_roster');

         // Attendnace dashboard manage for duty roster
        $subroutes->add('attendance_dashboard', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_26_attendance_dashboard');
        $subroutes->add('loadallshift', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_27_loadallshift');
        $subroutes->add('load_clkshftemp', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_28_load_clkshftemp');
        $subroutes->add('loadcngdate', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_29_loadcngdate');
        $subroutes->add('updtempshift_frm', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_30_updtempshift_frm');
        $subroutes->add('load_checkedshift', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_31_load_checkedshift');
        $subroutes->add('load_checkedroster', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_32_load_checkedroster');
        $subroutes->add('emp_shift_update', 'Bdtaskt1m9c6DutyRoster::bdtaskt1c6_33_emp_shift_update');

    });

    /*** Payroll part ***/
    $subroutes->group('payroll', function($subroutes)
    {

        $subroutes->add('add_benefits', 'Bdtaskt1m9c7Payroll::bdtask_0001_benefits_form');
        $subroutes->add('add_benefits/(:num)', 'Bdtaskt1m9c7Payroll::bdtask_0001_benefits_form/$1');
        $subroutes->add('benefit_list', 'Bdtaskt1m9c7Payroll::index/$1');

        $subroutes->add('edit_benefit/(:num)', 'Bdtaskt1m9c7Payroll::bdtask_0001_benefits_form/$1');
        $subroutes->add('delete_benefit/(:num)', 'Bdtaskt1m9c7Payroll::delete_benefit/$1');
        
        $subroutes->add('add_salarysetup', 'Bdtaskt1m9c7Payroll::bdtask_0002_salarysetup_form');
        $subroutes->add('employee_basic_info', 'Bdtaskt1m9c7Payroll::employeebasic');
        // $subroutes->add('tax_handle', 'Bdtaskt1m9c7Payroll::salarywithtax');
        $subroutes->add('save_salarysetup', 'Bdtaskt1m9c7Payroll::salary_setup_entry');
        $subroutes->add('salary_setup_list', 'Bdtaskt1m9c7Payroll::bdtask_0005_salarysetup_list');
        $subroutes->add('salary_setupdata', 'Bdtaskt1m9c7Payroll::bdtask_salarysetup_listdata');
        $subroutes->add('edit_salary_setup/(:num)', 'Bdtaskt1m9c7Payroll::salsetup_upform/$1');
        $subroutes->add('salary_setup_update', 'Bdtaskt1m9c7Payroll::salary_setup_update');
        $subroutes->add('delete_salsetup/(:num)', 'Bdtaskt1m9c7Payroll::delete_salsetup/$1');
        $subroutes->add('salary_generate', 'Bdtaskt1m9c7Payroll::bdtask_006_salary_generate');
        $subroutes->add('create_salary_sheet', 'Bdtaskt1m9c7Payroll::create_salary_generate');
        $subroutes->add('salary_sheet', 'Bdtaskt1m9c7Payroll::bdtask_0008_salar_sheet');
        $subroutes->add('get_salary_sheet', 'Bdtaskt1m9c7Payroll::bdtask_getSalarygenerate_list');
        $subroutes->add('delete_salaryshett/(:num)', 'Bdtaskt1m9c7Payroll::delete_salgenerate/$1');
        $subroutes->add('salary_payment', 'Bdtaskt1m9c7Payroll::bdtask_0009_salary_payment');
        $subroutes->add('get_salary_paymentlist', 'Bdtaskt1m9c7Payroll::bdtask_getSalarypayment_list');
        // $subroutes->add('employee_paydata', 'Bdtaskt1m9c7Payroll::employee_paydata');
        // $subroutes->add('pay_confirm', 'Bdtaskt1m9c7Payroll::payconfirm');
        // $subroutes->add('payslip/(:num)', 'Bdtaskt1m9c7Payroll::payslip/$1');

        //Overtime Calculation
        $subroutes->add('over_time', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_20_OverTimes');
        $subroutes->add('save_over_times', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_21_saveOverTimes');
        $subroutes->add('over_times_list', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_22_overTimesList');
        $subroutes->add('over_times_by_id/(:num)', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_24_overTimesById/$1');
        $subroutes->add('delete_over_time_by_id/(:num)', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_25_deleteOverTimeById/$1');
        $subroutes->add('getOverTimeByEmpId/(:num)', 'Bdtaskt1m9c7Payroll::bdtaskt1c7_26_getOverTimeByEmpId/$1');

    });

    /*** Payroll part ***/
    $subroutes->group('reports', function($subroutes)
    {
        $subroutes->add('attendance_sheet', 'Bdtaskt1m9c8Reports::bdtaskt1c8_01_attendance_sheet');
        $subroutes->add('get_attendnace_sheet', 'Bdtaskt1m9c8Reports::bdtaskt1c8_02_get_attendnace_sheet');
    });

});