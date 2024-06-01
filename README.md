# LV.SHOP Full-Stack Application
![screencapture-localhost-8000-2024-06-01-18_43_24](https://github.com/samtechy26/laravuecommerce/assets/101216609/bf6c59a0-e0a7-422f-a6a9-a1c9fa734cd7)

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Welcome to LV.SHOP full-stack application. This project is built using Laravel for the backend, Inertia.js to bridge Laravel with Vue.js, and Vue.js for the frontend. The application aims to provide a complete and scalable solution for an online store, including product management, user authentication, and a smooth shopping experience.

## Features

- User Authentication (Register, Login, Password Reset)
- Product Management (CRUD Operations)
- Category Management
- Shopping Cart
- Wishlist
- Order Processing
- Payment Integration
- User Profile Management
- Admin Dashboard
- Responsive Design

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Vue.js, Inertia.js
- **Database**: MySQL
- **Payment Gateway**: (Stripe)
- **Authentication**: Laravel Sanctum

## Installation

### Prerequisites

Ensure you have the following installed on your machine:

- PHP >= 8.0
- Composer
- Node.js & npm
- MySQL

### Steps

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/ecommerce-app.git
    cd ecommerce-app
    ```

2. Install backend dependencies:

    ```bash
    composer install
    ```

3. Install frontend dependencies:

    ```bash
    npm install
    ```

4. Create a `.env` file by copying the `.env.example`:

    ```bash
    cp .env.example .env
    ```

5. Generate an application key:

    ```bash
    php artisan key:generate
    ```

6. Set up your database and update your `.env` file:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ecommerce_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. Run migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

8. Build the frontend assets:

    ```bash
    npm run dev
    ```

9. Serve the application:

    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` in your browser to see the application.

## Usage

- Register a new account or log in with existing credentials.
- Browse products, add to cart, and proceed to checkout.
- Add items to wishlist if you so desire.
- Manage your profile and view order history.
- Admin users can manage products, categories, and view orders from the dashboard.

## Screenshots

- Home Page
- Product Page
- Shopping Cart
- Checkout
- User Profile
- Admin Dashboard

![Home Page](![screencapture-localhost-8000-2024-06-01-18_43_24](https://github.com/samtechy26/laravuecommerce/assets/101216609/bf6c59a0-e0a7-422f-a6a9-a1c9fa734cd7))
![Product Page](![screencapture-localhost-8000-products-2024-06-01-18_44_45](https://github.com/samtechy26/laravuecommerce/assets/101216609/16015e85-1a54-4024-a282-526c3b2cc800)
)

## Contributing

We welcome contributions! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
