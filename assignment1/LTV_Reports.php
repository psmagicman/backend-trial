<?php

class LTV_Reports {
    
    private $db;

    function __construct($db) {
        $this->db = $db;    
    }

    /*
    * $start: date in %m-%Y format
    * $period: integer
    */
    public function get_bookings($start = null, $period = null) {
        // $start_time = strtotime();
        if (null === $start) {
            $start = 1376956800; // August 2013
        }
        if (null === $period) {
            $period = 12;
        }
        $query = <<<Q
            SELECT strftime('%m-%Y', bookingsperiod.end_timestamp, 'unixepoch') AS month, COUNT(bookingsperiod.booker_id) AS total_first_bookings, 
                SUM(bookingsperiod.bookings_made) AS total_bookings, SUM(bookingsperiod.bookingitems_turnover) AS total_turnover 
            FROM (
                SELECT firstbookings.booker_id, firstbookings.end_timestamp, COUNT(bookings.id) AS bookings_made, 
                SUM(bookingitems.locked_total_price) AS bookingitems_turnover FROM (
                    SELECT bookings.booker_id AS booker_id, MIN(bookingitems.end_timestamp) AS end_timestamp
                    FROM bookings
                    INNER JOIN bookingitems ON bookings.id = bookingitems.booking_id
                    INNER JOIN spaces ON bookingitems.item_id = spaces.item_id
                    GROUP BY bookings.booker_id
                ) AS firstbookings
                INNER JOIN bookings ON firstbookings.booker_id = bookings.booker_id
                INNER JOIN bookingitems ON bookings.id = bookingitems.booking_id
                INNER JOIN spaces ON bookingitems.booking_id = spaces.item_id
                WHERE strftime('%s', firstbookings.end_timestamp, 'unixepoch') 
                    BETWEEN strftime('%s', $start, 'unixepoch', 'start of month') AND strftime('%s', $start, 'unixepoch', '+$period months', 'start of month')
                GROUP BY firstbookings.booker_id
            ) AS bookingsperiod
            GROUP BY strftime('%m-%Y', bookingsperiod.end_timestamp, 'unixepoch')
            ORDER BY strftime('%Y', bookingsperiod.end_timestamp, 'unixepoch') ASC
Q;
        return $this->run_query($query);
    }

    private function run_query($query) {
        return $this->db->prepare($query)->run();
    }
}





