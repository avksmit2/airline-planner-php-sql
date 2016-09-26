<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests

    require_once "src/City.php";
    require_once "src/Flight.php";

    $server = 'mysql:host=localhost;dbname=airline_planner_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class FlightTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          City::deleteAll();
          Flight::deleteAll();
        }

        function testSetDepartTime()
        {
            //Arrange
            $depart_time = "11:19:00";
            $id = null;
            $status = "on time";
            $test_flight = new Flight($depart_time, $status, $id);

            //Act
            $test_flight->setDepartTime("11:11:00");
            $result = $test_flight->getDepartTime();

            //Assert
            $this->assertEquals("11:11:00", $result);
        }

        function testSaveFlight()
        {
            //Arrange
            $depart_time = "11:19:00";
            //$id = null;
            $status = "on time";
            $test_flight = new Flight($depart_time, $status);

            //Act
            $test_flight->save();
            $result = $test_flight->getAll();

            //Assert
            $this->assertEquals($test_flight, $result[0]);
        }

    }
?>
