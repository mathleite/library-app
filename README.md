# Library App

## Setup
To set up and run the Library App using Docker Compose, follow these steps:
1. Create a `.env` file based on the `.env.example` file.
2. Run the following command to create the containers and start the application:
```shell
docker compose up --build -d
```
3. Run the following command to run database _migrations_:
```shell
docker compose exec php-app php artisan migrate
```

The application will be available at `http://localhost:8000`.

