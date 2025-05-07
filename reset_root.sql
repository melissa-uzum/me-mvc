USE mysql;

-- Delete root user if broken
DELETE FROM mysql.user WHERE user='root';

-- Recreate root user properly
CREATE USER 'root'@'localhost' IDENTIFIED BY 'Root1234!';

-- Grant full privileges
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;

-- Save changes
FLUSH PRIVILEGES;

