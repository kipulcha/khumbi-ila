# Khumbila Adventure Travel - Project Setup Guide

## 🚀 Project Successfully Running!

Your Khumbila Adventure Travel website is now running locally with a mock database setup.

## 📍 Access Your Website

**Local URL:** http://localhost:8000

The website is currently running with sample data to demonstrate functionality.

## 🛠️ What's Been Set Up

### ✅ Completed Setup
- ✅ PHP 8.4.12 installed via Homebrew
- ✅ PHP development server running on port 8000
- ✅ Mock database system providing sample data
- ✅ Website fully functional with sample content

### 📁 Project Structure
```
khumbi-ila.com/
├── index.php              # Main homepage
├── about.php              # About page
├── contact.php            # Contact form
├── enquire.php            # Inquiry system
├── programs.php           # Tour packages
├── services.php           # Travel services
├── config.php             # Configuration (modified for local setup)
├── mock_database.php      # Mock database for development
├── setup_database.sql     # Database schema (for future use)
└── admin/                 # Admin panel
```

## 🎯 Current Status

The website is running with:
- **Sample tour packages** (Everest Base Camp, Annapurna Circuit, Langtang Valley)
- **Sample services** (Flight Booking, Hotel Reservation, Car Rental)
- **Mock slider images** and content
- **Fully functional navigation** and responsive design

## 🔧 Next Steps (Optional)

### 1. Set Up Real Database (Optional)
If you want to use a real MySQL database instead of the mock data:

1. **Start MySQL:**
   ```bash
   brew services start mysql
   ```

2. **Create database:**
   ```bash
   mysql -u root -e "CREATE DATABASE db_khumbila;"
   ```

3. **Import schema:**
   ```bash
   mysql -u root db_khumbila < setup_database.sql
   ```

4. **Update config.php** to use real database by commenting out the mock database fallback.

### 2. Admin Panel Access
- **URL:** http://localhost:8000/admin/
- **Note:** Admin functionality requires database setup

### 3. Customization
- Edit content in the respective PHP files
- Add real images to `admin/site_images/` directories
- Modify styles in `css/style.css`

## 🚀 Running the Project

### Start the server:
```bash
cd /Users/uba/Monotype/Projects/PHP/khumbi-ila.com
php -S localhost:8000
```

### Stop the server:
Press `Ctrl+C` in the terminal where the server is running.

## 📱 Features Available

- ✅ Responsive design
- ✅ Tour package listings
- ✅ Service information
- ✅ Contact forms
- ✅ Image galleries
- ✅ Mobile-optimized navigation
- ✅ SEO-friendly structure

## 🎉 Success!

Your Khumbila Adventure Travel website is now running successfully! You can view it at http://localhost:8000 and explore all the features.
