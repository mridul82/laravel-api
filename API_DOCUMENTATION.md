# Lead Generation API Documentation

**IMPORTANT: This document is for internal use only. Do not share or commit to public repositories.**

## Base URL
```
http://localhost:8000/api
```

## Role-Based Access Control

This API uses role-based access control (RBAC) to manage permissions. There are two default roles:

1. **Admin**: Has access to all features
2. **User**: Has limited access to leads and contacts

Each role has specific permissions:

- **Role Permissions**: role-list, role-create, role-edit, role-delete
- **User Permissions**: user-list, user-create, user-edit, user-delete
- **Lead Permissions**: lead-list, lead-create, lead-edit, lead-delete
- **Contact Permissions**: contact-list, contact-create, contact-edit, contact-delete
- **Product Permissions**: product-list, product-create, product-edit, product-delete

Default users:
- Admin: admin@example.com / password
- Regular User: user@example.com / password

## Authentication APIs

### 1. Register a new user
```
POST /register
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
            "updated_at": "2023-05-01T12:00:00.000000Z",
            "roles": [
                {
                    "id": 2,
                    "name": "User",
                    "guard_name": "web",
                    "created_at": "2023-05-01T12:00:00.000000Z",
                    "updated_at": "2023-05-01T12:00:00.000000Z",
                    "pivot": {
                        "model_id": 1,
                        "role_id": 2,
                        "model_type": "App\\Models\\User"
                    },
                    "permissions": [
                        // ... permissions
                    ]
                }
            ]
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz",
        "permissions": [
            "lead-list",
            "lead-create",
            "contact-list",
            "contact-create"
        ],
        "redirect": {
            "path": "/dashboard",
            "name": "Dashboard"
        }
    }
}
```

### 2. Login
```
POST /login
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
            "updated_at": "2023-05-01T12:00:00.000000Z",
            "roles": [
                {
                    "id": 1,
                    "name": "Admin",
                    "guard_name": "web",
                    "created_at": "2023-05-01T12:00:00.000000Z",
                    "updated_at": "2023-05-01T12:00:00.000000Z",
                    "pivot": {
                        "model_id": 1,
                        "role_id": 1,
                        "model_type": "App\\Models\\User"
                    },
                    "permissions": [
                        // ... permissions
                    ]
                }
            ]
        },
        "token": "2|abcdefghijklmnopqrstuvwxyz",
        "permissions": [
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",
            "lead-list",
            "lead-create",
            "lead-edit",
            "lead-delete",
            "contact-list",
            "contact-create",
            "contact-edit",
            "contact-delete"
        ],
        "redirect": {
            "path": "/dashboard",
            "name": "Dashboard"
        }
    }
}
```

### 3. Logout
```
POST /logout
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

### 4. Get Current User
```
GET /me
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
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2023-05-01T12:00:00.000000Z",
            "updated_at": "2023-05-01T12:00:00.000000Z",
            "roles": [
                {
                    "id": 1,
                    "name": "Admin",
                    "guard_name": "web",
                    "created_at": "2023-05-01T12:00:00.000000Z",
                    "updated_at": "2023-05-01T12:00:00.000000Z",
                    "pivot": {
                        "model_id": 1,
                        "role_id": 1,
                        "model_type": "App\\Models\\User"
                    },
                    "permissions": [
                        // ... permissions
                    ]
                }
            ]
        },
        "permissions": [
            "role-list",
            "role-create",
            "role-edit",
            "role-delete",
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",
            "lead-list",
            "lead-create",
            "lead-edit",
            "lead-delete",
            "contact-list",
            "contact-create",
            "contact-edit",
            "contact-delete"
        ]
    }
}
```

## User Management APIs

### 1. Get Users (with Pagination)
```
GET /users?page=1&per_page=10
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** user-list

**Query Parameters:**
- `page`: Page number (default: 1)
- `per_page`: Number of users per page (default: 10, max: 100)

**Response (200 OK):**
```json
{
    "status": "success",
    "message": null,
    "data": {
        "users": [
            {
                "id": 1,
                "name": "Admin User",
                "email": "admin@example.com",
                "created_at": "2023-05-01T12:00:00.000000Z",
                "updated_at": "2023-05-01T12:00:00.000000Z",
                "roles": [
                    {
                        "id": 1,
                        "name": "Admin",
                        "guard_name": "web",
                        "created_at": "2023-05-01T12:00:00.000000Z",
                        "updated_at": "2023-05-01T12:00:00.000000Z",
                        "pivot": {
                            "model_id": 1,
                            "role_id": 1,
                            "model_type": "App\\Models\\User"
                        }
                    }
                ]
            },
            // ... other users
        ],
        "pagination": {
            "total": 25,
            "per_page": 10,
            "current_page": 1,
            "last_page": 3,
            "from": 1,
            "to": 10,
            "links": {
                "prev": null,
                "next": "http://localhost:8000/api/users?page=2&per_page=10"
            }
        }
    }
}
```

