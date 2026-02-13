-- create the tables for our movies
CREATE TABLE `movies` (
   `movieid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `title` varchar(100) NOT NULL,
   `year` char(4) DEFAULT NULL,
   PRIMARY KEY (`movieid`)
);
-- insert data into the tables
INSERT INTO movies
VALUES (1, "Elizabeth", "1998"),
   (2, "Black Widow", "2021"),
   (3, "Oh Brother Where Art Thou?", "2000"),
   (
      4,
      "The Lord of the Rings: The Fellowship of the Ring",
      "2001"
   ),
   (5, "Up in the Air", "2009");

CREATE TABLE `actors` (
   `actorid` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `last_name` varchar(100) NOT NULL,
   `first_names` varchar(100) NOT NULL,
   `dob` DATE NOT NULL,
   PRIMARY KEY (`actorid`)
);
INSERT INTO actors (first_names, last_name, dob)
VALUES ( "Elizabeth", "Cho", "1959-03-10"),
   ("Chadwick", "Boseman", "1960-12-05"),
   ("Charlie", "Jackson", "1955-3-11"),
   ("Charlie", "Jackson", "1956-4-12");

CREATE TABLE movie_actors (
  movieid INT UNSIGNED NOT NULL,
  actorid INT UNSIGNED NOT NULL,
  PRIMARY KEY (movieid, actorid),
  CONSTRAINT fk_ma_movie FOREIGN KEY (movieid) REFERENCES movies(movieid),
  CONSTRAINT fk_ma_actor FOREIGN KEY (actorid) REFERENCES actors(actorid)
);


);
INSERT INTO movie_actors (movieid, actorid) VALUES
  (1, 1),
  (3, 5),
  (5, 3),
  (7, 8),
  (8, 9);


