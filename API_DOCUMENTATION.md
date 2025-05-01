# API Documentation

## Authentication Endpoints

### Register a new user
```
POST /api/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

**Response (201 Created):**
```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2023-05-01T12:00:00.000000Z",
            "updated_at": "2023-05-01T12:00:00.000000Z"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz"
    }
}
```

### Login
```
POST /api/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "User logged in successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2023-05-01T12:00:00.000000Z",
            "updated_at": "2023-05-01T12:00:00.000000Z"
        },
        "token": "2|abcdefghijklmnopqrstuvwxyz"
    }
}
```

### Logout
```
POST /api/logout
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "User logged out successfully",
    "data": null
}
```

### Get Current User
```
GET /api/me
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": null,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z"
    }
}
```

### Request Password Reset
```
POST /api/forgot-password
```

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "We have emailed your password reset link!",
    "data": null
}
```

### Reset Password
```
POST /api/reset-password
```

**Request Body:**
```json
{
    "token": "abcdefghijklmnopqrstuvwxyz",
    "email": "john@example.com",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Your password has been reset!",
    "data": null
}
```
