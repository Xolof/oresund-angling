--
-- Creating a small table.
-- Create a database and a user having access to this database,
-- this must be done by hand, se commented rows on how to do it.
--



--
-- Create a database for test
--
-- DROP DATABASE qadb;
-- CREATE DATABASE IF NOT EXISTS qadb;
-- USE qadb;



--
-- Create a database user for the test database
--
-- GRANT ALL ON anaxdb.* TO anax@localhost IDENTIFIED BY 'anax';



-- Ensure UTF8 on the database connection
SET NAMES utf8mb4;



--
-- Table QuestionComment
--
DROP TABLE IF EXISTS QuestionComment;
CREATE TABLE QuestionComment (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `qid` INTEGER NOT NULL,
    `uid` INTEGER NOT NULL,
    `time` TIMESTAMP DEFAULT NOW() NOT NULL,
    `text` TEXT NOT NULL,
    `updated` TIMESTAMP NULL DEFAULT NULL,
    `deleted` TIMESTAMP NULL DEFAULT NULL
) ENGINE INNODB CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci;
