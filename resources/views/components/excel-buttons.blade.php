@props(['exportRoute', 'importRoute', 'templateRoute'])

<div class="excel-buttons-container" style="display: flex; gap: 10px; align-items: center;">
    <!-- Export Button -->
    <a href="{{ route($exportRoute) }}" class="btn btn-blue" style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; color: white; background-color: #3B82F6; transition: background 0.2s; white-space: nowrap;">
        <i class="icon" style="font-style: normal;">📊</i> Export Excel
    </a>
    
    <!-- Import Button (triggers file input) -->
    <button type="button" onclick="document.getElementById('excel-import-input').click()" class="btn btn-green" style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; color: white; background-color: #10B981; transition: background 0.2s; white-space: nowrap;">
        <i class="icon" style="font-style: normal;">📥</i> Import Excel
    </button>
    
    <!-- Download Template Button -->
    <a href="{{ route($templateRoute) }}" class="btn btn-gray" style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; color: #333333; background-color: #E2E8F0; transition: background 0.2s; white-space: nowrap;">
        <i class="icon" style="font-style: normal;">📄</i> Download Template
    </a>
    
    <!-- Hidden file input and form for import -->
    <form id="excel-import-form" method="POST" action="{{ route($importRoute) }}" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="file" id="excel-import-input" name="file" accept=".xlsx,.xls" onchange="document.getElementById('excel-import-form').submit()">
    </form>
</div>

<style>
    .excel-buttons-container .btn:hover {
        opacity: 0.9;
    }
</style>
