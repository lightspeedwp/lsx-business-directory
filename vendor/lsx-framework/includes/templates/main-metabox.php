	<span class="lsx-metabox-wrapper">
		<ul class="lsx-metabox-tab">
			<?php foreach( $panels as $panel_id => $panel ){ ?>
			<li {{#is _active_tab value="<?php echo $metabox['id']; ?>_tab_<?php echo $panel_id; ?>"}}class="active"{{/is}}>
				<a href="#<?php echo $metabox['id']; ?>_tab_<?php echo $panel_id; ?>"><?php echo $panel['name']; ?></a>
			</li>
			<?php } ?>
		</ul>
		<input autocomplete="off" type="hidden" name="_active_tab" class="active-tab" value="{{_active_tab}}">
	</span>

	<?php foreach( $panels as $panel_id => $panel ){ ?>
	<div style="display:{{#is _active_tab value="<?php echo $metabox['id']; ?>_tab_<?php echo $panel_id; ?>"}}block{{else}}none{{/is}};" class="lsx-tab-body" id="<?php echo $metabox['id']; ?>_tab_<?php echo $panel_id; ?>">
		<?php foreach( $panel['sections'] as $section_id => $section ){ ?>
		<h4><?php echo $section['name'];
		if( !empty( $section['description'] ) ){ ?>
		<small class="description"><?php echo $section['description']; ?></small>
		<?php } ?></h4>
		<div id="<?php echo $metabox['id']; ?>_section_<?php echo $section_id; ?>" data-panel-id="<?php echo $panel_id; ?>" class="lsx-grid ">
			{{:node_point}}
			<?php
				foreach( $section['parts'] as $part_id => $part ){
					if( !empty( $part['capability'] ) && !user_can( wp_get_current_user(), $part['capability'] ) ){
						continue;
					}
					?>

					{{#each @root/<?php echo $part_id; ?>}}					
					<div id="lsx-group-{{_id}}-part-<?php echo $part_id; ?>" class="field-part-wrap<?php if( !empty( $part['repeatable'] ) ){ echo ' repeatable-group-wrap'; } ?>">
					{{:node_point}}
					<?php
						if( !empty( $part['repeatable'] ) ){ 
							$confirm = null;
							if( !empty( $part['repeatable_confirm'] ) ){
								$confirm = 'data-confirm="' . esc_attr( $part['repeatable_confirm'] ) . '"';
							}
							echo '<button class="button lsx-remove-repeatable" ' . $confirm . ' data-remove-parent="#lsx-group-{{_id}}-part-' . $part_id. '" type="button"><span class="dashicons dashicons-no-alt"></span></button>';
							
							//Output a collapse button
							echo '<label class="button lsx-collapse-repeatable"><input style="display:none;" type="checkbox" name="{{:name}}[open]" value="1" data-live-sync="true" {{#if open}}checked="checked"{{/if}}><span class="dashicons {{#if open}}dashicons-arrow-up{{else}}dashicons-arrow-down{{/if}}"></span></label>';
							
							//Output a title for the collapsable part
							
							echo '{{#unless open}}<div class="lsx-group-collapsable-title" data-item-title="'.apply_filters('lsx_metabox_collapsable_panel_title',__('Item','lsx'),$part).'"></div>{{/unless}}';
							
							//Output a wrapper so we can collapse the fields
							echo '<div class="{{#unless open}}lsx-group-collapsable-wrapper{{/unless}}">';
						}
						$field_types = apply_filters( 'lsx_metabox_field_types', array() );
						
						
							
						foreach( $part['fields'] as $field_id=>$field ){

							if( !empty( $field['capability'] ) && !user_can( wp_get_current_user(), $field['capability'] ) ){
								continue;
							}
							if( !empty( $field['requires'] ) ){

								echo '<div style="display:{{#find ' . $field['requires'] . ' "value"}}block;{{else}}none;{{/find}}">';
							}

							if( isset( $field_types[ $field['type'] ] ) && isset( $field_types[ $field['type'] ]['file'] ) && file_exists( $field_types[ $field['type'] ]['file'] ) ){
								?>
								{{#unless <?php echo $field['slug']; ?>}}
								<?php if( empty( $field['repeatable'] ) ){ ?>
									<span class="lsx-baldrick" data-add-node="{{_node_point}}.<?php echo $field_id; ?>" data-autoload="true"></span>
								<?php }else{ ?>
									&nbsp;
								<?php } ?>
								{{/unless}}
								
								{{#each <?php echo $field['slug']; ?>}}
									<div id="lsx-field-{{_id}}" class="lsx-fieldtype-<?php echo $field['type']; ?> field-row<?php if( !empty( $field['repeatable'] ) ){ echo ' repeatable-field-wrap'; } ?>">
									<?php
										
										if( !empty( $field['repeatable'] ) ){ 
											$confirm = null;
											if( !empty( $field['repeatable_confirm'] ) ){
												$confirm = 'data-confirm="' . esc_attr( $field['repeatable_confirm'] ) . '"';
											}
											echo '<button class="button lsx-remove-repeatable" data-remove-parent="#lsx-field-{{_id}}" ' . $confirm . ' type="button"><span class="dashicons dashicons-no-alt"></span></button>';
										}
									?>

										{{:node_point}}
										<?php
										include $field_types[ $field['type'] ]['file'];
										?>
									</div>
								{{/each}}
								<div style="clear:both;"></div>
								<?php
								if( !empty( $field['repeatable'] ) ){
									echo '<div class="lsx-repeatable-border lsx-field-repeatable"><button type="button" class="button lsx-baldrick" data-add-node="{{_node_point}}.' . $field_id . '">';
									if( !empty( $field['repeatable_label'] ) ){
										echo $field['repeatable_label'];
									}else{
										_e('Add Another', 'lsx');
									}
									echo '</button></div>';
								}

							}
							
							if( !empty( $field['requires'] ) ){
								echo '</div>';
							}

						}
						
						if( !empty( $part['repeatable'] ) ){
							//close the .lsx-group-collapsable-wrapper div
							echo '</div>';
						}						
						
					?>	
					</div>
					{{/each}}
					
				<?php
					if( !empty( $part['repeatable'] ) ){
						//var_dump( $part );
						echo '<div class="lsx-repeatable-border lsx-group-repeatable"><button type="button" class="button lsx-baldrick" data-add-node="' . $part_id . '">';
						if( !empty( $part['repeatable_label'] ) ){
							echo $part['repeatable_label'];
						}else{
							_e('Add Another', 'lsx');
						}
						echo '</button></div>';
					}
				?>
			<?php } ?>

		</div>
		<?php } ?>
	</div>
	<?php } ?>