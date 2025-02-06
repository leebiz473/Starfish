-- Declare variables using MySQL syntax
SET @user = '${MYSQL_CLIENT_USER}';
SET @host = '%';  -- Default host, can be set to '%' for remote or 'localhost' for local access
SET @password = '${MYSQL_CLIENT_PASSWORD}';

-- Create user without GRANT OPTION
SET @create_user_query = CONCAT('CREATE USER IF NOT EXISTS ', QUOTE(@user), '@\'', @host, '\' IDENTIFIED BY ', QUOTE(@password), ';');
PREPARE stmt1 FROM @create_user_query;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;

-- Grant privileges with the specified user (without GRANT OPTION)
SET @grant_query1 = CONCAT('GRANT ALL PRIVILEGES ON *.* TO ', QUOTE(@user), '@\'', @host, '\' ;');
PREPARE stmt2 FROM @grant_query1;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- Apply changes
FLUSH PRIVILEGES;