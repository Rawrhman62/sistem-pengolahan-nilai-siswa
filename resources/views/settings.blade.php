<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
</head>
<body>
    <h1>Settings</h1>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    
    @if($errors->any())
        <div style="color: red;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        
        @if($user->password_set)
            <label>Current Password:</label>
            <input type="password" name="current_password" required>
            <br><br>
        @else
            <p>Set your password for first-time login:</p>
        @endif
        
        <label>New Password:</label>
        <input type="password" name="password" required>
        <br><br>
        
        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
        <br><br>
        
        <button type="submit">Update Password</button>
    </form>
    
    <br>
    <a href="{{ route('dashboard') }}">Back to Dashboard</a>
</body>
</html>