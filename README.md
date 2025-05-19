# EZBLOG

## 📌 Description
The **EZBlOG** is a web-based application designed to manage and publish blog posts efficiently. It allows users to create, edit, and delete blog posts while storing data in a MySQL database. The backend is built using PHP and MySQL, and the frontend uses HTML, CSS, and JavaScript.

## 🚀 Features
- Create, update, and delete blog posts
- Store posts in a MySQL database
- Responsive UI using HTML5, CSS3, and JavaScript
- PHP-based backend for handling CRUD operations

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

### 1. Clone the Repository
Open a terminal and run the following command:
```sh
git clone git@github.com:PrimeRin/blog-sphere.git
cd blog-sphere
```

### 2. Install MySQL Server
In Ubuntu, Run the mysql server in the docker
 ```
 docker run --name mysql_container -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=blog_sphere -e MYSQL_USER=blog_user -e MYSQL_PASSWORD=password -p 3306:3306 -v mysql_data:/var/lib/mysql -d mysql:latest 
 ```

### 3. Apply Schema Migrations

To create tables in the database, run:
```sh
mysql -u root -p blog_sphere < database/01_create_users_table.sql
```

You will be prompted to enter the MySQL root password.

### 4. Run the Application
#### Using PHP Built-in Server:
```sh
php -S localhost:8000
```

## 📜 License
This project is licensed under the MIT License.

---

## 🤝 Contribution
Feel free to fork this repository and submit a pull request.

Happy Coding! 🚀

