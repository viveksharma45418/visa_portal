-- ============================================================
-- Visa Vista Global — Study Visa Application Portal
-- Database Schema
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `visa_portal`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `visa_portal`;

-- ============================================================
-- Main Applications Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `study_visa_applications` (
  `id`                    INT(11) NOT NULL AUTO_INCREMENT,
  `application_id`        VARCHAR(25)  NOT NULL COMMENT 'e.g. VV-2026-0001',

  -- Personal Information
  `full_name`             VARCHAR(150) NOT NULL DEFAULT '',
  `date_of_birth`         DATE         DEFAULT NULL,
  `email`                 VARCHAR(150) NOT NULL DEFAULT '',
  `mobile`                VARCHAR(25)  NOT NULL DEFAULT '',
  `city`                  VARCHAR(100) NOT NULL DEFAULT '',
  `passport_number`       VARCHAR(60)  NOT NULL DEFAULT '',
  `passport_expiry`       DATE         DEFAULT NULL,
  `marital_status`        ENUM('Single','Married','Divorced','Widowed') DEFAULT NULL,

  -- Education & Destination
  `destination_country`   VARCHAR(100) DEFAULT NULL,
  `preferred_intake`      VARCHAR(30)  DEFAULT NULL,
  `course_program`        VARCHAR(250) DEFAULT NULL,
  `preferred_university`  VARCHAR(250) DEFAULT NULL,
  `highest_qualification` VARCHAR(60)  DEFAULT NULL,
  `ielts_score`           VARCHAR(30)  DEFAULT NULL,
  `annual_budget`         VARCHAR(60)  DEFAULT NULL,
  `visa_refusals`         ENUM('No','Yes') DEFAULT 'No',
  `refusal_details`       TEXT         DEFAULT NULL,
  `additional_notes`      TEXT         DEFAULT NULL,

  -- Uploaded Documents (relative paths from project root)
  `passport_file`         VARCHAR(350) DEFAULT NULL,
  `passport_photo`        VARCHAR(350) DEFAULT NULL,
  `tenth_marksheet`       VARCHAR(350) DEFAULT NULL,
  `twelfth_marksheet`     VARCHAR(350) DEFAULT NULL,
  `bachelor_degree`       VARCHAR(350) DEFAULT NULL,
  `ielts_scorecard`       VARCHAR(350) DEFAULT NULL,
  `bank_statement`        VARCHAR(350) DEFAULT NULL,
  `sop_file`              VARCHAR(350) DEFAULT NULL,

  -- Application Meta
  `status` ENUM(
    'Draft',
    'Submitted',
    'Under Review',
    'Documents Requested',
    'Approved',
    'Rejected'
  ) NOT NULL DEFAULT 'Draft',
  `admin_notes`           TEXT         DEFAULT NULL,
  `ip_address`            VARCHAR(45)  DEFAULT NULL,
  `created_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_application_id` (`application_id`),
  KEY `idx_status`     (`status`),
  KEY `idx_email`      (`email`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Admin Activity Log
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin_activity_log` (
  `id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `application_id` VARCHAR(25)  NOT NULL,
  `action`         VARCHAR(100) NOT NULL,
  `old_value`      VARCHAR(200) DEFAULT NULL,
  `new_value`      VARCHAR(200) DEFAULT NULL,
  `notes`          TEXT         DEFAULT NULL,
  `admin_ip`       VARCHAR(45)  DEFAULT NULL,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_app_id` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
