<?php

/**
* 
*/
class LTV_Reports {
    
    private $db;

    function __construct($db) {
        $this->db = $db;    
    }

    public function get_first_time_booking_spaces() {
        $query = "SELECT bookings.booker_id AS booker_id, MIN(bookingitems.end_timestamp) AS end_timestamp ";
        $query .= "FROM bookings ";
        $query .= "INNER JOIN bookingitems ON bookings.id = bookingitems.booking_id ";
        $query .= "INNER JOIN spaces ON bookingitems.item_id = spaces.item_id ";
        $query .= "GROUP BY bookings.booker_id";
        return $this->run_query($query);
    }


    private function run_query($query) {
        return $this->db->prepare($query)->run();
    }
}