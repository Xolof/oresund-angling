-- USE qadb;

-- Ensure UTF8 on the database connection
SET NAMES utf8mb4;

--
-- Table Question
--
DROP TABLE IF EXISTS TagToQuestion;
CREATE TABLE TagToQuestion (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `tagid` INTEGER NOT NULL,
    `qid` INTEGER NOT NULL
) ENGINE INNODB CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci;
