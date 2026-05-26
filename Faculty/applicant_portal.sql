CREATE DATABASE applicant_portal;

USE applicant_portal;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE applicant_details (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNIQUE,

    full_name VARCHAR(100),
    phone_number VARCHAR(15),
    email VARCHAR(100),

    degree_course VARCHAR(100),
    branch_specialization VARCHAR(100),
    university_board VARCHAR(150),
    year_of_passing INT,
    class_obtained VARCHAR(50),
    percentage DECIMAL(5,2),

    FOREIGN KEY(user_id)
    REFERENCES users(user_id)
    ON DELETE CASCADE
);

INSERT INTO users(email, password)
VALUES('student@gmail.com', '12345');