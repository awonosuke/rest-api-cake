USE rest_api;

INSERT INTO users (
    email,
    password,
    user_name,
    role
) VALUES (
    'root@example.com',
    '$2y$10$MplVem9OBEEzLKHjIoyiN.FualjIn6NIS4hLQqU1BXBWUlpWzosGG',
    'root',
    'admin'
);