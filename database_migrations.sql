-- Migration: Add game detail columns to orders table
-- This allows orders table to store complete cart item information

-- Check if columns exist, then add them
ALTER TABLE orders ADD COLUMN game_id INT NOT NULL DEFAULT 0;
ALTER TABLE orders ADD COLUMN game_title VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE orders ADD COLUMN quantity INT DEFAULT 1;
ALTER TABLE orders ADD COLUMN price DECIMAL(10, 2) NOT NULL DEFAULT 0.00;

-- Verify table structure
-- DESCRIBE orders;

-- Migration: Add download_link to games table to store external download/page URL
ALTER TABLE games ADD COLUMN download_link VARCHAR(1024) DEFAULT '';
