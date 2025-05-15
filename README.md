
# 📘 API Documentation

This API provides access to various resources of the application, such as articles, categories, tags, pages, and user authentication. Public endpoints can be accessed without a token, while protected routes require authentication using **Laravel Sanctum**.

---

## 🔐 Authentication

### Login
**POST** `/login`  
Authenticate a user and receive an access token.

#### Request
```json
{
  "email": "user@example.com",
  "password": "your-password"
}
```

#### Response
```json
{
  "token": "your-access-token",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

---

### Logout  
**POST** `/logout`  
Revoke the current access token.

---

### Get Authenticated User  
**GET** `/user`  
Returns information about the currently authenticated user.

---

### Update User  
**PUT** `/user`  
Update user profile.

#### Request
```json
{
  "name": "Updated Name",
  "email": "updated@example.com"
}
```

---

### Change Password  
**POST** `/passwordChange`

#### Request
```json
{
  "current_password": "old-password",
  "new_password": "new-password",
  "new_password_confirmation": "new-password"
}
```

---

## 📄 Articles

### Public Endpoints

- **GET** `/articles` – List all articles  
- **GET** `/articles/{id}` – Get a single article

### Protected Endpoints (auth required)

- **POST** `/articles` – Create a new article  
- **PUT** `/articles/{id}` – Update an article  
- **DELETE** `/articles/{id}` – Delete an article

---

## 💬 Comments

- **POST** `/comment` – Create a comment  
- **PUT** `/comment/{id}` – Update a comment  
- **DELETE** `/comment/{id}` – Delete a comment

---

## 🏷️ Tags

### Public Endpoints

- **GET** `/tags` – List all tags  
- **GET** `/tags/{id}` – Get a single tag

### Protected Endpoints (auth required)

- **POST** `/tags` – Create a tag  
- **PUT** `/tags/{id}` – Update a tag  
- **DELETE** `/tags/{id}` – Delete a tag

---

## 📂 Categories

### Public Endpoints

- **GET** `/categories` – List all categories  
- **GET** `/categories/{id}` – Get a single category

### Protected Endpoints (auth required)

- **POST** `/categories` – Create a category  
- **PUT** `/categories/{id}` – Update a category  
- **DELETE** `/categories/{id}` – Delete a category

---

## 📄 Pages

### Public Endpoints

- **GET** `/pages` – List all pages  
- **GET** `/pages/{id}` – Get a single page

### Protected Endpoints (auth required)

- **POST** `/pages` – Create a page  
- **PUT** `/pages/{id}` – Update a page  
- **DELETE** `/pages/{id}` – Delete a page

---

## ⚙️ Site Settings

### Public Endpoints

- **GET** `/settings` – Get all site settings  
- **GET** `/settings/{id}` – Get a specific setting

### Protected Endpoints (auth required)

- **POST** `/settings` – Create a setting  
- **PUT** `/settings/{id}` – Update a setting  
- **DELETE** `/settings/{id}` – Delete a setting

---

## 📎 Attachments

### Public Endpoints

- **GET** `/file` – List all files  
- **GET** `/file/{id}` – View a file

### Protected Endpoints (auth required)

- **POST** `/file` – Upload a file  
- **PUT** `/file/{id}` – Update file metadata  
- **DELETE** `/file/{id}` – Delete a file

---

## 🔒 Authentication Required

All `POST`, `PUT`, and `DELETE` routes (except for `/login`) require an **Authorization header**:

```
Authorization: Bearer {token}
```
