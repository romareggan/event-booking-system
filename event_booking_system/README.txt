EVENTBOOK - SIMPLE EVENT BOOKING SYSTEM
=======================================

TECHNOLOGIES
- HTML
- CSS
- JavaScript
- PHP
- MySQL

REQUIREMENTS
- XAMPP (Apache and MySQL)
- A web browser
- A code editor such as VS Code

INSTALLATION
1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Copy the event_booking_system folder into:
   C:\xampp\htdocs\
4. Open http://localhost/phpmyadmin
5. Select Import and import database.sql.
   The script creates the event_booking database, tables and two sample events.
6. Check config.php. The default XAMPP MySQL username is root and password is blank.
   Change these values if your local MySQL setup is different.
7. Open:
   http://localhost/event_booking_system/

CREATE AN ADMIN ACCOUNT
1. Register a normal account through the website.
2. Open phpMyAdmin and select the event_booking database.
3. Open the SQL tab and run this command, replacing the email:
   UPDATE users SET role='admin' WHERE email='your-email@example.com';
4. Log out and log in again. The Admin link/dashboard will now be available.

MAIN FEATURES
- User registration and login
- Browse and search events
- View event details
- Book multiple tickets
- View and cancel bookings
- Admin dashboard
- Add, edit and delete events
- View all bookings

PROJECT NOTES
- This is a simple educational class project.
- Bookings are recorded in the database; no real online payment is processed.
- No email or SMS notifications are sent.
- Before using this system in production, add CSRF protection, stronger validation,
  audit logging, and additional security controls.
