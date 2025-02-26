# Setup

This project is managed used docker compose and Makefile.

## Prerequisites
Ensure you have the following installed before running the commands:
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Make](https://www.gnu.org/software/make/)

---

# Architecture

The Docker Compose setup includes multiple services, each serving a specific purpose:

### Services
- **mysql**: A mysql service for managing data storage.
- **mysql_integration**: A mysql service with a temporary filesystem for running integration tests.
- **apache**: The service that contains the app production-ready, that is, no development dependencies installed, optimized autoload.php and in-image application source code.
- **phpmyadmin**: An utility service for inspecting the database
- **app_test**: A service apt for running tests, includes composer and the development dependencies for running unit tests and integration tests.

### Profiles

The services in the Docker Compose setup are tagged with specific profiles so they can be built and started based on the environment or task needed. The available profiles are:

- **production**: For running the deployed application. Starts `mysql`, `apache`, and `phpmyadmin`.
- **development**: For development purposes. Starts `phpmyadmin`, `mysql`, `apache`, and `phpmyadmin`.
- **test**: For running unit tests. Starts `app_test`, runs the unit tests, and exits.
- **integration**: For running integration tests. Starts `app_test` and `mysql_integration`.
 
## Usage

### Build the Project
To build all Docker images for different environments (production, development, testing, and integration), run:

```sh
make all
```

---

### Start and Stop Services
- **Start the application** (Apache, MySQL):  
  ```sh
  make up
  ```
- **Stop and remove all containers, networks, and volumes**:  
  ```sh
  make down
  ```
- **Clear all data**
  ```sh
  make clean
  ```
---

### Running Tests

Before running tests, it's recommended you build the project so you can docker compose skips building and you can see only phpunit output.


#### Unit Tests
Run unit tests inside the test environment:

```sh
make unit
```

If your shell supports it, to suppress docker compose logs and see only phpunit output run:

```sh
make unit 2>/dev/null
```

#### Integration Tests
Run integration tests inside a dedicated environment:

```sh
make integration

```
If your shell supports it, to suppress docker compose logs and see only phpunit output run:

```sh
make integration 2>/dev/null
```

The previous command will start the testing database service and run the command for running integration test inside app_test. app_test will wait for the database to be up and fail after a 30 seconds timeout.

#### Run All Tests
To run both unit and integration tests:

```sh
make test
```

---

### Cleaning Up
To remove all containers, images, and volumes (full cleanup):

```sh
make clean
```
---


# See it in action.

![Demo](doc/demo.gif)
