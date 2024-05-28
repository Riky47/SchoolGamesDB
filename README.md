# SchoolGamesDB
A PHP and SQL Database Manager for School Games Management
- A school project in preparation for the final exam.

## About the Project
Our system allows students to play subject-themed games and earn coins!

It actually simulates the games, as the main objective of the project is to develop and manage the entire system surrounding such:
- user management, game management, classes, leaderboards, and much more.


## Technologies Used
Our system is primarily built using **PHP** and **JavaScript**, and utilizes a **MySQL** database to securely store data.

Key features include:
- Password hashing with **Argon2**
- Protection against **SQL Injection**
- Secure connections through **token sessions**

## How to Run Locally
To set up the system locally, follow these steps:

1. **Ensure a Local MySQL Database is Running**
   - We recommend using [XAMPP](https://www.apachefriends.org/).
   - Once installed, open it and start both **Apache** and **MySQL**.

2. **Move the Repo Folder**
   - Move the repository folder to XAMPP's `htdocs` directory.
   - The default path is: `C:\xampp\htdocs`

3. **Access the System**
   - You can use the product locally by navigating to the `Main.php` page in your browser.
   - If you followed all the previous instructions, you should be able to access it by clicking [here](http://localhost/SchoolGamesDB/Interfaces/Main.php).

By following these steps, you'll be able to enjoy and manage the SchoolGamesDB system on your local machine!
