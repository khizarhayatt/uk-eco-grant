<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
	<div class="content">
		<?php if (
			isset($client) &&
			$client->registration_confirmed == 0 &&
			is_admin()
		) { ?>
			<div class="alert alert-warning">
				<h4>
					<?php echo _l("customer_requires_registration_confirmation"); ?>
				</h4>
				<a href="<?php echo admin_url(
								"clients/confirm_registration/" . $client->userid
							); ?>">
					<?php echo _l("confirm_registration"); ?>
				</a>
			</div>
		<?php } elseif (
			isset($client) &&
			$client->active == 0 &&
			$client->registration_confirmed == 1
		) { ?>
			<div class="alert alert-warning">
				<?php echo _l("customer_inactive_message"); ?>
				<br />
				<a href="<?php echo admin_url("clients/mark_as_active/" . $client->userid); ?>">
					<?php echo _l("mark_as_active"); ?>
				</a>
			</div>
		<?php } ?>
		<?php if (
			isset($client) &&
			(staff_cant("view", "customers") && is_customer_admin($client->userid))
		) { ?>
			<div class="alert alert-info">
				<?php echo e(
					_l(
						"customer_admin_login_as_client_message",
						get_staff_full_name(get_staff_user_id())
					)
				); ?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-3">
				<?php if (isset($client)) { ?>
					<h4 class="tw-text-lg tw-font-semibold tw-text-neutral-800 tw-mt-0">
						<div class="tw-space-x-3 tw-flex tw-items-center">
							<span class="tw-truncate">
								#<?php echo $client->userid . " " . $title; ?>
							</span>
							<?php if (staff_can("delete", "customers") || is_admin()) { ?>
								<div class="btn-group"> 
									<?php if (staff_can("delete", "customers")) { ?>
											<a href="<?php echo admin_url("clients/delete/" . $client->userid); ?>" class="text-white btn-danger btn delete-text _delete"><i class="fa fa-trash"></i>
													<?php echo _l("delete"); ?>
												</a>
										<?php } ?> 
								</div>
							<?php } ?>
						</div>
						 
					</h4>
				<?php } ?>
			</div>
			<div class="clearfix"></div>

			<?php if (isset($client)) { ?>
				<div class="col-md-3">
					<?php $this->load->view("admin/clients/tabs"); ?>
				</div>
			<?php } ?>

			<div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($client)
												? "col-md-9"
												: "col-md-8 col-md-offset-2"; ?>">
				<div class="panel_s">
					<div class="panel-body">
						<?php if (isset($client)) { ?>
							<?php echo form_hidden("isedit"); ?>
							<?php echo form_hidden("userid", $client->userid); ?>
							<div class="clearfix"></div>
						<?php } ?>
						<div>
							<div class="tab-content">
								<!-- Nav tabs -->


								<?php $this->load->view(isset($tab) ? $tab["view"] : "admin/clients/groups/profile"); ?>
								<?php if ($group == "profilea") { ?>
									<div class="panel-footer text-right tw-space-x-1" id="profile-save-section">
										<?php if (!isset($client)) { ?>
											<button class="btn btn-default save-and-add-contact customer-form-submiter">
												<?php echo _l("save_customer_and_add_contact"); ?>
											</button>
										<?php } ?>
										<button class="btn btn-primary only-save customer-form-submiter">
											<?php echo _l("submit"); ?>
										</button>
									</div>
								<?php } ?>



							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<?php init_tail(); ?>
<?php if (isset($client)) { ?>
	<script>
		$(function() {
			init_rel_tasks_table(<?php echo e($client->userid); ?>, 'customer');
		});
	</script>
<?php } ?>
<?php $this->load->view("admin/clients/client_js"); ?>
</body>

</html>