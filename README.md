## **Task Management API**  

A **RESTful API** built with **Laravel** for managing tasks. The API supports **CRUD operations**, follows **PSR coding standards**, implements the **Repository Pattern**, and includes **unit testing**. The application is **Dockerized** for easy deployment.  

---

## **Features**  

✅ **Create, Update, Delete, and List Tasks**  
✅ **Repository Pattern for Clean Architecture**  
✅ **Task List Caching for Performance Optimization**  
✅ **Unit Testing with PHPUnit**  
✅ **Dockerized for Containerized Deployment**  
✅ **PSR-12 Coding Standards**  
✅ **Postman Collection for API Testing**  

---

## **Installation**  

### **1. Clone the Repository**  
```bash
git clone https://github.com/your-username/task-management-api.git
cd task-management-api
```

### **2. Install Dependencies**  
```bash
composer install
```

### **3. Configure Environment**  
```bash
cp .env.example .env
php artisan key:generate
```

### **4. Set Up Database**  
Update `.env` with your database credentials:  
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

### **5. Run Migrations**  
```bash
php artisan migrate
```

### **6. Start the Application**  
```bash
php artisan serve
```

---

## **API Endpoints**  

| Method  | Endpoint          | Description          |
|---------|------------------|----------------------|
| `GET`   | `/api/tasks`     | Fetch all tasks     |
| `POST`  | `/api/tasks`     | Create a new task   |
| `GET`   | `/api/tasks/{id}` | Fetch a single task |
| `PUT`   | `/api/tasks/{id}` | Update a task       |
| `DELETE`| `/api/tasks/{id}` | Delete a task       |

### **Postman Collection**  
A Postman collection is included in the repository for testing the API.  

---

## **Docker Setup**  

### **1. Build and Start Containers**  
```bash
docker-compose up -d
```

### **2. Run Migrations Inside the Container**  
```bash
docker exec -it task-management-api-app php artisan migrate
```

---

## **Testing**  
Run the unit tests:  
```bash
php artisan test
```

---

## **Repository Pattern Implementation**  

- **BaseRepository:** Handles common database operations.  
- **TaskRepository:** Extends `BaseRepository` for task-specific logic.  

---

## **PSR Coding Standards**  

This project follows **PSR-12** coding standards. To check compliance, run:  
```bash
composer require squizlabs/php_codesniffer --dev
./vendor/bin/phpcs --standard=PSR12 app/
```

---

## **Contributing**  
1. Fork the repository  
2. Create a new branch (`feature/your-feature`)  
3. Commit your changes  
4. Push to the branch  
5. Create a Pull Request  

---

## **License**  
This project is licensed under the **MIT License**.
