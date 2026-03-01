# QTech Task - Job Portal API Documentation

This API provides endpoints for managing job categories, jobs, and job applications.

## Base URL
`http://your-domain.com/api`

---

## 🚀 Endpoints

### 1. Job Categories
Fetch all available job categories.

*   **URL:** `/job_categories`
*   **Method:** `GET`
*   **Response:**
    ```json
    {
        "success": true,
        "message": "Job Categories fetched successfully",
        "data": [
            {
                "id": 1,
                "title": "Software Development",
                "status": "active"
            }
        ],
        "code": 200
    }
    ```

### 2. Job List
Fetch all active jobs.

*   **URL:** `/jobs`
*   **Method:** `GET`
*   **Response:**
    ```json
    {
        "success": true,
        "message": "Jobs fetched successfully",
        "data": [
            {
                "id": 1,
                "title": "Laravel Developer",
                "company": "QTech Solutions",
                "category": "Software Development",
                "job_type": "full_time",
                "location": "Remote",
                "salary": "50k - 70k"
            }
        ],
        "code": 200
    }
    ```

### 3. Job Details
Fetch specific job details by ID.

*   **URL:** `/jobs/{id}`
*   **Method:** `GET`
*   **Response:**
    ```json
    {
        "success": true,
        "message": "Job fetched successfully",
        "data": {
            "id": 1,
            "title": "Laravel Developer",
            "company": "QTech Solutions",
            "description": "Full job description here...",
            "category": "Software Development",
            "job_type": "full_time",
            "location": "Remote"
        },
        "code": 200
    }
    ```

### 4. Submit Application
Submit a new job application.

*   **URL:** `/applications`
*   **Method:** `POST`
*   **Request Body (JSON / Form-data):**
    | Field | Type | Description |
    | :--- | :--- | :--- |
    | `job_id` | `integer` | ID of the job (Required, must exist in jobs table) |
    | `name` | `string` | Full name of the applicant (Required) |
    | `email` | `string` | Valid email address (Required) |
    | `resume_link` | `string` | Valid URL to resume (Required) |
    | `cover_note` | `text` | Introduction or cover letter (Optional) |

*   **Success Response:**
    ```json
    {
        "success": true,
        "message": "Application submitted successfully",
        "data": {
            "job_id": "1",
            "name": "John Doe",
            "email": "john@example.com",
            "resume_link": "https://googledrive.com/my-resume.pdf",
            "cover_note": "I am interested in this role.",
            "id": 5
        },
        "code": 200
    }
    ```

*   **Validation Error Response:**
    ```json
    {
        "message": "The resume link field must be a valid URL. (and other errors...)",
        "errors": {
            "resume_link": ["The resume link field must be a valid URL."]
        }
    }
    ```

---

## 🛠 Admin Endpoints (Requires Auth)
*Requires `auth:api` middleware and JWT token.*

*   **Store Job:** `POST /admin/jobs`
*   **Delete Job:** `DELETE /admin/jobs/{id}`

---

## 🔑 Authentication
For admin endpoints, please include the JWT token in the header:
`Authorization: Bearer <your_token>`
