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
*   **Request Body (Multipart/form-data):**
    | Field | Type | Description |
    | :--- | :--- | :--- |
    | `job_id` | `integer` | ID of the job (Required, must exist in jobs table) |
    | `name` | `string` | Full name of the applicant (Required) |
    | `email` | `string` | Email address (Required) |
    | `phone` | `string` | Phone number (Required) |
    | `resume` | `file` | PDF/DOC/DOCX file, max 200MB (Required) |
    | `cover_letter` | `text` | Introduction or cover letter (Optional) |

*   **Success Response:**
    ```json
    {
        "success": true,
        "message": "Application submitted successfully",
        "data": {
            "job_id": "1",
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "017XXXXXXXX",
            "resume": "resumes/1740830000_resume.pdf",
            "id": 5
        },
        "code": 200
    }
    ```

*   **Validation Error Response:**
    ```json
    {
        "message": "The job id field is required. (and other errors...)",
        "errors": {
            "job_id": ["The job id field is required."],
            "resume": ["The resume field is required."]
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
