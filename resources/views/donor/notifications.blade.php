<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;--primary-mid:#60A5FA;
    --secondary:#0D9488;--secondary-dark:#115E59;--secondary-light:#F0FDFA;--secondary-b:#99F6E4;
    --warning:#D97706;--warning-dark:#92400E;--warning-light:#FFFBEB;--warning-b:#FDE68A;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:66px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR (same as donor dashboard) */
  .sidebar{width:var(--sb);background:var(--primary-dark);display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:white;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;align-items:center;flex:1;width:100%;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.35);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.75);}
  .sb-item.active{background:rgba(255,255,255,0.15);}
  .sb-item.active svg{stroke:white;}
  .sb-tip{position:absolute;left:54px;background:#1E293B;color:white;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;z-index:99;}
  .sb-item:hover .sb-tip{opacity:1;}
  .sb-bot{margin-top:auto;padding-bottom:0.5rem;}
  .sb-av{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .tb-sub{font-size:0.75rem;color:var(--muted);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;max-width:900px;}

  .page-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;}
  .page-hd h1{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--text);}
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

  .empty-state{text-align:center;padding:3rem 1.5rem;background:white;border:1px solid var(--border);border-radius:14px;color:var(--muted);}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('donor.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tip">{{ __('Dashboard') }}</span>
    </a>
    <a href="{{ route('donor.requests.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tip">{{ __('Blood Requests') }}</span>
    </a>
    <a href="{{ route('donor.appointments.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">{{ __('Appointments') }}</span>
    </a>
    <a href="{{ route('donor.dashboard') }}#donation-history" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">{{ __('History') }}</span>
    </a>
    <a href="{{ route('donor.notifications.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
      <span class="sb-tip">{{ __('Notifications') }}</span>
    </a>
    <a href="{{ route('donor.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">{{ __('Settings') }}</span>
    </a>
  </div>
  <div class="sb-bot">
    <div class="sb-av" @if($donor->profile_image) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($donor->profile_image) }}');background-size:cover;background-position:center;" @endif>
      @unless($donor->profile_image)
        {{ strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1)) }}
      @endunless
    </div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  <div class="topbar">
    <div class="tb-left">
      <div>
        <div class="tb-title">{{ __('Notifications') }}</div>
        <div class="tb-sub">{{ __('Blood request matches, eligibility, and account updates') }}</div>
      </div>
    </div>
    <div class="tb-right">
      <form method="POST" action="{{ route('donor.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">{{ __('Logout') }}</button>
      </form>
    </div>
  </div>

  <div class="content">

    <div class="page-hd">
      <div>
        <h1>{{ __('All Notifications') }}</h1>
        <div class="page-sub">{{ __('Every alert LifeLink has sent you, newest first.') }}</div>
      </div>
      <form method="POST" action="{{ route('donor.notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn-mark-all">{{ __('Mark all as read') }}</button>
      </form>
    </div>

    <div class="filter-tabs">
      <a href="{{ route('donor.notifications.index') }}" class="ftab {{ request('status') !== 'unread' ? 'active' : '' }}">{{ __('All') }}</a>
      <a href="{{ route('donor.notifications.index', ['status' => 'unread']) }}" class="ftab {{ request('status') === 'unread' ? 'active' : '' }}">{{ __('Unread') }}</a>
    </div>

    <div class="list-card">
      @forelse($notifications as $n)
        <form method="POST" action="{{ route('donor.notifications.read', $n->id) }}" style="margin:0;">
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
        <div class="empty-state">{{ __('No notifications yet.') }}</div>
      @endforelse

      @if($notifications->hasPages())
        <div class="pagination-wrap">
          <div class="page-info">
            {{ __('Showing') }} {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} {{ __('of') }} {{ $notifications->total() }} {{ __('notifications') }}
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
