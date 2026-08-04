<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-dark:#92400E;--amber-bg:#FFFBEB;
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

  /* PAGE HEADER */
  .page-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;}
  .page-hd-left .page-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:3px;}
  .page-hd-left h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);}
  .page-sub{font-size:0.82rem;color:var(--muted);margin-top:4px;}
  .btn-mark-all{padding:0.55rem 1.1rem;border:1px solid var(--gray-b);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--primary);cursor:pointer;font-weight:500;}
  .btn-mark-all:hover{border-color:var(--primary);}

  /* FILTER TABS */
  .filter-tabs{display:flex;gap:8px;margin-bottom:1.25rem;}
  .ftab{padding:0.4rem 1rem;border-radius:20px;border:1px solid var(--gray-b);background:white;font-family:'DM Sans',sans-serif;font-size:0.775rem;color:var(--muted);cursor:pointer;text-decoration:none;transition:all 0.2s;}
  .ftab:hover{border-color:var(--primary);color:var(--primary);}
  .ftab.active{background:var(--primary-light);border-color:var(--border);color:var(--primary-dark);font-weight:500;}

  /* LIST */
  .list-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .n-row{display:block;width:100%;text-align:left;background:none;border:none;border-bottom:1px solid var(--border);padding:1rem 1.25rem;cursor:pointer;font-family:inherit;}
  .n-row:last-child{border-bottom:none;}
  .n-row:hover{background:var(--off);}
  .n-row.unread{background:var(--primary-light);}
  .n-row-inner{display:flex;gap:12px;align-items:flex-start;}
  .n-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;margin-top:5px;}
  .nd-g{background:var(--green);}
  .nd-b{background:var(--primary);}
  .nd-r{background:var(--amber);}
  .n-title{font-size:0.85rem;font-weight:600;color:var(--text);}
  .n-msg{font-size:0.8rem;color:var(--text);margin-top:2px;line-height:1.5;}
  .n-time{font-size:0.7rem;color:var(--muted);margin-top:4px;}
  .n-type-badge{font-size:0.65rem;font-weight:500;padding:2px 8px;border-radius:20px;background:var(--gray);color:var(--muted);text-transform:capitalize;margin-left:8px;}

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

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <div class="tb-badge"><span></span> Hospital Portal</div>
      <div class="tb-title">Notifications</div>
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

    <div class="page-hd">
      <div class="page-hd-left">
        <div class="page-label">Activity</div>
        <h1>All Notifications</h1>
        <div class="page-sub">Donor responses, request status updates, and fulfillment activity.</div>
      </div>
      <form method="POST" action="{{ route('hospital.notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn-mark-all">Mark all as read</button>
      </form>
    </div>

    <div class="filter-tabs">
      <a href="{{ route('hospital.notifications.index') }}" class="ftab {{ request('status') !== 'unread' ? 'active' : '' }}">All</a>
      <a href="{{ route('hospital.notifications.index', ['status' => 'unread']) }}" class="ftab {{ request('status') === 'unread' ? 'active' : '' }}">Unread</a>
    </div>

    <div class="list-card">
      @forelse($notifications as $n)
        <form method="POST" action="{{ route('hospital.notifications.read', $n->id) }}" style="margin:0;">
          @csrf
          <button type="submit" class="n-row {{ $n->read_at ? '' : 'unread' }}">
            <div class="n-row-inner">
              <div class="n-dot {{ $n->dotClass() }}"></div>
              <div style="flex:1;">
                <div>
                  <span class="n-title">{{ $n->title }}</span>
                  <span class="n-type-badge">{{ str_replace('_', ' ', $n->type) }}</span>
                </div>
                <div class="n-msg">{{ $n->message }}</div>
                <div class="n-time">{{ $n->created_at->diffForHumans() }}</div>
              </div>
            </div>
          </button>
        </form>
      @empty
        <div class="empty-state">No notifications yet.</div>
      @endforelse

      @if($notifications->hasPages())
        <div class="pagination-wrap">
          <div class="page-info">
            Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
          </div>
          <div class="page-links">
            @if($notifications->onFirstPage())
              <span>←</span>
            @else
              <a href="{{ $notifications->previousPageUrl() }}">←</a>
            @endif
            @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
              @if($page == $notifications->currentPage())
                <span class="active">{{ $page }}</span>
              @else
                <a href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach
            @if($notifications->hasMorePages())
              <a href="{{ $notifications->nextPageUrl() }}">→</a>
            @else
              <span>→</span>
            @endif
          </div>
        </div>
      @endif
    </div>

  </div>
</div>
