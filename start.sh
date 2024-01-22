#!/bin/bash

# Check if the 'vendor' directory exists for Composer
if [ ! -d "vendor" ]; then
    # 'vendor' directory does not exist, so install Composer dependencies
    composer install
else
    echo "Composer dependencies already installed."
fi


# Check if the 'node_modules' directory exists for npm
if [ ! -d "node_modules" ]; then
    # 'node_modules' directory does not exist, so install npm dependencies
    npm install
else
    echo "npm dependencies already installed."
fi

# Start PHP server
php -S localhost:8080 &

# Start npm
npm start
