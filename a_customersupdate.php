<?php
if (session_id() == '') session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once 'ewcfg12.php' ?>
<?php
if(EW_USE_ADODB) {
    include_once 'adodb5/adodb.inc.php';
} else {
    include_once 'ewmysql12.php';
}
?>
<?php include_once 'phpfn12.php' ?>
<?php include_once 'a_customersinfo.php' ?>
<?php include_once 'usersinfo.php' ?>
<?php include_once 'a_salesgridcls.php' ?>
<?php include_once 'userfn12.php' ?>
<?php include_once 'function_1.php'?>
<?php include_once 'load_1.php'?>
<?php include_once 'function_2.php'?>
<?php include_once 'funct_1.php'?>
<?php

//
// Page class
//

$a_customers_update = NULL; // Initialize page object first

class ca_customers_update extends ca_customers {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = '{B36B93AF-B58F-461B-B767-5F08C12493E9}';

	// Table name
	var $TableName = 'a_customers';

	// Page object name
	var $PageObjName = 'a_customers_update';


	var $PageHeader;
	var $PageFooter;


	var $Token = '';
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = 'ew_CheckToken';
	var $CreateTokenFn = 'ew_CreateToken';

	//
	// Page class constructor
	//
	function __construct() {

		$_SESSION['Page'] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
        $_SESSION['Language']  = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (a_customers)
		if (!isset($_SESSION['a_customers']) || get_class($_SESSION['a_customers']) == 'ca_customers"') {
            $_SESSION['a_customers'] = &$this;
            $_SESSION['Table'] = &$_SESSION['a_customers'];
		}

		// Table object (users)
		if (!isset($_SESSION['users'])) $_SESSION['users'] = new cusers();

		// Page ID
		if (!defined('EW_PAGE_ID'))
			define('EW_PAGE_ID', 'update', TRUE);

		// Table name (for backward compatibility)
		if (!defined('EW_TABLE_NAME'))
			define('EW_TABLE_NAME', 'a_customers', TRUE);

		// Start timer
		if (!isset($_SESSION['gTimer'])) $_SESSION['gTimer'] = new cTimer();

		// Open connection
        $_SESSION['conn'] = ew_Connect($this->DBID);


        // User table object (users)
        $_SESSION['UserTable']  = new cusers();
        $_SESSION['UserTableConn']  = Conn($_SESSION['UserTable']->DBID);

	}

	// 
	//  Page_Init
	//
	function Page_Init() {
        $init= new funct_1();
        $init->Page_Init1($this);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = '') {
        $term= new funct_1();
        $term->Page_Terminate1($url, $this);
	}
	var $FormClassName = 'form-horizontal ewForm ewUpdateForm';
	var $RecKeys;
	var $Disabled;
	var $Recordset;
	var $UpdateCount = 0;

	//
	// Page main
	//
	function Page_Main() {


		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		if ($_POST['a_update'] <> '') {

			// Get action
			$this->CurrentAction = $_POST['a_update'];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = 'I'; // Form error, reset action
				$this->setFailureMessage($_SESSION['gsFormError']);
			}
		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate('a_customerslist.php'); // No records selected, return to list
		switch ($this->CurrentAction) {
			case 'U': // Update
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == '')
						$this->setSuccessMessage($_SESSION['$Language']->Phrase('UpdateSuccess')); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
				break;
            default:
                echo 'it is not present in any case';
		}

