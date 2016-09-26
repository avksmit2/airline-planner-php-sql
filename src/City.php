<?php
class City
{
    private $name;
    private $id;

    function __construct($name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    function setName($new_name)
    {
        $this->name = (string) $new_name;
    }

    function getName()
    {
        return $this->name;
    }

    function getId()
    {
       return $this->id;
    }

    function save()
    {
         $GLOBALS['DB']->exec("INSERT INTO cities (name) VALUES ('{$this->getName()}');");
         $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function addFlight($flight)
    {
         $GLOBALS['DB']->exec("INSERT INTO cities_flights (cities_id, flight_id) VALUES ({$this->getId()}, {$flight->getId()});");
    }

    function getFlights()
    {
        $query = $GLOBALS['DB']->query("SELECT flight_id FROM cities_flights WHERE depart_city_id = {$this->getId()};");
        $flight_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $flights = array();
        foreach($flight_ids as $id) {
            $flight_id = $id['flight_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM flights WHERE id = {$flight_id};");
            $returned_flight = $result->fetchAll(PDO::FETCH_ASSOC);

            $depart_time = $returned_flight[0]['depart_time'];
            $status = $returned_flight[0]['status'];
            $id = $returned_flight[0]['id'];
            $new_flight = new Flight($depart_time, $status, $id);
            array_push($flights, $new_flight);
        }
        return $flights;
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM cities WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM cities_flights WHERE depart_city = {$this->getId()} OR arrival_city = {$this->getId()};");
    }

    function update($new_name)
    {
        $GLOBALS['DB']->exec("UPDATE cities SET name = '{$new_name}';");
        $this->setName($new_name);
    }

    static function getAll()
    {
        $returned_cities = $GLOBALS['DB']->query("SELECT * FROM cities;");
        $cities = array();
        foreach($returned_cities as $city) {
            $name = $city['name'];
            $id = $city['id'];
            $new_city = new City($name, $id);
            array_push($cities, $new_city);
        }
        return $cities;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM cities;");
        $GLOBALS['DB']->exec("DELETE FROM cities_flights;");
    }

    static function find($search_id)
    {
        $found_city = null;
        $cities = City::getAll();
        foreach($cities as $city) {
            $city_id = $city->getId();
            if ($city_id == $search_id) {
                $found_city = $city;
            }
        }
        return $found_city;
    }
}
?>
