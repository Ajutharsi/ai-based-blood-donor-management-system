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
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --amber:#D97706;--amber-bg:#FFFBEB;
    --warning:#D97706;--warning-dark:#92400E;--warning-light:#FFFBEB;--warning-b:#FDE68A;
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
  .sb-tooltip{position:absolute;left:56px;background:#1A0A0B;color:white;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;z-index:99;}
  .sb-item:hover .sb-tooltip{opacity:1;}
  .sb-bottom{margin-top:auto;display:flex;flex-direction:column;align-items:center;gap:8px;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .topbar-left{display:flex;flex-direction:column;}
  .topbar-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.07em;text-transform:uppercase;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);line-height:1.1;}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  .page-hd{margin-bottom:1.25rem;}
  .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}
  .page-sub{font-size:0.82rem;color:var(--muted);margin-top:4px;}

  /* FILTER BAR */
  .filter-bar{background:white;border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .filter-select{padding:0.5rem 2rem 0.5rem 0.9rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--text);outline:none;cursor:pointer;}
  .btn-filter{padding:0.5rem 1.1rem;border:none;border-radius:8px;background:var(--primary);color:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:500;cursor:pointer;}
  .btn-reset{padding:0.5rem 1rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;text-decoration:none;}
  .btn-reset:hover{border-color:var(--primary);color:var(--primary);}

  /* TABLE */
  .table-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.65rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  tbody tr{border-bottom:1px solid rgba(29,78,216,0.06);}
  tbody tr:last-child{border-bottom:none;}
  tbody tr:hover{background:var(--primary-light);}
  td{padding:0.75rem 1rem;color:var(--text);vertical-align:middle;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;}
  .b-elig{background:var(--blue-bg);color:var(--blue);}
  .b-resp{background:var(--amber-bg);color:var(--amber);}
  .b-anom{background:var(--warning-light);color:var(--warning-dark);}
  .b-other{background:var(--gray);color:var(--muted);}
  .donor-link{color:var(--primary-dark);text-decoration:none;font-weight:500;}
  .donor-link:hover{text-decoration:underline;}

  /* PAGINATION */
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
    <a href="{{ route('admin.ai-predictions.index') }}" class="sb-item active">
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
    <div class="sb-avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-label">{{ __('Admin Panel') }}</div>
      <div class="topbar-title">{{ __('AI Predictions') }}</div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
      @csrf
      <button type="submit" class="logout-btn">{{ __('Logout') }}</button>
    </form>
  </div>

  <div class="content">

    <div class="page-hd">
      <div class="page-label">{{ __('Audit Log') }}</div>
      <h1>{{ __('AI Prediction History') }}</h1>
      <div class="page-sub">{{ __('Every AI call the system has made — eligibility, response, and anomaly checks — with the model, input, output, and confidence recorded at the time.') }}</div>
    </div>

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('admin.ai-predictions.index') }}">
      <div class="filter-bar">
        <select name="prediction_type" class="filter-select">
          <option value="">{{ __('All Prediction Types') }}</option>
          @foreach($predictionTypes as $type)
            <option value="{{ $type }}" {{ request('prediction_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
          @endforeach
        </select>
        <select name="model" class="filter-select">
          <option value="">{{ __('All Models') }}</option>
          @foreach($models as $model)
            <option value="{{ $model }}" {{ request('model') === $model ? 'selected' : '' }}>{{ $model }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn-filter">{{ __('Filter') }}</button>
        <a href="{{ route('admin.ai-predictions.index') }}" class="btn-reset">{{ __('Reset') }}</a>
      </div>
    </form>

    {{-- TABLE --}}
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('Donor') }}</th>
              <th>{{ __('Type') }}</th>
              <th>{{ __('Model') }}</th>
              <th>{{ __('Confidence') }}</th>
              <th>{{ __('Recorded') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($predictions as $p)
              <tr>
                <td style="color:var(--muted);font-size:0.75rem;">#{{ $p->id }}</td>
                <td>
                  @if($p->donor)
                    <a href="{{ route('admin.donors.show', $p->donor->id) }}" class="donor-link">{{ $p->donor->full_name }}</a>
                  @else
                    <span style="color:var(--muted);">{{ __('Deleted donor') }}</span>
                  @endif
                </td>
                <td>
                  <span class="badge {{ $p->prediction_type === 'eligibility' ? 'b-elig' : ($p->prediction_type === 'response' ? 'b-resp' : ($p->prediction_type === 'anomaly' ? 'b-anom' : 'b-other')) }}">
                    {{ ucfirst($p->prediction_type) }}
                  </span>
                </td>
                <td>{{ $p->model }}</td>
                <td>{{ $p->confidence !== null ? number_format($p->confidence, 1).'%' : '—' }}</td>
                <td style="font-size:0.75rem;color:var(--muted);white-space:nowrap;">
                  {{ $p->created_at->format('d M Y') }}<br>
                  <span style="font-size:0.7rem;">{{ $p->created_at->format('h:i A') }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="empty-state">{{ __('No AI predictions recorded yet.') }}</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($predictions->hasPages())
        <div class="pagination-wrap">
          <div class="page-info">
            {{ __('Showing') }} {{ $predictions->firstItem() }}–{{ $predictions->lastItem() }} {{ __('of') }} {{ $predictions->total() }} {{ __('predictions') }}
          </div>
          <div class="page-links">
            @if($predictions->onFirstPage())
              <span>←</span>
            @else
              <a href="{{ $predictions->previousPageUrl() }}">←</a>
            @endif
            @foreach($predictions->getUrlRange(1, $predictions->lastPage()) as $page => $url)
              @if($page == $predictions->currentPage())
                <span class="active">{{ $page }}</span>
              @else
                <a href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach
            @if($predictions->hasMorePages())
              <a href="{{ $predictions->nextPageUrl() }}">→</a>
            @else
              <span>→</span>
            @endif
          </div>
        </div>
      @endif
    </div>

  </div>
</div>
