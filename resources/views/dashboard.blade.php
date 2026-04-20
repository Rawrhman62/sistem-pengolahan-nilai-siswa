<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ $user->user_id }}</h1>
    
    <p>Current role: {{ $role }}</p>
    
    @if(count($allRoles) > 1)
        <div>
            <p>Switch role:</p>
            <form method="POST" action="/switch-role" style="display: inline;">
                @csrf
                <select name="role" onchange="this.form.submit()">
                    @foreach($allRoles as $availableRole)
                        <option value="{{ $availableRole }}" {{ $role === $availableRole ? 'selected' : '' }}>
                            {{ $availableRole === 'lectureTeacher' ? 'Lecture Teacher' : 
                               ($availableRole === 'homeroomTeacher' ? 'Homeroom Teacher' : 
                               ucfirst($availableRole)) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <br>
    @endif
    
    <nav>
        <ul>
            <li><a href="/settings">Settings</a></li>
            @if($isAdmin)
                <li><a href="/admin">Administrator Panel</a></li>
                <li><a href="/admin/register">Register User</a></li>
                <li><a href="/admin/manage">Manage Users</a></li>
            @endif
            <li>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
    
    @if($isAdmin)
        <h2>Administrator Functions</h2>
        <p>You have administrator privileges.</p>
    @elseif($isTeacher)
        <h2>Teacher Functions</h2>
        <p>You have teacher privileges.</p>
        @if($role === 'lectureTeacher')
            <p>You are currently acting as a Lecture Teacher.</p>
        @elseif($role === 'homeroomTeacher')
            <p>You are currently acting as a Homeroom Teacher.</p>
        @endif
    @else
        <h2>Student Dashboard</h2>
        <p>Welcome to your student dashboard.</p>
    @endif
</body>
</html>