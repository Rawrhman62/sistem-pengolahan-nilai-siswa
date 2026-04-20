<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Administrator Dashboard</h1>
    
    <p>Welcome, {{ auth()->user()->user_id }}</p>
    
    <nav>
        <ul>
            <li><a href="{{ route('admin.register') }}">Register New User</a></li>
            <li><a href="{{ route('admin.manage') }}">Manage Users</a></li>
            <li><a href="{{ route('dashboard') }}">Main Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</body>
</html>