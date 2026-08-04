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

  /* SIDEBAR */
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
  .sb-av{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;cursor:pointer;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .greet{display:flex;flex-direction:column;}
  .greet-sub{font-size:0.7rem;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;}
  .greet-name{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .notif{width:36px;height:36px;border-radius:8px;background:var(--gray);border:1px solid var(--gray-b);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;}
  .notif svg{width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.75;}
  .ndot{position:absolute;top:7px;right:8px;width:5px;height:5px;border-radius:50%;background:var(--primary);border:1px solid white;}
  .notif-wrap{position:relative;}
  .notif-badge{position:absolute;top:-4px;right:-4px;min-width:16px;height:16px;padding:0 3px;border-radius:8px;background:var(--primary);color:#fff;font-size:0.6rem;font-weight:600;display:flex;align-items:center;justify-content:center;line-height:1;}
  .notif-dropdown{position:absolute;top:46px;right:0;width:340px;max-height:420px;overflow-y:auto;background:white;border:1px solid var(--border);border-radius:12px;box-shadow:0 10px 30px rgba(15,23,42,0.14);z-index:200;display:none;}
  .notif-dropdown.open{display:block;}
  .notif-dd-head{display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1rem;border-bottom:1px solid var(--border);}
  .notif-dd-title{font-size:0.82rem;font-weight:600;color:var(--text);}
  .notif-dd-markall{font-size:0.7rem;color:var(--primary);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;}
  .notif-dd-item{display:block;width:100%;text-align:left;background:none;border:none;padding:0.75rem 1rem;border-bottom:1px solid var(--border);cursor:pointer;font-family:inherit;}
  .notif-dd-item:last-child{border-bottom:none;}
  .notif-dd-item:hover{background:var(--off);}
  .notif-dd-item.unread{background:var(--primary-light);}
  .notif-dd-row{display:flex;gap:8px;align-items:flex-start;}
  .notif-dd-text{font-size:0.78rem;color:var(--text);line-height:1.45;}
  .notif-dd-text strong{font-weight:600;}
  .notif-dd-time{font-size:0.66rem;color:var(--muted);margin-top:3px;}
  .notif-dd-empty{padding:2rem 1rem;text-align:center;font-size:0.8rem;color:var(--muted);}
  .notif-dd-foot{padding:0.6rem 1rem;text-align:center;border-top:1px solid var(--border);}
  .notif-dd-foot a{font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:500;}
  .donor-pill{display:flex;align-items:center;gap:8px;background:var(--primary-light);border:1px solid var(--border);border-radius:20px;padding:0.3rem 0.9rem 0.3rem 0.5rem;}
  .dp-av{width:24px;height:24px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;color:white;}
  .dp-name{font-size:0.78rem;font-weight:500;color:var(--primary-dark);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* HERO ELIGIBILITY BANNER */
  .elig-banner{border-radius:16px;padding:1.75rem 2rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1.5rem;position:relative;overflow:hidden;}
  .eb-eligible{background:var(--secondary-dark);}
  .eb-not{background:var(--warning-dark);}
  .eb-bg{position:absolute;inset:0;pointer-events:none;}
  .ebc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.07);}
  .ebc1{width:300px;height:300px;top:-100px;right:-60px;}
  .ebc2{width:180px;height:180px;bottom:-60px;right:120px;}
  .eb-icon{width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:2;}
  .eb-icon svg{width:28px;height:28px;fill:white;}
  .eb-text{flex:1;position:relative;z-index:2;}
  .eb-status{font-size:0.7rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.6);margin-bottom:4px;}
  .eb-title{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:white;margin-bottom:4px;}
  .eb-sub{font-size:0.85rem;color:rgba(255,255,255,0.6);font-weight:300;}
  .eb-date{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.12);border-radius:8px;padding:0.5rem 1rem;margin-top:0.75rem;font-size:0.8rem;color:rgba(255,255,255,0.8);}
  .eb-date svg{width:14px;height:14px;stroke:rgba(255,255,255,0.6);fill:none;stroke-width:1.75;}
  .eb-right{position:relative;z-index:2;text-align:right;flex-shrink:0;}
  .eb-score{font-family:'Playfair Display',serif;font-size:3rem;font-weight:700;color:white;line-height:1;}
  .eb-score-label{font-size:0.72rem;color:rgba(255,255,255,0.5);letter-spacing:0.06em;text-transform:uppercase;margin-top:2px;}

  /* STATS ROW */
  .stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:1.5rem;}
  .sc{background:white;border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;}
  .sc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.65rem;}
  .sc-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;}
  .ic-r{background:var(--secondary-light);} .ic-r svg{stroke:var(--secondary);}
  .ic-g{background:var(--green-bg);} .ic-g svg{stroke:var(--green);}
  .ic-b{background:var(--blue-bg);} .ic-b svg{stroke:var(--blue);}
  .ic-a{background:var(--amber-bg);} .ic-a svg{stroke:var(--amber);}
  .sc-icon svg{width:15px;height:15px;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sc-badge{font-size:0.67rem;font-weight:500;padding:2px 7px;border-radius:20px;}
  .sb-g{background:var(--green-bg);color:var(--green);}
  .sb-a{background:var(--amber-bg);color:var(--amber);}
  .sb-r{background:var(--warning-light);color:var(--warning-dark);}
  .sb-b{background:var(--blue-bg);color:var(--blue);}
  .sc-num{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:2px;}
  .sc-label{font-size:0.73rem;color:var(--muted);}

  /* MAIN GRID */
  .grid-main{display:grid;grid-template-columns:1fr 320px;gap:14px;}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .card-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;}
  .card-action{font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:500;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}
  .card-act{font-size:0.75rem;color:var(--primary);cursor:pointer;text-decoration:none;}

  /* AI BREAKDOWN */
  .ai-breakdown{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:1.25rem;}
  .ai-metric{border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center;}
  .am-val{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--text);margin-bottom:2px;}
  .am-label{font-size:0.7rem;color:var(--muted);margin-bottom:8px;}
  .am-bar{background:var(--gray);border-radius:4px;height:5px;overflow:hidden;}
  .am-fill{height:5px;border-radius:4px;}
  .fill-g{background:var(--green);}
  .fill-r{background:var(--warning);}
  .am-status{font-size:0.65rem;font-weight:500;margin-top:6px;}
  .st-ok{color:var(--green);}
  .st-warn{color:var(--warning);}

  /* COOLDOWN */
  .cooldown-wrap{background:var(--gray);border-radius:10px;padding:1.1rem 1.25rem;margin-bottom:1.25rem;}
  .cd-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
  .cd-label{font-size:0.78rem;font-weight:500;color:var(--text);}
  .cd-days{font-size:0.78rem;font-weight:500;}
  .cd-track{background:white;border-radius:6px;height:10px;overflow:hidden;margin-bottom:6px;}
  .cd-fill{height:10px;border-radius:6px;background:linear-gradient(90deg,var(--primary),var(--primary-mid));}
  .cd-marks{display:flex;justify-content:space-between;font-size:0.67rem;color:var(--muted);}

  /* HISTORY */
  .hist-list{display:flex;flex-direction:column;gap:0;}
  .hist-item{display:flex;align-items:center;gap:12px;padding:0.85rem 0;border-bottom:1px solid rgba(29,78,216,0.06);}
  .hist-item:last-child{border-bottom:none;}
  .hist-icon{width:36px;height:36px;border-radius:9px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .hist-icon svg{width:16px;height:16px;stroke:var(--primary);fill:none;stroke-width:1.75;}
  .hist-info{flex:1;}
  .hist-t{font-size:0.85rem;font-weight:400;color:var(--text);}
  .hist-s{font-size:0.7rem;color:var(--muted);margin-top:1px;}
  .hist-right{text-align:right;}
  .hist-date{font-size:0.72rem;color:var(--muted);}
  .badge{display:inline-block;font-size:0.67rem;font-weight:500;padding:2px 8px;border-radius:20px;margin-top:3px;}
  .b-ok{background:var(--green-bg);color:var(--green);}
  .b-no{background:var(--warning-light);color:var(--warning-dark);}

  /* RIGHT COL */
  .right-col{display:flex;flex-direction:column;gap:14px;}

  /* PROFILE CARD */
  .profile-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .pc-top{background:var(--primary-dark);padding:1.5rem;text-align:center;position:relative;}
  .pc-circles{position:absolute;inset:0;pointer-events:none;}
  .pcc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.08);}
  .pcc1{width:150px;height:150px;top:-40px;right:-30px;}
  .pcc2{width:100px;height:100px;bottom:-20px;left:-20px;}
  .pc-avatar{width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,0.2);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:white;margin:0 auto 0.75rem;position:relative;z-index:2;}
  .pc-name{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:white;position:relative;z-index:2;}
  .pc-blood{display:inline-block;background:var(--primary);color:white;font-size:0.75rem;font-weight:600;padding:3px 12px;border-radius:20px;margin-top:6px;position:relative;z-index:2;}
  .pc-body{padding:1.1rem 1.25rem;}
  .pc-row{display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(29,78,216,0.06);}
  .pc-row:last-child{border-bottom:none;}
  .pc-key{font-size:0.75rem;color:var(--muted);}
  .pc-val{font-size:0.8rem;font-weight:500;color:var(--text);}
  .edit-btn{width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:8px;background:transparent;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--primary-dark);cursor:pointer;transition:all 0.2s;margin-top:0.75rem;}
  .edit-btn:hover{background:var(--primary-light);}

  /* NOTIFICATIONS */
  .notif-list{display:flex;flex-direction:column;gap:8px;}
  .notif-item{display:flex;gap:10px;padding:0.75rem;border-radius:9px;border:1px solid var(--border);}
  .notif-dot-wrap{padding-top:4px;}
  .n-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
  .nd-r{background:var(--warning);}
  .nd-g{background:var(--green);}
  .nd-b{background:var(--blue);}
  .notif-text{font-size:0.78rem;color:var(--text);line-height:1.5;}
  .notif-time{font-size:0.67rem;color:var(--muted);margin-top:3px;}

  /* EMPTY STATE */
  .empty-state{text-align:center;padding:2rem;color:var(--muted);font-size:0.85rem;}
