<?php
class Flight
{
    private $depart_time;
    private $status;
    private $id;

    function __construct($depart_time, $status = "on time", $id = null)
    {
        $this->depart_time = $depart_time;
        $this->status = $status;
        $this->id = $id;
    }

    function setDepartTime($new_depart_time)
    {
        $this->depart_time = $new_depart_time;
    }

    function getDepartTime()
    {
        return $this->depart_time;
    }

    function setStatus($new_status)
    {
        $this->status = $new_status;
    }

    function getStatus()
    {
        return $this->status;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO flights (depart_time, status) VALUES ('{$this->getDepartTime()}', '{$this->getStatus()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_flights = $GLOBALS['DB']->query("SELECT * FROM flights;");
        $flights = array();
        foreach($returned_flights as $flight) {
            $depart_time = $flight['depart_time'];
            $status = $flight['status'];
            $id = $flight['id'];
            $new_flight = new Flight($depart_time, $status, $id);
            array_push($flights, $new_flight);
        }
        return $flights;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM flights;");
        $GLOBALS['DB']->exec("DELETE FROM cities_flights;");
    }

    static function find($search_id)
    {
        $found_flight = null;
        $flights = Flight::getAll();
        foreach($flights as $flight) {
            $flight_id = $flight->getId();
            if ($flight_id == $search_id) {
            $found_flight = $flight;
            }
        }
        return $found_flight;
    }

    function update($new_depart_time, $new_status)
    {
        $GLOBALS['DB']->exec("UPDATE flights SET depart_time = '{$new_depart_time}', status = '{$new_status}';");
        $this->setDepartTime($new_depart_time);
        $this->setStatus($new_status);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM flights WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM cities_flights WHERE flight_id = {$this->getId()};");
    }

    function addCities($depart_city, $arrival_city)
    {
        $GLOBALS['DB']->exec("INSERT INTO cities_flights (depart_city_id, arrival_city_id, flight_id) VALUES ('{$depart_city->getId()}', '{$arrival_city->getId()}', {$this->getId()});");
    }

    function getDepartCities()
    {
        $query = $GLOBALS['DB']->query("SELECT depart_city_id FROM cities_flights WHERE flight_id = {$this->getId()};");
        $depart_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $departs = array();
        foreach($depart_ids as $id) {
            $depart_id = $id['depart_city_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM cities WHERE id = {$depart_id};");
            $returned_city = $result->fetchAll(PDO::FETCH_ASSOC);

            $name = $returned_city[0]['name'];
            $id = $returned_city[0]['id'];
            $new_depart = new City($name, $id);
            array_push($departs, $new_depart);
        }
        return $departs;
    }

    function getArrivalCities()
    {
        $query = $GLOBALS['DB']->query("SELECT arrival_city_id FROM cities_flights WHERE flight_id = {$this->getId()};");
        $arrival_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $arrivals = array();
        foreach($arrival_ids as $id) {
            $arrival_id = $id['arrival_city_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM cities WHERE id = {$arrival_id};");
            $returned_city = $result->fetchAll(PDO::FETCH_ASSOC);

            $name = $returned_city[0]['name'];
            $id = $returned_city[0]['id'];
            $new_arrival = new City($name, $id);
            array_push($arrivals, $new_arrival);
        }
        return $arrivals;
    }
}
?>
