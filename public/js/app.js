let token = null;

// Función para hacer login y obtener el token
async function login() {
    console.log("Login function called");
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Validación de campos vacíos
    if (!email || !password) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, completa todos los campos',
        });
        return; // Detiene la función si algún campo está vacío
    }

    // Validación del formato del correo electrónico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, introduce un correo electrónico válido',
        });
        return; // Detiene la función si el correo no es válido
    }

    try {
        const response = await fetch('http://localhost:8000/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();
        
        if (response.ok) {
            token = data.token;
            localStorage.setItem('token', token);
            document.getElementById('login-section').style.display = 'none';
            document.getElementById('tasks-section').style.display = 'block';
            loadTasks();
        } else {
            // Muestra un pop-up si el login falla
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Credenciales incorrectas',
            });
        }
    } catch (error) {
        console.error("Error during login:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al intentar iniciar sesión',
        });
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
            <li class="list-group-item">
                <strong>Título:</strong> ${task.title}<br>
                <strong>Descripción:</strong> ${task.description}<br>
                <strong>Estado:</strong> ${task.status}<br>
                <button onclick="deleteTask(${task.id})" class="btn btn-danger btn-sm">Eliminar</button>
                <button onclick="updateTask(${task.id})" class="btn btn-warning btn-sm">Actualizar</button>
            </li>
        `).join('');
    } catch (error) {
        console.error("Error loading tasks:", error);
        alert('Error al cargar las tareas');
    }
}

// Función para actualizar una tarea
async function updateTask(taskId) {
    try {
        // Obtener los datos actuales de la tarea
        const response = await fetch(`http://localhost:8000/api/tasks/${taskId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const task = await response.json();

        // Crear el formulario de edición en un pop-up
        const { value: formValues } = await Swal.fire({
            title: 'Editar Tarea',
            html:
                `<input id="swal-title" class="swal2-input" placeholder="Título" value="${task.title}">` +
                `<textarea id="swal-description" class="swal2-textarea" placeholder="Descripción">${task.description}</textarea>` +
                `<select id="swal-status" class="swal2-select">` +
                    `<option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pendiente</option>` +
                    `<option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>En progreso</option>` +
                    `<option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completada</option>` +
                `</select>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return {
                    title: document.getElementById('swal-title').value,
                    description: document.getElementById('swal-description').value,
                    status: document.getElementById('swal-status').value,
                };
            },
        });

        if (formValues) {
            // Enviar los datos actualizados al servidor
            const updateResponse = await fetch(`http://localhost:8000/api/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify(formValues),
            });

            if (!updateResponse.ok) {
                throw new Error(`HTTP error! status: ${updateResponse.status}`);
            }

            loadTasks(); // Recargar la lista después de actualizar
        }
    } catch (error) {
        console.error("Error updating task:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al actualizar la tarea',
        });
    }
}

// Función para crear una tarea
async function createTask() {
    const title = document.getElementById('task-title').value;

    if (!title) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El título de la tarea es obligatorio',
        });
        return; // Detiene la función si el título está vacío
    }

    try {
        const response = await fetch('http://localhost:8000/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ title }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        loadTasks(); // Recargar la lista después de crear la tarea
    } catch (error) {
        console.error("Error creating task:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al crear la tarea',
        });
    }
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

async function logout() {
    try {
        const response = await fetch('http://localhost:8000/api/auth/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
        });

        if (response.ok) {
            localStorage.removeItem('token'); // Elimina el token
            document.getElementById('login-section').style.display = 'block'; // Muestra el login
            document.getElementById('tasks-section').style.display = 'none'; // Oculta las tareas
        } else {
            alert('Error al cerrar sesión');
        }
    } catch (error) {
        console.error("Error during logout:", error);
        alert('Error al cerrar sesión');
    }
}

async function register() {
    const { value: formValues } = await Swal.fire({
        title: 'Registrarse',
        html:
            `<input id="swal-name" class="swal2-input" placeholder="Nombre">` +
            `<input id="swal-email" class="swal2-input" placeholder="Correo electrónico">` +
            `<input id="swal-password" class="swal2-input" placeholder="Contraseña" type="password">`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Registrarse',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const name = document.getElementById('swal-name').value;
            const email = document.getElementById('swal-email').value;
            const password = document.getElementById('swal-password').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email

            if (!name) {
                Swal.showValidationMessage('El nombre es obligatorio');
                return false; // Detiene el envío del formulario
            }

            if (!emailRegex.test(email)) {
                Swal.showValidationMessage('Por favor, introduce un correo electrónico válido');
                return false; // Detiene el envío del formulario
            }

            if (password.length < 8) {
                Swal.showValidationMessage('La contraseña debe tener al menos 8 caracteres');
                return false; // Detiene el envío del formulario
            }

            return {
                name: name,
                email: email,
                password: password,
            };
        },
    });

    if (formValues) {
        try {
            console.log("Datos enviados:", formValues);

            const response = await fetch('http://localhost:8000/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formValues),
            });

            console.log("Respuesta del servidor:", response);

            const data = await response.json();
            console.log("Datos de la respuesta:", data);

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Usuario registrado correctamente',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al registrar el usuario',
                });
            }
        } catch (error) {
            console.error("Error during registration:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al registrar el usuario',
            });
        }
    }
}

document.getElementById('register-btn').addEventListener('click', register);

document.getElementById('login-btn').addEventListener('click', login);