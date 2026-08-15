<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:68px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR */
  .sidebar{width:var(--sb);background:var(--primary-dark);display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:white;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;flex:1;width:100%;align-items:center;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.45);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.8);}
  .sb-item.active{background:rgba(255,255,255,0.15);}
  .sb-item.active svg{stroke:white;}
  .sb-tooltip{position:absolute;left:56px;background:#1E293B;color:white;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;z-index:99;}
  .sb-item:hover .sb-tooltip{opacity:1;}
  .sb-bottom{margin-top:auto;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .topbar-left{display:flex;flex-direction:column;}
  .topbar-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.07em;text-transform:uppercase;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);}
  .topbar-right{display:flex;align-items:center;gap:10px;}
  .admin-pill{display:flex;align-items:center;gap:8px;background:var(--primary-light);border:1px solid var(--border);border-radius:20px;padding:0.35rem 0.9rem 0.35rem 0.5rem;}
  .admin-avatar{width:24px;height:24px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;color:white;}
  .admin-name{font-size:0.78rem;font-weight:500;color:var(--primary-dark);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* PAGE HEADER */
  .page-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
  .page-hd-left .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd-left h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}
  .alert-error{background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:#92400E;}

  /* FILTER BAR */
  .filter-bar{background:white;border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .search-wrap{display:flex;align-items:center;gap:8px;background:var(--gray);border:1px solid var(--gray-b);border-radius:8px;padding:0.5rem 0.9rem;flex:1;min-width:200px;}
  .search-wrap svg{width:14px;height:14px;stroke:var(--muted);fill:none;stroke-width:2;flex-shrink:0;}
  .search-wrap input{background:none;border:none;outline:none;font-family:'DM Sans',sans-serif;font-size:0.825rem;color:var(--text);width:100%;}
  .filter-select{padding:0.5rem 2rem 0.5rem 0.9rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--text);outline:none;cursor:pointer;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;}
  .filter-select:focus{border-color:var(--primary);}
  .btn-filter{padding:0.5rem 1.1rem;border:none;border-radius:8px;background:var(--primary);color:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:500;cursor:pointer;transition:all 0.2s;}
  .btn-filter:hover{background:var(--primary-dark);}
  .btn-reset{padding:0.5rem 1rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;text-decoration:none;transition:all 0.2s;}
  .btn-reset:hover{border-color:var(--primary);color:var(--primary);}

  /* FILTER TABS */
  .filter-tabs{display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap;}
  .ftab{padding:0.4rem 1rem;border-radius:20px;border:1px solid var(--gray-b);background:white;font-family:'DM Sans',sans-serif;font-size:0.775rem;color:var(--muted);cursor:pointer;text-decoration:none;transition:all 0.2s;}
  .ftab:hover{border-color:var(--primary);color:var(--primary);}
  .ftab.active{background:var(--primary-light);border-color:var(--border);color:var(--primary-dark);font-weight:500;}

  /* TABLE CARD */
  .table-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.65rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  tbody tr{border-bottom:1px solid rgba(29,78,216,0.06);transition:background 0.15s;}
  tbody tr:last-child{border-bottom:none;}
  tbody tr:hover{background:var(--primary-light);}
  td{padding:0.75rem 1rem;color:var(--text);vertical-align:middle;}
  .td-name{display:flex;align-items:center;gap:10px;}
  .td-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;flex-shrink:0;background:var(--primary-light);color:var(--primary-dark);}
  .td-name-text{font-weight:400;color:var(--text);white-space:nowrap;}
  .td-name-sub{font-size:0.7rem;color:var(--muted);}
  .blood-pill{display:inline-block;background:var(--primary-light);color:var(--primary-dark);font-size:0.72rem;font-weight:600;padding:3px 9px;border-radius:5px;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;}
  .b-elig{background:#F0FDF4;color:#166534;}
  .b-not{background:#FFFBEB;color:#92400E;}

  /* ACTION BUTTONS */
  .actions{display:flex;align-items:center;gap:6px;}
  .btn-view{font-size:0.75rem;color:var(--blue);text-decoration:none;padding:0.3rem 0.7rem;border:1px solid var(--blue-b);border-radius:5px;background:var(--blue-bg);transition:all 0.15s;white-space:nowrap;}
  .btn-view:hover{background:var(--blue);color:white;}
  .btn-toggle{font-size:0.75rem;padding:0.3rem 0.7rem;border-radius:5px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.15s;white-space:nowrap;border:none;}
  .btn-toggle-off{background:var(--green-bg);color:var(--green);border:1px solid var(--green-b);}
  .btn-toggle-off:hover{background:var(--green);color:white;}
  .btn-toggle-on{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
  .btn-toggle-on:hover{background:#92400E;color:white;}
  .btn-delete{font-size:0.75rem;padding:0.3rem 0.7rem;border:1px solid #FDE68A;border-radius:5px;background:#FFFBEB;color:#92400E;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.15s;white-space:nowrap;}
  .btn-delete:hover{background:#92400E;color:white;}

  /* PAGINATION */
  .pagination-wrap{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
  .page-info{font-size:0.78rem;color:var(--muted);}
  .page-links{display:flex;gap:4px;}
  .page-links a,.page-links span{padding:0.35rem 0.75rem;border-radius:6px;font-size:0.78rem;text-decoration:none;border:1px solid var(--gray-b);color:var(--muted);background:white;transition:all 0.15s;}
  .page-links a:hover{border-color:var(--primary);color:var(--primary);}
  .page-links .active{background:var(--primary);color:white;border-color:var(--primary);}

  /* EMPTY */
  .empty-state{text-align:center;padding:3rem;color:var(--muted);}
  .empty-state svg{width:40px;height:40px;stroke:var(--gray-b);fill:none;stroke-width:1.5;margin-bottom:0.75rem;}
  .empty-state p{font-size:0.85rem;}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('admin.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tooltip">{{ __('Dashboard') }}</span>
    </a>
    <a href="{{ route('admin.donors.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      <span class="sb-tooltip">{{ __('Donors') }}</span>
    </a>
    <a href="{{ route('admin.hospitals.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
      <span class="sb-tooltip">{{ __('Hospitals') }}</span>
    </a>
    <a href="{{ route('admin.ai-predictions.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tooltip">{{ __('AI Predictions') }}</span>
    </a>
    <a href="{{ route('admin.inventory.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73V8z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
      <span class="sb-tooltip">{{ __('Blood Inventory') }}</span>
    </a>
    <a href="{{ route('admin.appointments.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tooltip">{{ __('Appointments') }}</span>
    </a>
    <a href="{{ route('admin.activity-logs.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <span class="sb-tooltip">{{ __('Activity Log') }}</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
      <span class="sb-tooltip">{{ __('Reports') }}</span>
    </a>
    <a href="{{ route('admin.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tooltip">{{ __('Settings') }}</span>
    </a>
  </div>
  <div class="sb-bottom">
    <div class="sb-avatar">{{ strtoupper(substr(auth('admin')->user()->name,0,2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-label">{{ __('Admin Panel') }}</div>
      <div class="topbar-title">{{ __('Donor Management') }}</div>
    </div>
    <div class="topbar-right">
      <div class="admin-pill">
        <div class="admin-avatar">{{ strtoupper(substr(auth('admin')->user()->name,0,2)) }}</div>
        <span class="admin-name">{{ auth('admin')->user()->name }}</span>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">{{ __('Logout') }}</button>
      </form>
    </div>
  </div>

  <div class="content">

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-hd">
      <div class="page-hd-left">
        <div class="page-label">{{ __('Manage') }}</div>
        <h1>{{ __('All Donors') }}</h1>
      </div>
      <span style="font-size:0.82rem;color:var(--muted);">
        {{ $donors->total() }} {{ $donors->total() !== 1 ? __('donors found') : __('donor found') }}
      </span>
    </div>

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('admin.donors.index') }}">
      <div class="filter-bar">
        <div class="search-wrap">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="search"
                 value="{{ request('search') }}"
                 placeholder="{{ __('Search by name or email...') }}">
        </div>

        <select name="blood_group" class="filter-select">
          <option value="">{{ __('All Blood Groups') }}</option>
          @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
            <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
          @endforeach
        </select>

        <select name="status" class="filter-select">
          <option value="">{{ __('All Status') }}</option>
          <option value="eligible"    {{ request('status') === 'eligible'    ? 'selected' : '' }}>{{ __('Eligible') }}</option>
          <option value="not_eligible"{{ request('status') === 'not_eligible'? 'selected' : '' }}>{{ __('Not Eligible') }}</option>
        </select>

        <select name="district" class="filter-select">
          <option value="">{{ __('All Districts') }}</option>
          @foreach(['Colombo','Gampaha','Kalutara','Kandy','Matale','Nuwara Eliya','Galle','Matara','Hambantota','Jaffna','Kurunegala','Ratnapura','Kegalle','Badulla'] as $d)
            <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
          @endforeach
        </select>

        <button type="submit" class="btn-filter">{{ __('Search') }}</button>
        <a href="{{ route('admin.donors.index') }}" class="btn-reset">{{ __('Reset') }}</a>
      </div>
    </form>

    {{-- QUICK FILTER TABS --}}
    <div class="filter-tabs">
      <a href="{{ route('admin.donors.index') }}"
         class="ftab {{ !request('status') && !request('blood_group') ? 'active' : '' }}">
        {{ __('All') }} ({{ $donors->total() }})
      </a>
      <a href="{{ route('admin.donors.index', ['status' => 'eligible']) }}"
         class="ftab {{ request('status') === 'eligible' ? 'active' : '' }}">
        ✓ {{ __('Eligible') }}
      </a>
      <a href="{{ route('admin.donors.index', ['status' => 'not_eligible']) }}"
         class="ftab {{ request('status') === 'not_eligible' ? 'active' : '' }}">
        ✗ {{ __('Not Eligible') }}
      </a>
      @foreach(['O+','A+','B+','AB+'] as $bg)
        <a href="{{ route('admin.donors.index', ['blood_group' => $bg]) }}"
           class="ftab {{ request('blood_group') === $bg ? 'active' : '' }}">
          {{ $bg }}
        </a>
      @endforeach
    </div>

    {{-- DONORS TABLE --}}
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('Donor') }}</th>
              <th>{{ __('Blood') }}</th>
              <th>{{ __('Age') }}</th>
              <th>{{ __('Weight') }}</th>
              <th>{{ __('Hemoglobin') }}</th>
              <th>{{ __('District') }}</th>
              <th>{{ __('AI Confidence') }}</th>
              <th>{{ __('Status') }}</th>
              <th>{{ __('Registered') }}</th>
              <th>{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($donors as $donor)
              <tr>
                <td style="color:var(--muted);font-size:0.75rem;">{{ $donor->id }}</td>
                <td>
                  <div class="td-name">
                    <div class="td-avatar">
                      {{ strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1)) }}
                    </div>
                    <div>
                      <div class="td-name-text">{{ $donor->full_name }}</div>
                      <div class="td-name-sub">{{ $donor->email }}</div>
                    </div>
                  </div>
                </td>
                <td><span class="blood-pill">{{ $donor->blood_group ?? '—' }}</span></td>
                <td>{{ $donor->age ?? '—' }}</td>
                <td>{{ $donor->weight_kg ? $donor->weight_kg.' kg' : '—' }}</td>
                <td>{{ $donor->hemoglobin ? number_format($donor->hemoglobin,1).' g/dL' : '—' }}</td>
                <td>{{ $donor->district ?? '—' }}</td>
                <td>
                  @if($donor->ai_confidence > 0)
                    <div style="display:flex;align-items:center;gap:6px;">
                      <div style="flex:1;background:var(--gray);border-radius:3px;height:5px;overflow:hidden;min-width:50px;">
                        <div style="height:5px;border-radius:3px;background:{{ $donor->ai_confidence >= 80 ? 'var(--green)' : ($donor->ai_confidence >= 50 ? 'var(--amber)' : '#92400E') }};width:{{ $donor->ai_confidence }}%"></div>
                      </div>
                      <span style="font-size:0.72rem;font-weight:500;color:var(--text);">{{ number_format($donor->ai_confidence,1) }}%</span>
                    </div>
                  @else
                    <span style="font-size:0.72rem;color:var(--muted);">—</span>
                  @endif
                </td>
                <td>
                  <span class="badge {{ $donor->is_eligible ? 'b-elig' : 'b-not' }}">
                    {{ $donor->is_eligible ? __('Eligible') : __('Not Eligible') }}
                  </span>
                </td>
                <td style="font-size:0.75rem;color:var(--muted);white-space:nowrap;">
                  {{ $donor->created_at->format('d M Y') }}
                </td>
                <td>
                  <div class="actions">
                    {{-- VIEW --}}
                    <a href="{{ route('admin.donors.show', $donor->id) }}" class="btn-view">{{ __('View') }}</a>

                    {{-- TOGGLE ELIGIBILITY --}}
                    <form method="POST" action="{{ route('admin.donors.toggle', $donor->id) }}" style="margin:0;">
                      @csrf
                      <button type="submit"
                              class="btn-toggle {{ $donor->is_eligible ? 'btn-toggle-on' : 'btn-toggle-off' }}"
                              onclick="return confirm('{{ $donor->is_eligible ? __('Mark as NOT eligible?') : __('Mark as ELIGIBLE?') }}')">
                        {{ $donor->is_eligible ? __('Disable') : __('Enable') }}
                      </button>
                    </form>

                    {{-- DELETE --}}
                    <form method="POST" action="{{ route('admin.donors.destroy', $donor->id) }}" style="margin:0;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-delete"
                              onclick="return confirm('{{ __('Delete') }} {{ $donor->full_name }}? {{ __('This cannot be undone.') }}')">
                        {{ __('Delete') }}
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11">
                  <div class="empty-state">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <p>{{ __('No donors found matching your search.') }}</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- PAGINATION --}}
      @if($donors->hasPages())
        <div class="pagination-wrap">
          <div class="page-info">
            {{ __('Showing') }} {{ $donors->firstItem() }}–{{ $donors->lastItem() }} {{ __('of') }} {{ $donors->total() }} {{ __('donors') }}
          </div>
          <div class="page-links">
            {{-- Previous --}}
            @if($donors->onFirstPage())
              <span>←</span>
            @else
              <a href="{{ $donors->previousPageUrl() }}">←</a>
            @endif

            {{-- Page numbers --}}
            @foreach($donors->getUrlRange(1, $donors->lastPage()) as $page => $url)
              @if($page == $donors->currentPage())
                <span class="active">{{ $page }}</span>
              @else
                <a href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach

            {{-- Next --}}
            @if($donors->hasMorePages())
              <a href="{{ $donors->nextPageUrl() }}">→</a>
            @else
              <span>→</span>
            @endif
          </div>
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Donor dashboard, Admin dashboard, Hospital dashboard --}}
@include('components.chatbot')