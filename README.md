# 🛒 Montik – Laravel E-commerce Application

**Montik** is a PHP-based e-commerce application developed as a coding challenge. It enables users to register products, add them to a shopping cart, and complete purchases. A unique feature is the integration of a Brazilian postal code (CEP) verification API, ensuring that purchases are only completed with valid CEP inputs.

## ✨ Features

- **Product Management**: Register and manage products in the database.
- **Shopping Cart**: Add products to a session-based shopping cart.
- **CEP Verification**: Validate Brazilian postal codes before allowing purchases.
- **User Session Handling**: Maintain cart state throughout the user's session.

## 🛠️ Technologies Used

- **Backend**: PHP
- **Frontend**: Javascript, HTML, CSS with Bootstrap
- **Database**: MySQL
- **CEP API**: Integration with a CEP verification service

## 📦 Installation

### Prerequisites

- PHP >= 7.4
- Composer
- MySQL
- Node.js & npm

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Andreeoak/Montik.git
   cd Montik
   php -S localhost:8000 -t public
   
