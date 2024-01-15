CREATE TABLE
    IF NOT EXISTS `Users` (
        `id` CHAR(36) PRIMARY KEY,
        `user_type` ENUM(
            'Candidate',
            'Recruiter',
            'Admin',
            'Consultant'
        ),
        `email` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `role` VARCHAR(255) DEFAULT 'ROLE_USER' NOT NULL,
        INDEX `idx_user_id` (`id`),
        INDEX `idx_user_type` (`user_type`)
    );

-- Create Admin Table

CREATE TABLE
    IF NOT EXISTS `Admin`(
        `id` CHAR(36) PRIMARY KEY,
        FOREIGN KEY (`id`) REFERENCES `Users` (`id`),
        `name` VARCHAR(255) NOT NULL,
        `firstname` VARCHAR(255) NOT NULL
    );

-- Create candidate table

CREATE TABLE
    IF NOT EXISTS `Candidate` (
        `id` CHAR(36) PRIMARY KEY,
        FOREIGN KEY (`id`) REFERENCES `Users` (`id`),
        `name` VARCHAR(255) NOT NULL,
        `firstname` VARCHAR(255) NOT NULL,
        `birthdate` DATE,
        `job` VARCHAR(255) NOT NULL,
        `cv_path` VARCHAR(350),
        `approved` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending'
    );

-- Create recruiter table

CREATE TABLE
    IF NOT EXISTS `Recruiter` (
        `id` CHAR(36) PRIMARY KEY,
        FOREIGN KEY (`id`) REFERENCES `Users` (`id`),
        `companyName` VARCHAR(255) NOT NULL,
        `companyAddress` VARCHAR(255) NOT NULL,
        `approved` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending'
    );

-- Create consultants table

CREATE TABLE
    IF NOT EXISTS `Consultant` (
        `id` CHAR(36) PRIMARY KEY,
        FOREIGN KEY (`id`) REFERENCES `Users` (`id`),
        `name` VARCHAR(255) NOT NULL,
        `firstname` VARCHAR(255) NOT NULL

);

-- Create table for registering approve request

CREATE TABLE
    IF NOT EXISTS `ApprovalRequest` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `user_id` CHAR(36) NOT NULL,
        `user_type` ENUM('Candidate', 'Recruiter'),
        `request_date` DATE NOT NULL,
        `status` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending',
        FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
    );

-- Create job advertisements table

CREATE TABLE
    IF NOT EXISTS `JobAdvertisement`(
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `city` VARCHAR(255) NOT NULL,
        `planning` VARCHAR(255) NOT NULL,
        `salary` INT(11) NOT NULL,
        `candidate_id` CHAR(36),
        `recruiter_id` CHAR(36) NOT NULL,
        `approved` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending'
    );

-- Create job advertisement approve request table

CREATE TABLE
    IF NOT EXISTS `JobApproveRequest` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `job_id` INT NOT NULL,
        `request_date` DATE NOT NULL,
        `status` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending',
        FOREIGN KEY (`job_id`) REFERENCES `JobAdvertisement` (`id`) ON DELETE CASCADE
    );

-- Create table for job applications approve request

CREATE TABLE
    IF NOT EXISTS `JobApplyApproveRequest` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `candidate_id` CHAR(36) NOT NULL,
        `job_id` INT NOT NULL,
        `request_date` DATE NOT NULL,
        `status` ENUM(
            'pending',
            'approved',
            'rejected'
        ) DEFAULT 'pending',
        FOREIGN KEY (`candidate_id`) REFERENCES `Users` (`id`),
        FOREIGN KEY (`job_id`) REFERENCES `JobAdvertisement` (`id`) ON DELETE CASCADE
    );

-- Create table for all applications on a job

CREATE TABLE
    IF NOT EXISTS `JobApplication` (
        `id` CHAR(36) PRIMARY KEY,
        `job_id` INT,
        `candidate_id` CHAR(36),
        UNIQUE (`job_id`, `candidate_id`),
        FOREIGN KEY (`job_id`) REFERENCES `JobAdvertisement` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`candidate_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
    );