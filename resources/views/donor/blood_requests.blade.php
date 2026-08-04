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
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* REQUEST CARD */
  .req-list{display:flex;flex-direction:column;gap:12px;}
  .req-card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .req-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:0.85rem;}
  .req-blood{width:52px;height:52px;border-radius:12px;background:var(--primary-light);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:1.1rem;flex-shrink:0;}
  .req-info{flex:1;}
  .req-hosp{font-size:0.95rem;font-weight:600;color:var(--text);}
  .req-meta{font-size:0.78rem;color:var(--muted);margin-top:2px;}
  .req-badges{display:flex;gap:6px;margin-top:0.6rem;flex-wrap:wrap;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;}
  .b-std{background:var(--gray);color:var(--muted);}
  .b-urg{background:var(--amber-bg);color:var(--amber);}
  .b-crit{background:var(--warning);color:white;}
  .b-district{background:var(--blue-bg);color:var(--blue);}
  .req-notes{font-size:0.8rem;color:var(--muted);line-height:1.5;margin:0.85rem 0;padding:0.75rem;background:var(--off);border-radius:8px;}

  .respond-row{display:flex;gap:10px;margin-top:0.5rem;}
  .respond-btn{flex:1;padding:0.6rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:500;cursor:pointer;border:1px solid var(--gray-b);background:white;color:var(--muted);transition:all 0.18s;}
  .respond-btn.yes{background:var(--green);color:white;border-color:var(--green);}
  .respond-btn.yes:hover{background:#15803D;}
  .respond-btn.no:hover{border-color:var(--warning);color:var(--warning);}

  .status-note{display:flex;align-items:center;gap:8px;padding:0.65rem 0.9rem;border-radius:8px;font-size:0.82rem;margin-top:0.5rem;}
  .status-available{background:var(--green-bg);color:var(--green);}
  .status-not-available{background:var(--gray);color:var(--muted);}
  .status-note a{color:inherit;font-weight:500;text-decoration:underline;margin-left:auto;cursor:pointer;}

  /* EMPTY */
  .empty-state{text-align:center;padding:3rem 1.5rem;background:white;border:1px solid var(--border);border-radius:14px;}
  .empty-state svg{width:48px;height:48px;stroke:var(--gray-b);fill:none;stroke-width:1.5;margin-bottom:1rem;}
  .empty-state h3{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--text);margin-bottom:0.5rem;}
  .empty-state p{font-size:0.82rem;color:var(--muted);line-height:1.6;}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('donor.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tip">Dashboard</span>
    </a>
    <a href="{{ route('donor.requests.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tip">Blood Requests</span>
    </a>
    <a href="{{ route('donor.appointments.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">Appointments</span>
    </a>
    <a href="{{ route('donor.dashboard') }}#donation-history" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">History</span>
    </a>
    <a href="{{ route('donor.profile.edit') }}" class="sb-item">
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

{{-- MAIN --}}
<div class="main">

  <div class="topbar">
    <div class="tb-left">
      <div>
        <div class="tb-title">Blood Requests Near You</div>
        <div class="tb-sub">Requests compatible with your blood group, {{ $donor->blood_group ?? 'N/A' }}</div>
      </div>
    </div>
    <div class="tb-right">
      <form method="POST" action="{{ route('donor.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(!$donor->is_eligible)
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <h3>Blood Requests Are Hidden While You're Not Eligible</h3>
        <p>Your AI eligibility check currently shows you as not eligible to donate, so matching requests are not shown here.<br>
        Update your health profile once your situation changes, and this page will update automatically.</p>
      </div>
    @elseif($requests->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        <h3>No Matching Requests Right Now</h3>
        <p>There are currently no open blood requests compatible with your blood group ({{ $donor->blood_group }}).<br>
        Check back later — this list updates as hospitals submit new requests.</p>
      </div>
    @else
      <div class="req-list">
        @foreach($requests as $r)
          @php
            $myResponse = $myResponses->get($r->id);
            $sameDistrict = $r->hospital?->district === $donor->district;
            $badgeClass = $r->urgency === 'critical' ? 'b-crit' : ($r->urgency === 'urgent' ? 'b-urg' : 'b-std');
          @endphp
          <div class="req-card">
            <div class="req-top">
              <div class="req-blood">{{ $r->blood_group }}</div>
              <div class="req-info">
                <div class="req-hosp">{{ $r->hospital?->name ?? 'Hospital' }}</div>
                <div class="req-meta">
                  {{ $r->units_needed }} unit{{ $r->units_needed > 1 ? 's' : '' }} needed
                  @if($r->ward) · {{ $r->ward }} @endif
                  @if($r->required_by) · Needed by {{ \Carbon\Carbon::parse($r->required_by)->format('d M Y') }} @endif
                </div>
                <div class="req-badges">
                  <span class="badge {{ $badgeClass }}">{{ ucfirst($r->urgency) }}</span>
                  @if($sameDistrict)
                    <span class="badge b-district">Your district</span>
                  @elseif($r->hospital?->district)
                    <span class="badge b-std">{{ $r->hospital->district }}</span>
                  @endif
                </div>
              </div>
            </div>

            @if($r->notes)
              <div class="req-notes">{{ $r->notes }}</div>
            @endif

            @if($myResponse)
              <div class="status-note {{ $myResponse->status === 'available' ? 'status-available' : 'status-not-available' }}">
                @if($myResponse->status === 'available')
                  You said you're <strong>available</strong> for this request.
                @else
                  You said you're <strong>not available</strong> for this request.
                @endif
                <a onclick="document.getElementById('resp-forms-{{ $r->id }}').style.display='flex'; this.parentElement.style.display='none';">Change response</a>
              </div>
              <div id="resp-forms-{{ $r->id }}" class="respond-row" style="display:none;">
                <form method="POST" action="{{ route('donor.requests.respond', $r->id) }}" style="flex:1;">
                  @csrf
                  <input type="hidden" name="status" value="available">
                  <button type="submit" class="respond-btn yes" style="width:100%;">I'm Available</button>
                </form>
                <form method="POST" action="{{ route('donor.requests.respond', $r->id) }}" style="flex:1;">
                  @csrf
                  <input type="hidden" name="status" value="not_available">
                  <button type="submit" class="respond-btn no" style="width:100%;">Not Available</button>
                </form>
              </div>
            @else
              <div class="respond-row">
                <form method="POST" action="{{ route('donor.requests.respond', $r->id) }}" style="flex:1;">
                  @csrf
                  <input type="hidden" name="status" value="available">
                  <button type="submit" class="respond-btn yes" style="width:100%;">I'm Available</button>
                </form>
                <form method="POST" action="{{ route('donor.requests.respond', $r->id) }}" style="flex:1;">
                  @csrf
                  <input type="hidden" name="status" value="not_available">
                  <button type="submit" class="respond-btn no" style="width:100%;">Not Available</button>
                </form>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>

@include('components.chatbot')
