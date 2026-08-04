<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-dark:#92400E;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --accent:#06B6D4;--accent-light:#ECFEFF;
    --sb:66px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR */
  .sidebar{width:var(--sb);background:#0F172A;display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(59,130,246,0.25);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:none;stroke:#93C5FD;stroke-width:1.75;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;align-items:center;flex:1;width:100%;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.3);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.7);}
  .sb-item.active{background:rgba(59,130,246,0.2);}
  .sb-item.active svg{stroke:#93C5FD;}
  .sb-tip{position:absolute;left:54px;background:#0F172A;color:#E2E8F0;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;border:1px solid rgba(255,255,255,0.08);z-index:99;}
  .sb-item:hover .sb-tip{opacity:1;}
  .sb-bot{margin-top:auto;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(59,130,246,0.3);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:#93C5FD;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .tb-badge{display:flex;align-items:center;gap:6px;background:var(--primary-light);border:1px solid var(--border);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.72rem;font-weight:500;color:var(--primary);}
  .tb-badge span{width:5px;height:5px;border-radius:50%;background:var(--primary);display:inline-block;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .hosp-pill{display:flex;align-items:center;gap:8px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:20px;padding:0.3rem 0.9rem 0.3rem 0.5rem;}
  .hosp-av{width:24px;height:24px;border-radius:6px;background:var(--primary);display:flex;align-items:center;justify-content:center;}
  .hosp-av svg{width:13px;height:13px;stroke:white;fill:none;stroke-width:2;}
  .hosp-name{font-size:0.78rem;font-weight:500;color:var(--primary);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}
  .alert-warning{background:var(--amber-bg);border:1px solid var(--amber-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--amber-dark);}

  /* PAGE HEADER */
  .page-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
  .page-hd-left .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd-left h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}
  .btn-history{padding:0.6rem 1.25rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;color:var(--primary);cursor:pointer;text-decoration:none;font-weight:500;}
  .btn-history:hover{border-color:var(--primary);}

  /* STAT ROW */
  .stat-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:1.5rem;}
  .sc{background:white;border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;}
  .sc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
  .sc-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;}
  .ic-b{background:var(--primary-light);} .ic-b svg{stroke:var(--primary);}
  .ic-a{background:var(--amber-bg);} .ic-a svg{stroke:var(--amber);}
  .ic-r{background:#FEF2F2;} .ic-r svg{stroke:var(--amber-dark);}
  .sc-icon svg{width:16px;height:16px;fill:none;stroke-width:1.75;stroke-linecap:round;}
  .sc-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:2px;}
  .sc-label{font-size:0.73rem;color:var(--muted);}

  /* TABLE */
  .table-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.65rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  tbody tr{border-bottom:1px solid rgba(29,78,216,0.06);}
  tbody tr:last-child{border-bottom:none;}
  td{padding:0.85rem 1rem;color:var(--text);vertical-align:middle;}
  .blood-pill{display:inline-block;background:var(--primary-light);color:var(--primary-dark);font-size:0.85rem;font-weight:700;padding:4px 12px;border-radius:6px;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;}
  .b-suff{background:var(--green-bg);color:var(--green);}
  .b-low{background:var(--amber-bg);color:var(--amber);}
  .b-crit{background:var(--amber-b);color:var(--amber-dark);}
  .updated-at{font-size:0.72rem;color:var(--muted);}

  /* INLINE ACTION FORMS */
  .actions-cell{display:flex;flex-wrap:wrap;gap:6px;align-items:center;}
  .mini-form{display:flex;gap:4px;align-items:center;}
  .mini-input{width:56px;padding:0.35rem 0.5rem;border:1px solid var(--gray-b);border-radius:6px;font-family:'DM Sans',sans-serif;font-size:0.78rem;color:var(--text);}
  .mini-btn{padding:0.35rem 0.7rem;border:none;border-radius:6px;font-family:'DM Sans',sans-serif;font-size:0.75rem;font-weight:500;cursor:pointer;white-space:nowrap;}
  .mini-btn.add{background:var(--green-bg);color:var(--green);border:1px solid var(--green-b);}
  .mini-btn.add:hover{background:var(--green);color:white;}
  .mini-btn.remove{background:var(--amber-bg);color:var(--amber-dark);border:1px solid var(--amber-b);}
  .mini-btn.remove:hover{background:var(--amber-dark);color:white;}
  .mini-btn.threshold{background:var(--primary-light);color:var(--primary-dark);border:1px solid var(--border);}
  .mini-btn.threshold:hover{background:var(--primary);color:white;}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('hospital.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tip">Dashboard</span>
    </a>
    <a href="{{ route('hospital.request.create') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tip">New Request</span>
    </a>
    <a href="{{ route('hospital.requests.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">All Requests</span>
    </a>
    <a href="{{ route('hospital.appointments.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">Appointments</span>
    </a>
    <a href="{{ route('hospital.inventory.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73V8z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
      <span class="sb-tip">Blood Inventory</span>
    </a>
    <a href="{{ route('hospital.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">Settings</span>
    </a>
  </div>
  <div class="sb-bot">
    <div class="sb-avatar">{{ strtoupper(substr($hospital->name,0,2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <div class="tb-badge"><span></span> Hospital Portal</div>
      <div class="tb-title">Blood Inventory</div>
    </div>
    <div class="tb-right">
      <div class="hosp-pill">
        <div class="hosp-av"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg></div>
        <span class="hosp-name">{{ $hospital->name }}</span>
      </div>
      <form method="POST" action="{{ route('hospital.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
      <div class="alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="page-hd">
      <div class="page-hd-left">
        <div class="page-label">Manage</div>
        <h1>Blood Stock Levels</h1>
      </div>
      <a href="{{ route('hospital.inventory.history') }}" class="btn-history">View History →</a>
    </div>

    @php
      $totalUnits = $inventory->sum('available_units');
      $lowCount = $inventory->filter(fn($i) => $i->status() === 'low_stock')->count();
      $critCount = $inventory->filter(fn($i) => $i->status() === 'critical')->count();
    @endphp

    <div class="stat-row">
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-b"><svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73V8z"/></svg></div>
        </div>
        <div class="sc-num">{{ $totalUnits }}</div>
        <div class="sc-label">Total Units in Stock</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-a"><svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
        </div>
        <div class="sc-num">{{ $lowCount }}</div>
        <div class="sc-label">Blood Groups Low on Stock</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-r"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
        </div>
        <div class="sc-num">{{ $critCount }}</div>
        <div class="sc-label">Critical Blood Groups</div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Blood Group</th>
              <th>Available Units</th>
              <th>Minimum Threshold</th>
              <th>Status</th>
              <th>Last Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inventory as $item)
              <tr>
                <td><span class="blood-pill">{{ $item->blood_group }}</span></td>
                <td>{{ $item->available_units }}</td>
                <td>{{ $item->minimum_threshold }}</td>
                <td>
                  @php $status = $item->status(); @endphp
                  <span class="badge {{ $status === 'critical' ? 'b-crit' : ($status === 'low_stock' ? 'b-low' : 'b-suff') }}">
                    {{ $item->statusLabel() }}
                  </span>
                </td>
                <td class="updated-at">{{ $item->last_updated ? $item->last_updated->diffForHumans() : '—' }}</td>
                <td>
                  <div class="actions-cell">
                    <form method="POST" action="{{ route('hospital.inventory.add', $item->id) }}" class="mini-form">
                      @csrf
                      <input type="number" name="units" min="1" max="500" value="1" class="mini-input" aria-label="Units to add">
                      <button type="submit" class="mini-btn add">+ Add</button>
                    </form>
                    <form method="POST" action="{{ route('hospital.inventory.remove', $item->id) }}" class="mini-form">
                      @csrf
                      <input type="number" name="units" min="1" max="500" value="1" class="mini-input" aria-label="Units to remove">
                      <button type="submit" class="mini-btn remove">− Remove</button>
                    </form>
                    <form method="POST" action="{{ route('hospital.inventory.threshold', $item->id) }}" class="mini-form">
                      @csrf
                      <input type="number" name="minimum_threshold" min="0" max="1000" value="{{ $item->minimum_threshold }}" class="mini-input" aria-label="Minimum threshold">
                      <button type="submit" class="mini-btn threshold">Set Min</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
