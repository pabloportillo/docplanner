// Agrega un evento al formulario de inicio de sesión para manejar el envío
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Obtiene los valores de los campos de email y contraseña
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Realiza una petición POST a la API para autenticar al usuario
    const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });

    // Si la respuesta es exitosa (status 200)
    if (response.ok) {
        const data = await response.json(); // Extrae el JSON de la respuesta
        localStorage.setItem('token', data.token); // Almacena el token en localStorage
        showTaskSection(); // Muestra la sección de tareas tras iniciar sesión
    } else {
        alert('Credenciales inválidas');
    }
});

// Función para mostrar la sección de tareas después del inicio de sesión
function showTaskSection() {
    document.getElementById('login-section').style.display = 'none';
    document.getElementById('task-section').style.display = 'block';
    loadTasks();
}