### 2. Get User by ID
```
GET /users/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** user-list

**URL Parameters:**
- `id`: The ID of the user to retrieve

**Response (200 OK):**
```json
{
    "status": "success",
    "message": null,
    "data": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z",
        "roles": [
            {
                "id": 1,
                "name": "Admin",
                "guard_name": "web",
                "created_at": "2023-05-01T12:00:00.000000Z",
                "updated_at": "2023-05-01T12:00:00.000000Z",
                "pivot": {
                    "model_id": 1,
                    "role_id": 1,
                    "model_type": "App\\Models\\User"
                }
            }
        ]
    }
}
```

### 3. Create User
```
POST /users
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** user-create

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "roles": ["User"]
}
```

**Response (201 Created):**
```json
{
    "status": "success",
    "message": "User created successfully",
    "data": {
        "id": 3,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z",
        "roles": [
            {
                "id": 2,
                "name": "User",
                "guard_name": "web",
                "created_at": "2023-05-01T12:00:00.000000Z",
                "updated_at": "2023-05-01T12:00:00.000000Z",
                "pivot": {
                    "model_id": 3,
                    "role_id": 2,
                    "model_type": "App\\Models\\User"
                }
            }
        ]
    }
}
```

### 4. Update User
```
PUT /users/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** user-edit

**URL Parameters:**
- `id`: The ID of the user to update

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "newpassword",
    "password_confirmation": "newpassword",
    "roles": ["Editor"]
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "User updated successfully",
    "data": {
        "id": 3,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z",
        "roles": [
            {
                "id": 3,
                "name": "Editor",
                "guard_name": "web",
                "created_at": "2023-05-01T12:00:00.000000Z",
                "updated_at": "2023-05-01T12:00:00.000000Z",
                "pivot": {
                    "model_id": 3,
                    "role_id": 3,
                    "model_type": "App\\Models\\User"
                }
            }
        ]
    }
}
```

### 5. Delete User
```
DELETE /users/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** user-delete

**URL Parameters:**
- `id`: The ID of the user to delete

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "User deleted successfully",
    "data": null
}
```

### 6. Update User Profile
```
PUT /profile
```

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Allows users to update their own profile information (name and email only).

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john.updated@example.com"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Profile updated successfully",
    "data": {
        "id": 3,
        "name": "John Doe",
        "email": "john.updated@example.com",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z"
    }
}
```

### 7. Update User Password
```
PUT /password
```

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Allows users to update their own password.

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "password": "newpassword",
    "password_confirmation": "newpassword"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Password updated successfully",
    "data": null
}
```

## Role Management APIs

### 1. Get All Roles
```
GET /roles
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** role-list

**Response (200 OK):**
```json
{
    "status": "success",
    "message": null,
    "data": [
        {
            "id": 1,
            "name": "Admin",
            "guard_name": "web",
            "created_at": "2023-05-01T12:00:00.000000Z",
            "updated_at": "2023-05-01T12:00:00.000000Z",
            "permissions": [
                {
                    "id": 1,
                    "name": "role-list",
                    "guard_name": "web",
                    "created_at": "2023-05-01T12:00:00.000000Z",
                    "updated_at": "2023-05-01T12:00:00.000000Z",
                    "pivot": {
                        "role_id": 1,
                        "permission_id": 1
                    }
                },
                // ... other permissions
            ]
        },
        // ... other roles
    ]
}
```

### 2. Get All Permissions
```
GET /permissions
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** role-list

**Response (200 OK):**
```json
{
    "status": "success",
    "message": null,
    "data": [
        {
            "id": 1,
            "name": "role-list",
            "guard_name": "web",
            "created_at": "2023-05-01T12:00:00.000000Z",
            "updated_at": "2023-05-01T12:00:00.000000Z"
        },
        // ... other permissions
    ]
}
```

### 3. Create Role
```
POST /roles
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** role-create

**Request Body:**
```json
{
    "name": "Editor",
    "permissions": [1, 2, 3, 4]
}
```

**Response (201 Created):**
```json
{
    "status": "success",
    "message": "Role created successfully",
    "data": {
        "id": 3,
        "name": "Editor",
        "guard_name": "web",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z",
        "permissions": [
            // ... permissions
        ]
    }
}
```

### 4. Update Role
```
PUT /roles/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** role-edit

**URL Parameters:**
- `id`: The ID of the role to update

**Request Body:**
```json
{
    "name": "Editor",
    "permissions": [1, 2, 3, 4, 5]
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Role updated successfully",
    "data": {
        "id": 3,
        "name": "Editor",
        "guard_name": "web",
        "created_at": "2023-05-01T12:00:00.000000Z",
        "updated_at": "2023-05-01T12:00:00.000000Z",
        "permissions": [
            // ... permissions
        ]
    }
}
```

### 5. Delete Role
```
DELETE /roles/{id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Required Permission:** role-delete

**URL Parameters:**
- `id`: The ID of the role to delete

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Role deleted successfully",
    "data": null
}
```

## Error Handling

The API returns error responses in this format:

```json
{
    "status": "error",
    "message": "The provided credentials are incorrect.",
    "data": null
}
```

For validation errors:

```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "data": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

## Security Considerations

1. Always use HTTPS in production
2. Store tokens securely (localStorage is convenient but vulnerable to XSS attacks; consider using HttpOnly cookies in production)
3. Implement token expiration handling
4. Add CSRF protection for cookie-based authentication
5. Validate all user inputs on both client and server sides
6. Implement proper role-based access control on the frontend to match backend permissions

## Conclusion

This documentation provides a comprehensive guide to the Lead Generation API, including authentication, user management, and role-based access control. By following the implementation guidelines, you can build a secure and feature-rich frontend application that integrates seamlessly with the Laravel backend.

Key features of this API include:
- Secure authentication with Laravel Sanctum
- Role-based access control with granular permissions
- User management with profile features
- Dashboard redirection after login
- Comprehensive error handling

For any questions or issues, please contact the development team.