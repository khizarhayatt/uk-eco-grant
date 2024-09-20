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
                                    <?php echo render_leads_source_select($sources, '', 'lead_add_edit_source'); ?>
                                </div>
                            </div>
                            <div class="col-md-5ths lead-report-btn">
                                <a href="#" id="apply_filters_leads" class="btn btn-primary pull-left mt-4"><?php echo _l('apply'); ?></a>
                            </div>
                            <hr class="py-4">
                        </div>
                        <div class="clearfix"></div>
                        <table class="table dt-table" id="leads-report-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lead ID</th>
                                    <th>Customer ID</th>
                                    <th>Lead Status</th>
                                    <th>Lead Source</th>
                                    <!-- <th>Last Workflow</th>
                                    <th>Last Survey</th> -->
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    console.log('<?php echo admin_url('reports/workflows_report_data'); ?>');

    $(function() {
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#leads-report-table')) {
            $('#leads-report-table').DataTable().clear().destroy();
        }

        // Initialize DataTable
        var table = $('#leads-report-table').DataTable({
            "processing": true,
            "serverSide": false, // Client-side processing
            "ajax": {
                "url": '<?php echo admin_url('reports/workflows_report_data'); ?>',
                "type": "POST",
                "dataSrc": function(json) {
                    console.log('Received data:', json);

                    // If there is an error, display an alert and return an empty array
                    if (json.error) {
                        console.error('Server returned an error:', json.error);
                        alert(json.error); // Show error to user
                        return [];
                    }

                    // Log each row for debugging
                    json.data.forEach(row => {
                        console.log(row);
                    });

                    // Return the data for the table
                    return json.data || [];
                },
                "data": function(d) {
                    d.period_from = $('#period-from').val();
                    d.period_to = $('#period-to').val();
                    d.lead_status = $('select[name="lead_add_edit_status"]').val();
                    d.lead_source = $('select[name="lead_add_edit_source"]').val();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching data: " + textStatus, errorThrown);
                    console.error("Response: " + jqXHR.responseText);
                    alert("An error occurred while fetching the report data.");
                }
            },
            "columns": [
                {
                    "data": null, // For row index
                    "render": function (data, type, row, meta) {
                        return meta.row + 1; // Row index number
                    }
                },
                { "data": "lead_id" },       // Lead ID
                { "data": "customer_id", "defaultContent": "" }, // Customer ID (can be null)
                { "data": "lead_status", "defaultContent": "" }, // Lead Status (can be null)
                { "data": "lead_source", "defaultContent": "" }  // Lead Source (can be null)
            ],
            "order": [[1, 'asc']], // Order by Lead ID
            "language": {
                "emptyTable": "No data available"
            }
        });

        // Apply filters and reload table
        $('#apply_filters_leads').on('click', function(e) {
            e.preventDefault();
            table.ajax.reload(); // Reload the DataTable with the new filters
        });
    });

    
</script>

</body>
</html>
