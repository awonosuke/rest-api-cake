USE rest_api;

CREATE TABLE users (
    id int(11) NOT NULL AUTO_INCREMENT,
    email varchar(255) NOT NULL UNIQUE COMMENT 'as user ID',
    password varchar(255) NOT NULL,
    user_name varchar(255) NOT NULL,
    role varchar(255) NOT NULL DEFAULT 'user',
    created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
) ENGINE = InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE snippets (
    id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    content text NOT NULL,
    expire datetime NOT NULL,
    created datetime NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;