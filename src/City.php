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

    function addCity($flight)
    {
         $GLOBALS['DB']->exec("INSERT INTO cities_flights (cities_id, flight_id) VALUES ({$this->getId()}, {$flight->getId()});");
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
