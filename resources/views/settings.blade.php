<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            width: 320px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 15px;
        }

        p {
            text-align: center;
            font-size: 12px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #3490dc;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #2779bd;
        }

        .success {
            color: green;
            text-align: center;
            font-size: 14px;
        }

        .error {
            color: red;
            font-size: 13px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #3490dc;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Settings</h1>

    @if(!$user->password_set)
        <p>Set your password for first-time login</p>
    @endif

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf

        @if($user->password_set)
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        @endif

        <label>New Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Update Password</button>
    </form>

    <a href="{{ route('dashboard') }}">Back to Dashboard</a>
</div>

</body>
</html>