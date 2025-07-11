-- Users Table
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    role_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NULL, 
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    access_token VARCHAR(255) NULL, 
    session_token VARCHAR(255) NULL, -- custom session management beyond Laravel's
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Roles Table
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL, -- e.g., 'Admin', 'Technician', 'Instructor'
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- AccessTokens (custom token tracking ) 
CREATE TABLE access_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(255) UNIQUE NOT NULL,
    user_id BIGINT NOT NULL,
    expires_at TIMESTAMP NULL, -- When the token expires
    duration INT NULL, -- Original duration in minutes/hours
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Courses Table
-- Represents a full course that can contain multiple topics (sections)
CREATE TABLE courses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE, -- Can be used to enable/disable courses
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE topics (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    course_id BIGINT NOT NULL, -- Link to the parent course
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order_in_course INT NOT NULL, -- To maintain the serial order of sections
    is_approved BOOLEAN DEFAULT FALSE, -- for approval by an admin before it's live
    code VARCHAR(255) UNIQUE NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Videos Table
CREATE TABLE videos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    topic_id BIGINT NOT NULL, -- Link to the topic it belongs to
    url VARCHAR(255) NOT NULL, -- YouTube URL
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NULL, -- videos need unique codes
    length_seconds INT NULL, -- Storing length in seconds for easier calculations
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id)
);

-- Tests Table
CREATE TABLE tests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    topic_id BIGINT NOT NULL, -- Link to the topic it belongs to
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    minimum_approved_grade DECIMAL(5,2) DEFAULT 70.00, -- Default passing grade for this specific test
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id)
);

-- Questions Table
CREATE TABLE questions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    test_id BIGINT NOT NULL, -- Link to the test it belongs to
    question_text TEXT NOT NULL,
    type ENUM('free_text', 'multiple_choice', 'single_choice') NOT NULL, -- More descriptive types
    score_value DECIMAL(5,2) DEFAULT 1.00, -- Points for this question
    correct_answer_id BIGINT NULL, -- For 'multiple_choice' or 'single_choice'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (test_id) REFERENCES tests(id),
    FOREIGN KEY (correct_answer_id) REFERENCES answers(id) -- This will require deferring constraint check or adding the constraint after answers are created
);

-- Answers Table
CREATE TABLE answers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    question_id BIGINT NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE, -- To easily mark correct answers for multiple choice/single choice
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- Attempts Table 
CREATE TABLE attempts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    test_id BIGINT NOT NULL,
    score DECIMAL(5,2) NULL, -- Renamed from 'result', nullable until graded
    passed BOOLEAN NULL, -- Denormalized for quick lookup if the user passed this attempt
    attempt_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES tests(id)
);

-- AttemptAnswers 
CREATE TABLE attempt_answers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    attempt_id BIGINT NOT NULL,
    question_id BIGINT NOT NULL,
    selected_answer_id BIGINT NULL, -- For multiple/single choice questions
    free_text_answer TEXT NULL, -- For free text questions
    is_correct BOOLEAN NULL, -- If you want to store if THIS specific user answer was correct
    score_awarded DECIMAL(5,2) NULL, -- How many points the user got for this question
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (selected_answer_id) REFERENCES answers(id)
);