</style>

{{-- ── SIDEBAR ── --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('donor.dashboard') }}" class="sb-item {{ request()->routeIs('donor.dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tip">Dashboard</span>
    </a>
    <a href="{{ route('donor.requests.index') }}" class="sb-item {{ request()->routeIs('donor.requests.index') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tip">Blood Requests</span>
    </a>
    <a href="{{ route('donor.appointments.index') }}" class="sb-item {{ request()->routeIs('donor.appointments.index') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">Appointments</span>
    </a>
    <a href="{{ route('donor.dashboard') }}#ai-health" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
      <span class="sb-tip">Eligibility</span>
    </a>
    <a href="{{ route('donor.dashboard') }}#donation-history" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">History</span>
    </a>
    <a href="{{ route('donor.notifications.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
      <span class="sb-tip">Notifications</span>
    </a>
    <a href="{{ route('donor.profile.edit') }}" class="sb-item {{ request()->routeIs('donor.profile.edit') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">Settings</span>
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

{{-- ── MAIN ── --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <div class="greet">
        <div class="greet-sub">Welcome back</div>
        <div class="greet-name">{{ $donor->full_name }}</div>
      </div>
    </div>
    <div class="tb-right">
      <div class="notif-wrap">
        <button type="button" class="notif" onclick="toggleNotifDropdown(event)">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
          @if($unreadNotifications > 0)
            <span class="notif-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
          @endif
        </button>
        <div class="notif-dropdown" id="notifDropdown">
          <div class="notif-dd-head">
            <span class="notif-dd-title">Notifications</span>
            @if($unreadNotifications > 0)
              <form method="POST" action="{{ route('donor.notifications.readAll') }}" style="margin:0;">
                @csrf
                <button type="submit" class="notif-dd-markall">Mark all read</button>
              </form>
            @endif
          </div>
          @forelse($notifications as $n)
            <form method="POST" action="{{ route('donor.notifications.read', $n->id) }}" style="margin:0;">
              @csrf
              <button type="submit" class="notif-dd-item {{ $n->read_at ? '' : 'unread' }}">
                <div class="notif-dd-row">
                  <div class="n-dot {{ $n->dotClass() }}" style="margin-top:5px;"></div>
                  <div>
                    <div class="notif-dd-text"><strong>{{ $n->title }}</strong><br>{{ $n->message }}</div>
                    <div class="notif-dd-time">{{ $n->created_at->diffForHumans() }}</div>
                  </div>
                </div>
              </button>
            </form>
          @empty
            <div class="notif-dd-empty">No notifications yet.</div>
          @endforelse
          <div class="notif-dd-foot"><a href="{{ route('donor.notifications.index') }}">View all →</a></div>
        </div>
      </div>
      <div class="donor-pill">
        <div class="dp-av" @if($donor->profile_image) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($donor->profile_image) }}');background-size:cover;background-position:center;" @endif>
        @unless($donor->profile_image)
          {{ strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1)) }}
        @endunless
      </div>
        <span class="dp-name">{{ $donor->blood_group ?? 'N/A' }} Donor</span>
      </div>
      <form method="POST" action="{{ route('donor.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- ── ELIGIBILITY BANNER ── --}}
    @php
      $eligible   = $donor->is_eligible;
      $age        = $donor->age ?? 0;
      $weight     = $donor->weight_kg ?? 0;
      $hemo       = $donor->hemoglobin ?? 0;
      $initials   = strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1));
      $donations  = $donor->total_donations ?? 0;
      $livesImpact= $donations * 3;

      // Cooldown calculation
      $daysUntil  = 0;
      $cooldownPct= 100;
      $lastDonation = $donor->last_donation_date;
      if ($lastDonation) {
          $daysSince  = (int) floor(\Carbon\Carbon::parse($lastDonation)->diffInDays(now()));
          $daysUntil  = max(0, 56 - $daysSince);
          $cooldownPct= min(100, round(($daysSince / 56) * 100));
      }

      // Donor since
      $donorSince = $donor->created_at ? \Carbon\Carbon::parse($donor->created_at)->format('M Y') : 'N/A';
      $donorYears = $donor->created_at ? (int) floor(\Carbon\Carbon::parse($donor->created_at)->diffInYears(now())) : 0;

      // AI metric bars
      $ageBar    = $age > 0    ? min(100, round(($age / 65) * 100))    : 0;
      $weightBar = $weight > 0 ? min(100, round(($weight / 100) * 100)): 0;
      $hemoBar   = $hemo > 0   ? min(100, round(($hemo / 18) * 100))   : 0;
    @endphp

    <div class="elig-banner {{ $eligible ? 'eb-eligible' : 'eb-not' }}">
      <div class="eb-bg"><div class="ebc ebc1"></div><div class="ebc ebc2"></div></div>
      <div class="eb-icon">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
      </div>
      <div class="eb-text">
        <div class="eb-status">AI Eligibility Status</div>
        <div class="eb-title">
          {{ $eligible ? 'You are Eligible to Donate' : 'Currently Not Eligible' }}
        </div>
        <div class="eb-sub">
  {{ $modelMetrics['model'] ?? 'AI' }} model ·
  @if($donor->last_ai_check)
    Last AI check: {{ \Carbon\Carbon::parse($donor->last_ai_check)->diffForHumans() }}
  @else
    AI check: at registration
  @endif
