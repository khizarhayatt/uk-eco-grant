<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-items-center tw-space-x-4 tw-mb-4">
                    <a href="<?php echo admin_url('reports/leads?type=staff'); ?>" class="btn btn-success">
                        <?php echo _l('switch_to_general_report'); ?>
                    </a>
                    <p class="text-info inline-block" data-placement="bottom" data-toggle="tooltip"
                        data-title="<?php echo _l('leads_report_converted_notice'); ?>">
                        <i class="fa-regular fa-circle-question fa-lg"></i>
                    </p>
                </div>
            </div>
            <div class="col-md-6 animated fadeIn">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo _l('report_this_week_leads_conversions'); ?></h3>
                    </div>
                    <div class="panel-body">
                        <canvas class="leads-this-week" height="150" id="leads-this-week"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animated fadeIn">
                <div class="panel_s">
                    <div class="panel-heading">
                           <div style="display:flex; flex-direction:row;justify-content: space-between; align-items: center;">
                        <h3 class="panel-title"> Sources <span id="leads_sources_type"></span> </h3>
                        <!-- <h3 class="panel-title"><?php echo _l('report_leads_sources_conversions'); ?></h3> -->
                         <select class="selectpicker" id="leads-sources">
                        <?php foreach($statuses as $status){ ?>
                            <option value="<?php echo $status['id'] ?>"><?php echo $status['name'] ?></option>
                        <?php } ?>
                         </select>
                        
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas class="leads-sources-report" height="150" id="leads-sources-report"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 animated fadeIn">
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo _l('report_leads_monthly_conversions'); ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                            echo '<select name="month" class="selectpicker" data-none-selected-text="' . _l('dropdown_non_selected_tex') . '">' . PHP_EOL;
                            for ($m = 1; $m <= 12; $m++) {
                                $_selected = '';
                                if ($m == date('m')) {
                                    $_selected = ' selected';
                                }
                                echo '  <option value="' . $m . '"' . $_selected . '>' . _l(date('F', mktime(0, 0, 0, $m, 1))) . '</option>' . PHP_EOL;
                            }
                      echo '</select>' . PHP_EOL;
                      ?>
                            </div>
                        </div>
                        <div class="relative" style="max-height:400px;">
                            <canvas class="leads-monthly chart mtop20" id="leads-monthly" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
var MonthlyLeadsChart;
var leads_sources_report;
$(document).ready(function(){
        leads_sources_type(<?php echo $statuses[0]['id'] ?>, "<?php echo $statuses[0]['name'] ?>")
});
$(function() {
    $.get(admin_url + 'reports/leads_monthly_report/' + $('select[name="month"]').val(), function(response) {
        var ctx = $('#leads-monthly').get(0).getContext('2d');
        MonthlyLeadsChart = new Chart(ctx, {
            'type': 'bar',
            data: response,
            options: {
                responsive: true,
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },
            },
        });
    }, 'json');
    $('select[name="month"]').on('change', function() {
        MonthlyLeadsChart.destroy();
        $.get(admin_url + 'reports/leads_monthly_report/' + $('select[name="month"]').val(), function(
            response) {
            var ctx = $('#leads-monthly').get(0).getContext('2d');
            MonthlyLeadsChart = new Chart(ctx, {
                'type': 'bar',
                data: response,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            }
                        }]
                    },
                },
            });
        }, 'json');
    });

    new Chart($("#leads-this-week"), {
        type: 'pie',
        data: <?php echo $leads_this_week_report; ?>,
        option: {
            responsive: true
        }
    });

    // new Chart($('#leads-sources-report'), {
    //     type: 'bar',
    //     data: <?php echo $leads_sources_report; ?>,
    //     options: {
    //         responsive: true,
    //         legend: {
    //             display: false,
    //         },
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     beginAtZero: true,
    //                 }
    //             }]
    //         },
    //     },
    // });
});

$('#leads-sources').on('change',function(){
    leads_sources_type($('#leads-sources option:selected').val(),$('#leads-sources option:selected').text())
})

function leads_sources_type(id, lable){
   $('#leads_sources_type').text(lable)
   $.ajax({
   url: admin_url + '/reports/leadsReportByStatus/'+id, 
   type: 'GET',
   dataType: 'json',
   success: function(data) {
    leads_sources_report && leads_sources_report.destroy();
    
       leads_sources_report = new Chart($('#leads-sources-report'), {
       type: 'bar',
       data: data,
       options: {
           responsive: true,
           legend: {
               display: false,
           },
           scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero: true,
                   }
               }]
           },
       },
   });
   },
   error: function(xhr, status, error) {
       console.error('Error:', status, error);
   }
});
}
</script>
</body>

</html>
