# Share2People 📢🔗

**Share2People** is a modern API built with Laravel 11 following Clean Architecture principles. It allows users to share content such as shortlinks, links, code snippets, markdown content, and images. The API features secure JWT authentication with auto-expiring tokens and refresh support, email verification, and password reset capabilities.

---

## 🚀 Features

- **Clean Architecture**:
    - Separation into Domain, Application, Infrastructure, and Presentation layers.
- **User Authentication**:
    - JWT authentication with auto-expiration and refresh tokens.
- **Email Verification**:
    - New users must verify their email before accessing protected endpoints.
- **Password Reset**:
    - Built-in endpoints for sending password reset emails and resetting passwords.
- **Content Sharing**:
    - CRUD operations for sharing links, code, images, and more.
- **API Documentation**:
    - OpenAPI (Swagger) annotations included for easy API exploration.

---

## 🏗️ Architecture Overview

### Domain Layer
- **Core Models**:
    - **User**: A plain PHP object representing a user, containing fields like `id`, `name`, `email`, `password`, and timestamps.
    - **Share**: Represents shared content with attributes such as `userId`, `title`, `description`, `contentType`, etc.
- **Repository Interfaces**:
    - Define contracts for data persistence (e.g., `UserRepositoryInterface`, `ShareRepositoryInterface`).

### Application Layer
- **DTOs & UseCases**:
    - **DTOs** (e.g., `CreateUserRequestDTO`, `LoginUserRequestDTO`) transfer data between layers.
    - **UseCases** (e.g., `RegisterUserUseCase`, `LoginUserUseCase`) encapsulate business logic such as registration (with email verification) and login (with an email verification check).

### Infrastructure Layer
- **Eloquent Models**:
    - **EloquentUser**: Extends Laravel’s Authenticatable, implements `JWTSubject` and `MustVerifyEmail`, and provides email verification capabilities.
    - **EloquentShare**: Manages persistence for share content.
- **Services & Repositories**:
    - Concrete implementations for repositories and services (e.g., `EmailVerificationService`, `JWTAuthService`) are here.
- **Mappers**:
    - Map between Domain models and Eloquent models to keep the Domain layer framework-agnostic.

### Presentation Layer
- **Controllers & Requests**:
    - **AuthController**: Provides endpoints for registration, login, logout, refresh, and retrieving user details.
    - **EmailVerificationController** & **PasswordController**: Manage email verification and password resets.
    - **UserController** and **ShareController**: Handle CRUD operations.
- **Routes**:
    - Public endpoints for registration, login, email verification, and password resets; protected endpoints (using JWT middleware) for all other actions.
- **OpenAPI Documentation**:
    - Each controller is documented with Swagger annotations. Global API info and the bearer token security scheme are defined in the BaseController.

---

## 🔧 Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- Docker & Docker Compose
- Laravel Sail

### Installation Steps
1. **Clone the Repository:**
    ```bash
    git clone https://github.com/yourusername/share2people.git
    cd share2people
    ```
2. **Install Dependencies:**
    ```bash
    composer install
    ```
3. **Configure Environment:**
    - Copy `.env.example` to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Update the `.env` file with your database and mail credentials.
4. **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```
5. **Start Docker Containers (using Sail):**
    ```bash
    ./vendor/bin/sail up -d
    ```
6. **Run Migrations:**
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

### Running the Application
- **API Endpoints:** Available at [http://localhost/api](http://localhost/api)
- **API Documentation:** Available at [http://localhost/api/documentation](http://localhost/api/documentation)
- **phpMyAdmin:** Access via [http://localhost:8080](http://localhost:8080)

---

## 📖 API Documentation

OpenAPI (Swagger) documentation is integrated. You can generate or view the docs using [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) or your preferred tool. This documentation details all endpoints, required parameters, and responses.

---

## 🤝 Contributing

Contributions are welcome!
1. Fork the repository.
2. Create your feature branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -am 'feat: Add your feature'`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a pull request.

---

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## 📫 Contact

For questions or suggestions, please reach out via [your.email@example.com](mailto:your.email@example.com).

Happy Coding! 🎉🚀
