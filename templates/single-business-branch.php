<?php
	extract( $parameters );
?>
<div class="branch">
	<h4><?php echo $branch_name; ?></h4>

	<div class="branch-content">
		<div class="row">
			<div class="col-md-4">
				<?php if ( $branch_telephone ) : ?>
					<div class="branch-telephone">
						<span><strong>Telephone: </strong> <a href="tel:<?php echo str_replace(' ', '', $branch_telephone); ?>" target="_blank"><?php echo $branch_telephone; ?></a></span>
					</div>
				<?php endif; ?>
				<?php if ( $branch_email ) : ?>
					<div class="branch-email">
						<span><strong>Email: </strong> <a href="mailto:<?php echo $branch_email; ?>" target="_blank"><?php echo $branch_email; ?></a></span>
					</div>
				<?php endif; ?>
				
				<?php if ( $branch_email ) : ?>
				<div class="branch-website">
					<span><strong>Website: </strong> <a href="<?php echo $branch_email; ?>" target="_blank"><?php echo $branch_email; ?></a></span>
				</div>
				<?php endif; ?>
			</div>

			<div class="col-md-8">
				<?php // Where descriptions would go if we use them ?>
			</div>
		</div>
	</div>
</div>