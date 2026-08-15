<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
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
  .topbar-left{display:flex;align-items:center;gap:12px;}
  .back-btn{display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--muted);text-decoration:none;padding:0.4rem 0.9rem;border:1px solid var(--gray-b);border-radius:7px;transition:all 0.2s;}
  .back-btn:hover{border-color:var(--primary);color:var(--primary);}
  .back-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .topbar-right{display:flex;align-items:center;gap:10px;}
  .admin-pill{display:flex;align-items:center;gap:8px;background:var(--primary-light);border:1px solid var(--border);border-radius:20px;padding:0.35rem 0.9rem 0.35rem 0.5rem;}
  .admin-avatar{width:24px;height:24px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;color:white;}
  .admin-name{font-size:0.78rem;font-weight:500;color:var(--primary-dark);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* PAGE LAYOUT */
  .page-grid{display:grid;grid-template-columns:300px 1fr;gap:16px;}

  /* PROFILE CARD */
  .profile-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .pc-top{background:var(--blue);padding:2rem 1.5rem;text-align:center;position:relative;}
  .pc-bg{position:absolute;inset:0;pointer-events:none;}
  .pc-circle{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.15);}
  .pcc1{width:180px;height:180px;top:-50px;right:-40px;}
  .pcc2{width:120px;height:120px;bottom:-30px;left:-30px;}
  .pc-avatar{width:72px;height:72px;border-radius:14px;background:rgba(255,255,255,0.2);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:white;margin:0 auto 0.85rem;position:relative;z-index:2;}
  .pc-name{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:white;position:relative;z-index:2;margin-bottom:4px;}
  .pc-email{font-size:0.75rem;color:rgba(255,255,255,0.7);position:relative;z-index:2;margin-bottom:10px;}
  .pc-body{padding:1.1rem 1.25rem;}
  .pc-row{display:flex;align-items:center;justify-content:space-between;padding:0.55rem 0;border-bottom:1px solid rgba(29,78,216,0.06);}
  .pc-row:last-child{border-bottom:none;}
  .pc-key{font-size:0.75rem;color:var(--muted);}
  .pc-val{font-size:0.8rem;font-weight:500;color:var(--text);text-align:right;}

  /* ACTION BUTTONS */
  .action-btns{display:flex;flex-direction:column;gap:8px;padding:0 1.25rem 1.25rem;}
  .action-btn{width:100%;padding:0.65rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;}
  .action-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
  .ab-toggle-on{background:var(--green-bg);color:var(--green);border:1px solid var(--green-b);}
  .ab-toggle-on:hover{background:var(--green);color:white;}
  .ab-toggle-off{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
  .ab-toggle-off:hover{background:#92400E;color:white;}
  .ab-delete{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;}
  .ab-delete:hover{background:#92400E;color:white;}
  .ab-back{background:var(--gray);color:var(--muted);border:1px solid var(--gray-b);}
  .ab-back:hover{border-color:var(--primary);color:var(--primary);}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;margin-bottom:14px;}
  .card-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}

  /* INFO GRID */
  .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .info-item{background:var(--gray);border-radius:9px;padding:0.9rem 1rem;}
  .info-key{font-size:0.7rem;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:4px;}
  .info-val{font-size:0.9rem;font-weight:500;color:var(--text);}
  .info-val.large{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;}

  /* BADGE */
  .badge{display:inline-block;font-size:0.72rem;font-weight:500;padding:4px 10px;border-radius:20px;}
  .b-verified{background:var(--green-bg);color:var(--green);}
  .b-pending{background:var(--amber-bg);color:#92400E;}
  .b-req-pending{background:var(--blue-bg);color:var(--blue);}
  .b-req-fulfilled{background:var(--green-bg);color:var(--green);}
  .b-req-cancelled{background:var(--gray);color:var(--muted);}
  .b-urgency-standard{background:var(--gray);color:var(--muted);}
  .b-urgency-urgent{background:var(--amber-bg);color:#92400E;}
  .b-urgency-critical{background:var(--amber-bg);color:#92400E;}

  /* EMPTY */
  .notes-box{background:var(--gray);border:1px solid var(--gray-b);border-radius:9px;padding:1rem;font-size:0.82rem;color:var(--muted);}
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
    <a href="{{ route('admin.hospitals.index') }}" class="sb-item active">
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
      <a href="{{ route('admin.hospitals.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        {{ __('Back to Hospitals') }}
      </a>
      <div class="topbar-title">{{ __('Hospital Profile') }}</div>
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

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    @php
      $initials = strtoupper(substr($hospital->name,0,2));
    @endphp

    <div class="page-grid">

      {{-- LEFT — PROFILE CARD --}}
      <div>
        <div class="profile-card">
          <div class="pc-top">
            <div class="pc-bg">
              <div class="pc-circle pcc1"></div>
              <div class="pc-circle pcc2"></div>
            </div>
            <div class="pc-avatar">{{ $initials }}</div>
            <div class="pc-name">{{ $hospital->name }}</div>
            <div class="pc-email">{{ $hospital->email }}</div>
          </div>
          <div class="pc-body">
            <div class="pc-row">
              <span class="pc-key">{{ __('ID') }}</span>
              <span class="pc-val">#{{ $hospital->id }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('Registration ID') }}</span>
              <span class="pc-val">{{ $hospital->registration_id ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('Phone') }}</span>
              <span class="pc-val">{{ $hospital->phone ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('City') }}</span>
              <span class="pc-val">{{ $hospital->city ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('District') }}</span>
              <span class="pc-val">{{ $hospital->district ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('Address') }}</span>
              <span class="pc-val">{{ $hospital->address ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('Registered') }}</span>
              <span class="pc-val">{{ $hospital->created_at->format('d M Y') }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">{{ __('Status') }}</span>
              <span class="badge {{ $hospital->is_verified ? 'b-verified' : 'b-pending' }}">
                {{ $hospital->is_verified ? __('Verified') : __('Pending') }}
              </span>
            </div>
          </div>

          {{-- ACTION BUTTONS --}}
          <div class="action-btns">
            {{-- TOGGLE VERIFICATION --}}
            <form method="POST" action="{{ route('admin.hospitals.toggle', $hospital->id) }}" style="margin:0;">
              @csrf
              <button type="submit"
                      class="action-btn {{ $hospital->is_verified ? 'ab-toggle-off' : 'ab-toggle-on' }}"
                      onclick="return confirm('{{ $hospital->is_verified ? __('Mark as unverified?') : __('Verify this hospital?') }}')">
                <svg viewBox="0 0 24 24">
                  @if($hospital->is_verified)
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                  @else
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                  @endif
                </svg>
                {{ $hospital->is_verified ? __('Mark as Unverified') : __('Verify Hospital') }}
              </button>
            </form>

            {{-- DELETE --}}
            <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital->id) }}" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="action-btn ab-delete"
                      onclick="return confirm('{{ __('Permanently delete') }} {{ $hospital->name }}? {{ __('This will also delete all of their blood requests. This cannot be undone.') }}')">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                {{ __('Delete Hospital') }}
              </button>
            </form>

            <a href="{{ route('admin.hospitals.index') }}" class="action-btn ab-back">
              <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              {{ __('Back to List') }}
            </a>
          </div>
        </div>
      </div>

      {{-- RIGHT COLUMN --}}
      <div>

        {{-- OVERVIEW --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">{{ __('Overview') }}</div>
              <div class="card-s">{{ __('Blood request activity for this hospital') }}</div>
            </div>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-key">{{ __('Total Requests') }}</div>
              <div class="info-val large">{{ $hospital->bloodRequests->count() }}</div>
            </div>
            <div class="info-item">
              <div class="info-key">{{ __('Pending') }}</div>
              <div class="info-val large">{{ $hospital->bloodRequests->where('status', 'pending')->count() }}</div>
            </div>
            <div class="info-item">
              <div class="info-key">{{ __('Fulfilled') }}</div>
              <div class="info-val large">{{ $hospital->bloodRequests->where('status', 'fulfilled')->count() }}</div>
            </div>
            <div class="info-item">
              <div class="info-key">{{ __('Critical Requests') }}</div>
              <div class="info-val large">{{ $hospital->bloodRequests->where('urgency', 'critical')->count() }}</div>
            </div>
          </div>
        </div>

        {{-- BLOOD REQUEST HISTORY --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">{{ __('Blood Request History') }}</div>
              <div class="card-s">{{ $hospital->bloodRequests->count() }} {{ $hospital->bloodRequests->count() !== 1 ? __('recorded requests') : __('recorded request') }}</div>
            </div>
          </div>

          @if($hospital->bloodRequests->count() > 0)
            <div style="display:flex;flex-direction:column;">
              @foreach($hospital->bloodRequests as $req)
                <div class="pc-row">
                  <span class="pc-key">
                    {{ $req->created_at->format('d M Y') }} ·
                    <span class="badge {{ 'b-urgency-' . $req->urgency }}">{{ ucfirst($req->urgency) }}</span>
                  </span>
                  <span class="pc-val">
                    {{ $req->blood_group }} · {{ $req->units_needed }} {{ $req->units_needed !== 1 ? __('units') : __('unit') }} ·
                    <span class="badge {{ 'b-req-' . $req->status }}">{{ ucfirst($req->status) }}</span>
                  </span>
                </div>
              @endforeach
            </div>
          @else
            <div class="notes-box">{{ __('No blood requests submitted yet.') }}</div>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
