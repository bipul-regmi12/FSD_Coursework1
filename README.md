# Kiyome - Pet Adoption Platform

## Overview
**Kiyome** is a modern, full-stack pet adoption platform designed to bridge the gap between animal shelters and prospective pet owners. Built with a focus on usability, security, and performance, it provides a streamlined experience for users to find their perfect animal companions and for shelters to manage their listings efficiently.

## Key Features
- **Automatic Database Setup**: The application automatically handles database and table creation upon first run, simplifying the deployment process.
- **Modern UI/UX**: A sleek, high-end responsive design featuring glassmorphism, vibrant gradients, and smooth animations.
- **Role-Based Experience**: 
  - **Adopters**: Can browse pets, view detailed profiles, and submit adoption applications.
  - **Shelters**: Can manage pet listings (CRUD) and track adoption statuses through a dedicated dashboard.
- **Advanced Routing**: Implements a custom **Front Controller pattern** via `public/index.php`, providing clean, SEO-friendly URLs (e.g., `/pets/view/1` instead of `view_pet.php?id=1`).
- **User Profile Management**: Users can update their personal information and upload profile pictures.
- **Secure Auth System**: Robust session-based authentication with password hashing and role verification.
- **Mobile-First Design**: Fully responsive layout that adapts seamlessly to desktops, tablets, and smartphones.

## Technology Stack
- **Backend**: PHP (Object-Oriented approaches with PDO)
- **Frontend**: Vanilla JavaScript, Modern CSS3 (Grid & Flexbox), Font Awesome 6.4
- **Database**: MySQL 8.x
- **Architecture**: Front Controller Pattern with a clear separation of concerns.

## Project Structure
```text
/Coursework
├── assets/             # Static assets (CSS, JS, Images)
│   ├── css/            # Global styles and design system
│   ├── js/             # Interactive components and frontend logic
│   └── img/            # Site icons and placeholders
├── config/             # Environment & Database configuration
├── includes/           # Core logic and shared components
│   ├── auth.php        # Session and role-based access control
│   ├── functions.php   # Security helpers (CSRF, XSS) and utilities
│   ├── header.php      # Global navigation and meta tags
│   └── footer.php      # Reusable site footer
├── public/             # Entry point and individual view files
│   ├── auth/           # Login, Register, and Account Settings
│   ├── dashboard/      # Role-specific user dashboards
│   ├── pets/           # Pet browsing, adding, and editing views
│   └── index.php       # The application router (Front Controller)
└── database.sql        # Database schema and initialization script
```

## Security Implementation
- **Data Protection**: Consistent use of **PDO Prepared Statements** to eliminate SQL Injection risks.
- **XSS Mitigation**: Centralized `e()` helper function for strict HTML entity encoding on all user-generated content.
- **CSRF Defense**: Token-based validation implemented across all state-changing forms.
- **Credential Security**: Passwords are encrypted using the `BCRYPT` hashing algorithm.
- **HTTP Security**: Implementation of security headers including `X-Frame-Options` and `X-Content-Type-Options`.

## Installation & Setup
1. **Clone the Project**:3x
   ```bash
   git clone <repository-url>
   cd Coursework
   ```
2. **Setup Database**:
   - Create a new MySQL database named `pet_adoption_db`.
   - Import the `database.sql` file into your new database.
3. **Configure Connection**:
   - Edit `config/db.php` and update the database host, name, username, and password.
4. **Launch Application**:
   - Ensure your local server (XAMPP, MAMP, Local) is pointing to the `public/` directory as the document root.
   - Alternatively, use the built-in PHP server:
     ```bash
     php -S localhost:8000 -t public
     ```

## SEO & Accessibility
- **Semantic HTML5**: Proper use of `<header>`, `<main>`, `<nav>`, and `<footer>` elements.
- **Meta Optimization**: Dynamic page titles and descriptive meta tags for improved search visibility.
- **Responsive Media**: Optimized image handling and fluid layouts for all screen sizes.

---
*Developed as part of the Full Stack Development Coursework.*
