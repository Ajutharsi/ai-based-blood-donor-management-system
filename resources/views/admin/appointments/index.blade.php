<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;--primary-mid:#60A5FA;
    --secondary:#0D9488;--secondary-dark:#115E59;--secondary-light:#F0FDFA;--secondary-b:#99F6E4;
    --accent:#06B6D4;--accent-light:#ECFEFF;--accent-b:#A5F3FC;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --warning:#D97706;--warning-dark:#92400E;--warning-light:#FFFBEB;--warning-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:68px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  .sidebar{width:var(--sb);background:var(--primary-dark);display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:white;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;flex:1;width:100%;align-items:center;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.45);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.8);}
  .sb-item.active{background:rgba(255,255,255,0.15);}
  .sb-item.active svg{stroke:white;}
  .sb-tooltip{position:absolute;left:56px;background:#1A0A0B;color:white;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;z-index:99;}
  .sb-item:hover .sb-tooltip{opacity:1;}
  .sb-bottom{margin-top:auto;display:flex;flex-direction:column;align-items:center;gap:8px;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .topbar-left{display:flex;flex-direction:column;}
  .topbar-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.07em;text-transform:uppercase;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);line-height:1.1;}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  .content{padding:1.75rem 2rem;}

  .page-hd{margin-bottom:1.25rem;}
  .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}
  .page-sub{font-size:0.82rem;color:var(--muted);margin-top:4px;}

  .stat-row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:1.5rem;}
  .sc{background:white;border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;}
  .sc-num{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:2px;}
  .sc-label{font-size:0.72rem;color:var(--muted);}

  .grid-2{display:grid;grid-template-columns:1.4fr 1fr;gap:1.25rem;margin-bottom:1.5rem;}
  .panel{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.4rem;}
  .panel-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--text);margin-bottom:1rem;}

  .chart-row{display:flex;align-items:flex-end;gap:10px;height:150px;}
  .chart-col{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;}
  .chart-bar-wrap{width:100%;display:flex;align-items:flex-end;justify-content:center;height:110px;gap:3px;}
  .chart-bar{width:40%;border-radius:4px 4px 0 0;background:var(--primary-light);}
  .chart-bar.completed{background:var(--secondary);}
  .chart-bar.total{background:var(--primary);}
  .chart-label{font-size:0.68rem;color:var(--muted);margin-top:6px;}
  .chart-legend{display:flex;gap:14px;margin-top:0.75rem;font-size:0.72rem;color:var(--muted);}
  .legend-dot{width:8px;height:8px;border-radius:2px;display:inline-block;margin-right:4px;}

  .breakdown-row{display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--gray);font-size:0.82rem;}
  .breakdown-row:last-child{border-bottom:none;}
  .breakdown-name{color:var(--text);font-weight:500;}
  .breakdown-count{color:var(--muted);font-size:0.78rem;}

  .filter-bar{background:white;border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .filter-select{padding:0.5rem 2rem 0.5rem 0.9rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--text);outline:none;cursor:pointer;}
  .btn-filter{padding:0.5rem 1.1rem;border:none;border-radius:8px;background:var(--primary);color:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:500;cursor:pointer;}
  .btn-reset{padding:0.5rem 1rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;text-decoration:none;}
  .btn-reset:hover{border-color:var(--primary);color:var(--primary);}

  .table-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.65rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  tbody tr{border-bottom:1px solid rgba(29,78,216,0.06);}
  tbody tr:last-child{border-bottom:none;}
  tbody tr:hover{background:var(--primary-light);}
  td{padding:0.75rem 1rem;color:var(--text);vertical-align:middle;}
  .blood-pill{display:inline-block;background:var(--primary-light);color:var(--primary-dark);font-size:0.8rem;font-weight:700;padding:3px 10px;border-radius:5px;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:600;padding:3px 10px;border-radius:20px;white-space:nowrap;text-transform:uppercase;}
  .st-pending{background:var(--blue-bg);color:var(--blue);}
  .st-approved{background:var(--green-bg);color:var(--green);}
  .st-rejected{background:var(--warning-light);color:var(--warning);}
  .st-completed{background:var(--secondary-light);color:var(--secondary-dark);}
  .st-cancelled{background:var(--gray);color:var(--muted);}
  .hosp-link{color:var(--primary-dark);text-decoration:none;font-weight:500;}
  .hosp-link:hover{text-decoration:underline;}

  .pagination-wrap{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
  .page-info{font-size:0.78rem;color:var(--muted);}
  .page-links{display:flex;gap:4px;}
  .page-links a,.page-links span{padding:0.35rem 0.75rem;border-radius:6px;font-size:0.78rem;text-decoration:none;border:1px solid var(--gray-b);color:var(--muted);background:white;}
  .page-links a:hover{border-color:var(--primary);color:var(--primary);}
  .page-links .active{background:var(--primary);color:white;border-color:var(--primary);}

  .empty-state{text-align:center;padding:3rem;color:var(--muted);}
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
    <a href="{{ route('admin.donors.index') }}" class="sb-item">
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
    <a href="{{ route('admin.appointments.index') }}" class="sb-item active">
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
    <div class="sb-avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-label">{{ __('Admin Panel') }}</div>
      <div class="topbar-title">{{ __('Appointments') }}</div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
      @csrf
      <button type="submit" class="logout-btn">{{ __('Logout') }}</button>
    </form>
  </div>

  <div class="content">

    <div class="page-hd">
      <div class="page-label">{{ __('Cross-Hospital View') }}</div>
      <h1>{{ __('Donation Appointment Overview') }}</h1>
      <div class="page-sub">{{ __("Read-only visibility into every hospital's appointments — only the owning hospital can approve, reject, reschedule, or complete.") }}</div>
    </div>

    <div class="stat-row">
      <div class="sc"><div class="sc-num">{{ $stats['total'] }}</div><div class="sc-label">{{ __('Total') }}</div></div>
      <div class="sc"><div class="sc-num">{{ $stats['completed'] }}</div><div class="sc-label">{{ __('Completed') }}</div></div>
      <div class="sc"><div class="sc-num">{{ $stats['pending'] }}</div><div class="sc-label">{{ __('Pending') }}</div></div>
      <div class="sc"><div class="sc-num">{{ $stats['approved'] }}</div><div class="sc-label">{{ __('Approved') }}</div></div>
      <div class="sc"><div class="sc-num">{{ $stats['cancelled'] + $stats['rejected'] }}</div><div class="sc-label">{{ __('Cancelled/Rejected') }}</div></div>
    </div>

    <div class="grid-2">
      <div class="panel">
        <div class="panel-title">{{ __('Monthly Appointment Volume') }}</div>
        @php $maxTotal = max(1, $monthlyChart->max('total')); @endphp
        <div class="chart-row">
          @foreach($monthlyChart as $m)
            <div class="chart-col">
              <div class="chart-bar-wrap">
                <div class="chart-bar total" style="height:{{ $m['total'] > 0 ? max(6, ($m['total'] / $maxTotal) * 100) : 2 }}%;" title="{{ $m['total'] }} {{ __('total') }}"></div>
                <div class="chart-bar completed" style="height:{{ $m['completed'] > 0 ? max(6, ($m['completed'] / $maxTotal) * 100) : 2 }}%;" title="{{ $m['completed'] }} {{ __('completed') }}"></div>
              </div>
              <div class="chart-label">{{ $m['label'] }}</div>
            </div>
          @endforeach
        </div>
        <div class="chart-legend">
          <span><span class="legend-dot" style="background:var(--primary);"></span>{{ __('Total booked') }}</span>
          <span><span class="legend-dot" style="background:var(--secondary);"></span>{{ __('Completed') }}</span>
        </div>
      </div>
      <div class="panel">
        <div class="panel-title">{{ __('By Blood Group') }}</div>
        @forelse($byBloodGroup as $row)
          <div class="breakdown-row">
            <span class="breakdown-name">{{ $row['blood_group'] }}</span>
            <span class="breakdown-count">{{ $row['total'] }} {{ __('total') }} · {{ $row['completed'] }} {{ __('completed') }}</span>
          </div>
        @empty
          <div class="empty-state">{{ __('No appointments yet.') }}</div>
        @endforelse
      </div>
    </div>

    <div class="panel" style="margin-bottom:1.5rem;">
      <div class="panel-title">{{ __('By Hospital') }}</div>
      @forelse($byHospital as $row)
        <div class="breakdown-row">
          <span class="breakdown-name">{{ $row['hospital'] }}</span>
          <span class="breakdown-count">{{ $row['total'] }} {{ __('total') }} · {{ $row['completed'] }} {{ __('completed') }}</span>
        </div>
      @empty
        <div class="empty-state">{{ __('No appointments yet.') }}</div>
      @endforelse
    </div>

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('admin.appointments.index') }}">
      <div class="filter-bar">
        <select name="hospital_id" class="filter-select">
          <option value="">{{ __('All Hospitals') }}</option>
          @foreach($hospitals as $h)
            <option value="{{ $h->id }}" {{ (string) request('hospital_id') === (string) $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
          @endforeach
        </select>
        <select name="blood_group" class="filter-select">
          <option value="">{{ __('All Blood Groups') }}</option>
          @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
            <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
          @endforeach
        </select>
        <select name="status" class="filter-select">
          <option value="">{{ __('All Status') }}</option>
          @foreach(['pending','approved','rejected','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn-filter">{{ __('Filter') }}</button>
        <a href="{{ route('admin.appointments.index') }}" class="btn-reset">{{ __('Reset') }}</a>
      </div>
    </form>

    {{-- TABLE --}}
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('Hospital') }}</th>
              <th>{{ __('Donor') }}</th>
              <th>{{ __('Blood Group') }}</th>
              <th>{{ __('Date') }}</th>
              <th>{{ __('Time') }}</th>
              <th>{{ __('Status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($appointments as $a)
              <tr>
                <td style="color:var(--muted);font-size:0.75rem;">#{{ $a->id }}</td>
                <td><a href="{{ route('admin.hospitals.show', $a->hospital_id) }}" class="hosp-link">{{ $a->hospital?->name ?? __('Deleted hospital') }}</a></td>
                <td><a href="{{ route('admin.donors.show', $a->donor_id) }}" class="hosp-link">{{ $a->donor?->full_name ?? __('Deleted donor') }}</a></td>
                <td><span class="blood-pill">{{ $a->bloodRequest?->blood_group }}</span></td>
                <td>{{ $a->appointment_date->format('d M Y') }}</td>
                <td>{{ $a->appointment_time }}</td>
                <td><span class="badge st-{{ $a->status }}">{{ $a->statusLabel() }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="7">
                  <div class="empty-state">{{ __('No appointments match these filters.') }}</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($appointments->hasPages())
        <div class="pagination-wrap">
          <div class="page-info">{{ __('Showing') }} {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} {{ __('of') }} {{ $appointments->total() }}</div>
          <div class="page-links">
            @if($appointments->onFirstPage())
              <span>←</span>
            @else
              <a href="{{ $appointments->previousPageUrl() }}">←</a>
            @endif
            @foreach($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
              @if($page == $appointments->currentPage())
                <span class="active">{{ $page }}</span>
              @else
                <a href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach
            @if($appointments->hasMorePages())
              <a href="{{ $appointments->nextPageUrl() }}">→</a>
            @else
              <span>→</span>
            @endif
          </div>
        </div>
      @endif
    </div>

  </div>
</div>
