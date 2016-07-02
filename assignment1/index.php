<?php

// Load database connection, helpers, etc.
require_once(__DIR__ . '/errors.php');
require_once(__DIR__ . '/include.php');
require_once(__DIR__ . '/post.php');
require_once(__DIR__ . '/LTV_Reports.php');
$input_months = get_months($db);
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
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<div>
				<label for="start">Starting month: </label>
				<select id="start" name="start">
					<?php foreach ($input_months as $index => $row): ?>
					<option value="<?= DateTime::createFromFormat('m-Y', $row->time)->format('M Y'); ?>">
						<?= DateTime::createFromFormat('m-Y', $row->time)->format('M Y'); ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div>
				<label for="period">Period: </label>
				<select id="period" name="period">
					<option value="3">3</option>
					<option value="6">6</option>
					<option value="12">12</option>
					<option value="18">18</option>
					<option value="24">24</option>
				</select>
			</div>
			<div><label for="commission">commission: </label><input id="commission" name="commission"></div>
			<div><button type="submit" id="submit">Submit</button></div>
		</form>
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
				<?php foreach ($LTV->get_bookings($start, $period) as $index => $row): ?>
				<tr>
					<td><?= DateTime::createFromFormat('m-Y', $row->month)->format('M Y') ?></td>
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