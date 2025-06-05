#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Setting up TradeShare...${NC}"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}PHP is not installed. Please install PHP 7.4 or higher.${NC}"
    exit 1
fi

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    echo -e "${RED}MySQL is not installed. Please install MySQL 5.7 or higher.${NC}"
    exit 1
fi

# Create uploads directory if it doesn't exist
if [ ! -d "uploads" ]; then
    echo -e "${YELLOW}Creating uploads directory...${NC}"
    mkdir uploads
    chmod 777 uploads
fi

# Copy config file if it doesn't exist
if [ ! -f "config.php" ]; then
    echo -e "${YELLOW}Creating config.php from config.bak...${NC}"
    cp config.bak config.php
fi

# Ask for database credentials
echo -e "${YELLOW}Please enter your MySQL credentials:${NC}"
read -p "MySQL Username: " DB_USER
read -s -p "MySQL Password: " DB_PASS
echo

# Create database
echo -e "${YELLOW}Creating database...${NC}"
mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS tradeshare;"

# Import schema
echo -e "${YELLOW}Importing database schema...${NC}"
mysql -u "$DB_USER" -p"$DB_PASS" tradeshare < schema.sql

# Update config.php with database credentials
echo -e "${YELLOW}Updating database configuration...${NC}"
sed -i.bak "s/define('DB_USER', 'root');/define('DB_USER', '$DB_USER');/" config.php
sed -i.bak "s/define('DB_PASS', '');/define('DB_PASS', '$DB_PASS');/" config.php
rm config.php.bak

echo -e "${GREEN}Setup completed successfully!${NC}"
echo -e "${YELLOW}You can now start the application with:${NC}"
echo -e "php -S localhost:8000"
echo -e "${YELLOW}Then visit:${NC}"
echo -e "http://localhost:8000"
echo -e "${YELLOW}Default admin credentials:${NC}"
echo -e "Username: admin"
echo -e "Password: admin123" 