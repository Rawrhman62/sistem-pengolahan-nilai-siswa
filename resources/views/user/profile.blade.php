<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    
    <p><strong>Name:</strong> {{ $user->name }}</p>
    
    @if($user->isStudent())
        <p><strong>NIS:</strong> {{ $user->student->nis ?? 'N/A' }}</p>
    @elseif($user->isTeacher())
        <p><strong>Nomor Induk:</strong> {{ $user->teacher->nomor_induk ?? 'N/A' }}</p>
    @endif
    
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ $user->getCurrentRole() }}</p>
    
    @if($user->phone_number)
        <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
    @endif
    
    <br>
    <a href="{{ route('dashboard') }}">Back to Dashboard</a>
</body>
</html>