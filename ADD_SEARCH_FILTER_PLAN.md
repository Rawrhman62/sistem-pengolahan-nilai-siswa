# Add Search and Filter to Admin Pages

## Pages That Need Search & Filter

1. `/admin/siswa` - Student management
2. `/admin/guru` - Teacher management  
3. `/admin/mapel` - Subject management
4. `/admin/kelas` - Class management

## Implementation Plan

### 1. Update AdminController

Add search and filter logic to each method:

```php
// In AdminController.php

public function siswa(Request $request)
{
    $search = $request->input('search');
    $kelas = $request->input('kelas');
    
    $siswas = Siswa::with('user')
        ->when($search, function($query, $search) {
            $query->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        })
        ->when($kelas, function($query, $kelas) {
            $query->where('kelas', $kelas);
        })
        ->paginate(20);
    
    $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas');
    
    return view('admin.siswa', compact('siswas', 'kelasList', 'search', 'kelas'));
}

public function guru(Request $request)
{
    $search = $request->input('search');
    $mapel = $request->input('mapel');
    
    $gurus = Guru::with('user')
        ->when($search, function($query, $search) {
            $query->where('nip', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        })
        ->when($mapel, function($query, $mapel) {
            $query->where('mapel_diampu', 'like', "%{$mapel}%");
        })
        ->paginate(20);
    
    $mapelList = Mapel::pluck('nama_mapel');
    
    return view('admin.guru', compact('gurus', 'mapelList', 'search', 'mapel'));
}

public function mapel(Request $request)
{
    $search = $request->input('search');
    
    $mapels = Mapel::when($search, function($query, $search) {
            $query->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
        })
        ->paginate(20);
    
    return view('admin.mapel', compact('mapels', 'search'));
}

public function kelas(Request $request)
{
    $search = $request->input('search');
    $tingkat = $request->input('tingkat');
    
    $kelas = Kelas::with('waliKelas')
        ->when($search, function($query, $search) {
            $query->where('nama_kelas', 'like', "%{$search}%");
        })
        ->when($tingkat, function($query, $tingkat) {
            $query->where('tingkat', $tingkat);
        })
        ->paginate(20);
    
    $tingkatList = Kelas::select('tingkat')->distinct()->pluck('tingkat');
    
    return view('admin.kelas', compact('kelas', 'tingkatList', 'search', 'tingkat'));
}
```

### 2. Add Search/Filter UI Component

Add this to each view (after the page header, before the table):

```html
<!-- Search and Filter Section -->
<div class="search-filter-section">
    <form method="GET" action="{{ route('admin.siswa') }}" class="search-filter-form">
        <div class="search-box">
            <input type="text" 
                   name="search" 
                   placeholder="Cari nama atau NIS..." 
                   value="{{ request('search') }}"
                   class="search-input">
            <button type="submit" class="search-btn">
                <span>🔍</span> Cari
            </button>
        </div>
        
        @if(isset($kelasList))
        <div class="filter-box">
            <select name="kelas" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        
        @if(request('search') || request('kelas'))
        <a href="{{ route('admin.siswa') }}" class="reset-btn">Reset</a>
        @endif
    </form>
</div>
```

### 3. Add CSS Styles

```css
/* Search and Filter Section */
.search-filter-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: var(--card-shadow);
}

.search-filter-form {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    display: flex;
    gap: 10px;
    flex: 1;
    min-width: 300px;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.search-input:focus {
    border-color: var(--primary-blue);
}

.search-btn {
    background: var(--primary-blue);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.search-btn:hover {
    background: var(--primary-blue-hover);
}

.filter-box {
    display: flex;
    gap: 10px;
}

.filter-select {
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    outline: none;
    min-width: 150px;
}

.filter-select:focus {
    border-color: var(--primary-blue);
}

.reset-btn {
    padding: 10px 20px;
    background: var(--gray-light);
    color: var(--text-gray);
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s;
}

.reset-btn:hover {
    background: var(--border-color);
}

/* Results info */
.results-info {
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 15px;
}
```

### 4. Add Pagination

At the bottom of each table:

```html
<!-- Pagination -->
@if($siswas->hasPages())
<div class="pagination-wrapper">
    {{ $siswas->appends(request()->query())->links() }}
</div>
@endif
```

### 5. Specific Filters for Each Page

**admin/siswa**:
- Search: Name, NIS
- Filter: Kelas

**admin/guru**:
- Search: Name, NIP
- Filter: Mapel Diampu

**admin/mapel**:
- Search: Nama Mapel, Kode Mapel
- No filter needed

**admin/kelas**:
- Search: Nama Kelas
- Filter: Tingkat

## Benefits

1. **Better UX**: Users can quickly find specific records
2. **Performance**: Pagination reduces load time
3. **Flexibility**: Multiple filter options
4. **Consistency**: Same UI across all admin pages

## Implementation Steps

1. Update `AdminController.php` with search/filter logic
2. Update each view file to add search/filter UI
3. Add CSS styles for search/filter components
4. Test each page with various search/filter combinations
5. Add pagination to handle large datasets

## Testing Checklist

- [ ] Search by name works
- [ ] Search by ID (NIS/NIP) works
- [ ] Filter by class/subject works
- [ ] Reset button clears all filters
- [ ] Pagination preserves search/filter params
- [ ] Empty results show appropriate message
- [ ] UI is responsive on mobile

---

This implementation will make the admin pages much more user-friendly and efficient!
