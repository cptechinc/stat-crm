<?php
	$bookingspanel = new BookingsPanel(session_id(), $page->fullURL, '#ajax-modal', 'data-loadinto=#bookings-panel|data-focus=#bookings-panel');
	$bookingspanel->generate_filter($input);
	$bookings = $bookingspanel->get_bookings();
	
	foreach ($bookings as $booking) {
		$bookdata = array(
			'bookdate' => DplusDateTime::format_date($booking['bookdate'], 'Y-m-d'),
			'amount' => floatval($booking['amount'])
		);
		if ($bookingspanel->interval == 'day') {
			$bookdata['dayurl'] = $bookingspanel->generate_viewsalesordersbydaylink($booking['bookdate']);
		}
		
		$bookingdata[] = $bookdata;
	}
?>
<div class="panel panel-primary not-round" id="bookings-panel">
	<div class="panel-heading not-round" id="bookings-panel">
		<a href="#bookings-div" class="panel-link" data-parent="#bookings-panel" data-toggle="collapse" aria-expanded="true">
			<span class="glyphicon glyphicon-book"></span> &nbsp; Bookings <span class="caret"></span>
			&nbsp; | <?= $bookingspanel->generate_refreshlink(); ?>
			<?php if ($input->get->date || $input->get->bookdate) : ?>
				&nbsp; <?= $bookingspanel->generate_cleardateparameterslink(); ?>
			<?php endif; ?>
			<span class="pull-right"><?= $bookingspanel->generate_todaysbookingsdescription(); ?></span>
		</a>
	</div>
	<div id="bookings-div" class="" aria-expanded="true">
		<div class="panel-body">
			<button class="btn btn-primary toggle-order-search pull-right" type="button" data-toggle="collapse" data-target="#bookings-search-div" aria-expanded="false" aria-controls="bookings-search-div">Toggle Search <i class="fa fa-search" aria-hidden="true"></i></button>
			<div id="bookings-search-div" class="<?= (!empty($bookingspanel->filters)) ? 'collapse' : ''; ?>">
				<?php include $config->paths->content.'dashboard/bookings/search-form.php'; ?>
			</div>
		</div>
		<div>
			<h3 class="text-center"><?= $bookingspanel->generate_title(); ?></h3>
			<div id="bookings-chart">
				
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive bookings-table-div">
						<?php include $config->paths->content."dashboard/bookings/$bookingspanel->interval-table.php"; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include $config->paths->content."dashboard/bookings/bookings-line-chart.js.php"; ?>
