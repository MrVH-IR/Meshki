# Meshki

**Meshki** is an online platform where users can discover, listen to, and watch music videos from their favorite artists. The name "Meshki" means "black" in Persian, symbolizing the deep and immersive experience we aim to provide.

## Features:
- User registration and personalized profiles
- Personalized playlists
- Discover and follow your favorite artists
- Stream and enjoy high-quality music and videos
- Admin panel to manage the website
- Playlist creation and management
- User-friendly interface
- Mail Verification
- User-friendly interface

Join Meshki today and dive into the world of beautiful music and videos!

## Local Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/MrVH-IR/meshki.git
   ```

2. Navigate to the project directory:
   ```bash
   cd meshki
   ```

3. Import the database:
   ```bash
   mysql -u yourusername -p yourdatabase < meshki.sql
   ```

4. Start the development server:
   ```bash
   php -S localhost:8000
   ```

5. Create an admin user in the `tbladmins` table in your database:
   ```sql
   INSERT INTO tbladmins (username, password, phone, email, is_admin, role) 
   VALUES ('admin', 'admin', '09123456789', 'admin@admin.com', 1, 'admin');
   ```

6. Open your browser and navigate to `http://localhost:8000`.

7. Access the admin panel at `http://localhost:8000/admin/dashboard.php`.







