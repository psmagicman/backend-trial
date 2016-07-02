<?php

date_default_timezone_set('UTC');

// defaults
$start = 'Aug 2013';
$period = 12;
$commission = 0.10;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = isset($_POST['start']) ? $_POST['start'] : $start;
    $period = isset($_POST['period']) ? $_POST['period'] : $period;
    $commission = isset($_POST['commission']) && !empty($_POST['commission']) ? clean($_POST['commission']) : $commission;
    if (!is_numeric($commission) || $commission < 0) {
        echo "<pre>[ERROR]: invalid number entered for commission <br/>";
        echo "Please enter a number greater or equal to 0</pre>";
        $commission = 0.10;
    }
}

function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function get_months($db) {
    $query = "SELECT strftime('%m-%Y', end_timestamp, 'unixepoch') AS time FROM bookingitems ";
    $query .= "GROUP BY strftime('%m %Y', end_timestamp, 'unixepoch') ORDER BY strftime('%Y', end_timestamp, 'unixepoch')";
    return $db->prepare($query)->run();
}