document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });

    if (response.ok) {
        const data = await response.json();
        localStorage.setItem('token', data.token); // Guardar token
        showTaskSection(); // Mostrar la sección de tareas
    } else {
        alert('Credenciales inválidas');
    }
});

function showTaskSection() {
    document.getElementById('login-section').style.display = 'none';
    document.getElementById('task-section').style.display = 'block';
    loadTasks(); // Cargar las tareas después del login
}