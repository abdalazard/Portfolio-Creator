CREATE Database NoobPHP;

use NoobPHP;

-- CREATE TABLE Usuarios(
-- 	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     `user` VARCHAR(250) NOT NULL,
--     'pass' VARCHAR(250) NOT NULL,
-- );
INSERT INTO users VALUES(NULL, 'admin', MD5(123));