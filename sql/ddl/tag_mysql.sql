USE qadb;

-- Ensure UTF8 on the database connection
SET NAMES utf8mb4;

--
-- Table Tag
--
DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `tag` VARCHAR(255)
) ENGINE INNODB CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci;
