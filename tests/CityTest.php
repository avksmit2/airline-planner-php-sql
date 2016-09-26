<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    //TO RUN TESTS IN TERMINAL:
    //export PATH=$PATH:./vendor/bin
    //phpunit tests

    require_once "src/City.php";
    //require_once "src/Flight.php";

    $server = 'mysql:host=localhost;dbname=airline_planner_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CityTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          City::deleteAll();
        //   Flight::deleteAll();
        }

        function testSetName()
        {
            //Arrange
            $name = "Portland, OR";
            $test_city = new City($name);

            //Act
            $test_city->setName("PDX");
            $result = $test_city->getName();

            //Assert
            $this->assertEquals("PDX", $result);
        }

        function testGetName()
        {
            //Arrange
            $name = "Portland, OR";
            $test_city = new City($name);

            //Act
            $result = $test_city->getName();

            //Assert
            $this->assertEquals("Portland, OR", $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Portland, OR";
            $id = 1;
            $test_city = new City($name, $id);

            //Act
            $result = $test_city->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $name = "Portland, OR";
            $id = 1;
            $test_city = new City($name);

            //Act
            $test_city->save();
            $result = $test_city->getAll();

            //Assert
            $this->assertEquals($test_city, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $name = "Portland, OR";
            $id = null;
            $test_city = new City($name, $id);
            $test_city->save();

            $name2 = "Chicago, Il";
            $id2 = null;
            $test_city2 = new City($name2, $id2);
            $test_city2->save();

            //Act
            $result = City::getAll();

            //Assert
            $this->assertEquals([$test_city, $test_city2], $result);
        }

        function deleteAll()
        {
            //Arrange
            $name = "Portland, OR";
            $id = null;
            $test_city = new City($name, $id);
            $test_city->save();

            $name2 = "Chicago, Il";
            $id2 = null;
            $test_city2 = new City($name2, $id2);
            $test_city2->save();

            //Act
            $result = City::deleteAll();

            //Assert
            $this->assertEquals([], $result);
        }


    }
?>
