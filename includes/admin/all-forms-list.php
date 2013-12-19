<?php

function ninja_forms_admin_all_forms() {
	$all_forms = ninja_forms_get_all_forms();
	$form_count = count($all_forms);

	if( isset( $_REQUEST['limit'] ) ){
		$saved_limit = absint( $_REQUEST['limit'] );
		$limit = absint( $_REQUEST['limit'] );
	}else{
		$saved_limit = 20;
		$limit = 20;
	}

	if( $form_count < $limit ){
		$limit = $form_count;
	}

	if( isset( $_REQUEST['paged']) AND !empty( $_REQUEST['paged'] ) ){
		$current_page = absint( $_REQUEST['paged'] );
	}else{
		$current_page = 1;
	}

	if( $form_count > $limit ){
		$page_count = ceil( $form_count / $limit );
	}else{
		$page_count = 1;
	}

	if( $current_page > 1 ){
		$start = ( ( $current_page - 1 ) * $limit );
		if( $form_count < $limit ){
			$end = $form_count;
		}else{
			$end = $current_page * $limit;
			$end = $end - 1;
		}

		if( $end > $form_count ){
			$end = $form_count;
		}
	}else{
		$start = 0;
		$end = $limit;
	}

	?>
	<div id="icon-ninja-custom-forms" class="icon32"><br></div>
	<h2><a href="#new_form" rel="modal:open"><input type="button" id="btn_new_form" class="open-settings-modal button button-primary" value="<?php _e( 'New Form', 'ninja-forms' );?>"></a></h2>

	<ul class="subsubsub">
		<li class="all"><a href="" class="current"><?php _e( 'All', 'ninja-forms' ); ?> <span class="count">(<?php echo $form_count;?>)</span></a>
	</ul>
	<div id="" class="tablenav top">
		<div class="alignleft actions">
			<select id="" class="" name="bulk_action">
				<option value=""><?php _e( 'Bulk Actions', 'ninja-forms' );?></option>
				<option value="delete"><?php _e( 'Delete', 'ninja-forms' );?></option>
				<!-- <option value="export"><?php _e( 'Export Forms', 'ninja-forms' );?></option> -->
			</select>
			<input type="submit" name="submit" value="<?php _e( 'Apply', 'ninja-forms' ); ?>" class="button-secondary">
		</div>
		<div class="alignleft actions">
			<select id="" name="limit">
				<option value="20" <?php selected($saved_limit, 20);?>>20</option>
				<option value="50" <?php selected($saved_limit, 50);?>>50</option>
				<option value="100" <?php selected($saved_limit, 100);?>>100</option>
			</select>
			<?php _e( 'Forms Per Page', 'ninja-forms' ); ?>
			<input type="submit" name="submit" value="<?php _e( 'Go', 'ninja-forms' ); ?>" class="button-secondary">
		</div>
		<div id="" class="alignright navtable-pages">
			<?php
			if($form_count != 0 AND $current_page <= $page_count){
			?>
			<span class="displaying-num"><?php if($start == 0){ echo 1; }else{ echo $start; }?> - <?php echo $end;?> <?php _e( 'of', 'ninja-forms' ); ?> <?php echo $form_count;?> <?php if($form_count == 1){ _e( 'Form', 'ninja-forms' ); }else{ _e( 'Forms', 'ninja-forms' ); }?></span>
			<?php
			}
				if($page_count > 1){

					$first_page = remove_query_arg('paged');
					$last_page = add_query_arg(array('paged' => $page_count));

					if($current_page > 1){
						$prev_page = $current_page - 1;
						$prev_page = add_query_arg(array('paged' => $prev_page));
					}else{
						$prev_page = $first_page;
					}
					if($current_page != $page_count){
						$next_page = $current_page + 1;
						$next_page = add_query_arg(array('paged' => $next_page));
					}else{
						$next_page = $last_page;
					}

			?>
			<span class="pagination-links">
				<a class="first-page disabled" title="<?php _e( 'Go to the first page', 'ninja-forms' ); ?>" href="<?php echo $first_page;?>">«</a>
				<a class="prev-page disabled" title="<?php _e( 'Go to the previous page', 'ninja-forms' ); ?>" href="<?php echo $prev_page;?>">‹</a>
				<span class="paging-input"><input class="current-page" title="Current page" type="text" name="paged" value="<?php echo $current_page;?>" size="2"> of <span class="total-pages"><?php echo $page_count;?></span></span>
				<a class="next-page" title="<?php _e( 'Go to the next page', 'ninja-forms' ); ?>" href="<?php echo $next_page;?>">›</a>
				<a class="last-page" title="<?php _e( 'Go to the last page', 'ninja-forms' ); ?>" href="<?php echo $last_page;?>">»</a>
			</span>
			<?php
				}
			?>
		</div>
	</div>
	<table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th class="check-column"><input type="checkbox" id="" class="ninja-forms-select-all" title="ninja-forms-bulk-action"></th>
				<th><?php _e( 'Form Title', 'ninja-forms' );?></th>
				<th><?php _e( 'Shortcode', 'ninja-forms' );?></th>
				<th><?php _e( 'Template Function', 'ninja-forms' );?></th>
				<th><?php _e( 'Date Updated', 'ninja-forms' );?></th>
			</tr>
		</thead>
		<tbody>
	<?php
	if(is_array($all_forms) AND !empty($all_forms) AND $current_page <= $page_count){
		for ($i = $start; $i < $end; $i++) {
			$form_id = $all_forms[$i]['id'];
			$data = $all_forms[$i]['data'];
			$date_updated = $all_forms[$i]['date_updated'];
			$date_updated = strtotime( $date_updated );
			$date_updated = date_i18n( __( 'F d, Y', 'ninja-forms' ), $date_updated );
			$edit_link = esc_url( add_query_arg( array( 'form_id' => $form_id ), admin_url( 'admin.php?page=ninja-forms-edit' ) ) );
			$subs_link = admin_url( 'admin.php?page=ninja-forms-subs&form_id='.$form_id );
			$export_link = esc_url( add_query_arg( array( 'export_form' => 1, 'form_id' => $form_id ) ) );
			$duplicate_link = esc_url( add_query_arg( array( 'duplicate_form' => 1, 'form_id' => $form_id ) ) );
			?>
			<tr id="ninja_forms_form_<?php echo $form_id;?>_tr">
				<th scope="row" class="check-column">
					<input type="checkbox" id="" name="form_ids[]" value="<?php echo $form_id;?>" class="ninja-forms-bulk-action">
				</th>
				<td class="post-title page-title column-title">
					<strong>
						<a href="<?php echo $edit_link;?>"><?php echo $data['form_title'];?></a>
					</strong>
					<div class="row-actions">
						<span class="edit"><a href="<?php echo $edit_link;?>"><?php _e( 'Edit', 'ninja-forms' ); ?></a> | </span>
						<span class="trash"><a class="ninja-forms-delete-form" title="<?php _e( 'Delete this form', 'ninja-forms' ); ?>" href="#" id="ninja_forms_delete_form_<?php echo $form_id;?>"><?php _e( 'Delete', 'ninja-forms' ); ?></a> | </span>
						<span class="export"><a href="<?php echo $export_link;?>" title="<?php _e( 'Export Form', 'ninja-forms' ); ?>"><?php _e( 'Export', 'ninja-forms' ); ?></a> | </span>
						<span class="duplicate"><a href="<?php echo $duplicate_link;?>" title="<?php _e( 'Duplicate Form', 'ninja-forms' ); ?>"><?php _e( 'Duplicate', 'ninja-forms' ); ?></a> | </span>
						<span class="bleep"><?php echo ninja_forms_preview_link( $form_id ); ?> | </span>
						<span class="subs"><a href="<?php echo $subs_link;?>" class="" title="<?php _e( 'View Submissions', 'ninja-forms' ); ?>"><?php _e( 'View Submissions', 'ninja-forms' ); ?></a></span>
					</div>
				</td>
				<td>
					[ninja_forms_display_form id=<?php echo $form_id;?>]
				</td>
				<td>
					<pre>if( function_exists( 'ninja_forms_display_form' ) ){ ninja_forms_display_form( <?php echo $form_id;?> ); }</pre>
				</td>
				<td>
					<?php echo $date_updated;?>
				</td>
			</tr>

			<?php
		}
	}else{


	}	//End $all_forms if statement
	?>
		</tbody>
		<tfoot>
			<tr>
				<th class="check-column"><input type="checkbox" id="" class="ninja-forms-select-all" title="ninja-forms-bulk-action"></th>
				<th><?php _e( 'Form Title', 'ninja-forms' );?></th>
				<th><?php _e( 'Shortcode', 'ninja-forms' );?></th>
				<th><?php _e( 'Template Function', 'ninja-forms' );?></th>
				<th><?php _e( 'Date Updated', 'ninja-forms' );?></th>
			</tr>
		</tfoot>
	</table>

  <div id="new_form" style="display:none;">
<!--   	<a class="media-modal-close" href="#close-modal" rel="modal:close" title="Close">
  		<span class="media-modal-icon"></span>
  	</a> -->
  	<div class="nf-new-form wp-core-ui media-frame" id="">
		<div class="wizard-header">
			<div alt="Close overlay" href="#close-modal" rel="modal:close" class="close dashicons dashicons-no"></div>
			<div alt="Show previous" class="left dashicons dashicons-no disabled"></div>
			<div alt="Show next" class="right dashicons dashicons-no"></div>
		</div>
		<div id="new-form-creation">
			<div class="ninja-row">
				<div class="ninja-col-1-2 nf-wz-options">
					<div class="inside" id="wizard-left">
						<p><input type="text" id="ninja_forms_new_form_title" class="widefat code" value="" placeholder="Form Title"></p>
						<p class="wizard-section-actions"><a href="#" id="new-form-wizard" class="button-primary"><?php _e( 'Creation Wizard, Please', 'ninja-forms' );?></a>
							<a href="#" id="ninja_forms_new_form_create" class="button-secondary"><?php _e( 'Start Editing, Skip The Wizard', 'ninja-forms' );?></a></p>
						<span class='hidden'><?php wp_editor('hi','hi');?></span>
					</div>
				</div>
				<div class="ninja-col-1-2 nf-wz-instructions">
					<div class="inside" id="wizard-right">
						<h3><?php _e( 'Create A New Form', 'ninja-forms' );?></h3>
						<div class="wizard-update-message">
							<p><strong>To get started, enter a form title and then select whether or not you'd like to use the form creation wizard.</strong></p>
						</div>
						<p class="wizard-description">The form creation wizard will assist you with all of the steps necessary to create a basic form. Once you have completed the wizard, you will be taken to the form editing page where you can make more changes to your form.</p>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="wizard-actions" id="wizard-actions">
		&nbsp;
	</div>
  </div>
  <?php
  if ( isset( $_REQUEST['form_id'] ) and $_REQUEST['form_id'] == 'new' ) {
  	?>
	<script type="text/html" id="tmpl-wizard-left">
		<table class="form-table">
			<tr>
				<th>
					<label for="">
						Do you want to add this form to the bottom of a page?
					</label>
				</th>
				<td>
					<select id="" class="">
						<option value="">- No</option>
						<option value="">Hello World</option>
						<option value="">Why do you ask?</option>
						<option value="">Contact Us</option>
					</select>
					<span class="howto">
						It will display just below any content you may have on the page.
					</span>
				</td>
			</tr>			
			<tr>
				<th>
					<label for="">
						Would you like the form to submit without reloading the page?
					</label>
				</th>
				<td>
					<input type="checkbox" name="" id="" class="">
					<span class="howto">
						This will let the form submit without the user ever leaving the page that the form is attached to. Once the form is submitted, they'll see a success message that we'll setup in a moment.
					</span>
				</td>
			</tr>			
			<tr>
				<th>
					<label for="">
						Would you like to show the title of the form above the form itself?
					</label>
				</th>
				<td>
					<input type="checkbox" name="" id="" class="">
					<span class="howto">
						If this box is checked, we'll output the form's title just before the actual form.
					</span>
				</td>
			</tr>

		</table>
	</script>	

	<script type="text/html" id="tmpl-wizard-right">
		<h3><?php _e( 'Display Settings', 'ninja-forms' );?></h3>
		<div class="wizard-update-message">
			<p><strong>First, we&#39;ll take a look at some display settings.</strong></p>
		</div>
		<p class="wizard-description">These settings will affect both how the form is shown and how it is submitted. The video below will explain these settings further.</p>
		<p class="wizard-description"><iframe width="640" height="390" src="//www.youtube.com/embed/hVfPmKzqYpk" frameborder="0" allowfullscreen></iframe></p>
	</script>

	<script type="text/html" id="tmpl-wizard-actions">
		<a class="button button-secondary" href="#" >Previous</a>
		<a class="button button-primary" href="#">Next</a>
	</script>

  	<?php

  }

}
