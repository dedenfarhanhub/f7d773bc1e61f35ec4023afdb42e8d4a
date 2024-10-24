# Transaction API Service
This is a simple Transaction API built in PHP without using any framework, utilizing MySQL for the database, and Docker for containerization. It provides endpoints for creating transactions and checking their status.

## Table of Contents
- [Requirements](#requirements)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Makefile Commands](#makefile-commands)
- [Docker Setup](#docker-setup)
- [Running Locally Without Docker](#running-locally-without-docker)
- [API Endpoints](#api-endpoints)
- [Security](#security)
- [Error Handling](#error-handling)
- [Contributing](#contributing)
- [License](#license)


## Requirements
- **PHP 8.3+**
- **MySQL 8.0+**
- **Docker**

## Project Structure
```
payment-service
├── .docker                  # Docker-related files and configurations
├── config                   # Configuration files for the application
│   └── Database.php         # Database connection configuration
├── database                 # Database migration scripts and seeders
│   └── migration.php        # Script for running database migrations
├── public                   # Public directory for serving the application
│   ├── index.php            # Entry point for the application
│   └── openapi.yaml         # OpenAPI specification for the API
├── src                      # Source code for the application
│   ├── Controllers          # Controllers for handling requests and responses
│   ├── Exceptions           # Custom exception classes for error handling
│   ├── Models               # Data models representing database entities
│   ├── Repositories         # Interfaces and implementations for data access
│   ├── Requests             # Classes for handling incoming requests
│   ├── Responses            # Classes for structuring outgoing responses
│   └── Services             # Business logic and service classes
├── .env                     # Environment variables for local
├── .env.docker              # Environment variables for Docker
├── docker-compose.yml       # Docker Compose configuration file
├── Dockerfile               # Dockerfile for building the application
└── Makefile                 # Makefile for managing build and run tasks
```

## Getting Started
1. **Clone the repository**.  Navigate to the project directory.
   ```bash
   git clone https://github.com/dedenfarhanhub/f7d773bc1e61f35ec4023afdb42e8d4a.git
   ```
2. Rename the cloned repository directory to blog-service
   ```bash
   mv <cloned-repo> payment-service
   ```
3. Set up environment variables

    Create a `.env` file by copying `.env.example` and configure the values based on your setup:
   ```bash
   cp .env.example .env
   ```

    Before running the application with Docker, make sure to configure the environment variables by creating a `.env.docker` file.
    ```bash
   cp .env.example .env.docker
   ```
    Make sure to configure the following:
   ```
   DB_HOST=db
   DB_PORT=3306
   DB_USER=root
   DB_PASSWORD=app_password
   DB_NAME=payment_db
   ```
## Makefile Commands
This project comes with a Makefile that simplifies the build process. Below are the available commands:

- **build**: Run Docker Compose to build and start the services.
- **build-local**: Set up and run the project locally without Docker.
- **clean**: Stop and remove Docker containers and networks.
- **help**: Display the available Makefile targets.
### Usage
- To build and run with Docker:
    ```bash
    make build
    ```
- To set up and run locally (without Docker):
    ```bash
    make build-local
    ```
- To clean up Docker containers:
    ```bash
    make clean
    ```
## Docker Setup
1. Build and start the containers
   Use Docker to build and start the application and MySQL database:
    ```bash
    make build
    ```
2. Running the CLI Script

    To update the transaction status using the `scripts/transaction-cli.php` script, pass the `references_id` and `status` as arguments when running the script through Docker.

    Example Command:
    ```bash
    docker exec -it payment-service-app-1 php /var/www/html/scripts/transaction-cli.php {references_id} {status}
    ```
    `{references_id}`: The ID of the transaction you want to update.

    `{status}`: The new status you want to set for the transaction (e.g., pending, paid, failed, expired).

## Running Locally Without Docker
To run the application locally without Docker, make sure you have PHP and MySQL set up on your machine. Then, follow these steps:

1. Create the database schema using the migration script:
    ```bash
    php database/migration.php
    ```
2. Start the PHP built-in server:
    ```bash
    php -S localhost:9000 -t public
    ```
3. Running the CLI Script

   To update the transaction status using the scripts/transaction-cli.php script, pass the `references_id` and `status` as arguments when running the script.

   Example Command:
    ```bash
    php scripts/transaction-cli.php {references_id} {status}
    ```
   `{references_id}`: The ID of the transaction you want to update.

   `{status}`: The new status you want to set for the transaction (e.g., pending, paid, failed, expired).

Alternatively, you can run the entire setup using the Makefile command:
```bash
make build-local
```

## API Endpoints
You can access the API documentation using Swagger at the following URL:

- [API Documentation](http://localhost:9000/swagger.html)

### Endpoints
- **Create Transaction**
    - **Method**: `POST`
    - **URL**: `/api/transactions`
    - **Description**: Creates a new transaction.

- **Check Transaction Status** 
  - **Method**: `GET`
  - **URL**: `/api/transactions/status`
  - **Description**: Checks the status of a transaction.

## Security
The following security measures have been implemented:

- **CORS**: Only allow requests from specific domains (configured via the `Access-Control-Allow-Origin header`).
- **Content Security Headers**:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: DENY`
  - `X-XSS-Protection: 1; mode=block`
  
For production, ensure SSL/TLS is enforced for secure data transmission.

## Error Handling
- **404 Not Found**: Returned when an invalid endpoint is accessed.
- **405 Method Not Allowed**: Returned when an invalid HTTP method is used on an endpoint.
- **500 Internal Server Error**: Returned for any server-side issues, with a detailed error logged for debugging purposes.

## Contributing
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Commit your changes.
4. Push to your branch.
5. Open a Pull Request.

## License
This project is licensed under the MIT License - see the LICENSE file for details.

