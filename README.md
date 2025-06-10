# Job Board Application

A full-stack job board application built with Laravel (Backend) and Vue.js (Frontend). The application allows employers to post jobs and job seekers to find and apply for positions.

## Features

- **User Authentication**
  - Register as employer or job seeker
  - Login/Logout functionality
  - Protected routes based on user type

- **Employer Features**
  - Create and manage company profile
  - Post, edit, and delete job listings
  - View job applications
  - Manage application statuses

- **Job Seeker Features**
  - Create and manage professional profile
  - Search and filter job listings
  - Apply to jobs
  - Save favorite jobs
  - Track application status

## Prerequisites

- PHP >= 8.0
- Composer
- Node.js >= 14
- MySQL >= 5.7
- WAMP/XAMPP/MAMP (or similar local development environment)

## Installation

### Backend Setup

1. Navigate to the backend directory:
```bash
cd backend
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create a copy of the environment file:
```bash
cp .env.example .env
```

4. Configure your database settings in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_board
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations and seeders:
```bash
php artisan migrate --seed
```

7. Start the Laravel development server:
```bash
php artisan serve
```

The backend API will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to the frontend directory:
```bash
cd frontend
```

2. Install Node.js dependencies:
```bash
npm install
```

3. Create a copy of the environment file:
```bash
cp .env.example .env
```

4. Configure the API endpoint in `.env`:
```
VITE_API_URL=http://localhost:8000/api
```

5. Start the development server:
```bash
npm run dev
```

The frontend application will be available at `http://localhost:5173`

## Usage

### Default Test Accounts

After running the seeders, you can use these test accounts:

**Employer Account:**
- Email: employer@example.com
- Password: password

**Job Seeker Account:**
- Email: jobseeker@example.com
- Password: password

### Development Workflow

1. Start the backend server:
```bash
cd backend
php artisan serve
```

2. In a new terminal, start the frontend development server:
```bash
cd frontend
npm run dev
```

3. Access the application at `http://localhost:5173`

## API Documentation

The API endpoints are documented using OpenAPI/Swagger. You can access the documentation at:
`http://localhost:8000/api/documentation`

## Testing

### Backend Tests
```bash
cd backend
php artisan test
```

### Frontend Tests
```bash
cd frontend
npm run test
```

## Deployment

### Backend Deployment
1. Set up your production environment
2. Configure your web server (Apache/Nginx)
3. Update `.env` with production settings
4. Run migrations: `php artisan migrate --force`
5. Optimize Laravel: `php artisan optimize`

### Frontend Deployment
1. Build the production assets:
```bash
npm run build
```
2. Deploy the contents of the `dist` directory to your web server

## Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details 