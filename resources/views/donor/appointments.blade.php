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

  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .tb-sub{font-size:0.75rem;color:var(--muted);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  .content{padding:1.75rem 2rem;max-width:920px;}
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  .section-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--text);margin:1.75rem 0 0.9rem;}
  .section-title:first-child{margin-top:0;}
  .section-sub{font-size:0.78rem;color:var(--muted);margin-top:-0.6rem;margin-bottom:0.9rem;}

  .apt-list{display:flex;flex-direction:column;gap:12px;}
  .apt-card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.1rem 1.4rem;}
  .apt-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;}
  .apt-blood{width:48px;height:48px;border-radius:12px;background:var(--primary-light);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:1rem;flex-shrink:0;}
  .apt-info{flex:1;}
  .apt-hosp{font-size:0.93rem;font-weight:600;color:var(--text);}
  .apt-meta{font-size:0.78rem;color:var(--muted);margin-top:2px;}
  .apt-status{display:inline-block;font-size:0.68rem;font-weight:600;padding:4px 11px;border-radius:20px;text-transform:uppercase;letter-spacing:0.03em;}
  .st-pending{background:var(--blue-bg);color:var(--blue);}
  .st-approved{background:var(--green-bg);color:var(--green);}
  .st-rejected{background:var(--amber-bg);color:var(--amber);}
  .st-completed{background:var(--secondary-light);color:var(--secondary-dark);}
  .st-cancelled{background:var(--gray);color:var(--muted);}
  .apt-notes{font-size:0.8rem;color:var(--muted);line-height:1.5;margin-top:0.75rem;padding:0.7rem;background:var(--off);border-radius:8px;}
  .apt-actions{margin-top:0.85rem;display:flex;gap:8px;}
  .cancel-btn{padding:0.5rem 1rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.78rem;font-weight:500;cursor:pointer;border:1px solid var(--gray-b);background:white;color:var(--muted);}
  .cancel-btn:hover{border-color:var(--warning);color:var(--warning);}

  .book-form{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;margin-top:0.9rem;padding-top:0.9rem;border-top:1px dashed var(--gray-b);}
  .book-field{display:flex;flex-direction:column;gap:4px;}
  .book-field label{font-size:0.7rem;color:var(--muted);font-weight:500;}
  .book-field input{padding:0.5rem 0.7rem;border:1px solid var(--gray-b);border-radius:7px;font-family:'DM Sans',sans-serif;font-size:0.82rem;color:var(--text);}
  .book-btn{padding:0.55rem 1.2rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:500;cursor:pointer;border:none;background:var(--primary);color:white;}
  .book-btn:hover{background:var(--primary-dark);}

  .empty-state{text-align:center;padding:2.5rem 1.5rem;background:white;border:1px solid var(--border);border-radius:14px;}
  .empty-state svg{width:42px;height:42px;stroke:var(--gray-b);fill:none;stroke-width:1.5;margin-bottom:0.85rem;}
  .empty-state h3{font-family:'Playfair Display',serif;font-size:1rem;color:var(--text);margin-bottom:0.4rem;}
  .empty-state p{font-size:0.8rem;color:var(--muted);line-height:1.6;}
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
    <a href="{{ route('donor.appointments.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">{{ __('Appointments') }}</span>
    </a>
    <a href="{{ route('donor.dashboard') }}#donation-history" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">{{ __('History') }}</span>
    </a>
    <a href="{{ route('donor.notifications.index') }}" class="sb-item">
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
        <div class="tb-title">{{ __('Donation Appointments') }}</div>
        <div class="tb-sub">{{ __('Book, track, and manage your donation appointments') }}</div>
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

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-title">{{ __('Book an Appointment') }}</div>
    <div class="section-sub">{{ __('Only requests you responded "Available" to can be booked.') }}</div>
    @if($bookableRequests->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <h3>{{ __('No Bookable Requests Right Now') }}</h3>
        <p>{{ __('Respond "Available" to a blood request from the Blood Requests page to unlock booking here.') }}</p>
      </div>
    @else
      <div class="apt-list">
        @foreach($bookableRequests as $r)
          <div class="apt-card">
            <div class="apt-top">
              <div class="apt-blood">{{ $r->blood_group }}</div>
              <div class="apt-info">
                <div class="apt-hosp">{{ $r->hospital?->name ?? 'Hospital' }}</div>
                <div class="apt-meta">{{ $r->units_needed }} unit{{ $r->units_needed > 1 ? 's' : '' }} {{ __('needed') }} @if($r->ward) · {{ $r->ward }} @endif</div>
              </div>
            </div>
            <form method="POST" action="{{ route('donor.appointments.store', $r->id) }}" class="book-form">
              @csrf
              <div class="book-field">
                <label>{{ __('Date') }}</label>
                <input type="date" name="appointment_date" min="{{ now()->format('Y-m-d') }}" required>
              </div>
              <div class="book-field">
                <label>{{ __('Time') }}</label>
                <input type="time" name="appointment_time" required>
              </div>
              <div class="book-field" style="flex:1;min-width:160px;">
                <label>{{ __('Notes (optional)') }}</label>
                <input type="text" name="notes" placeholder="{{ __('Any note for the hospital') }}">
              </div>
              <button type="submit" class="book-btn">{{ __('Book Appointment') }}</button>
            </form>
          </div>
        @endforeach
      </div>
    @endif

    <div class="section-title">{{ __('Upcoming Appointments') }}</div>
    @if($upcoming->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <h3>{{ __('No Upcoming Appointments') }}</h3>
        <p>{{ __('Once you book an appointment, it will appear here pending hospital approval.') }}</p>
      </div>
    @else
      <div class="apt-list">
        @foreach($upcoming as $a)
          <div class="apt-card">
            <div class="apt-top">
              <div class="apt-blood">{{ $a->bloodRequest?->blood_group }}</div>
              <div class="apt-info">
                <div class="apt-hosp">{{ $a->hospital?->name ?? 'Hospital' }}</div>
                <div class="apt-meta">{{ $a->appointment_date->format('d M Y') }} {{ __('at') }} {{ $a->appointment_time }}</div>
              </div>
              <span class="apt-status st-{{ $a->status }}">{{ $a->statusLabel() }}</span>
            </div>
            @if($a->notes)
              <div class="apt-notes">{{ $a->notes }}</div>
            @endif
            @if($a->status === 'pending' || $a->status === 'approved')
              <div class="apt-actions">
                <form method="POST" action="{{ route('donor.appointments.cancel', $a->id) }}">
                  @csrf
                  <button type="submit" class="cancel-btn">{{ __('Cancel Appointment') }}</button>
                </form>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

    <div class="section-title">{{ __('Appointment History') }}</div>
    @if($history->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/></svg>
        <h3>{{ __('No Past Appointments') }}</h3>
        <p>{{ __('Completed, rejected, and cancelled appointments will show up here.') }}</p>
      </div>
    @else
      <div class="apt-list">
        @foreach($history as $a)
          <div class="apt-card">
            <div class="apt-top">
              <div class="apt-blood">{{ $a->bloodRequest?->blood_group }}</div>
              <div class="apt-info">
                <div class="apt-hosp">{{ $a->hospital?->name ?? 'Hospital' }}</div>
                <div class="apt-meta">{{ $a->appointment_date->format('d M Y') }} {{ __('at') }} {{ $a->appointment_time }}</div>
              </div>
              <span class="apt-status st-{{ $a->status }}">{{ $a->statusLabel() }}</span>
            </div>
            @if($a->notes)
              <div class="apt-notes">{{ $a->notes }}</div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>

@include('components.chatbot')