</div>
        <div class="eb-date">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          @if($eligible)
            Next eligible donation: <strong style="color:white;margin-left:4px;">
              {{ $daysUntil === 0 ? 'Available Now' : 'In '.$daysUntil.' days' }}
            </strong>
          @else
            Reason: Age, weight or hemoglobin below required threshold
          @endif
        </div>
      </div>
      <div class="eb-right">
        <!-- <div class="eb-score">{{ $eligible ? '95' : '—' }}<span style="font-size:1.5rem">{{ $eligible ? '%' : '' }}</span></div>
        <div class="eb-score-label">{{ $eligible ? 'AI Confidence' : 'Not Eligible' }}</div> -->
        <div class="eb-score">
  {{ $donor->ai_confidence > 0 ? number_format($donor->ai_confidence, 1) : '—' }}
  <span style="font-size:1.5rem">{{ $donor->ai_confidence > 0 ? '%' : '' }}</span>
</div>
<div class="eb-score-label">AI Confidence</div>
      </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-row">
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-r"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
          <span class="sc-badge sb-g">All time</span>
        </div>
        <div class="sc-num">{{ $donations }}</div>
        <div class="sc-label">Total Donations</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-g"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
          <span class="sc-badge sb-g">Lives saved</span>
        </div>
        <div class="sc-num">{{ $livesImpact }}</div>
        <div class="sc-label">Lives Impacted</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-b"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
          <span class="sc-badge {{ $daysUntil === 0 ? 'sb-g' : 'sb-a' }}">
            {{ $daysUntil === 0 ? 'Ready now' : 'Cooldown' }}
          </span>
        </div>
        <div class="sc-num">{{ $daysUntil }}</div>
        <div class="sc-label">Days Until Available</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-a"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
          <span class="sc-badge sb-a">Member</span>
        </div>
        <div class="sc-num">{{ $donorYears }}<span style="font-size:1rem;font-family:'DM Sans',sans-serif;">yr</span></div>
        <div class="sc-label">Donor Since {{ $donorSince }}</div>
      </div>
    </div>

    <div class="grid-main">

      {{-- ── LEFT COLUMN ── --}}
      <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- AI HEALTH BREAKDOWN --}}
        <div class="card" id="ai-health" style="scroll-margin-top:74px;">
          <div class="card-hd">
            <div>
              <div class="card-t">AI Health Breakdown</div>
              <div class="card-s">Factors used in your eligibility prediction</div>
            </div>
            <span class="sc-badge {{ $eligible ? 'sb-g' : 'sb-r' }}" style="font-size:0.68rem;padding:3px 10px;border-radius:20px;">
              {{ $eligible ? 'Eligible' : 'Not Eligible' }}
            </span>
          </div>

          <div class="ai-breakdown">
            <div class="ai-metric">
              <div class="am-val">{{ $age > 0 ? $age : '—' }}</div>
              <div class="am-label">Age (years)</div>
              <div class="am-bar"><div class="am-fill {{ $age >= 18 ? 'fill-g' : 'fill-r' }}" style="width:{{ $ageBar }}%"></div></div>
              <div class="am-status {{ $age >= 18 ? 'st-ok' : 'st-warn' }}">
                {{ $age >= 18 ? '✓ Meets criteria (18+)' : '✗ Must be 18+' }}
              </div>
            </div>
            <div class="ai-metric">
              <div class="am-val">{{ $weight > 0 ? $weight : '—' }}<span style="font-size:0.9rem;font-family:'DM Sans',sans-serif;font-weight:300">{{ $weight > 0 ? 'kg' : '' }}</span></div>
              <div class="am-label">Weight</div>
              <div class="am-bar"><div class="am-fill {{ $weight >= 50 ? 'fill-g' : 'fill-r' }}" style="width:{{ $weightBar }}%"></div></div>
              <div class="am-status {{ $weight >= 50 ? 'st-ok' : 'st-warn' }}">
                {{ $weight >= 50 ? '✓ Meets criteria (50+)' : '✗ Must be 50+ kg' }}
              </div>
            </div>
            <div class="ai-metric">
              <div class="am-val">{{ $hemo > 0 ? number_format($hemo,1) : '—' }}</div>
              <div class="am-label">Hemoglobin (g/dL)</div>
              <div class="am-bar"><div class="am-fill {{ $hemo >= 12 ? 'fill-g' : 'fill-r' }}" style="width:{{ $hemoBar }}%"></div></div>
              <div class="am-status {{ $hemo >= 12 ? 'st-ok' : 'st-warn' }}">
                {{ $hemo >= 12 ? '✓ Meets criteria (12+)' : '✗ Must be 12+ g/dL' }}
              </div>
            </div>
          </div>

          {{-- COOLDOWN BAR --}}
          <div class="cooldown-wrap">
            <div class="cd-top">
              <span class="cd-label">56-Day Recovery Period</span>
              <span class="cd-days" style="color:{{ $daysUntil === 0 ? 'var(--green)' : 'var(--amber)' }}">
                {{ $daysUntil === 0 ? '✓ Completed — Available now' : $daysUntil.' days remaining' }}
              </span>
            </div>
            <div class="cd-track">
              <div class="cd-fill" style="width:{{ $cooldownPct }}%"></div>
            </div>
            <div class="cd-marks">
              <span>Last donation: {{ $lastDonation ? \Carbon\Carbon::parse($lastDonation)->format('d M Y') : 'Never' }}</span>
              <span>Day 28</span>
              <span>Day 56 {{ $daysUntil === 0 ? '✓' : '' }}</span>
            </div>
          </div>

          <div style="display:flex;gap:10px;">
            <div style="flex:1;background:{{ $eligible ? 'var(--green-bg)' : 'var(--warning-light)' }};border:1px solid {{ $eligible ? 'var(--green-b)' : 'var(--border)' }};border-radius:9px;padding:0.85rem 1rem;display:flex;align-items:center;gap:8px;">
              <svg width="16" height="16" fill="none" stroke="{{ $eligible ? 'var(--green)' : 'var(--warning)' }}" stroke-width="2" viewBox="0 0 24 24">
                @if($eligible)
                  <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                @else
                  <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                @endif
              </svg>
              <div>
                <div style="font-size:0.78rem;font-weight:500;color:{{ $eligible ? 'var(--green)' : 'var(--warning)' }};">
                  {{ $eligible ? 'All criteria met' : 'Criteria not met' }}
                </div>
                <div style="font-size:0.7rem;color:{{ $eligible ? '#166534' : 'var(--warning-dark)' }};">
                  {{ $eligible ? 'Ready to donate today' : 'Update your health profile' }}
                </div>
              </div>
            </div>
            <div style="flex:1;background:var(--blue-bg);border:1px solid var(--blue-b);border-radius:9px;padding:0.85rem 1rem;display:flex;align-items:center;gap:8px;">
              <svg width="16" height="16" fill="none" stroke="var(--blue)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
              <div>
                <div style="font-size:0.78rem;font-weight:500;color:var(--blue);">Model: {{ $modelMetrics['model'] ?? 'unavailable' }}</div>
                <div style="font-size:0.7rem;color:#1E40AF;">
                  @if(!empty($modelMetrics['accuracy']))
                    {{ number_format($modelMetrics['accuracy'], 2) }}% system accuracy
                  @else
                    Accuracy not available
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- UPCOMING APPOINTMENT --}}
        <div class="card" id="appointments" style="scroll-margin-top:74px;">
          <div class="card-hd">
            <div>
              <div class="card-t">Upcoming Appointment</div>
              <div class="card-s">@if($upcomingAppointment) {{ $upcomingAppointment->statusLabel() }} @else No appointment booked @endif</div>
            </div>
            <a href="{{ route('donor.appointments.index') }}" class="card-action">Manage appointments →</a>
          </div>
          @if($upcomingAppointment)
            <div class="hist-list">
              <div class="hist-item">
                <div class="hist-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                <div class="hist-info">
                  <div class="hist-t">{{ $upcomingAppointment->hospital?->name ?? 'Hospital' }}</div>
                  <div class="hist-s">{{ $upcomingAppointment->bloodRequest?->blood_group }} donation</div>
                </div>
                <div class="hist-right">
                  <div class="hist-date">{{ $upcomingAppointment->appointment_date->format('d M Y') }} · {{ $upcomingAppointment->appointment_time }}</div>
                  <span class="badge {{ $upcomingAppointment->status === 'approved' ? 'b-ok' : '' }}">{{ $upcomingAppointment->statusLabel() }}</span>
                </div>
              </div>
            </div>
          @else
            <div class="empty-state">
              No upcoming appointment. Respond "Available" to a blood request, then book an appointment from the Appointments page.
            </div>
          @endif
        </div>

        {{-- DONATION HISTORY --}}
        <div class="card" id="donation-history" style="scroll-margin-top:74px;">
          <div class="card-hd">
            <div>
              <div class="card-t">Donation History</div>
              <div class="card-s">{{ $donations }} completed donation{{ $donations !== 1 ? 's' : '' }}</div>
            </div>
          </div>
          @if($donor->donations->count() > 0)
            <div class="hist-list">
              @foreach($donor->donations as $d)
              <div class="hist-item">
                <div class="hist-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                <div class="hist-info">
                  <div class="hist-t">Whole Blood Donation</div>
                  <div class="hist-s">{{ $d->blood_group ?? $donor->blood_group ?? 'N/A' }} · {{ $d->donation_center ?? 'NBTS' }}</div>
                </div>
                <div class="hist-right">
                  <div class="hist-date">{{ $d->donation_date->format('d M Y') }}</div>
                  <span class="badge b-ok">Completed</span>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <div class="empty-state">
              No recorded donations yet. Once you donate, your care team will log it here.
            </div>
          @endif
        </div>

      </div>

      {{-- ── RIGHT COLUMN ── --}}
      <div class="right-col">

        {{-- PROFILE CARD --}}
        <div class="profile-card">
          <div class="pc-top">
            <div class="pc-circles"><div class="pcc pcc1"></div><div class="pcc pcc2"></div></div>
            <div class="pc-avatar" @if($donor->profile_image) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($donor->profile_image) }}');background-size:cover;background-position:center;" @endif>
              @unless($donor->profile_image)
                {{ $initials }}
              @endunless
            </div>
            <div class="pc-name">{{ $donor->full_name }}</div>
            <div><span class="pc-blood">{{ $donor->blood_group ?? 'N/A' }} Donor</span></div>
          </div>
          <div class="pc-body">
            <div class="pc-row">
              <span class="pc-key">Age</span>
              <span class="pc-val">{{ $age > 0 ? $age.' years' : 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Weight</span>
              <span class="pc-val">{{ $weight > 0 ? $weight.' kg' : 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Hemoglobin</span>
              <span class="pc-val">{{ $hemo > 0 ? number_format($hemo,1).' g/dL' : 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Gender</span>
              <span class="pc-val">{{ $donor->gender ?? 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">City</span>
              <span class="pc-val">{{ $donor->city ?? 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">District</span>
              <span class="pc-val">{{ $donor->district ?? 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Email</span>
              <span class="pc-val" style="font-size:0.72rem;">{{ $donor->email }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Phone</span>
              <span class="pc-val">{{ $donor->phone ?? 'Not set' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Donations</span>
              <span class="pc-val">{{ $donations }} completed</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Member since</span>
              <span class="pc-val">{{ $donorSince }}</span>
            </div>
            <a href="{{ route('donor.profile.edit') }}" class="edit-btn" style="display:block;text-align:center;text-decoration:none;">Edit Profile</a>
          </div>
        </div>

        {{-- NOTIFICATIONS --}}
        <div class="card" id="notifications" style="scroll-margin-top:74px;">
          <div class="card-hd">
            <div><div class="card-t">Notifications</div></div>
            <a href="{{ route('donor.notifications.index') }}" class="card-action">View all →</a>
          </div>
          <div class="notif-list">
            @forelse($notifications as $n)
              <form method="POST" action="{{ route('donor.notifications.read', $n->id) }}" style="margin:0;">
                @csrf
                <button type="submit" class="notif-item" style="width:100%;text-align:left;background:none;border:1px solid var(--border);cursor:pointer;font-family:inherit;{{ $n->read_at ? 'opacity:0.65;' : '' }}">
                  <div class="notif-dot-wrap"><div class="n-dot {{ $n->dotClass() }}"></div></div>
                  <div>
                    <div class="notif-text"><strong>{{ $n->title }}</strong> — {{ $n->message }}</div>
                    <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
                  </div>
                </button>
              </form>
            @empty
              <div class="notif-item" style="opacity:0.65;">
                <div class="notif-dot-wrap"><div class="n-dot nd-b"></div></div>
                <div>
                  <div class="notif-text">Welcome to <strong>LifeLink</strong>! Your donor profile has been created successfully.</div>
                  <div class="notif-time">{{ $donorSince }}</div>
                </div>
              </div>
            @endforelse
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Donor dashboard, Admin dashboard, Hospital dashboard --}}
@include('components.chatbot')

<script>
function toggleNotifDropdown(e) {
  e.stopPropagation();
  document.getElementById('notifDropdown').classList.toggle('open');
}
document.addEventListener('click', function (e) {
  var dd = document.getElementById('notifDropdown');
  if (dd && !dd.contains(e.target) && !e.target.closest('.notif')) {
    dd.classList.remove('open');
  }
});
</script>