<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-5ths">
                            <label for="period-from">From</label>
                            <?php echo render_date_input('period-from'); ?>
                        </div>
                        
                        <div class="col-md-5ths">
                                <label for="period-to">To</label>
                                <?php echo render_date_input('period-to'); ?>
                            </div>

                            <div class="col-md-5ths">
                            
                                <div class="select-placeholder">
                                <?php echo render_leads_status_select($statuses, '', 'lead_add_edit_status'); ?>
                                </div>
                            </div>
                            <div class="col-md-5ths">
                            
                                <div class="select-placeholder">
                                <?php echo     render_leads_source_select($sources, '', 'lead_add_edit_source');  ?>
                                </div>
                            </div>
                            <div class="col-md-5ths lead-report-btn " >
                                    <a href="#" id="apply_filters_leads" class="btn btn-primary pull-left  mt-4"><?php echo _l('apply'); ?></a>
                             </div>  
                             <hr class="py-4">
                        </div>
                         
                        <div class="clearfix"></div>
                        <table class="table table-lead-report">
                            <thead>
                                <tr>
                                    <th>Lead Source</th>
                                    <th>Workflow Status</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Postcode</th>
                                    <th>Phone Number</th>
                                    <th>Date Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data rows will be dynamically inserted here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <div class="pull-right">
                                            <p>Date Range: <span id="date-range"></span></p>
                                            <p>Total Leads: <span id="total-leads"></span></p>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
 
 $(function() {
    var leadTable = $('.table-lead-report');
    
    $('#apply_filters_leads').on('click', function(e) {
        e.preventDefault();
        leadTable.DataTable().ajax.reload();
    });

    var Leads_ServerParams = {};
    Leads_ServerParams['period-from'] = '[name="period-from"]';
    Leads_ServerParams['period-to'] = '[name="period-to"]';
    Leads_ServerParams['lead_status'] = '[name="lead_add_edit_status"]';
    Leads_ServerParams['lead_source'] = '[name="lead_add_edit_source"]';

    initDataTable('.table-lead-report', admin_url + 'reports/workflows_report_data', undefined, undefined, Leads_ServerParams, [7, 'desc']);

    leadTable.on('draw.dt', function() {
        var LeadTable = $(this).DataTable();
        var ajaxData = LeadTable.ajax.json();
        
        // Update date range in the footer
        $('#date-range').text(ajaxData.period_from + ' - ' + ajaxData.period_to);
        
        // Update total leads count in the footer
        $('#total-leads').text(ajaxData.total_leads);
    });

    // Initialize date pickers
    $('input[name="period-from"], input[name="period-to"]').datepicker({
        format: app.options.date_format,
        autoclose: true
    });

    // Initialize select2 for status and source dropdowns
    $('select[name="lead_add_edit_status"], select[name="lead_add_edit_source"]').select2({
        theme: 'bootstrap'
    });
});

// Helper function to initialize DataTables (assuming this is defined elsewhere in your codebase)
function initDataTable(selector, url, notsearchable, notsortable, fnserverparams, defaultorder) {
    var table = typeof(selector) == 'string' ? $("body").find(selector) : selector;

    if (table.length === 0) {
        return false;
    }

    fnserverparams = typeof(fnserverparams) == 'undefined' ? [] : fnserverparams;

    // Parse the server params
    var serverData = {};
    if (fnserverparams) {
        $.each(fnserverparams, function(i, obj) {
            serverData[obj] = function() {
                return $(obj).val();
            }
        });
    }

    var options = {
        "serverSide": true,
        "retrieve": true,
        "processing": true,
        'paginate': true,
        "bLengthChange": false,
        "pageLength": app.options.tables_pagination_limit,
        "order": defaultorder,
        "language": app.lang.datatables,
        "ajax": {
            "url": url,
            "type": "POST",
            "data": serverData,
        },
        "initComplete": function(settings, json) {
            $('.dataTables_filter').addClass('hide');
        }
    };

    table = table.dataTable(options);
    return table;
}

 
</script>
</body>

</html>