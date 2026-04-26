<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfigurasi Excel - E-RAPOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #F4F7F6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            background: linear-gradient(135deg, #0A2E5C, #15407D);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
        }

        .back-link {
            display: inline-block;
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .back-link:hover {
            opacity: 1;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #E2E8F0;
        }

        .tab {
            padding: 12px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            transition: all 0.2s;
        }

        .tab.active {
            color: #0A2E5C;
            border-bottom-color: #0A2E5C;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 20px;
            color: #0A2E5C;
            margin-bottom: 20px;
        }

        .config-editor {
            background: #F8F9FA;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }

        .alert-danger {
            background: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #0A2E5C;
            color: white;
        }

        .btn-primary:hover {
            background: #15407D;
        }

        .template-info {
            background: #E3F2FD;
            border-left: 4px solid #1976D2;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .template-info h3 {
            color: #1976D2;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .template-info ul {
            margin-left: 20px;
            color: #555;
        }

        .template-info li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('admin.index') }}" class="back-link">← Kembali ke Dashboard</a>
            <h1>Konfigurasi Template Excel</h1>
            <p>Kelola konfigurasi template Excel untuk import dan export data</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="tabs">
            @foreach($templates as $type => $template)
                <button class="tab {{ $loop->first ? 'active' : '' }}" onclick="showTab('{{ $type }}')">
                    {{ ucfirst($type) }}
                </button>
            @endforeach
        </div>

        @foreach($templates as $type => $template)
            <div id="{{ $type }}-tab" class="tab-content {{ $loop->first ? 'active' : '' }}">
                <div class="card">
                    <h2>Template {{ ucfirst($type) }}</h2>
                    
                    <div class="template-info">
                        <h3>Informasi Template</h3>
                        <ul>
                            <li><strong>Jumlah Kolom:</strong> {{ count($template['columns']) }}</li>
                            <li><strong>Field Wajib:</strong> {{ count($template['required'] ?? []) }}</li>
                            <li><strong>Validasi:</strong> {{ count($template['validation'] ?? []) }}</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('admin.excelconfig.update') }}">
                        @csrf
                        <input type="hidden" name="template_type" value="{{ $type }}">
                        
                        <div class="config-editor" contenteditable="true" id="{{ $type }}-editor">{{ json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
                        
                        <input type="hidden" name="config" id="{{ $type }}-config">
                        
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;" onclick="saveConfig('{{ $type }}', event)">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }

        function saveConfig(type, event) {
            event.preventDefault();
            
            const editor = document.getElementById(type + '-editor');
            const configInput = document.getElementById(type + '-config');
            
            try {
                // Validate JSON
                const config = JSON.parse(editor.textContent);
                configInput.value = JSON.stringify(config);
                
                // Submit form
                event.target.closest('form').submit();
            } catch (e) {
                alert('Format JSON tidak valid! Silakan periksa kembali konfigurasi Anda.\n\nError: ' + e.message);
            }
        }
    </script>
</body>
</html>
