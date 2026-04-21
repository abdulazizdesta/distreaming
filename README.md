HOW ABOUT THIS PROJECT

REST API project, building on Laraval 12. Purpose to make simple CRUD systems

## Tech Stack

    1. Laravel Framework 12.56.0
    2. PHP 8.2.30
    3. Composer 2.9.2
    4. Postman
    5. Mysql 8
    6. Git
    7. Laragon (optional)
    8. Laravel Sanctum (Authentication)

## Intallation

1. Clone this repository

-- run this command on your terminal --

git clone https://github.com/abdulazizdesta/distreaming.git
cd distreaming

2. Install Dependencies

composer install
php artisan install:api
php artisan 

3. Setup Envirorenment

cp .env.example .env
php artisan key:generate

4. Database Configuration

Create Your Database and Then Setup youur .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=distreaming
DB_USERNAME=root
DB_PASSWORD=

5. Run Migration

php artisan migrate

6.Install Storage

php artisan storage:link

7. Run The Server

php artisan serve

if you're using laragon, url running on: `http://distreaming.test`

## API Endpoints

### Auth

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login & get a token |
| GET | `/api/auth/profile` | Look on the profile |
| POST | `/api/auth/logout` | Logout |

### Movies

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/movies` | All Movies |
| GET | `/api/movies/{id}` | Movie Detail |
| POST | `/api/movies` | Add new movie |
| PATCH | `/api/movies/{id}` | Update movie |
| DELETE | `/api/movies/{id}` | Soft Delete movie |

Query parameters untuk GET `/api/movies`:

| Parameter | Contoh | Deskripsi |
|-----------|--------|-----------|
| `search` | `?search=adventure` | Search by title |
| `category_id` | `?category_id=1` | Filter by id |
| `sort_by` | `?sort_by=rating&order=desc` | Sort the data by request field |
| `per_page` | `?per_page=5` | Amount of data per page |

### Movie Categories

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/categories` | All Categories |
| GET | `/api/categories/{id}` | Detail Category and List the movies |
| POST | `/api/categories` | Add new category |
| PATCH | `/api/categories/{id}` | Update category |
| DELETE | `/api/categories/{id}` | Delete category |

## Authentication

Every single endpoint (except register & login) need a token. add on header:

Authorization: Bearer {token}
Accept: application/json

## Response Format JSON

Success:
{
    "success": true,
    "message": "Success message",
    "data": {}
}

Error:
{
    "success": false,
    "message": "Error message",
    "errors": {}
}


