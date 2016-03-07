<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Player.php";

    //if using database
    $server = 'mysql:host=localhost;dbname=rps_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class  PlayerTest  extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Player::deleteAll();
        }

        function test_getNamePasswordId()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            //Act
            $result_name = $test_player->getName();
            $result_password = $test_player->getPassword();
            $result_id = $test_player->getId();

            //Assert
            $this->assertEquals($test_name, $result_name);
            $this->assertEquals($test_password, $result_password);
            $this->assertEquals($test_id, $result_id);
        }

        function test_setNamePassword()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            //Act
            $test_player->setName('Joseph');
            $test_player->setPassword('4567');

            $result_name = $test_player->getName();
            $result_password = $test_player->getPassword();
            $result_id = $test_player->getId();

            //Assert
            $this->assertEquals('Joseph', $result_name);
            $this->assertEquals('4567', $result_password);
            $this->assertEquals($test_id, $result_id);
        }

        function test_save()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            //Act
            $test_player->save();

            //Assert
            $this->assertEquals([$test_player], Player::getAll());
        }

        function test_getAllPlayers()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            $test_name2 = "Joseph";
            $test_password2 = '1234';
            $test_id2 = 2;
            $test_player2 = new Player($test_name2, $test_password2, $test_id2);

            //Act
            $test_player->save();
            $test_player2->save();

            //Assert
            $this->assertEquals([$test_player, $test_player2], Player::getAll());
        }

        function test_deleteAllPlayers()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            $test_name2 = "Joseph";
            $test_password2 = '1234';
            $test_id2 = 2;
            $test_player2 = new Player($test_name2, $test_password2, $test_id2);

            //Act
            $test_player->save();
            $test_player2->save();
            Player::deleteAll();

            //Assert
            $this->assertEquals([], Player::getAll());
        }

        function test_findById()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            //Act
            $test_player->save();
            $search_id = $test_player->getId();
            $result = Player::findById($search_id);
            $result2 = Player::findById(-2);

            //Assert
            $this->assertEquals($test_player, $result);
            $this->assertEquals(null, $result2);
        }

        function test_findByName()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_id = 1;
            $test_player = new Player($test_name, $test_password, $test_id);

            //Act
            $test_player->save();
            $result = Player::findByName($test_name);
            $result2 = Player::findByName('Joseph');

            //Assert
            $this->assertEquals($test_player, $result);
            $this->assertEquals(null, $result2);
        }

        function test_getTotalGames()
        {
            //Arrange
            $test_name = "Aundra";
            $test_password = '1234';
            $test_player = new Player($test_name, $test_password);
            $test_player->save();
            $test_player_id = $test_player->getId();

            $test_name2 = "Joseph";
            $test_password2 = '1234';
            $test_player2 = new Player($test_name2, $test_password2);
            $test_player2->save();
            $test_player_id2 = $test_player2->getId();

            $GLOBALS['DB']->exec("INSERT INTO rounds (player_one_id, player_one_choice, player_two_id, player_two_choice, winner_id) VALUES ({$test_player_id}, 'rock', {$test_player_id2}, 'scissors', {$test_player_id});");

            $GLOBALS['DB']->exec("INSERT INTO rounds (player_one_id, player_one_choice, player_two_id, player_two_choice, winner_id) VALUES ({$test_player_id}, 'rock', {$test_player_id2}, 'scissors', {$test_player_id});");

            $GLOBALS['DB']->exec("INSERT INTO rounds (player_one_id, player_one_choice, player_two_id, player_two_choice, winner_id) VALUES (-10, 'rock', {$test_player_id2}, 'scissors', -10);");

            //Act
            $result = $test_player->getTotalGames();
            $result2 = $test_player2->getTotalGames();

            //Assert
            $this->assertEquals(2, $result);
            $this->assertEquals(3, $result2);
        }

    }
?>
