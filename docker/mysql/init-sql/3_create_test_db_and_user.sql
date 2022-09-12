CREATE DATABASE test_rest_api CHARACTER SET utf8mb4;
CREATE USER 'test_rest_api'@'%' IDENTIFIED BY 'test_rest_api';
GRANT ALL PRIVILEGES ON test_rest_api.* TO 'test_rest_api'@'%';