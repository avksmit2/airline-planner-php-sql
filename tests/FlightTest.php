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
            $status = "on time";
            $test_flight = new Flight($depart_time, $status);

            //Act
            $test_flight->save();
            $result = $test_flight->getAll();

            //Assert
            $this->assertEquals($test_flight, $result[0]);
        }

        function testGetAll()
        {
           //Arrange
           $depart_time = "11:19:00";
           $status = "on time";
           $id = null;
           $test_flight = new Flight($depart_time, $status, $id);
           $test_flight->save();

           $depart_time2 = "12:30:00";
           $status2 = "late";
           $id2 = null;
           $test_flight2 = new Flight($depart_time2, $status2, $id2);
           $test_flight2->save();

           //Act
           $result = Flight::getAll();
           //Assert
           $this->assertEquals([$test_flight, $test_flight2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            $depart_time2 = "12:30:00";
            $status2 = "late";
            $id2 = null;
            $test_flight2 = new Flight($depart_time2, $status2, $id2);
            $test_flight2->save();

            //Act
            Flight::deleteAll();

            //Assert
            $result = Flight::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            $depart_time2 = "12:30:00";
            $status2 = "late";
            $id2 = null;
            $test_flight2 = new Flight($depart_time2, $status2, $id2);
            $test_flight2->save();

            //Act
            $result = Flight::find($test_flight2->getId());
            //Assert
            $this->assertEquals($test_flight2, $result);
        }

        function testUpdateDepart()
        {
            //Arrange
            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            $new_depart_time = "12:30:00";
            $new_status = "late";

            //Act
            $test_flight->update($new_depart_time, $new_status);

            //Assert
            $this->assertEquals("12:30:00", $test_flight->getDepartTime());
        }

        function testUpdateStatus()
        {
            //Arrange
            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            $new_depart_time = "12:30:00";
            $new_status = "late";

            //Act
            $test_flight->update($new_depart_time, $new_status);

            //Assert
            $this->assertEquals("late", $test_flight->getStatus());
        }

        function testDeleteFlight()
        {
           //Arrange
           $depart_time = "11:19:00";
           $status = "on time";
           $id = null;
           $test_flight = new Flight($depart_time, $status, $id);
           $test_flight->save();

           $depart_time2 = "12:30:00";
           $status2 = "late";
           $id2 = null;
           $test_flight2 = new Flight($depart_time2, $status2, $id2);
           $test_flight2->save();

           //Act
           $test_flight->delete();

           //Assert
           $this->assertEquals([$test_flight2], Flight::getAll());
        }

        function testAddDepartCity()
        {
            //Arrange
            $name = "Portland, OR";
            $id = null;
            $test_depart_city = new City($name, $id);
            $test_depart_city->save();

            $name2 = "Chicago, IL";
            $id2 = null;
            $test_arrival_city = new City($name2, $id2);
            $test_arrival_city->save();

            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            //Act
            $test_flight->addCities($test_depart_city, $test_arrival_city);

            //Assert
            $this->assertEquals($test_flight->getDepartCities(), [$test_depart_city]);
        }

        function testAddArrivalCity()
        {
            //Arrange
            $name = "Portland, OR";
            $id = null;
            $test_depart_city = new City($name, $id);
            $test_depart_city->save();

            $name2 = "Chicago, IL";
            $id2 = null;
            $test_arrival_city = new City($name2, $id2);
            $test_arrival_city->save();

            $depart_time = "11:19:00";
            $status = "on time";
            $id = null;
            $test_flight = new Flight($depart_time, $status, $id);
            $test_flight->save();

            //Act
            $test_flight->addCities($test_depart_city, $test_arrival_city);

            //Assert
            $this->assertEquals($test_flight->getArrivalCities(), [$test_arrival_city]);
        }
    }
?>
