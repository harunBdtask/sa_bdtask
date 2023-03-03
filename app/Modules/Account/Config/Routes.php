<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('account', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
    // Routes of service invoices 
    $subroutes->group('services', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
        $subroutes->add('invoices', 'Bdtaskt1m8c2ServInvoices::index');
        $subroutes->add('getAllList', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_01_getList');
        $subroutes->add('getDelInvList', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_10_getDeleteInvoices');
        $subroutes->add('addInvoice', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_02_addInvoice');
        $subroutes->add('createInvoice', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_05_createInvoice');
        $subroutes->add('serviceByAppId/(:any)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_03_serviceByAppId/$1');
        $subroutes->add('serviceByPId/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_04_serviceByPId/$1');
        $subroutes->add('servicesByPackId/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_12_getServicesByPackId/$1');
        $subroutes->add('detailsInvoice/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_06_detailsInvoice/$1');
        $subroutes->add('getInvoiceInfo/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_08_getInvoiceInfo/$1');
        $subroutes->add('getPacksByPId/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_11_getPacksByPId/$1');
        $subroutes->add('servicesByDocId/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_13_getServicesByDocId/$1');
        $subroutes->add('deleteInvoice', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_07_deleteInvoice');
        $subroutes->add('invDateModify', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_09_dateModify');
        $subroutes->add('generatePDF/(:num)', 'Bdtaskt1m8c2ServInvoices::generatePDF/$1');
        $subroutes->add('approvedDelReq', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_15_approvedDelReq');
        $subroutes->add('getInvoiceInfoByOrder/(:any)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_16_invoiceInfoByServiceOrder/$1');
        $subroutes->add('getInvoicePayInfo/(:num)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_17_getInvoicePayInfo/$1');
        $subroutes->add('invoiceConsumptions/(:any)', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_18_invoiceConsumptions/$1');
        $subroutes->add('patientAvailablePoints', 'Bdtaskt1m8c2ServInvoices::bdtaskt1m8c2_19_patientAvailablePoints');
    });

    // Routes of balance transfer
    $subroutes->group('balanceTransfer', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
        // patient to patient
        $subroutes->add('patients', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_01_PatientBlnTrans');
        $subroutes->add('getPntBTransfer', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_02_getPntBTransfer');
        $subroutes->add('savePntTrans', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_04_savePntTrans');
        $subroutes->add('pntBalance/(:num)/(:num)', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_03_pntCurrBalance/$1/$2');
        $subroutes->add('pntTransById/(:num)', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_05_pntTransById/$1');
        $subroutes->add('approvedPntTrans', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_06_approvedPntTrans');
        $subroutes->add('balanceByCode/(:num)/(:num)', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_09_patientBalanceByCode/$1/$2');
        // branch to branch
        $subroutes->add('branch_to_branch', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_07_branchToBranch');
        $subroutes->add('branchList', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_08_branchList');
        $subroutes->add('saveBranchToBranch', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_10_saveBranchBalTrans');
        $subroutes->add('branchToBranchBal', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_11_branchTransferBal');
        $subroutes->add('branchTransById/(:num)', 'Bdtaskt1m8c8BalanceTransfer::bdtaskt1m8c8_12_branchTransById/$1');
    });

    //Routes of accounts
    $subroutes->group('accounts', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
        $subroutes->add('/', 'Bdtaskt1m8c1Accounts::index');
        $subroutes->add('selectedForm/(:num)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_01_selectedForm/$1');
        $subroutes->add('dOrCHead', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_07_getCreditOrDebitAcc'); // debit or credit headcode
        $subroutes->add('newForm/(:num)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_03_newForm/$1');
        $subroutes->add('deletecoa/(:num)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_04_deleteCoa/$1');
        $subroutes->add('insertCoa', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_04_insertCoa');
        $subroutes->add('pntInfoById/(:num)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_05_patientInfoById/$1');
        $subroutes->add('getAll', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_08_getAllAccount');
        $subroutes->add('getAllOpeningHead', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_09_getAllOpeningAccount');
        $subroutes->add('predefine_account', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_11_prdefine_accounts');
        $subroutes->add('getsubtype', 'Bdtaskt1m8c1Accounts::getsubtype');
        
        $subroutes->add('addOpeningBalance', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_09_addOpeningBalance');
        $subroutes->add('saveOpeningBalance', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_10_saveOpeningBalance');
        $subroutes->add('getOpeningDetails/(:any)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_11_getOpeningDetails/$1');
        $subroutes->add('add_checkinfo', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_13_checkinfoform');
        $subroutes->add('checkprint', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_12_checkform');
        
        $subroutes->add('getOpeningBalanceList', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c9_13_getOpeningBalanceList');
        // receipt vouchers
        $subroutes->add('receipt_voucher', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_01_ReceiptVoucher');
        $subroutes->add('packServices/(:num)', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_02_psckServById/$1');
        $subroutes->add('saveRVoucher', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_03_saveRVoucher');
        $subroutes->add('receiptVList', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_05_RVList');
        $subroutes->add('getRVList', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_06_getRVList');
        $subroutes->add('receiptVDetails/(:num)', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_04_voucherDetails/$1');
        $subroutes->add('deleteVoucher', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_07_deleteVoucher');
        $subroutes->add('getVoucherInfo/(:num)', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_08_getVoucherInfo/$1');
        $subroutes->add('updateVoucher', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_09_updateVoucher');
        $subroutes->add('deleteRVList', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_10_deleteVoucherList');
        $subroutes->add('searchCreditInvoice', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_11_searchCreditInvoices');
        $subroutes->add('getCreditInvoiceById/(:num)', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_12_getCreditInvoiceById/$1');
        $subroutes->add('appReceiptDelReq', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_13_appReceiptDelReq');
        $subroutes->add('getPackagesByPid', 'Bdtaskt1m8c3ReceiptVoucher::bdtaskt1m8c3_14_getPackageByPid');

        // payment vouchers
        $subroutes->add('payment_voucher', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_01_paymentVoucher');
        $subroutes->add('savePayVoucher', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_03_savePVoucher');
        $subroutes->add('paymentVList', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_05_paymentVList');
        $subroutes->add('getPVList', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_06_getPVList');
        $subroutes->add('paymentVDetails/(:num)', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_04_pvDetails/$1');
        $subroutes->add('rvForPayment/(:num)', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_02_rvDetailsById/$1');
        $subroutes->add('deletePaymentReq', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_07_deleteVoucherReq');
        $subroutes->add('approvedPaymentReq', 'Bdtaskt1m8c4PaymentVoucher::bdtaskt1m8c4_08_approvedPaymentReq');

        // vouchers approval
        $subroutes->add('voucher_approval', 'Bdtaskt1m8c7VoucherApproval::bdtaskt1m8c7_01_VoucherApproval');
        $subroutes->add('getVApprovalList', 'Bdtaskt1m8c7VoucherApproval::bdtaskt1m8c7_02_getVApprovalList');
        $subroutes->add('changeApproval', 'Bdtaskt1m8c7VoucherApproval::bdtaskt1m8c7_03_changeApproval');
        $subroutes->add('approve_multiple_voucher', 'Bdtaskt1m8c7VoucherApproval::bdtaskt1m8c9_04_approvedmultipleVoucher');
        
    });

    //Routes of vouchers
    $subroutes->group('vouchers', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
        $subroutes->add('maxVNo/(:any)', 'Bdtaskt1m8c1Accounts::bdtaskt1m8c1_06_getMaxVoucher/$1');
        $subroutes->add('debOrCHead', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c1_07_getCreditOrDebitAcc');
        $subroutes->add('debit', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_01_DebitVoucher');
        $subroutes->add('getVoucherList/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_02_getVoucherList/$1');
        $subroutes->add('saveDebitVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_03_saveDebitVoucher');
        $subroutes->add('editDebitVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_03_updateDebitVoucher');
        
        $subroutes->add('approvedVoucher/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_04_approvedVoucher/$1');
        $subroutes->add('reverseVoucher/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_04_reverseVoucher/$1');
        $subroutes->add('deleteVoucher/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_05_deleteVoucher/$1');
        $subroutes->add('getVoucherDetails/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_05_voucherDetails/$1');
        $subroutes->add('credit', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_06_CreditVoucher');
        $subroutes->add('saveCreditVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_07_saveCreditVoucher');
        $subroutes->add('updateCreditVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_09_updateCreditVoucher');
        
        $subroutes->add('voucherEditform/(:any)/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_09_editvouchers/$1/$2');
        $subroutes->add('journal', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_08_JournalVoucher');
        $subroutes->add('saveJournalVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_09_saveJournalVoucher');
        $subroutes->add('updateJournalVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_11_updateJournalVoucher');
        
        $subroutes->add('searchTransactionAcc', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_10_searchTransactionAcc');
        $subroutes->add('searchTransactionCredit', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_11_searchTransactionCreditvoucher');
        $subroutes->add('searchallwithoutcashTransactionAcc', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_12_searchTransactionAccwithoutcash');
        $subroutes->add('getTransByVoucher/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_11_getTransByVoucher/$1');
        $subroutes->add('deleteVoucherByVNo/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_12_deleteVoucher/$1');
        $subroutes->add('getTypeWiseVoucher/(:any)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_13_getTypeWiseVoucher/$1');
        $subroutes->add('getVoucherDetailsById/(:num)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_14_voucherDetailsById/$1');
        $subroutes->add('deleteVoucherById/(:num)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_15_deleteVoucherById/$1');
        $subroutes->add('approvedVoucherById/(:num)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_16_approvedVoucherById/$1');
        $subroutes->add('approvedVoucherById/(:num)', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_16_approvedVoucherById/$1');
        $subroutes->add('searchSubcode', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_1Searchsubcode');
        $subroutes->add('contra', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_01_ContraVoucher');
        $subroutes->add('saveContraVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_03_saveContraVoucher');
        $subroutes->add('updateContraVoucher', 'Bdtaskt1m8c9Vouchers::bdtaskt1m8c9_04_updateContraVoucher');
        
    });

    $subroutes->group('financial_year', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
 
    $subroutes->add('addFinancialType', 'Bdtaskt1c1Setting::bdtaskt1c1_12_addFinancialType');
	$subroutes->add('financialTypeList', 'Bdtaskt1c1Setting::bdtaskt1c1_11_financialTypeList');
	$subroutes->add('getFinanTypeById', 'Bdtaskt1c1Setting::bdtaskt1c1_13_getFinanTypeById');
	$subroutes->add('deleteFinanType', 'Bdtaskt1c1Setting::bdtaskt1c1_14_deleteFinanTypeById');
    $subroutes->add('set_financial_year', 'Bdtaskt1m15c4FinancialYear::index');
    $subroutes->add('getFinancialYear', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_01_getFinYearList');
    $subroutes->add('addFinancialYear', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_02_addFinancialYear');
    $subroutes->add('getFyById', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_03_getFyById');
    $subroutes->add('activateFyById', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_04_activeFinancialYear');
    $subroutes->add('closeFinancialYear', 'Bdtaskt1m15c4FinancialYear::bdtaskt1m15c4_05_closeFinancialYear');
    
      });

    //Routes of account reports 
    $subroutes->group('reports', ['namespace' => 'App\Modules\Account\Controllers'], function ($subroutes) {
        $subroutes->add('form', 'Bdtaskt1m8c5Reports::index');
        $subroutes->add('cash_book', 'Bdtaskt1m8c5Reports::bdtask021_cash_book');
        $subroutes->add('cashbook_report', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_01_getCashbookData');
        $subroutes->add('bank_book', 'Bdtaskt1m8c5Reports::bdtask022_bank_book');
        $subroutes->add('getAllVList', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_02_getVoucherList');
        $subroutes->add('detailsByVId/(:any)', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_03_detailsTransByVId/$1');
        $subroutes->add('userReportForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_01_userReportForm');
        $subroutes->add('getUserReports', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_04_getUserWiseReports');
        $subroutes->add('patientReportForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_05_patientReportForm');
        $subroutes->add('patientByReports', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_06_patientByReports');
        $subroutes->add('trialBalanceForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_07_trialBalanceForm');
        $subroutes->add('trialBalanceReport', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_08_trialBalanceReport');
        $subroutes->add('balanceSheetForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_09_balanceSheetForm');
        $subroutes->add('cashFlowForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_10_cashFlowForm');
        $subroutes->add('cashFlowReports', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_11_cashFlowResports');
        $subroutes->add('viewTransByIds', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_12_viewTransByIds');
        $subroutes->add('GeneralLForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_13_GeneralLForm');
        $subroutes->add('GeneralLedgerReport', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_14_GeneralLedgerReport');
        
        $subroutes->add('generalLedgerReportdata', 'Bdtaskt1m8c5Reports::accounts_general_ledgerreport_search');
        
        $subroutes->add('getGLHeadList', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_15_getGLHeadList');
        $subroutes->add('getGLTransList/(:any)', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_16_getGLTransList/$1');
        $subroutes->add('profitLossForm', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_17_profitLossForm');
        $subroutes->add('profitLossReoprtResult', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_19_profitLossReoprtSearch');
        $subroutes->add('profitLossReoprt', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_18_profitLossReoprt');
        $subroutes->add('getTransHead', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_19_getTransHead');
        $subroutes->add('viewTrialBalanceDetails', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_20_viewTrialBalanceDetails');
        $subroutes->add('exportTrialBalance', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_21_exportTrialBalance');
        $subroutes->add('trialBalanceData', 'Bdtaskt1m8c5Reports::bdtaskt1m8c5_22_trialBalanceData');
        $subroutes->add('non_transactional_general_ledger', 'Bdtaskt1m8c5Reports::general_ledger_non_transactional');
        $subroutes->add('sub_ledger', 'Bdtaskt1m8c5Reports::bdtask_124subLedger');
        $subroutes->get('getSubcodes/(:any)', 'Bdtaskt1m8c5Reports::getSubcode/$1');
        $subroutes->get('getSubAccountHead/(:any)', 'Bdtaskt1m8c5Reports::getSubAccountHeads/$1');
        $subroutes->add('sub_ledger_report', 'Bdtaskt1m8c5Reports::bdtask_125subLedger_report');
        
    });
});
