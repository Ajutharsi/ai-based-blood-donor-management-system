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
  .tb-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;max-width:720px;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* VERIFIED BADGE */
  .verify-banner{display:flex;align-items:center;gap:10px;border-radius:10px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.82rem;}
  .verify-banner.pending{background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;}
  .verify-banner.verified{background:var(--green-bg);border:1px solid var(--green-b);color:var(--green);}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.5rem 1.75rem;}
  .card-t{font-size:1rem;font-weight:600;color:var(--text);margin-bottom:0.25rem;}
  .card-s{font-size:0.78rem;color:var(--muted);margin-bottom:1.5rem;}

  /* FORM */
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem 1.25rem;}
  .field{display:flex;flex-direction:column;gap:6px;}
  .field.full{grid-column:1/-1;}
  .field label{font-size:0.78rem;font-weight:500;color:var(--text);}
  .field input, .field textarea{font-family:'DM Sans',sans-serif;font-size:0.85rem;padding:0.6rem 0.75rem;border:1px solid var(--gray-b);border-radius:8px;background:var(--off);color:var(--text);}
  .field input:focus, .field textarea:focus{outline:none;border-color:var(--blue);background:white;}
  .field input.is-invalid{border-color:var(--amber-dark);}
  .error-msg{font-size:0.72rem;color:var(--amber-dark);}
  .hint{font-size:0.72rem;color:var(--muted);}
  .section-divider{grid-column:1/-1;border-top:1px solid var(--border);margin:0.5rem 0;padding-top:1rem;font-size:0.78rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.04em;}

  .actions{display:flex;gap:10px;margin-top:1.5rem;}
  .btn-save{padding:0.7rem 1.5rem;border:none;border-radius:8px;background:var(--blue);color:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:500;cursor:pointer;}
  .btn-save:hover{background:#1E40AF;}
  .btn-cancel{padding:0.7rem 1.5rem;border:1px solid var(--gray-b);border-radius:8px;background:white;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:0.85rem;text-decoration:none;display:flex;align-items:center;}
  .btn-cancel:hover{border-color:var(--primary);color:var(--primary);}
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
    <a href="{{ route('hospital.profile.edit') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">Settings</span>
    </a>
  </div>
  <div class="sb-bot">
    <div class="sb-avatar">{{ strtoupper(substr($hospital->name, 0, 2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <div class="tb-title">Hospital Profile</div>
    </div>
    <div class="tb-right">
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

    @if($hospital->is_verified)
      <div class="verify-banner verified">✓ Your hospital account is verified.</div>
    @else
      <div class="verify-banner pending">⏳ Your hospital account is awaiting admin verification. You can still submit blood requests in the meantime.</div>
    @endif

    <div class="card">
      <div class="card-t">Hospital Details</div>
      <div class="card-s">Update your hospital's profile and contact information.</div>

      <form method="POST" action="{{ route('hospital.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
          <div class="field full">
            <label>Hospital Name</label>
            <input type="text" name="name" value="{{ old('name', $hospital->name) }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $hospital->email) }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Registration ID</label>
            <input type="text" name="registration_id" value="{{ old('registration_id', $hospital->registration_id) }}" class="{{ $errors->has('registration_id') ? 'is-invalid' : '' }}">
            @error('registration_id')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $hospital->phone) }}" class="{{ $errors->has('phone') ? 'is-invalid' : '' }}">
            @error('phone')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>City</label>
            <input type="text" name="city" value="{{ old('city', $hospital->city) }}" class="{{ $errors->has('city') ? 'is-invalid' : '' }}">
            @error('city')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>District</label>
            <input type="text" name="district" value="{{ old('district', $hospital->district) }}" class="{{ $errors->has('district') ? 'is-invalid' : '' }}">
            @error('district')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address', $hospital->address) }}" class="{{ $errors->has('address') ? 'is-invalid' : '' }}">
            @error('address')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div style="grid-column:1/-1;">
            @include('components.location_picker', ['latitude' => $hospital->latitude, 'longitude' => $hospital->longitude])
          </div>

          <div class="section-divider">Change Password (optional)</div>

          <div class="field">
            <label>New Password</label>
            <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            <span class="hint">Leave blank to keep your current password.</span>
            @error('password')<span class="error-msg">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation">
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="btn-save">Save Changes</button>
          <a href="{{ route('hospital.dashboard') }}" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
