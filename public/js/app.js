let token = null;

// Función para hacer login y obtener el token
async function login() {
    console.log("Login function called");
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const response = await fetch('http://localhost:8000/api/auth/login', { // URL corregida
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    
    if (response.ok) {
        token = data.token; // Guardar el token
        localStorage.setItem('token', token); // Opcional: Persistir en localStorage
        document.getElementById('login-section').style.display = 'none';
        document.getElementById('tasks-section').style.display = 'block';
        loadTasks(); // Cargar tareas después del login
    } else {
        alert('Login failed: ' + data.message);
    }
}

// Función para cargar tareas
async function loadTasks() {
    try {
        const response = await fetch('http://localhost:8000/api/tasks', {
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const tasks = await response.json();
        const list = document.getElementById('tasks-list');
        list.innerHTML = tasks.map(task => `
            <li>
                <strong>Título:</strong> ${task.title}<br>
                <strong>Descripción:</strong> ${task.description}<br>
                <strong>Estado:</strong> ${task.status}<br>
                <button onclick="deleteTask(${task.id})" style="background-color: red; color: white;">Eliminar</button>
                <button onclick="updateTask(${task.id})" style="background-color: green; color: white;">Actualizar</button>
            </li>
        `).join('');
    } catch (error) {
        console.error("Error loading tasks:", error);
        alert('Error al cargar las tareas');
    }
}

// Función para actualizar una tarea
async function updateTask(taskId) {
    const title = prompt("Nuevo título:");
    const description = prompt("Nueva descripción:");
    const status = prompt("Nuevo estado:");

    if (title && description && status) {
        try {
            const response = await fetch(`http://localhost:8000/api/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({ title, description, status }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            loadTasks(); // Recargar la lista después de actualizar
        } catch (error) {
            console.error("Error updating task:", error);
            alert('Error al actualizar la tarea');
        }
    }
}

// Función para crear una tarea
async function createTask() {
    const title = document.getElementById('task-title').value;
    await fetch('http://localhost:8000/api/tasks', { // URL corregida
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({ title }),
    });
    loadTasks(); // Recargar la lista
}

// Función para eliminar una tarea
async function deleteTask(taskId) {
    try {
        const response = await fetch(`http://localhost:8000/api/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        loadTasks(); // Recargar la lista después de eliminar
    } catch (error) {
        console.error("Error deleting task:", error);
        alert('An error occurred while deleting the task');
    }
}

document.getElementById('login-btn').addEventListener('click', login);