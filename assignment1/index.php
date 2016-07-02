<?php

// Load database connection, helpers, etc.
require_once(__DIR__ . '/errors.php');
require_once(__DIR__ . '/include.php');
require_once(__DIR__ . '/post.php');
require_once(__DIR__ . '/LTV_Reports.php');

// Vars
$period = 12; // Life-Time of 12 months
$commission = 0.10; // 10% commission

$LTV = new LTV_Reports($db);
?>
<!doctype html>
<html>
	<head>
		<title>Assignment 1: Create a Report (SQL)</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<style type="text/css">
			.report-table
			{
				width: 100%;
				border: 1px solid #000000;
			}
			.report-table td,
			.report-table th
			{
				text-align: left;
				border: 1px solid #000000;
				padding: 5px;
			}
			.report-table .right
			{
				text-align: right;
			}
		</style>
	</head>
	<body>
		<h1>Report:</h1>
		<table class="report-table">
			<thead>
				<tr>
					<th>Start</th>
					<th>Bookers</th>
					<th># of bookings (avg)</th>
					<th>Turnover (avg)</th>
					<th>LTV</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($LTV->get_bookings(1, 1) as $index => $row): ?>
					<tr>
						<td><?= $row->month ?></td>
						<td><?= $row->total_first_bookings ?></td>
						<td><?= number_format($row->total_bookings/$period, 2) ?></td>
						<td><?= number_format($row->total_turnover/$period, 2) ?></td>
						<td><?= number_format(($row->total_turnover/$period) * $commission, 2)?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="right"><strong>Total rows:</strong></td>
					<td><?= $index + 1 ?></td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>