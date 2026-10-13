Step 1 - First enter mysql db details in .env-
		DB_CONNECTION=mysql
		DB_HOST=127.0.0.1
		DB_PORT=3306
		DB_DATABASE=laravel_task
		DB_USERNAME=root
		DB_PASSWORD=

Step 2 - Run commands - 
         Composer Install
         php artisan migrate - (to run migration & create table into your db)
Step 3 - php artisan serve(to run project in localhost)

Step 4 - Go to url localhost:8000/tasks/create(to create your task)

Step 5 - Visit url - localhost:8000/tasks (to see listing of tasks)

Step 6 - Drag & drop Task Name column to reorder the tasks.