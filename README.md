<img width="1000" height="500" alt="Ekran Resmi 2026-05-10 23 22 26" src="https://github.com/user-attachments/assets/0c1417b7-bea6-4bea-ab98-9d5110e19603" />

# Lab Sheet 4 — PHP Authentication System

A secure login system built with PHP and MySQL, featuring SHA-256 password hashing and user activity tracking.

## Features

- SHA-256 password hashing (no plain-text passwords stored)
- Three-state authentication logic:
  - ✅ Authenticated — valid credentials, session created
  - 🚨 Fraud — correct username but wrong password, logged with IP
  - 🔵 Visitor — username not found in the system, logged for review
- Separate database tables for users, fraud attempts, and visitors
- Modular database connection via `config.php`

## Technologies

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

[PHP]: https://www.php.net
[MySQL]: https://www.mysql.com
[HTML5]: https://html.com
[CSS3]: https://www.w3.org/Style/CSS/Overview.en.html
