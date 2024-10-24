# Makefile

# Define the default target when running "make" without arguments
default: help

# Help target to display available targets
help:
	@echo "Available targets:"
	@echo "build     		- Run Docker Compose"
	@echo "build-local	 	- Setup without Docker"
	@echo "clean       		- Stop and remove containers"
	@echo "help        		- Display this help message"

# Run Docker Compose
build:
	@echo "Running Docker Compose..."
	@docker-compose up --build

# Build Without Docker
build-local:
	@echo "Running Setup local..."
	@php database/migration.php
	@php -S localhost:9000 -t public
# Stop and remove containers
clean:
	@echo "Stopping and removing containers..."
	@docker-compose down --remove-orphans

.PHONY: default help build build-local clean