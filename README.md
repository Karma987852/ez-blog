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
📁 ez_blog/
├── 📁 config/          # Database configuration
├── 📁 database/        # SQL migration files
├── 📁 public/          # Public assets (CSS, JS, images)
├── 📁 src/             # Core PHP scripts
├── index.php           # Entry point
├── README.md           # Project documentation
```

---

## 🚀 Installation Guide

### Project setup
### 1. Clone the Repository
Open a terminal and run the following command:
```sh
git clone git@github.com:Karma987852/ez-blog.git
cd ez-blog
```

### 2. Apply Schema Migrations

To create tables in the database, run:
```sh
database/01_create_users_table.sql
```
