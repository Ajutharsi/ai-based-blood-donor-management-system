<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;
    --white:#fff;--off:#F7F3F3;--text:#1A0A0B;--muted:#6B3B40;
    --border:rgba(200,25,42,0.12);--gray:#F4F1F1;--gray-b:#E4DEDE;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:66px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR */
  .sidebar{width:var(--sb);background:#0F172A;display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(59,130,246,0.25);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:none;stroke:#93C5FD;stroke-width:1.75;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;align-items:center;flex:1;width:100%;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;position:relative;text-decoration:none;}
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
  .tb-badge{display:flex;align-items:center;gap:6px;background:var(--blue-bg);border:1px solid var(--blue-b);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.72rem;font-weight:500;color:var(--blue);}
  .tb-badge span{width:5px;height:5px;border-radius:50%;background:var(--blue);display:inline-block;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .hosp-pill{display:flex;align-items:center;gap:8px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:20px;padding:0.3rem 0.9rem 0.3rem 0.5rem;}
  .hosp-av{width:24px;height:24px;border-radius:6px;background:var(--blue);display:flex;align-items:center;justify-content:center;}
  .hosp-av svg{width:13px;height:13px;stroke:white;fill:none;stroke-width:2;}
  .hosp-name{font-size:0.78rem;font-weight:500;color:var(--blue);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .logout-btn:hover{border-color:var(--red);color:var(--red);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}
  .alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:#991B1B;}

  /* STAT ROW */
  .stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:1.5rem;}
  .sc{background:white;border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;}
  .sc-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
  .sc-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;}
  .ic-r{background:var(--red-light);} .ic-r svg{stroke:var(--red);}
  .ic-b{background:#EFF6FF);}        .ic-b svg{stroke:var(--blue);}
  .ic-g{background:var(--green-bg);} .ic-g svg{stroke:var(--green);}
  .ic-a{background:var(--amber-bg);} .ic-a svg{stroke:var(--amber);}
  .sc-icon svg{width:16px;height:16px;fill:none;stroke-width:1.75;stroke-linecap:round;}
  .sc-trend{font-size:0.68rem;font-weight:500;padding:2px 7px;border-radius:20px;}
  .t-r{background:var(--red-light);color:var(--red-dark);}
  .t-g{background:var(--green-bg);color:var(--green);}
  .t-b{background:#EFF6FF;color:var(--blue);}
  .t-a{background:var(--amber-bg);color:var(--amber);}
  .sc-num{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:3px;}
  .sc-label{font-size:0.75rem;color:var(--muted);}

  /* MAIN GRID */
  .main-grid{display:grid;grid-template-columns:1fr 340px;gap:14px;}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .card-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}
  .card-act{font-size:0.75rem;color:var(--red);text-decoration:none;cursor:pointer;}

  /* REQUEST FORM */
  .form-label{font-size:0.75rem;font-weight:500;color:var(--text);margin-bottom:8px;display:block;}
  .form-label span{color:var(--red);}
  .blood-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:7px;margin-bottom:1.25rem;}
  .bb{padding:0.55rem 0.3rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;}
  .bb:hover{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .bb.sel{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .bb-sub{font-size:0.6rem;display:block;opacity:0.65;margin-top:1px;}
  .urg-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:7px;margin-bottom:1.25rem;}
  .ub{padding:0.55rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.78rem;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;display:flex;align-items:center;justify-content:center;gap:5px;}
  .ub svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
  .ub.sel-std{border-color:var(--green);color:var(--green);background:var(--green-bg);}
  .ub.sel-urg{border-color:var(--amber);color:var(--amber);background:var(--amber-bg);}
  .ub.sel-crit{border-color:var(--red);color:var(--red-dark);background:var(--red-light);}
  .form-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:1.1rem;}
  .form-row.two{grid-template-columns:1fr 1fr;}
  .field label{display:block;font-size:0.75rem;font-weight:500;color:var(--text);margin-bottom:5px;}
  .field input,.field select,.field textarea{width:100%;padding:0.6rem 0.85rem;border:1px solid var(--gray-b);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.835rem;color:var(--text);background:white;outline:none;transition:border 0.2s;appearance:none;-webkit-appearance:none;}
  .field select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B3B40' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:2rem;}
  .field input:focus,.field select:focus{border-color:var(--red);box-shadow:0 0 0 3px rgba(200,25,42,0.07);}
  .field textarea{min-height:70px;resize:none;}
  .error-msg{font-size:0.72rem;color:var(--red);margin-top:3px;display:block;}
  .btn-req{width:100%;padding:0.8rem;border:none;border-radius:9px;background:var(--red);color:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:0.5rem;}
  .btn-req:hover{background:var(--red-dark);transform:translateY(-1px);}
  .btn-req svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2;}

  /* REQUEST HISTORY */
  .rh-list{display:flex;flex-direction:column;gap:0;}
  .rh-item{display:flex;align-items:center;gap:12px;padding:0.85rem 0;border-bottom:1px solid rgba(200,25,42,0.06);}
  .rh-item:last-child{border-bottom:none;}
  .rh-blood{width:40px;height:40px;border-radius:9px;background:var(--red-light);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;color:var(--red-dark);flex-shrink:0;}
  .rh-info{flex:1;}
  .rh-t{font-size:0.85rem;font-weight:400;color:var(--text);}
  .rh-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}
  .rh-right{text-align:right;flex-shrink:0;}
  .rh-date{font-size:0.7rem;color:var(--muted);margin-bottom:4px;}
  .badge{display:inline-block;font-size:0.67rem;font-weight:500;padding:3px 8px;border-radius:20px;}
  .b-pen{background:#EFF6FF;color:#1D4ED8;}
  .b-ful{background:var(--green-bg);color:var(--green);}
  .b-can{background:var(--gray);color:var(--muted);}
  .b-urg{background:var(--red-light);color:var(--red-dark);}
  .b-std{background:var(--gray);color:var(--muted);}
  .b-crit{background:#FEF2F2;color:#991B1B;}

  /* HOSPITAL INFO */
  .hosp-info-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .hi-top{background:#0F172A;padding:1.5rem;text-align:center;position:relative;}
  .hi-icon{width:56px;height:56px;border-radius:12px;background:rgba(59,130,246,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;}
  .hi-icon svg{width:26px;height:26px;stroke:#93C5FD;fill:none;stroke-width:1.75;}
  .hi-name{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:white;margin-bottom:4px;}
  .hi-tag{display:inline-block;background:rgba(59,130,246,0.2);color:#93C5FD;font-size:0.7rem;padding:2px 10px;border-radius:20px;}
  .hi-body{padding:1rem 1.25rem;}
  .hi-row{display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(200,25,42,0.06);}
  .hi-row:last-child{border-bottom:none;}
  .hi-key{font-size:0.75rem;color:var(--muted);}
  .hi-val{font-size:0.8rem;font-weight:500;color:var(--text);}

  /* EMPTY */
  .empty-state{text-align:center;padding:2rem;color:var(--muted);font-size:0.85rem;}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('hospital.dashboard') }}" class="sb-item active">
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
    <a href="{{ route('hospital.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">Settings</span>
    </a>
  </div>
  <div class="sb-bot">
    <div class="sb-avatar">
      {{ strtoupper(substr($hospital->name, 0, 2)) }}
    </div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <div class="tb-badge"><span></span> Hospital Portal</div>
      <div class="tb-title">{{ $hospital->name }}</div>
    </div>
    <div class="tb-right">
      <div class="hosp-pill">
        <div class="hosp-av">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
        </div>
        <span class="hosp-name">{{ $hospital->district ?? 'Hospital' }}</span>
      </div>
      <form method="POST" action="{{ route('hospital.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- STAT CARDS --}}
    <div class="stat-row">
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-r"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
          <span class="sc-trend t-r">Total</span>
        </div>
        <div class="sc-num">{{ $stats['total_requests'] }}</div>
        <div class="sc-label">Blood Requests</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-b" style="background:#EFF6FF;"><svg viewBox="0 0 24 24" style="stroke:var(--blue)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
          <span class="sc-trend t-b">Active</span>
        </div>
        <div class="sc-num">{{ $stats['pending'] }}</div>
        <div class="sc-label">Pending Requests</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-g"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
          <span class="sc-trend t-g">Done</span>
        </div>
        <div class="sc-num">{{ $stats['fulfilled'] }}</div>
        <div class="sc-label">Fulfilled</div>
      </div>
      <div class="sc">
        <div class="sc-top">
          <div class="sc-icon ic-a"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
          <span class="sc-trend t-a">Month</span>
        </div>
        <div class="sc-num">{{ $stats['this_month'] }}</div>
        <div class="sc-label">This Month</div>
      </div>
    </div>

    <div class="main-grid">

      {{-- LEFT — REQUEST FORM + HISTORY --}}
      <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- BLOOD REQUEST FORM --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">New Blood Request</div>
              <div class="card-s">AI will instantly find matched eligible donors</div>
            </div>
          </div>

          <form method="POST" action="{{ route('hospital.request.store') }}">
            @csrf

            {{-- BLOOD GROUP --}}
            <span class="form-label">Blood Group Required <span>*</span></span>
            <input type="hidden" name="blood_group" id="bloodGroupInput" value="{{ old('blood_group') }}">
            <div class="blood-grid">
              @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <button type="button"
                        class="bb {{ old('blood_group') === $bg ? 'sel' : '' }}"
                        onclick="selectBlood(this, '{{ $bg }}')">
                  {{ $bg }}<span class="bb-sub">{{ str_contains($bg,'+') ? 'positive' : 'negative' }}</span>
                </button>
              @endforeach
            </div>
            @error('blood_group')<span class="error-msg">{{ $message }}</span>@enderror

            {{-- URGENCY --}}
            <span class="form-label">Urgency Level <span>*</span></span>
            <input type="hidden" name="urgency" id="urgencyInput" value="{{ old('urgency','standard') }}">
            <div class="urg-row">
              <button type="button" class="ub {{ old('urgency','standard') === 'standard' ? 'sel-std' : '' }}"
                      onclick="selectUrgency(this,'standard','sel-std')">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Standard
              </button>
              <button type="button" class="ub {{ old('urgency') === 'urgent' ? 'sel-urg' : '' }}"
                      onclick="selectUrgency(this,'urgent','sel-urg')">
                <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Urgent
              </button>
              <button type="button" class="ub {{ old('urgency') === 'critical' ? 'sel-crit' : '' }}"
                      onclick="selectUrgency(this,'critical','sel-crit')">
                <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>Critical
              </button>
            </div>
            @error('urgency')<span class="error-msg">{{ $message }}</span>@enderror

            {{-- UNITS + WARD + DATE --}}
            <div class="form-row">
              <div class="field">
                <label>Units Needed <span style="color:var(--red)">*</span></label>
                <input type="number" name="units_needed"
                       value="{{ old('units_needed', 1) }}"
                       min="1" max="20" placeholder="e.g. 2">
                @error('units_needed')<span class="error-msg">{{ $message }}</span>@enderror
              </div>
              <div class="field">
                <label>Ward / Department</label>
                <select name="ward">
                  @foreach(['' => 'Select ward','ICU' => 'ICU','Surgery' => 'Surgery','Maternity' => 'Maternity','Trauma' => 'Trauma','Paediatric' => 'Paediatric','Oncology' => 'Oncology','General' => 'General'] as $val => $label)
                    <option value="{{ $val }}" {{ old('ward') === $val ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field">
                <label>Required By</label>
                <input type="date" name="required_by" value="{{ old('required_by') }}">
              </div>
            </div>

            {{-- NOTES --}}
            <div class="field" style="margin-bottom:1.1rem;">
              <label>Special Notes</label>
              <textarea name="notes" placeholder="Patient condition, special requirements...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn-req">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
              Find Matched Donors with AI
            </button>
          </form>
        </div>

        {{-- RECENT REQUESTS --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">Recent Requests</div>
              <div class="card-s">Your latest blood requests</div>
            </div>
            <a href="{{ route('hospital.requests.index') }}" class="card-act">View all →</a>
          </div>
          <div class="rh-list">
            @forelse($recent_requests as $req)
              <div class="rh-item">
                <div class="rh-blood">{{ $req->blood_group }}</div>
                <div class="rh-info">
                  <div class="rh-t">{{ $req->units_needed }} unit{{ $req->units_needed > 1 ? 's' : '' }} · {{ $req->ward ?? 'General' }}</div>
                  <div class="rh-s">
                    <span class="badge {{ $req->urgency === 'critical' ? 'b-crit' : ($req->urgency === 'urgent' ? 'b-urg' : 'b-std') }}">
                      {{ ucfirst($req->urgency) }}
                    </span>
                    · {{ $req->created_at->diffForHumans() }}
                  </div>
                </div>
                <div class="rh-right">
                  <div class="rh-date">{{ $req->created_at->format('d M') }}</div>
                  <span class="badge {{ $req->status === 'fulfilled' ? 'b-ful' : ($req->status === 'cancelled' ? 'b-can' : 'b-pen') }}">
                    {{ ucfirst($req->status) }}
                  </span>
                </div>
              </div>
            @empty
              <div class="empty-state">No requests yet. Submit your first blood request above.</div>
            @endforelse
          </div>
        </div>

      </div>

      {{-- RIGHT — HOSPITAL INFO --}}
      <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- HOSPITAL PROFILE --}}
        <div class="hosp-info-card">
          <div class="hi-top">
            <div class="hi-icon">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
            </div>
            <div class="hi-name">{{ $hospital->name }}</div>
            <div style="margin-top:6px;">
              <span class="hi-tag">{{ $hospital->is_verified ? '✓ Verified' : 'Pending Verification' }}</span>
            </div>
          </div>
          <div class="hi-body">
            <div class="hi-row">
              <span class="hi-key">Email</span>
              <span class="hi-val" style="font-size:0.72rem;">{{ $hospital->email }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">Phone</span>
              <span class="hi-val">{{ $hospital->phone ?? '—' }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">City</span>
              <span class="hi-val">{{ $hospital->city ?? '—' }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">District</span>
              <span class="hi-val">{{ $hospital->district ?? '—' }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">Address</span>
              <span class="hi-val" style="font-size:0.72rem;text-align:right;max-width:160px;">{{ $hospital->address ?? '—' }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">Total Requests</span>
              <span class="hi-val">{{ $stats['total_requests'] }}</span>
            </div>
            <div class="hi-row">
              <span class="hi-key">Fulfilled</span>
              <span class="hi-val" style="color:var(--green);">{{ $stats['fulfilled'] }}</span>
            </div>
          </div>
        </div>

        {{-- QUICK STATS --}}
        <div class="card">
          <div class="card-hd">
            <div class="card-t">Blood Group Stats</div>
          </div>
          @php
            $bloodGroups = ['O+','A+','B+','AB+','O-','A-','B-','AB-'];
            $maxDonors = 1;
            $bgCounts = [];
            foreach($bloodGroups as $bg) {
              $count = \App\Models\Donor::where('blood_group',$bg)->where('is_eligible',true)->count();
              $bgCounts[$bg] = $count;
              if($count > $maxDonors) $maxDonors = $count;
            }
          @endphp
          <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach($bgCounts as $bg => $count)
              @php $pct = $maxDonors > 0 ? round(($count/$maxDonors)*100) : 0; @endphp
              <div style="display:flex;align-items:center;gap:8px;">
                <span style="display:inline-block;background:var(--red-light);color:var(--red-dark);font-size:0.72rem;font-weight:600;padding:2px 7px;border-radius:4px;width:36px;text-align:center;flex-shrink:0;">{{ $bg }}</span>
                <div style="flex:1;background:var(--gray);border-radius:3px;height:5px;overflow:hidden;">
                  <div style="height:5px;border-radius:3px;background:var(--red);width:{{ $pct }}%"></div>
                </div>
                <span style="font-size:0.72rem;font-weight:500;color:var(--text);min-width:20px;text-align:right;">{{ $count }}</span>
              </div>
            @endforeach
          </div>
          <div style="font-size:0.7rem;color:var(--muted);margin-top:0.75rem;">Eligible donors available per blood group</div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
let selBlood = '{{ old('blood_group','') }}';
let selUrgency = '{{ old('urgency','standard') }}';

function selectBlood(btn, val) {
  document.querySelectorAll('.bb').forEach(b => b.classList.remove('sel'));
  btn.classList.add('sel');
  selBlood = val;
  document.getElementById('bloodGroupInput').value = val;
}

function selectUrgency(btn, val, cls) {
  document.querySelectorAll('.ub').forEach(b => {
    b.classList.remove('sel-std','sel-urg','sel-crit');
  });
  btn.classList.add(cls);
  selUrgency = val;
  document.getElementById('urgencyInput').value = val;
}

// Restore on page reload after validation error
window.addEventListener('DOMContentLoaded', () => {
  if (selUrgency) {
    const map = { standard:'sel-std', urgent:'sel-urg', critical:'sel-crit' };
    document.querySelectorAll('.ub').forEach(b => {
      b.classList.remove('sel-std','sel-urg','sel-crit');
    });
    const urgBtns = document.querySelectorAll('.ub');
    const urgVals = ['standard','urgent','critical'];
    urgVals.forEach((v,i) => {
      if(v === selUrgency) urgBtns[i].classList.add(map[v]);
    });
  }
});
</script>

{{-- Donor dashboard, Admin dashboard, Hospital dashboard --}}
@include('components.chatbot')