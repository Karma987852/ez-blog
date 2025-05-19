# EZBLOG
![logo](./public/assets/img/logo.png)

## 📌 Description
The **EZBlOG** is a web-based application designed to manage and publish blog posts efficiently. It allows users to create, edit, and delete blog posts while storing data in a MySQL database. The backend is built using PHP and MySQL, and the frontend uses HTML, CSS, and JavaScript.

## 🚀 Features
- **User Registration & Authentication**  
  - Email/password login with secure hashing.
  
- **Blog Post Management**  
  - Create/edit/delete/publish posts with images & text.

- **User Roles & Permissions**  
  - **Blogger**: Manage own posts.  
  - **Reader**: Read/comment on posts.

- **Commenting System**  
  - Comments + like/dislike functionality.

- **Search**  
  - Enables users to search posts by the post title.

## 📂 Project Structure
```
📁 blog_sphere/
├── 📁 config/          # Database configuration
├── 📁 database/        # SQL migration files
├── 📁 public/          # Public assets (CSS, JS, images)
├── 📁 src/             # Core PHP scripts
├── index.php           # Entry point
├── README.md           # Project documentation
```

---

## 🚀 Installation Guide

### Database setup (mysql)
#### In windows
##### 1. Download MySQL Installer
- Download mysql installer from https://dev.mysql.com/downloads/installer/

##### 2. Run the Installer
- Select "Developer Default" (includes MySQL Server + tools like Workbench).
- Follow prompts to install.

#### 3. Configure MySQL Server
- Choose "Standalone MySQL Server".
- Set root password (remember this!).
- Keep default settings (port 3306).

#### 4. Complete Installation
- Click "Execute" to install.

#### 5. Create the ez_blog Database
- Open MySQL Command Line Client or run:
```sh
mysql -u root -p
```

- Create Database
```sh
CREATE DATABASE ez_blog;
```

- (Optional) Create a Dedicated User
```sh
CREATE USER 'blog_admin'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON ez_blog.* TO 'blog_admin'@'localhost';
FLUSH PRIVILEGES;
```

#### In Ubuntu
1. In Ubuntu, Run the mysql server in the docker
 ```sh
 docker run --name mysql_container -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=blog_sphere -e MYSQL_USER=blog_user -e MYSQL_PASSWORD=password -p 3306:3306 -v mysql_data:/var/lib/mysql -d mysql:latest 
 ```

### Project setup
### 1. Clone the Repository
Open a terminal and run the following command:
```sh
git clone git@github.com:PrimeRin/blog-sphere.git
cd blog-sphere
```

### 2. Apply Schema Migrations

To create tables in the database, run:
```sh
mysql -u root -p blog_sphere < database/01_create_users_table.sql
```

You will be prompted to enter the MySQL root password.

### 3. Run the Application
#### Using PHP Built-in Server:
```sh
php -S localhost:8000
```
