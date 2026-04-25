<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    
    <p><strong>User ID:</strong> {{ $user->user_id }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ $user->role }}</p>
    
    @if($user->phone_number)
        <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
    @endif
    
    <br>
    <a href="{{ route('dashboard') }}">Back to Dashboard</a>
</body>
</html>