		// Render row
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {
		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
					$this->Customer_Number->setDbValue($this->Recordset->fields('Customer_Number'));
					$this->Customer_Name->setDbValue($this->Recordset->fields('Customer_Name'));
					$this->Address->setDbValue($this->Recordset->fields('Address'));
					$this->City->setDbValue($this->Recordset->fields('City'));
					$this->Country->setDbValue($this->Recordset->fields('Country'));
					$this->Contact_Person->setDbValue($this->Recordset->fields('Contact_Person'));
					$this->Phone_Number->setDbValue($this->Recordset->fields('Phone_Number'));
					$this->_Email->setDbValue($this->Recordset->fields('Email'));
					$this->Mobile_Number->setDbValue($this->Recordset->fields('Mobile_Number'));
					$this->Notes->setDbValue($this->Recordset->fields('Notes'));
					$this->Balance->setDbValue($this->Recordset->fields('Balance'));
					$this->Date_Added->setDbValue($this->Recordset->fields('Date_Added'));
					$this->Added_By->setDbValue($this->Recordset->fields('Added_By'));
					$this->Date_Updated->setDbValue($this->Recordset->fields('Date_Updated'));
					$this->Updated_By->setDbValue($this->Recordset->fields('Updated_By'));
				} else {
					if (!ew_CompareValue($this->Customer_Number->DbValue, $this->Recordset->fields('Customer_Number')))
						$this->Customer_Number->CurrentValue = NULL;
					if (!ew_CompareValue($this->Customer_Name->DbValue, $this->Recordset->fields('Customer_Name')))
						$this->Customer_Name->CurrentValue = NULL;
					if (!ew_CompareValue($this->Address->DbValue, $this->Recordset->fields('Address')))
						$this->Address->CurrentValue = NULL;
					if (!ew_CompareValue($this->City->DbValue, $this->Recordset->fields('City')))
						$this->City->CurrentValue = NULL;
					if (!ew_CompareValue($this->Country->DbValue, $this->Recordset->fields('Country')))
						$this->Country->CurrentValue = NULL;
					if (!ew_CompareValue($this->Contact_Person->DbValue, $this->Recordset->fields('Contact_Person')))
						$this->Contact_Person->CurrentValue = NULL;
					if (!ew_CompareValue($this->Phone_Number->DbValue, $this->Recordset->fields('Phone_Number')))
						$this->Phone_Number->CurrentValue = NULL;
					if (!ew_CompareValue($this->_Email->DbValue, $this->Recordset->fields('Email')))
						$this->_Email->CurrentValue = NULL;
					if (!ew_CompareValue($this->Mobile_Number->DbValue, $this->Recordset->fields('Mobile_Number')))
						$this->Mobile_Number->CurrentValue = NULL;
					if (!ew_CompareValue($this->Notes->DbValue, $this->Recordset->fields('Notes')))
						$this->Notes->CurrentValue = NULL;
					if (!ew_CompareValue($this->Balance->DbValue, $this->Recordset->fields('Balance')))
						$this->Balance->CurrentValue = NULL;
					if (!ew_CompareValue($this->Date_Added->DbValue, $this->Recordset->fields('Date_Added')))
						$this->Date_Added->CurrentValue = NULL;
					if (!ew_CompareValue($this->Added_By->DbValue, $this->Recordset->fields('Added_By')))
						$this->Added_By->CurrentValue = NULL;
					if (!ew_CompareValue($this->Date_Updated->DbValue, $this->Recordset->fields('Date_Updated')))
						$this->Date_Updated->CurrentValue = NULL;
					if (!ew_CompareValue($this->Updated_By->DbValue, $this->Recordset->fields('Updated_By')))
						$this->Updated_By->CurrentValue = NULL;
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {
		$sKeyFld = $key;
		if (!is_numeric($sKeyFld))
			return FALSE;
		$this->Customer_ID->CurrentValue = $sKeyFld;
		return TRUE;
	}

	// Update all selected rows
	function UpdateRows() {

		$conn = &$this->Connection();
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();



		// Update all rows
		$sKey = '';
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
				$sThisKey = $key;
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}
			if (!$UpdateRows)
				break; // Update failed
			if ($sKey <> '') $sKey .= ', ';
			$sKey .= $sThisKey;
		}

		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset

		} else {
			$conn->RollbackTrans(); // Rollback transaction
		}
		return $UpdateRows;
	}

	// Get upload files
	function GetUploadFiles() {
		

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		
		if (!$this->Customer_Number->FldIsDetailKey) {
			$this->Customer_Number->setFormValue($_SESSION['objForm']->GetValue('x_Customer_Number'));
		}
		$this->Customer_Number->MultiUpdate = $_SESSION['objForm']->GetValue('u_Customer_Number');
		if (!$this->Customer_Name->FldIsDetailKey) {
			$this->Customer_Name->setFormValue($_SESSION['objForm']->GetValue('x_Customer_Name'));
		}
		$this->Customer_Name->MultiUpdate = $_SESSION['objForm']->GetValue('u_Customer_Name');
		if (!$this->Address->FldIsDetailKey) {
			$this->Address->setFormValue($_SESSION['objForm']->GetValue('x_Address'));
		}
		$this->Address->MultiUpdate = $_SESSION['objForm']->GetValue('u_Address');
		if (!$this->City->FldIsDetailKey) {
			$this->City->setFormValue($_SESSION['objForm']->GetValue('x_City'));
		}
		$this->City->MultiUpdate = $_SESSION['objForm']->GetValue('u_City');
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($_SESSION['objForm']->GetValue('x_Country'));
		}
		$this->Country->MultiUpdate = $_SESSION['objForm']->GetValue('u_Country');
		if (!$this->Contact_Person->FldIsDetailKey) {
			$this->Contact_Person->setFormValue($_SESSION['objForm']->GetValue('x_Contact_Person'));
		}
		$this->Contact_Person->MultiUpdate = $_SESSION['objForm']->GetValue('u_Contact_Person');
		if (!$this->Phone_Number->FldIsDetailKey) {
			$this->Phone_Number->setFormValue($_SESSION['objForm']->GetValue('x_Phone_Number'));
		}
		$this->Phone_Number->MultiUpdate = $_SESSION['objForm']->GetValue('u_Phone_Number');
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($_SESSION['objForm']->GetValue('x__Email'));
		}
		$this->_Email->MultiUpdate = $_SESSION['objForm']->GetValue('u__Email');
		if (!$this->Mobile_Number->FldIsDetailKey) {
			$this->Mobile_Number->setFormValue($_SESSION['objForm']->GetValue('x_Mobile_Number'));
		}
		$this->Mobile_Number->MultiUpdate = $_SESSION['objForm']->GetValue('u_Mobile_Number');
		if (!$this->Notes->FldIsDetailKey) {
			$this->Notes->setFormValue($_SESSION['objForm']->GetValue('x_Notes'));
		}
		$this->Notes->MultiUpdate = $_SESSION['objForm']->GetValue('u_Notes');
		if (!$this->Balance->FldIsDetailKey) {
			$this->Balance->setFormValue($_SESSION['objForm']->GetValue('x_Balance'));
		}
		$this->Balance->MultiUpdate = $_SESSION['objForm']->GetValue('u_Balance');
		if (!$this->Date_Added->FldIsDetailKey) {
			$this->Date_Added->setFormValue($_SESSION['objForm']->GetValue('x_Date_Added'));
			$this->Date_Added->CurrentValue = ew_UnFormatDateTime($this->Date_Added->CurrentValue, 0);
		}
		$this->Date_Added->MultiUpdate = $_SESSION['objForm']->GetValue('u_Date_Added');
		if (!$this->Added_By->FldIsDetailKey) {
			$this->Added_By->setFormValue($_SESSION['objForm']->GetValue('x_Added_By'));
		}
		$this->Added_By->MultiUpdate = $_SESSION['objForm']->GetValue('u_Added_By');
		if (!$this->Date_Updated->FldIsDetailKey) {
			$this->Date_Updated->setFormValue($_SESSION['objForm']->GetValue('x_Date_Updated'));
			$this->Date_Updated->CurrentValue = ew_UnFormatDateTime($this->Date_Updated->CurrentValue, 0);
		}
		$this->Date_Updated->MultiUpdate = $_SESSION['objForm']->GetValue('u_Date_Updated');
		if (!$this->Updated_By->FldIsDetailKey) {
			$this->Updated_By->setFormValue($_SESSION['objForm']->GetValue('x_Updated_By'));
		}
		$this->Updated_By->MultiUpdate = $_SESSION['objForm']->GetValue('u_Updated_By');
		if (!$this->Customer_ID->FldIsDetailKey)
			$this->Customer_ID->setFormValue($_SESSION['objForm']->GetValue('x_Customer_ID'));
	}

	// Restore form values
	function RestoreFormValues() {

		$this->Customer_ID->CurrentValue = $this->Customer_ID->FormValue;
		$this->Customer_Number->CurrentValue = $this->Customer_Number->FormValue;
		$this->Customer_Name->CurrentValue = $this->Customer_Name->FormValue;
		$this->Address->CurrentValue = $this->Address->FormValue;
		$this->City->CurrentValue = $this->City->FormValue;
		$this->Country->CurrentValue = $this->Country->FormValue;
		$this->Contact_Person->CurrentValue = $this->Contact_Person->FormValue;
		$this->Phone_Number->CurrentValue = $this->Phone_Number->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->Mobile_Number->CurrentValue = $this->Mobile_Number->FormValue;
		$this->Notes->CurrentValue = $this->Notes->FormValue;
		$this->Balance->CurrentValue = $this->Balance->FormValue;
		$this->Date_Added->CurrentValue = $this->Date_Added->FormValue;
		$this->Date_Added->CurrentValue = ew_UnFormatDateTime($this->Date_Added->CurrentValue, 0);
		$this->Added_By->CurrentValue = $this->Added_By->FormValue;
		$this->Date_Updated->CurrentValue = $this->Date_Updated->FormValue;
		$this->Date_Updated->CurrentValue = ew_UnFormatDateTime($this->Date_Updated->CurrentValue, 0);
		$this->Updated_By->CurrentValue = $this->Updated_By->FormValue;
	}


	// Validate form
	function ValidateForm() {
		

		// Initialize form error message
		$_SESSION['gsFormError'] = '';
		$lUpdateCnt = 0;
		if ($this->Customer_Number->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Customer_Name->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Address->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->City->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Country->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Contact_Person->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Phone_Number->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->_Email->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Mobile_Number->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Notes->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Balance->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Date_Added->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Added_By->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Date_Updated->MultiUpdate == '1') $lUpdateCnt++;
		if ($this->Updated_By->MultiUpdate == '1') $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$_SESSION['gsFormError'] = $_SESSION['Language']->Phrase('NoFieldSelected');
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($_SESSION['gsFormError'] == '');
		if ($this->Customer_Number->MultiUpdate <> '' && !$this->Customer_Number->FldIsDetailKey && !is_null($this->Customer_Number->FormValue) && $this->Customer_Number->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Customer_Number->FldCaption(), $this->Customer_Number->ReqErrMsg));
		}
		if ($this->Customer_Name->MultiUpdate <> '' && !$this->Customer_Name->FldIsDetailKey && !is_null($this->Customer_Name->FormValue) && $this->Customer_Name->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Customer_Name->FldCaption(), $this->Customer_Name->ReqErrMsg));
		}
		if ($this->Address->MultiUpdate <> '' && !$this->Address->FldIsDetailKey && !is_null($this->Address->FormValue) && $this->Address->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Address->FldCaption(), $this->Address->ReqErrMsg));
		}
		if ($this->City->MultiUpdate <> '' && !$this->City->FldIsDetailKey && !is_null($this->City->FormValue) && $this->City->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->City->FldCaption(), $this->City->ReqErrMsg));
		}
		if ($this->Country->MultiUpdate <> '' && !$this->Country->FldIsDetailKey && !is_null($this->Country->FormValue) && $this->Country->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Country->FldCaption(), $this->Country->ReqErrMsg));
		}
		if ($this->Contact_Person->MultiUpdate <> '' && !$this->Contact_Person->FldIsDetailKey && !is_null($this->Contact_Person->FormValue) && $this->Contact_Person->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Contact_Person->FldCaption(), $this->Contact_Person->ReqErrMsg));
		}
		if ($this->Phone_Number->MultiUpdate <> '' && !$this->Phone_Number->FldIsDetailKey && !is_null($this->Phone_Number->FormValue) && $this->Phone_Number->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Phone_Number->FldCaption(), $this->Phone_Number->ReqErrMsg));
		}
		if ($this->_Email->MultiUpdate <> '' && !$this->_Email->FldIsDetailKey && !is_null($this->_Email->FormValue) && $this->_Email->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->_Email->FldCaption(), $this->_Email->ReqErrMsg));
		}
		if ($this->Mobile_Number->MultiUpdate <> '' && !$this->Mobile_Number->FldIsDetailKey && !is_null($this->Mobile_Number->FormValue) && $this->Mobile_Number->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Mobile_Number->FldCaption(), $this->Mobile_Number->ReqErrMsg));
		}
		if ($this->Notes->MultiUpdate <> '' && !$this->Notes->FldIsDetailKey && !is_null($this->Notes->FormValue) && $this->Notes->FormValue == '') {
			ew_AddMessage($_SESSION['gsFormError'], str_replace('%s', $this->Notes->FldCaption(), $this->Notes->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($_SESSION['gsFormError'] == '');

		// Call Form_CustomValidate event
		$sFormCustomError = '';
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate();
		if ($sFormCustomError <> '') {
			ew_AddMessage($_SESSION['gsFormError'], $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {

		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $_SESSION['EW_ERROR_FN'];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($_SESSION['Language']->Phrase('NoRecord')); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Customer_Number
			$this->Customer_Number->SetDbValueDef($rsnew, $this->Customer_Number->CurrentValue, '', $this->Customer_Number->ReadOnly || $this->Customer_Number->MultiUpdate <> '1');

			// Customer_Name
			$this->Customer_Name->SetDbValueDef($rsnew, $this->Customer_Name->CurrentValue, '', $this->Customer_Name->ReadOnly || $this->Customer_Name->MultiUpdate <> '1');

			// Address
			$this->Address->SetDbValueDef($rsnew, $this->Address->CurrentValue, '', $this->Address->ReadOnly || $this->Address->MultiUpdate <> '1');

			// City
			$this->City->SetDbValueDef($rsnew, $this->City->CurrentValue, '', $this->City->ReadOnly || $this->City->MultiUpdate <> '1');

			// Country
			$this->Country->SetDbValueDef($rsnew, $this->Country->CurrentValue, '', $this->Country->ReadOnly || $this->Country->MultiUpdate <> '1');

			// Contact_Person
			$this->Contact_Person->SetDbValueDef($rsnew, $this->Contact_Person->CurrentValue, '', $this->Contact_Person->ReadOnly || $this->Contact_Person->MultiUpdate <> '1');

			// Phone_Number
			$this->Phone_Number->SetDbValueDef($rsnew, $this->Phone_Number->CurrentValue, '', $this->Phone_Number->ReadOnly || $this->Phone_Number->MultiUpdate <> '1');

			// Email
			$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, '', $this->_Email->ReadOnly || $this->_Email->MultiUpdate <> '1');

			// Mobile_Number
			$this->Mobile_Number->SetDbValueDef($rsnew, $this->Mobile_Number->CurrentValue, '', $this->Mobile_Number->ReadOnly || $this->Mobile_Number->MultiUpdate <> '1');

			// Notes
			$this->Notes->SetDbValueDef($rsnew, $this->Notes->CurrentValue, '', $this->Notes->ReadOnly || $this->Notes->MultiUpdate <> '1');

			// Balance
			$this->Balance->SetDbValueDef($rsnew, $this->Balance->CurrentValue, NULL, $this->Balance->ReadOnly || $this->Balance->MultiUpdate <> '1');

			// Date_Added
			$this->Date_Added->SetDbValueDef($rsnew, $this->Date_Added->CurrentValue, NULL, $this->Date_Added->ReadOnly || $this->Date_Added->MultiUpdate <> '1');

			// Added_By
			$this->Added_By->SetDbValueDef($rsnew, $this->Added_By->CurrentValue, NULL, $this->Added_By->ReadOnly || $this->Added_By->MultiUpdate <> '1');

			// Date_Updated
			$this->Date_Updated->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['Date_Updated'] = &$this->Date_Updated->DbValue;

			// Updated_By
			$this->Updated_By->SetDbValueDef($rsnew, CurrentUserName(), NULL);
			$rsnew['Updated_By'] = &$this->Updated_By->DbValue;

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $_SESSION['EW_ERROR_FN'];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> '' || $this->getFailureMessage() <> '') {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> '') {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = '';
				} else {
					$this->setFailureMessage($_SESSION['Language']->Phrase('UpdateCancelled'));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated();
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {

		$_SESSION['Breadcrumb'] = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), '/')+1);
        $_SESSION['Breadcrumb']->Add('list', $this->TableVar, $this->AddMasterUrl('a_customerslist.php'), '', $this->TableVar, TRUE);
		$PageId = 'update';
        $_SESSION['Breadcrumb']->Add('update', $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting() {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing($type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering() {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered() {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate() {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($a_customers_update)) $a_customers_update = new ca_customers_update();

// Page init
$a_customers_update->Page_Init();

// Page main
$a_customers_update->Page_Main();

// Begin of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
getCurrentPageTitle(ew_CurrentPage());

// End of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
// Global Page Rendering event (in userfn*.php)

Page_Rendering();

// Global auto switch table width style (in userfn*.php), by Masino Sinaga, January 7, 2015
AutoSwitchTableWidthStyle();

// Page Rendering event
$a_customers_update->Page_Render();
?>
<?php include_once 'header.php' ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "update";
var CurrentForm = fa_customersupdate = new ew_Form("fa_customersupdate", "update");



// Form_CustomValidate event
fa_customersupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fa_customersupdate.ValidateRequired = true;
<?php } else { ?>
fa_customersupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (MS_SHOW_PHPMAKER_BREADCRUMBLINKS) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if (MS_SHOW_MASINO_BREADCRUMBLINKS) { ?>
<?php echo htmlspecialchars(MasinoBreadcrumbLinks()); ?>
<?php } ?>
<?php if (MS_LANGUAGE_SELECTOR_VISIBILITY=='belowheader') { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $a_customers_update->ShowPageHeader(); ?>
<?php
$a_customers_update->ShowMessage();
?>
<form name="fa_customersupdate" id="fa_customersupdate" class="<?php echo $a_customers_update->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($a_customers_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $a_customers_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="a_customers">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php foreach ($a_customers_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_a_customersupdate">
	<div class="form-group">
		<label class="col-sm-2"><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"> <?php echo $Language->Phrase('UpdateSelectAll') ?></label>
	</div>
<?php if ($a_customers->Customer_Number->Visible) { // Customer_Number ?>
	<div id="r_Customer_Number" class="form-group">
		<label for="x_Customer_Number" class="col-sm-2 control-label">
<input type="checkbox" name="u_Customer_Number" id="u_Customer_Number" value="1"<?php echo ($a_customers->Customer_Number->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Customer_Number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Customer_Number->CellAttributes() ?>>
<span id="el_a_customers_Customer_Number">
<input type="text" data-table="a_customers" data-field="x_Customer_Number" name="x_Customer_Number" id="x_Customer_Number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($a_customers->Customer_Number->getPlaceHolder()) ?>" value="<?php echo $a_customers->Customer_Number->EditValue ?>"<?php echo $a_customers->Customer_Number->EditAttributes() ?>>
</span>
<?php echo $a_customers->Customer_Number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Customer_Name->Visible) { // Customer_Name ?>
	<div id="r_Customer_Name" class="form-group">
		<label for="x_Customer_Name" class="col-sm-2 control-label">
<input type="checkbox" name="u_Customer_Name" id="u_Customer_Name" value="1"<?php echo ($a_customers->Customer_Name->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Customer_Name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Customer_Name->CellAttributes() ?>>
<span id="el_a_customers_Customer_Name">
<input type="text" data-table="a_customers" data-field="x_Customer_Name" name="x_Customer_Name" id="x_Customer_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->Customer_Name->getPlaceHolder()) ?>" value="<?php echo $a_customers->Customer_Name->EditValue ?>"<?php echo $a_customers->Customer_Name->EditAttributes() ?>>
</span>
<?php echo $a_customers->Customer_Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Address->Visible) { // Address ?>
	<div id="r_Address" class="form-group">
		<label for="x_Address" class="col-sm-2 control-label">
<input type="checkbox" name="u_Address" id="u_Address" value="1"<?php echo ($a_customers->Address->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Address->CellAttributes() ?>>
<span id="el_a_customers_Address">
<textarea data-table="a_customers" data-field="x_Address" name="x_Address" id="x_Address" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($a_customers->Address->getPlaceHolder()) ?>"<?php echo $a_customers->Address->EditAttributes() ?>><?php echo $a_customers->Address->EditValue ?></textarea>
</span>
<?php echo $a_customers->Address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->City->Visible) { // City ?>
	<div id="r_City" class="form-group">
		<label for="x_City" class="col-sm-2 control-label">
<input type="checkbox" name="u_City" id="u_City" value="1"<?php echo ($a_customers->City->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->City->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->City->CellAttributes() ?>>
<span id="el_a_customers_City">
<input type="text" data-table="a_customers" data-field="x_City" name="x_City" id="x_City" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->City->getPlaceHolder()) ?>" value="<?php echo $a_customers->City->EditValue ?>"<?php echo $a_customers->City->EditAttributes() ?>>
</span>
<?php echo $a_customers->City->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Country->Visible) { // Country ?>
	<div id="r_Country" class="form-group">
		<label for="x_Country" class="col-sm-2 control-label">
<input type="checkbox" name="u_Country" id="u_Country" value="1"<?php echo ($a_customers->Country->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Country->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Country->CellAttributes() ?>>
<span id="el_a_customers_Country">
<input type="text" data-table="a_customers" data-field="x_Country" name="x_Country" id="x_Country" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($a_customers->Country->getPlaceHolder()) ?>" value="<?php echo $a_customers->Country->EditValue ?>"<?php echo $a_customers->Country->EditAttributes() ?>>
</span>
<?php echo $a_customers->Country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Contact_Person->Visible) { // Contact_Person ?>
	<div id="r_Contact_Person" class="form-group">
		<label for="x_Contact_Person" class="col-sm-2 control-label">
<input type="checkbox" name="u_Contact_Person" id="u_Contact_Person" value="1"<?php echo ($a_customers->Contact_Person->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Contact_Person->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Contact_Person->CellAttributes() ?>>
<span id="el_a_customers_Contact_Person">
<input type="text" data-table="a_customers" data-field="x_Contact_Person" name="x_Contact_Person" id="x_Contact_Person" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->Contact_Person->getPlaceHolder()) ?>" value="<?php echo $a_customers->Contact_Person->EditValue ?>"<?php echo $a_customers->Contact_Person->EditAttributes() ?>>
</span>
<?php echo $a_customers->Contact_Person->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Phone_Number->Visible) { // Phone_Number ?>
	<div id="r_Phone_Number" class="form-group">
		<label for="x_Phone_Number" class="col-sm-2 control-label">
<input type="checkbox" name="u_Phone_Number" id="u_Phone_Number" value="1"<?php echo ($a_customers->Phone_Number->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Phone_Number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Phone_Number->CellAttributes() ?>>
<span id="el_a_customers_Phone_Number">
<input type="text" data-table="a_customers" data-field="x_Phone_Number" name="x_Phone_Number" id="x_Phone_Number" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->Phone_Number->getPlaceHolder()) ?>" value="<?php echo $a_customers->Phone_Number->EditValue ?>"<?php echo $a_customers->Phone_Number->EditAttributes() ?>>
</span>
<?php echo $a_customers->Phone_Number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label for="x__Email" class="col-sm-2 control-label">
<input type="checkbox" name="u__Email" id="u__Email" value="1"<?php echo ($a_customers->_Email->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->_Email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->_Email->CellAttributes() ?>>
<span id="el_a_customers__Email">
<input type="text" data-table="a_customers" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($a_customers->_Email->getPlaceHolder()) ?>" value="<?php echo $a_customers->_Email->EditValue ?>"<?php echo $a_customers->_Email->EditAttributes() ?>>
</span>
<?php echo $a_customers->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Mobile_Number->Visible) { // Mobile_Number ?>
	<div id="r_Mobile_Number" class="form-group">
		<label for="x_Mobile_Number" class="col-sm-2 control-label">
<input type="checkbox" name="u_Mobile_Number" id="u_Mobile_Number" value="1"<?php echo ($a_customers->Mobile_Number->MultiUpdate == '1') ? ' checked': '' ?>>
 <?php echo $a_customers->Mobile_Number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Mobile_Number->CellAttributes() ?>>
<span id="el_a_customers_Mobile_Number">
<input type="text" data-table="a_customers" data-field="x_Mobile_Number" name="x_Mobile_Number" id="x_Mobile_Number" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->Mobile_Number->getPlaceHolder()) ?>" value="<?php echo $a_customers->Mobile_Number->EditValue ?>"<?php echo $a_customers->Mobile_Number->EditAttributes() ?>>
</span>
<?php echo $a_customers->Mobile_Number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Notes->Visible) { // Notes ?>
	<div id="r_Notes" class="form-group">
		<label for="x_Notes" class="col-sm-2 control-label">
<input type="checkbox" name="u_Notes" id="u_Notes" value="1"<?php echo ($a_customers->Notes->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Notes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Notes->CellAttributes() ?>>
<span id="el_a_customers_Notes">
<input type="text" data-table="a_customers" data-field="x_Notes" name="x_Notes" id="x_Notes" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($a_customers->Notes->getPlaceHolder()) ?>" value="<?php echo $a_customers->Notes->EditValue ?>"<?php echo $a_customers->Notes->EditAttributes() ?>>
</span>
<?php echo $a_customers->Notes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($a_customers->Balance->Visible) { // Balance ?>
	<div id="r_Balance" class="form-group">
		<label for="x_Balance" class="col-sm-2 control-label">
<input type="checkbox" name="u_Balance" id="u_Balance" value="1"<?php echo ($a_customers->Balance->MultiUpdate == '1') ? ' checked' : '' ?>>
 <?php echo $a_customers->Balance->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $a_customers->Balance->CellAttributes() ?>>
<span id="el_a_customers_Balance">
<input type="text" data-table="a_customers" data-field="x_Balance" name="x_Balance" id="x_Balance" size="30" placeholder="<?php echo ew_HtmlEncode($a_customers->Balance->getPlaceHolder()) ?>" value="<?php echo $a_customers->Balance->EditValue ?>"<?php echo $a_customers->Balance->EditAttributes() ?>>
<?php if (!$a_customers->Balance->ReadOnly && !$a_customers->Balance->Disabled && $a_customers->Balance->EditAttrs['readonly'] == '' && $a_customers->Balance->EditAttrs['disabled'] == '') { ?>
<script type="text/javascript">
$('#x_Balance').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php echo $a_customers->Balance->CustomMsg ?></div></div>
	</div>
<?php } ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase('UpdateBtn') ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $a_customers_update->getReturnUrl() ?>"><?php echo $Language->Phrase('CancelBtn') ?></button>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
fa_customersupdate.Init();
</script>
<?php
$a_customers_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php if (MS_ENTER_MOVING_CURSOR_TO_NEXT_FIELD) { ?>
<script type="text/javascript">
$(document).ready(function(){$("#fa_customersupdate:first *:input[type!=hidden]:first").focus(),$("input").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("select").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()}),$("radio").keydown(function(i){if(13==i.which){var e=$(this).closest("form").find(":input:visible:enabled"),n=e.index(this);n==e.length-1||(e.eq(e.index(this)+1).focus(),i.preventDefault())}else 113==i.which&&$("#btnAction").click()})});
</script>
<?php } ?>
<?php if ($a_customers->Export == '') { ?>
<script type="text/javascript">
$('#btnAction').attr('onclick', 'return alertifyUpdate(this)'); function alertifyUpdate(obj) { <?php global $Language; ?> if (fa_customersupdate.Validate() == true ) { alertify.confirm("<?php echo  $Language->Phrase('AlertifyEditConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifyEdit'); ?>"); $("#fa_customersupdate").submit(); } }).set("title", "<?php echo $Language->Phrase('AlertifyConfirm'); ?>").set("defaultFocus", "cancel").set('oncancel', function(closeEvent){ alertify.error('<?php echo $Language->Phrase('AlertifyCancel'); ?>');}).set('labels', {ok:'<?php echo $Language->Phrase("MyOKMessage"); ?>!', cancel:'<?php echo $Language->Phrase("MyCancelMessage"); ?>'}); } return false; }
</script>
<?php } ?>
<?php include_once 'footer.php' ?>
<?php
$a_customers_update->Page_Terminate();
?>
