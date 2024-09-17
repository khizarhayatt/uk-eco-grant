<?php defined('BASEPATH') or exit('No direct script access allowed');
if(!isset($filter_table_name)){
    $filter_table_name = '.table-expenses';
}
?>
<div class="_filters _hidden_inputs hidden">
   <?php echo form_hidden('billable');
   echo form_hidden('non-billable');
   echo form_hidden('invoiced');
   echo form_hidden('unbilled');
   echo form_hidden('recurring');
   foreach($years as $year){
    echo form_hidden('year_'.$year['year'],$year['year']);
}
for ($m = 1; $m <= 12; $m++) {
   echo form_hidden('expenses_by_month_'.$m);
}
foreach($categories as $category){
 echo form_hidden('expenses_by_category_'.$category['id']);
}

foreach($payment_modes as $mode){
   echo form_hidden('expense_payments_by_'.$mode['id']);
}
?>
</div>
<div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-right width300">
        <li>
            <a href="#" data-cview="all" onclick="dt_custom_view('','<?php echo e($filter_table_name); ?>',''); return false;">
                <?php echo _l('expenses_list_all'); ?>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#" data-cview="billable" onclick="dt_custom_view('billable','<?php echo e($filter_table_name); ?>','billable'); return false;">
                <?php echo _l('expenses_list_billable'); ?>
            </a>
        </li>
        <li>
            <a href="#" data-cview="non-billable" onclick="dt_custom_view('non-billable','<?php echo e($filter_table_name); ?>','non-billable'); return false;">
                <?php echo _l('expenses_list_non_billable'); ?>
            </a>
        </li>
        <li>
        <a href="#" data-cview="invoiced" onclick="dt_custom_view('invoiced','<?php echo e($filter_table_name); ?>','invoiced'); return false;">
                <?php echo _l('expenses_list_invoiced'); ?>
            </a>
        </li>
        <li>
            <a href="#" data-cview="unbilled" onclick="dt_custom_view('unbilled','<?php echo e($filter_table_name); ?>','unbilled'); return false;">
                <?php echo _l('expenses_list_unbilled'); ?>
            </a>
        </li>
        <li>
            <a href="#" data-cview="recurring" onclick="dt_custom_view('recurring','<?php echo e($filter_table_name); ?>','recurring'); return false;">
                <?php echo _l('expenses_list_recurring'); ?>
            </a>
        </li>
        <?php if(count($years) > 0){ ?>
            <li class="divider years-divider"></li>
            <?php foreach($years as $year){ ?>
                <li class="active expenses-filter-year">
                    <a href="#" data-cview="year_<?php echo e($year['year']); ?>" onclick="dt_custom_view(<?php echo e($year['year']); ?>,'<?php echo e($filter_table_name); ?>','year_<?php echo e($year['year']); ?>'); return false;"><?php echo e($year['year']); ?></a>
                </li>
                <?php } ?>
                <?php } ?>
                <?php if(count($categories) > 0){ ?>
                   <div class="clearfix"></div>
                   <li class="divider"></li>
                   <li class="dropdown-submenu pull-left">
                     <a href="#" tabindex="-1"><?php echo _l('expenses_filter_by_categories'); ?></a>
                     <ul class="dropdown-menu dropdown-menu-left">
                        <?php foreach($categories as $category){ ?>
                            <li>
                                <a href="#" data-cview="expenses_by_category_<?php echo e($category['id']); ?>" onclick="dt_custom_view(<?php echo e($category['id']); ?>,'<?php echo e($filter_table_name); ?>','expenses_by_category_<?php echo e($category['id']); ?>'); return false;"><?php echo e($category['name']); ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <li class="divider months-divider"></li>
                    <li class="dropdown-submenu pull-left expenses-filter-month-wrapper">
                      <a href="#" tabindex="-1"><?php echo _l('months'); ?></a>
                      <ul class="dropdown-menu dropdown-menu-left">
                        <?php for ($m = 1; $m <= 12; $m++) { ?>
                          <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_<?php echo e($m); ?>" onclick="dt_custom_view(<?php echo e($m); ?>,'<?php echo e($filter_table_name); ?>','expenses_by_month_<?php echo e($m); ?>'); return false;"><?php echo _l(date('F', mktime(0, 0, 0, $m, 1))); ?></a></li>
                          <?php } ?>
                      </ul>
                  </li>
                <div class="clearfix"></div>
                <?php if(count($payment_modes) > 0){ ?>
                    <li class="divider"></li>
                <?php } ?>
                <?php foreach($payment_modes as $mode){
                    if(total_rows(db_prefix().'expenses',array('paymentmode'=>$mode['id'])) == 0){continue;}
                    ?>
                    <li>
                        <a href="#" data-cview="expense_payments_by_<?php echo e($mode['id']); ?>" onclick="dt_custom_view('<?php echo e($mode['id']); ?>','<?php echo e($filter_table_name); ?>','expense_payments_by_<?php echo e($mode['id']); ?>'); return false;">
                            <?php echo _l('expenses_list_made_payment_by',$mode['name']); ?>
                        </a>
                    </li>
                <?php } ?>
              </ul>
          </div>
