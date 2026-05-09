# Views Update Status

## ✓ Completed: admin/siswa.blade.php

Search and filter UI has been added with:
- Search box for name/NIS
- Filter dropdown for Kelas
- Filter dropdown for Tahun Ajaran
- Reset button
- Results counter

## Remaining Views to Update

### 1. admin/guru.blade.php

Add after the tabs, before the table:

```html
<!-- Search and Filter Section -->
<div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    <form method="GET" action="{{ route('admin.guru') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px; flex: 1; min-width: 300px;">
            <input type="text" 
                   name="search" 
                   placeholder="Cari nama atau NIP..." 
                   value="{{ request('search') }}"
                   style="flex: 1; padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; outline: none;">
            <button type="submit" style="background: #0A2E5C; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <span>🔍</span> Cari
            </button>
        </div>
        
        @if(isset($mapelList) && $mapelList->count() > 0)
        <select name="mapel" style="padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; min-width: 180px;" onchange="this.form.submit()">
            <option value="">Semua Mata Pelajaran</option>
            @foreach($mapelList as $m)
                <option value="{{ $m }}" {{ request('mapel') == $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
        @endif
        
        @if(request('search') || request('mapel'))
        <a href="{{ route('admin.guru') }}" style="padding: 10px 20px; background: #F3F4F6; color: #6B7280; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500;">
            Reset
        </a>
        @endif
    </form>
    
    @if(request('search') || request('mapel'))
    <div style="margin-top: 15px; color: #6B7280; font-size: 14px;">
        Menampilkan {{ $guru->total() }} hasil
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('mapel'))
            mapel "<strong>{{ request('mapel') }}</strong>"
        @endif
    </div>
    @endif
</div>
```

### 2. admin/mapel.blade.php

Add after the tabs, before the table:

```html
<!-- Search and Filter Section -->
<div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    <form method="GET" action="{{ route('admin.mapel') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px; flex: 1; min-width: 300px;">
            <input type="text" 
                   name="search" 
                   placeholder="Cari kode atau nama mapel..." 
                   value="{{ request('search') }}"
                   style="flex: 1; padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; outline: none;">
            <button type="submit" style="background: #0A2E5C; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <span>🔍</span> Cari
            </button>
        </div>
        
        @if(isset($kelompokList) && $kelompokList->count() > 0)
        <select name="kelompok" style="padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; min-width: 150px;" onchange="this.form.submit()">
            <option value="">Semua Kelompok</option>
            @foreach($kelompokList as $kel)
                <option value="{{ $kel }}" {{ request('kelompok') == $kel ? 'selected' : '' }}>{{ $kel }}</option>
            @endforeach
        </select>
        @endif
        
        @if(request('search') || request('kelompok'))
        <a href="{{ route('admin.mapel') }}" style="padding: 10px 20px; background: #F3F4F6; color: #6B7280; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500;">
            Reset
        </a>
        @endif
    </form>
    
    @if(request('search') || request('kelompok'))
    <div style="margin-top: 15px; color: #6B7280; font-size: 14px;">
        Menampilkan {{ $mapel->total() }} hasil
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('kelompok'))
            kelompok "<strong>{{ request('kelompok') }}</strong>"
        @endif
    </div>
    @endif
</div>
```

### 3. admin/kelas.blade.php

Add after the tabs, before the table:

```html
<!-- Search and Filter Section -->
<div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    <form method="GET" action="{{ route('admin.kelas') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px; flex: 1; min-width: 300px;">
            <input type="text" 
                   name="search" 
                   placeholder="Cari nama kelas..." 
                   value="{{ request('search') }}"
                   style="flex: 1; padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; outline: none;">
            <button type="submit" style="background: #0A2E5C; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <span>🔍</span> Cari
            </button>
        </div>
        
        @if(isset($tingkatList) && $tingkatList->count() > 0)
        <select name="tingkat" style="padding: 10px 15px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; min-width: 150px;" onchange="this.form.submit()">
            <option value="">Semua Tingkat</option>
            @foreach($tingkatList as $t)
                <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        @endif
        
        @if(request('search') || request('tingkat'))
        <a href="{{ route('admin.kelas') }}" style="padding: 10px 20px; background: #F3F4F6; color: #6B7280; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500;">
            Reset
        </a>
        @endif
    </form>
    
    @if(request('search') || request('tingkat'))
    <div style="margin-top: 15px; color: #6B7280; font-size: 14px;">
        Menampilkan {{ $kelas->total() }} hasil
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('tingkat'))
            tingkat "<strong>{{ request('tingkat') }}</strong>"
        @endif
    </div>
    @endif
</div>
```

## Implementation Summary

### What's Been Done:
✓ AdminController updated with filter logic for all 4 pages
✓ admin/siswa.blade.php - Search & filter UI added

### What Needs to Be Done:
- [ ] admin/guru.blade.php - Add search & filter UI
- [ ] admin/mapel.blade.php - Add search & filter UI  
- [ ] admin/kelas.blade.php - Add search & filter UI

### Where to Add:
Insert the search/filter section after the tabs div and before the table-card div in each view.

Look for this pattern:
```html
<!-- Tabs -->
<div class="tabs">
    ...
</div>

<!-- ADD SEARCH/FILTER HERE -->

<!-- Table Area -->
<div class="table-card">
```

## Features Included:
- Search input with icon
- Filter dropdowns (auto-submit on change)
- Reset button (only shows when filters are active)
- Results counter (shows total and active filters)
- Responsive design
- Consistent styling across all pages

---

**Status**: 1 of 4 views complete. Ready to add to remaining 3 views.
