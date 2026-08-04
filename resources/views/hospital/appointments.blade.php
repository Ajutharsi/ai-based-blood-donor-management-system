<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-dark:#92400E;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:66px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

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

  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .tb-badge{display:flex;align-items:center;gap:6px;background:var(--blue-bg);border:1px solid var(--blue-b);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.72rem;font-weight:500;color:var(--blue);}
  .tb-badge span{width:5px;height:5px;border-radius:50%;background:var(--blue);display:inline-block;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .hosp-pill{display:flex;align-items:center;gap:8px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:20px;padding:0.3rem 0.9rem 0.3rem 0.5rem;}
  .hosp-av{width:24px;height:24px;border-radius:6px;background:var(--blue);display:flex;align-items:center;justify-content:center;}
  .hosp-av svg{width:13px;height:13px;stroke:white;fill:none;stroke-width:2;}
  .hosp-name{font-size:0.78rem;font-weight:500;color:var(--blue);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  .content{padding:1.75rem 2rem;}
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  .page-hd{margin-bottom:1.5rem;}
  .page-hd .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}

  .stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:1.5rem;}
  .sc{background:white;border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;}
  .sc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.65rem;}
  .sc-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;}
  .ic-r{background:var(--primary-light);} .ic-r svg{stroke:var(--primary);}
  .ic-b{background:#EFF6FF;}          .ic-b svg{stroke:var(--blue);}
  .ic-g{background:var(--green-bg);} .ic-g svg{stroke:var(--green);}
  .ic-a{background:var(--amber-bg);} .ic-a svg{stroke:var(--amber);}
  .sc-icon svg{width:15px;height:15px;fill:none;stroke-width:1.75;stroke-linecap:round;}
  .sc-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:2px;}
  .sc-label{font-size:0.73rem;color:var(--muted);}

  .filter-tabs{display:flex;gap:8px;margin-bottom:1.25rem;}
  .ftab{padding:0.4rem 1rem;border-radius:20px;border:1px solid var(--gray-b);background:white;font-family:'DM Sans',sans-serif;font-size:0.775rem;color:var(--muted);cursor:pointer;text-decoration:none;transition:all 0.2s;}
  .ftab:hover{border-color:var(--primary);color:var(--primary);}
  .ftab.active{background:var(--primary-light);border-color:var(--border);color:var(--primary-dark);font-weight:500;}

  .table-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:1.5rem;}
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.65rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  tbody tr{border-bottom:1px solid rgba(29,78,216,0.06);transition:background 0.15s;}
  tbody tr:last-child{border-bottom:none;}
  tbody tr:hover{background:var(--primary-light);}
  td{padding:0.75rem 1rem;color:var(--text);vertical-align:middle;}
  .blood-pill{display:inline-block;background:var(--primary-light);color:var(--primary-dark);font-size:0.75rem;font-weight:700;padding:3px 10px;border-radius:5px;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:600;padding:3px 10px;border-radius:20px;white-space:nowrap;text-transform:uppercase;letter-spacing:0.02em;}
  .st-pending{background:var(--blue-bg);color:var(--blue);}
  .st-approved{background:var(--green-bg);color:var(--green);}
  .st-rejected{background:var(--amber-bg);color:var(--amber);}
  .st-completed{background:#F0FDFA;color:#115E59;}
  .st-cancelled{background:var(--gray);color:var(--muted);}

  .actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap;}
  .abtn{font-size:0.73rem;padding:0.3rem 0.7rem;border-radius:5px;cursor:pointer;font-family:'DM Sans',sans-serif;border:1px solid var(--gray-b);background:white;color:var(--muted);white-space:nowrap;}
  .abtn.approve{border-color:var(--green-b);background:var(--green-bg);color:var(--green);}
  .abtn.approve:hover{background:var(--green);color:white;}
  .abtn.reject{border-color:var(--amber-b);background:var(--amber-bg);color:var(--amber);}
  .abtn.reject:hover{background:var(--amber);color:white;}
  .abtn.complete{border-color:var(--blue-b);background:var(--blue-bg);color:var(--blue);}
  .abtn.complete:hover{background:var(--blue);color:white;}

  .resched-form{display:flex;gap:6px;align-items:center;}
  .resched-form input{padding:0.3rem 0.5rem;border:1px solid var(--gray-b);border-radius:5px;font-family:'DM Sans',sans-serif;font-size:0.73rem;}

  .empty-state{text-align:center;padding:3rem;color:var(--muted);}
  .empty-state svg{width:44px;height:44px;stroke:var(--gray-b);fill:none;stroke-width:1.5;margin-bottom:0.75rem;}
  .empty-state p{font-size:0.85rem;}
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
    <a href="{{ route('hospital.appointments.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">Appointments</span>
    </a>
    <a href="{{ route('hospital.inventory.index') }}" class="sb-item">
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

  <div class="topbar">
    <div class="tb-left">
      <div class="tb-badge"><span></span> Hospital Portal</div>
      <div class="tb-title">Appointment Management</div>
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

    <div class="page-hd">
      <div class="page-label">Appointments</div>
      <h1>Donation Appointment Management</h1>
    </div>

    <div class="stat-row">
      <div class="sc">
        <div class="sc-top"><div class="sc-icon ic-b"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
        <div class="sc-num">{{ $today->count() }}</div><div class="sc-label">Today's Appointments</div>
      </div>
      <div class="sc">
        <div class="sc-top"><div class="sc-icon ic-r"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
        <div class="sc-num">{{ $upcoming->count() }}</div><div class="sc-label">Upcoming</div>
      </div>
      <div class="sc">
        <div class="sc-top"><div class="sc-icon ic-a"><svg viewBox="0 0 24 24"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg></div></div>
        <div class="sc-num">{{ $pending->count() }}</div><div class="sc-label">Awaiting Approval</div>
      </div>
      <div class="sc">
        <div class="sc-top"><div class="sc-icon ic-g"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
        <div class="sc-num">{{ $appointments->where('status','completed')->count() }}</div><div class="sc-label">Completed</div>
      </div>
    </div>

    <div class="filter-tabs">
      <a href="{{ route('hospital.appointments.index') }}" class="ftab {{ !request('status') ? 'active' : '' }}">All ({{ $appointments->count() }})</a>
      <a href="{{ route('hospital.appointments.index', ['status' => 'pending']) }}" class="ftab {{ request('status') === 'pending' ? 'active' : '' }}">Pending ({{ $pending->count() }})</a>
      <a href="{{ route('hospital.appointments.index', ['status' => 'approved']) }}" class="ftab {{ request('status') === 'approved' ? 'active' : '' }}">Approved</a>
      <a href="{{ route('hospital.appointments.index', ['status' => 'completed']) }}" class="ftab {{ request('status') === 'completed' ? 'active' : '' }}">Completed</a>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Donor</th>
              <th>Blood Group</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($appointments as $a)
              <tr>
                <td style="color:var(--muted);font-size:0.75rem;">#{{ $a->id }}</td>
                <td>{{ $a->donor?->full_name ?? 'Donor' }}</td>
                <td><span class="blood-pill">{{ $a->bloodRequest?->blood_group }}</span></td>
                <td>{{ $a->appointment_date->format('d M Y') }}</td>
                <td>{{ $a->appointment_time }}</td>
                <td><span class="badge st-{{ $a->status }}">{{ $a->statusLabel() }}</span></td>
                <td>
                  <div class="actions">
                    @if($a->status === 'pending')
                      <form method="POST" action="{{ route('hospital.appointments.approve', $a->id) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="abtn approve">Approve</button>
                      </form>
                      <form method="POST" action="{{ route('hospital.appointments.reject', $a->id) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="abtn reject" onclick="return confirm('Reject this appointment?')">Reject</button>
                      </form>
                    @endif
                    @if($a->status === 'approved')
                      <form method="POST" action="{{ route('hospital.appointments.complete', $a->id) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="abtn complete" onclick="return confirm('Mark this appointment as completed and record the donation?')">Complete</button>
                      </form>
                    @endif
                    @if(in_array($a->status, ['pending', 'approved']))
                      <form method="POST" action="{{ route('hospital.appointments.reschedule', $a->id) }}" class="resched-form">
                        @csrf
                        <input type="date" name="appointment_date" min="{{ now()->format('Y-m-d') }}" value="{{ $a->appointment_date->format('Y-m-d') }}" required>
                        <input type="time" name="appointment_time" value="{{ $a->appointment_time }}" required>
                        <button type="submit" class="abtn">Reschedule</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7">
                  <div class="empty-state">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <p>No appointments yet.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
