<!DOCTYPE html>
<html>
<head>
    <title>Register New User</title>
</head>
<body>
    <h1>Register New User</h1>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf
        
        <div>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        
        <div>
            <label for="user_name">Username:</label>
            <input type="text" id="user_name" name="user_name" value="{{ old('user_name') }}" required>
        </div>
        
        <div>
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="{{ old('user_id') }}" required>
        </div>
        
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
        </div>
        
        <div>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                <option value="lectureTeacher" {{ old('role') == 'lectureTeacher' ? 'selected' : '' }}>Lecture Teacher</option>
                <option value="homeroomTeacher" {{ old('role') == 'homeroomTeacher' ? 'selected' : '' }}>Homeroom Teacher</option>
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
            </select>
        </div>
        
        <div>
            <label>
                <input type="checkbox" name="dual_teacher" value="1" {{ old('dual_teacher') ? 'checked' : '' }}>
                Both Lecture and Homeroom Teacher
            </label>
        </div>
        
        <button type="submit">Register User</button>
    </form>
    
    <p><a href="{{ route('admin.index') }}">Back to Admin Dashboard</a></p>
</body>
</html>