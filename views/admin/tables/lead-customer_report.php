<?php defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [
    '#',
    'Lead ID',
    'Customer ID',
    'Lead Status',
    'Lead Source',
    'Last Workflow',
    'Last Survey',
];

$aColumns = [
    'tblleads.id as lead_id',
    'tblclients.userid as customer_id',
    'tblleads_status.name as lead_status',
    'tblleads_sources.name as lead_source',
    'MAX(CASE WHEN tblclient_worflow_statuses.status_type = "under_installation" THEN "Under Installation"
              WHEN tblclient_worflow_statuses.status_type = "under_submission" THEN "Under Submission"
              ELSE NULL END) as last_workflow',
    'MAX(CASE WHEN tblclient_survey_statuses.status_type = "awaiting_submission" THEN "Awaiting Submission"
              WHEN tblclient_survey_statuses.status_type = "awaiting_doc_verification" THEN "Awaiting Doc Verification"
              WHEN tblclient_survey_statuses.status_type = "awaiting_installation" THEN "Awaiting Installation"
              ELSE NULL END) as last_survey'
];

$sIndexColumn = "lead_id";
$sTable       = 'tblleads';

$join = [
    'LEFT JOIN tblclients ON tblclients.userid = tblleads.client_id',
    'LEFT JOIN tblleads_status ON tblleads_status.id = tblleads.status',
    'LEFT JOIN tblleads_sources ON tblleads_sources.id = tblleads.source',
    'LEFT JOIN tblclient_worflow_statuses ON tblclient_worflow_statuses.customer_id = tblclients.userid',
    'LEFT JOIN tblclient_survey_statuses ON tblclient_survey_statuses.customer_id = tblclients.userid'
];

$additionalSelect = [
    'tblleads.id as lead_id',
    'tblclients.userid as customer_id'
];

$custom_fields = get_custom_fields('leads', ['show_on_table' => 1]);
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN tblcustomfieldsvalues as ctable_' . $key . ' ON tblleads.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];

// If not admin, only show own leads
if (!is_admin()) {
    array_push($where, 'AND tblleads.assigned = ' . get_staff_user_id());
}

$group_by = 'GROUP BY tblleads.id';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $group_by);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'lead_id') {
            $_data = '<a href="' . admin_url('leads/index/' . $aRow['lead_id']) . '" onclick="init_lead(' . $aRow['lead_id'] . ');return false;">' . $_data . '</a>';
        } elseif ($aColumns[$i] == 'customer_id') {
            $_data = $aRow['customer_id'] ? '<a href="' . admin_url('clients/client/' . $aRow['customer_id']) . '">' . $aRow['customer_id'] . '</a>' : 'N/A';
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}

echo json_encode($output);
die();
?>