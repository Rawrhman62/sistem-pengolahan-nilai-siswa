<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('images/login_wall.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            width: 320px;
            text-align: center;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 5px;
        }

        p {
            color: #777;
            font-size: 14px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #6c63ff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background: #574fd6;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>

    <script>
        function checkUserRoles() {
            const userId = document.getElementById('user_id').value;
            const roleSelection = document.getElementById('role_selection');
            
            if (userId.trim() === '') {
                roleSelection.style.display = 'none';
                return;
            }
            
            // Make AJAX request to check if user has multiple roles
            fetch('/check-user-roles', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.has_multiple_roles) {
                    roleSelection.style.display = 'block';
                    const roleSelect = document.getElementById('selected_role');
                    roleSelect.innerHTML = '<option value="">Select Role</option>';
                    data.roles.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role;
                        option.textContent = role === 'lectureTeacher' ? 'Lecture Teacher' : 
                                           role === 'homeroomTeacher' ? 'Homeroom Teacher' : role;
                        roleSelect.appendChild(option);
                    });
                } else {
                    roleSelection.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                roleSelection.style.display = 'none';
            });
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="box">
    <h1>E-RAPOR</h1>
    <p>Sistem Informasi Raport Elektronik</p>

    <form method="POST" action="/login">
        @csrf

        <select name="selected_role">
            <option value="">Select User Type</option>
            <option value="admin">Administrator</option>
            <option value="lectureTeacher">Lecture Teacher</option>
            <option value="homeroomTeacher">Homeroom Teacher</option>
            <option value="student">Student</option>
        </select>

        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
    </form>
</div>

</body>
</html>