<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To-Do List</title>
    <style>
        .task-item { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .completed { text-decoration: line-through; }
    </style>
</head>
<body>
    <div>
        <h1>To-Do List</h1>
        <input type="text" id="taskInput" placeholder="Add a new task...">
        <button onclick="addTask()">Add Task</button>
        <button onclick="showAllTasks()">Show All Tasks</button>
        <ul id="taskList">
            @foreach($tasks as $task)
                <li class="task-item" id="task-{{ $task->id }}">
                    <span class="{{ $task->completed ? 'completed' : '' }}">{{ $task->task }}</span>
                    <input type="checkbox" onchange="toggleTask({{ $task->id }})" {{ $task->completed ? 'checked' : '' }}>
                    <button onclick="deleteTask({{ $task->id }})">Delete</button>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Add Task
        function addTask() {
            const taskInput = document.getElementById('taskInput').value;
            if (taskInput.trim() === '') {
                alert('Task cannot be empty');
                return;
            }

            fetch('/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ task: taskInput })
            })
            .then(response => response.json())
            .then(data => {
                location.reload(); // Reload page to see new task
            })
            .catch(error => console.log(error));
        }

        // Toggle Task Completion
        function toggleTask(id) {
            fetch(`/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload(); // Reload page to update task
            })
            .catch(error => console.log(error));
        }

        // Delete Task with confirmation
        function deleteTask(id) {
            if (confirm('Are you sure to delete this task?')) {
                fetch(`/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById(`task-${id}`).remove(); // Remove task from DOM
                })
                .catch(error => console.log(error));
            }
        }

        // Show All Tasks (completed and non-completed)
        function showAllTasks() {
            const tasks = document.querySelectorAll('.task-item');
            tasks.forEach(task => {
                task.style.display = 'flex';
            });
        }
    </script>
</body>
</html>
