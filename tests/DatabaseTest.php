<?php
    
// run tests agsainst db
function createScoresTable($db_connection) {
    $db_connection->query(
        'DROP TABLE IF EXISTS game_scores'
    );
    
/*
    $db_connection->query(
        'CREATE TABLE game_scores (
        id int(10) NOT NULL AUTO_INCREMENT,
        name varchar(16) NOT NULL DEFAULT '',
        score int(10) NOT NULL,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB'
    );
*/

/*
    $db_connection->query(
        'INSERT INTO `game_scores` (`name`, `score`)
           VALUES ("Matt", 20), ("Matt, 1200),
           ("Matt", 2300), ("Kathleen", 6700),
           ("Will", 6200), ("Will", 4800)'
    );
*/
}

function testHighScores() { 
echo 'a';
}