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

[<img align="left" alt="PHP" width="50px"  src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/php/php.png" />][PHP]
[<img align="left" alt="MySQL" width="50px"  src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/mysql/mysql.png" />][MySQL]
[<img align="left" alt="HTML" width="50px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/html/html.png" />][HTML]
[<img align="left" alt="CSS" width="50px" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/css/css.png" />][CSS]

[PHP]: https://www.php.net
[MySQL]: https://www.mysql.com
[HTML]: https://html.com
[CSS]: https://www.w3.org/Style/CSS/Overview.en.html
