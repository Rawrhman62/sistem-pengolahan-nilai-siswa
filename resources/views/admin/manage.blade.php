<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>
    
    <form method="GET" action="{{ route('admin.manage') }}">
        <div>
            <label for="search">Search Users:</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by Name, Username, User ID, Email, or Role">
            <button type="submit">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.manage') }}">Clear</a>
            @endif
        </div>
    </form>
    
    <table border="1" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>User ID</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Password Set</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->user_name }}</td>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->password_set ? 'Yes' : 'No' }}</td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($users->hasPages())
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @endif
    
    <p><a href="{{ route('admin.index') }}">Back to Admin Dashboard</a></p>
</body>
</html>