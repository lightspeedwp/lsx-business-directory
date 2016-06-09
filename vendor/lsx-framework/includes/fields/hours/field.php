<?php


$times = array(
	"00:00",
	"00:30",
	"01:00",
	"01:30",
	"02:00",
	"02:30",
	"03:00",
	"03:30",
	"04:00",
	"04:30",
	"05:00",
	"05:30",
	"06:00",
	"06:30",
	"07:00",
	"07:30",
	"08:00",
	"08:30",
	"09:00",
	"09:30",
	"10:00",
	"10:30",
	"11:00",
	"11:30",
	"12:00",
	"12:30",
	"13:00",
	"13:30",
	"14:00",
	"14:30",
	"15:00",
	"15:30",
	"16:00",
	"16:30",
	"17:00",
	"17:30",
	"18:00",
	"18:30",
	"19:00",
	"19:30",
	"20:00",
	"20:30",
	"21:00",
	"21:30",
	"22:00",
	"22:30",
	"23:00",
	"23:30",
);

$days = array(
	'monday',
	'tuesday',
	'wednesday',
	'thursday',
	'friday',
	'saturday',
	'sunday',
);

?>
<div class="field-row">
	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
		<?php foreach( $days as $day ){ ?>
			<div class="row form-group">
				<label class="control-label col-sm-3" for="{{_id}}_<?php echo $day; ?>"><?php echo ucwords( $day ); ?></label>
				<div class="col-sm-4">
					<select data-live-sync="true" data-field="<?php echo $field_id; ?>" name="{{:name}}[value][<?php echo $day; ?>][start]" id="{{_id}}_<?php echo $day; ?>_start" class="form-control">
						<option></option>
						<option value="close" {{#is value/<?php echo $day; ?>/start value="close"}}selected="selected"{{/is}}>Close</option>
						<?php foreach( $times as $time ){ ?>
						
						<option value="<?php echo $time; ?>" {{#is value/<?php echo $day; ?>/start value="<?php echo $time; ?>"}}selected="selected"{{/is}}><?php echo strtoupper( $time ); ?></option>
						
						<?php } ?>
					</select>
				</div>
				{{#is value/<?php echo $day; ?>/start value="close"}}
				{{else}}
				<div class="col-sm-1" style="text-align: center;">
				To
				</div>
				<div class="col-sm-4">
					<select data-live-sync="true" data-field="<?php echo $field_id; ?>" name="{{:name}}[value][<?php echo $day; ?>][end]" id="{{_id}}_<?php echo $day; ?>_end" class="form-control">
						<option></option>
						<?php foreach( $times as $time ){ ?>
						<option value="<?php echo $time; ?>" {{#is value/<?php echo $day; ?>/end value="<?php echo $time; ?>"}}selected="selected"{{/is}}><?php echo strtoupper( $time ); ?></option>
						<?php } ?>
						<option value="late" {{#is value/<?php echo $day; ?>/end value="late"}}selected="selected"{{/is}}>Late</option>
					</select>
				</div>
				{{/is}}
			</div>
		<?php } ?>
		</div>
		
	</div>
</div>


