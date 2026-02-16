-- Migration to add department field and registered_at timestamp to sign_up table
-- Run this if you already have the sign_up table created

USE klm;

-- Add new columns to existing sign_up table
ALTER TABLE sign_up 
ADD COLUMN department varchar(255) NULL AFTER demo,
ADD COLUMN registered_at datetime DEFAULT CURRENT_TIMESTAMP AFTER department;

-- If you need to recreate the table, drop it first and run the klm.sql file
-- DROP TABLE IF EXISTS sign_up;
