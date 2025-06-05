# Job Board Application

A modern job board platform built with Vue.js and Laravel, allowing companies to post jobs and job seekers to find and apply for opportunities.

## Features

### For Job Seekers
- Browse available job listings
- Search and filter jobs by various criteria
- Create and manage your profile
- Apply to jobs with cover letters
- Track your job applications
- Save jobs for later

### For Companies
- Post and manage job listings
- Company profile management
- Review job applications
- Track applicant status
- Manage active and inactive job posts

## Tech Stack

### Frontend
- Vue.js 3
- TypeScript
- Tailwind CSS
- Pinia for state management
- Vue Router
- Headless UI components

### Backend
- Laravel
- MySQL
- Laravel Sanctum for authentication
- RESTful API

## Getting Started

### Prerequisites
- Node.js (v14 or higher)
- PHP 8.0 or higher
- Composer
- MySQL
- WAMP/XAMPP/MAMP (for local development)

### Installation

1. **Clone the repository**
```bash
git clone [repository-url]
cd job_board2
```

2. **Backend Setup**
```bash
cd backend
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_board
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Start the backend server
php artisan serve
```

3. **Frontend Setup**
```bash
cd frontend
# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Configure API URL in .env file
VITE_API_URL=http://localhost:8000

# Start development server
npm run dev
```

## Usage

### For Job Seekers
1. Register a new account as a "Job Seeker"
2. Complete your profile with relevant information
3. Browse available jobs
4. Apply to jobs with a cover letter
5. Track your applications in the dashboard

### For Companies
1. Register a new account as a "Company"
2. Complete your company profile
3. Post new job listings from your dashboard
4. Manage job posts (activate/deactivate)
5. Review and manage applications

## Project Structure

```
job_board2/
├── backend/                # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   ├── Models/
│   │   └── ...
│   ├── database/
│   └── routes/
└── frontend/              # Vue.js frontend
    ├── src/
    │   ├── components/
    │   ├── views/
    │   ├── stores/
    │   └── router/
    └── public/
```

## API Endpoints

### Authentication
- POST `/api/auth/register` - Register new user
- POST `/api/auth/login` - User login
- POST `/api/auth/logout` - User logout

### Jobs
- GET `/api/jobs` - List all jobs
- POST `/api/jobs` - Create new job
- GET `/api/jobs/{id}` - Get job details
- PUT `/api/jobs/{id}` - Update job
- DELETE `/api/jobs/{id}` - Delete job

### Applications
- POST `/api/applications` - Submit job application
- GET `/api/applications/user/{user}` - Get user's applications
- GET `/api/applications/job/{job}` - Get job's applications

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details 