<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;
    --white:#fff;--off:#FDF7F7;--text:#1A0A0B;--muted:#6B3B40;
    --border:rgba(200,25,42,0.15);--gray:#F5F5F5;--gray-b:#E2E2E2;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);min-height:100vh;display:flex;flex-direction:column;}

  /* NAV */
  nav{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 3rem;background:white;border-bottom:1px solid var(--border);}
  .logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--red-dark);text-decoration:none;}
  .logo-icon{width:32px;height:32px;background:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;}
  .logo-icon svg{width:16px;height:16px;fill:white;}
  .nav-right{font-size:0.85rem;color:var(--muted);}
  .nav-right a{color:var(--red);text-decoration:none;font-weight:500;}

  /* PAGE */
  .page{display:grid;grid-template-columns:1fr 1fr;flex:1;min-height:calc(100vh - 60px);}

  /* LEFT */
  .left{background:var(--red-dark);position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:3.5rem 3rem;}
  .left-circles{position:absolute;inset:0;pointer-events:none;}
  .lc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.07);}
  .lc1{width:480px;height:480px;top:-120px;right:-120px;}
  .lc2{width:320px;height:320px;bottom:-60px;left:-60px;}
  .lc3{width:160px;height:160px;top:50%;right:15%;}
  .left-top{position:relative;z-index:2;}
  .left-tag{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.7rem;font-weight:500;color:rgba(255,255,255,0.8);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:1.75rem;}
  .left-tag span{width:5px;height:5px;border-radius:50%;background:#86EFAC;display:inline-block;animation:pulse 2s infinite;}
  @keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.3;}}
  .left h2{font-family:'Playfair Display',serif;font-size:2.1rem;font-weight:700;color:white;line-height:1.25;margin-bottom:1rem;}
  .left p{font-size:0.875rem;color:rgba(255,255,255,0.55);line-height:1.7;font-weight:300;max-width:320px;}

  /* ROLE CARDS */
  .role-cards{display:flex;flex-direction:column;gap:10px;position:relative;z-index:2;}
  .role-card{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:center;gap:14px;transition:all 0.2s;cursor:pointer;}
  .role-card.active-card{background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.22);}
  .role-card:hover{background:rgba(255,255,255,0.1);}
  .rc-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .rc-donor{background:rgba(200,25,42,0.35);}
  .rc-hospital{background:rgba(59,130,246,0.25);}
  .rc-admin{background:rgba(234,179,8,0.2);}
  .rc-icon svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .rc-info{flex:1;}
  .rc-title{font-size:0.875rem;font-weight:500;color:white;margin-bottom:2px;}
  .rc-sub{font-size:0.75rem;color:rgba(255,255,255,0.45);}
  .rc-arrow{font-size:0.8rem;color:rgba(255,255,255,0.3);transition:color 0.2s;}
  .rc-arrow.active-arrow{color:rgba(255,255,255,0.8);}

  /* RIGHT */
  .right{display:flex;align-items:center;justify-content:center;padding:3rem;background:var(--off);}
  .form-box{width:100%;max-width:400px;}
  .form-top{margin-bottom:2rem;}
  .form-step{font-size:0.72rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.4rem;}
  .form-top h1{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:var(--text);margin-bottom:0.4rem;}
  .form-top p{font-size:0.85rem;color:var(--muted);font-weight:300;}

  /* ROLE TABS */
  .role-tabs{display:grid;grid-template-columns:1fr 1fr 1fr;gap:0;background:white;border:1px solid var(--gray-b);border-radius:10px;padding:4px;margin-bottom:1.75rem;}
  .role-tab{padding:0.6rem 0.5rem;border-radius:7px;border:none;background:transparent;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:400;color:var(--muted);cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:6px;}
  .role-tab svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:1.75;}
  .role-tab.active{background:var(--red);color:white;font-weight:500;}
  .role-tab:not(.active):hover{background:var(--red-light);color:var(--red);}

  /* ROLE BADGE */
  .role-badge{display:flex;align-items:center;gap:8px;padding:0.65rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.8rem;font-weight:400;}
  .rb-donor{background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;}
  .rb-hospital{background:#EFF6FF;color:#1E3A8A;border:1px solid #BFDBFE;}
  .rb-admin{background:#FEFCE8;color:#713F12;border:1px solid #FDE68A;}
  .role-badge svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:1.75;flex-shrink:0;}

  /* ADMIN HINT */
  .admin-hint{background:#FEFCE8;border:1px solid #FDE68A;border-radius:8px;padding:0.75rem 1rem;font-size:0.775rem;color:#713F12;display:none;margin-bottom:1.1rem;line-height:1.5;}
  .admin-hint.show{display:block;}
  .admin-hint strong{display:block;margin-bottom:2px;}



  /* FIELDS */
  .field{margin-bottom:1.1rem;}
  .field label{display:block;font-size:0.8rem;font-weight:500;color:var(--text);margin-bottom:6px;}
  .input-wrap{position:relative;}
  .input-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.75;pointer-events:none;}
  .input-wrap input{width:100%;padding:0.7rem 0.9rem 0.7rem 2.4rem;border:1px solid var(--gray-b);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.875rem;color:var(--text);background:white;outline:none;transition:border-color 0.2s;}
  .input-wrap input:focus{border-color:var(--red);box-shadow:0 0 0 3px rgba(200,25,42,0.08);}
  .input-wrap input.is-invalid{border-color:var(--red);}
  .eye-btn{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);padding:0;display:flex;}
  .eye-btn svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.75;}
  .error-msg{font-size:0.72rem;color:var(--red);margin-top:4px;display:block;}

  /* REMEMBER + FORGOT */
  .field-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
  .remember{display:flex;align-items:center;gap:7px;font-size:0.8rem;color:var(--muted);}
  .remember input{accent-color:var(--red);}
  .forgot{font-size:0.8rem;color:var(--red);text-decoration:none;}

  /* ALERTS */
  .alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:0.8rem 1rem;margin-bottom:1.25rem;font-size:0.8rem;color:#991B1B;}
  .alert-success{background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:0.8rem 1rem;margin-bottom:1.25rem;font-size:0.8rem;color:#166534;}

  /* SUBMIT */
  .btn-submit{width:100%;padding:0.85rem;border:none;border-radius:9px;background:var(--red);color:white;font-family:'DM Sans',sans-serif;font-size:0.95rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;}
  .btn-submit:hover{background:var(--red-dark);transform:translateY(-1px);}
  .btn-submit svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2;}

  /* DIVIDER */
  .divider{display:flex;align-items:center;gap:12px;margin:1.25rem 0;color:var(--muted);font-size:0.75rem;}
  .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--gray-b);}

  /* REGISTER LINK */
  .register-link{text-align:center;font-size:0.825rem;color:var(--muted);margin-top:1.25rem;}
  .register-link a{color:var(--red);text-decoration:none;font-weight:500;}

  /* SECURITY NOTE */
  .security-note{display:flex;align-items:center;gap:8px;padding:0.7rem 1rem;background:white;border:1px solid var(--gray-b);border-radius:8px;margin-top:1.5rem;font-size:0.75rem;color:var(--muted);}
  .security-note svg{width:14px;height:14px;stroke:var(--muted);fill:none;stroke-width:1.75;flex-shrink:0;}
</style>

{{-- NAV --}}
<nav>
  <a class="logo" href="{{ url('/') }}">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg></div>
    LifeLink
  </a>
  <div class="nav-right">New here? <a href="{{ route('donor.register') }}">Register as Donor →</a></div>
</nav>

<div class="page">

  {{-- LEFT PANEL --}}
  <div class="left">
    <div class="left-circles">
      <div class="lc lc1"></div><div class="lc lc2"></div><div class="lc lc3"></div>
    </div>
    <div class="left-top">
      <div class="left-tag"><span></span> Secure Portal</div>
      <h2>Welcome back to LifeLink</h2>
      <p>Sign in to your account. Choose your role and access your personalised dashboard.</p>
    </div>
    <div class="role-cards">
      <div class="role-card active-card" id="card-donor" onclick="setRole('donor')">
        <div class="rc-icon rc-donor">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        </div>
        <div class="rc-info">
          <div class="rc-title">Donor</div>
          <div class="rc-sub">View donations &amp; eligibility status</div>
        </div>
        <span class="rc-arrow active-arrow" id="arr-donor">→</span>
      </div>
      <div class="role-card" id="card-hospital" onclick="setRole('hospital')">
        <div class="rc-icon rc-hospital">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
        </div>
        <div class="rc-info">
          <div class="rc-title">Hospital</div>
          <div class="rc-sub">Request blood &amp; manage donors</div>
        </div>
        <span class="rc-arrow" id="arr-hospital">→</span>
      </div>
      <div class="role-card" id="card-admin" onclick="setRole('admin')">
        <div class="rc-icon rc-admin">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/><path d="M18 14l2 2 4-4"/></svg>
        </div>
        <div class="rc-info">
          <div class="rc-title">Admin</div>
          <div class="rc-sub">Full system control &amp; oversight</div>
        </div>
        <span class="rc-arrow" id="arr-admin">→</span>
      </div>
    </div>
  </div>

  {{-- RIGHT — FORM --}}
  <div class="right">
    <div class="form-box">

      <div class="form-top">
        <div class="form-step" id="formStep">Donor Portal</div>
        <h1 id="formTitle">Sign In</h1>
        <p id="formSub">Welcome back. Enter your credentials to continue.</p>
      </div>

      {{-- ALERTS --}}
      @if($errors->any())
        <div class="alert-error">
          @foreach($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
        </div>
      @endif

      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
      @endif

      {{-- ROLE TABS --}}
      <div class="role-tabs">
        <button type="button" class="role-tab active" id="tab-donor" onclick="setRole('donor')">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
          Donor
        </button>
        <button type="button" class="role-tab" id="tab-hospital" onclick="setRole('hospital')">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
          Hospital
        </button>
        <button type="button" class="role-tab" id="tab-admin" onclick="setRole('admin')">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Admin
        </button>
      </div>

      {{-- ROLE BADGE --}}
      <div class="role-badge rb-donor" id="roleBadge">
        <svg viewBox="0 0 24 24" id="badgeIcon"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        <span id="roleBadgeText">Signing in as a registered blood donor</span>
      </div>

      {{-- ADMIN HINT --}}
      <div class="admin-hint" id="adminHint">
        <strong>Admin access only</strong>
        This portal is restricted to authorised system administrators.
      </div>

      {{-- HOSPITAL ID FIELD --}}
      <!-- <div class="extra-field" id="hospitalIdField">
        <label>Hospital Registration ID</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
          <input type="text" name="hospital_id" placeholder="e.g. HOS-2024-0042" id="hospitalId">
        </div>
      </div> -->

      {{-- MAIN FORM — action changes via JS --}}
      <form method="POST" id="loginForm" action="{{ route('donor.login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="field">
          <label id="emailLabel">Email Address</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
          </div>
          @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        {{-- PASSWORD --}}
        <div class="field">
          <label>Password</label>
          <div class="input-wrap">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <input type="password" name="password"
                   id="passInput"
                   placeholder="Enter your password">
            <button type="button" class="eye-btn" onclick="togglePass()">
              <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        {{-- REMEMBER + FORGOT --}}
        <div class="field-row">
          <label class="remember">
            <input type="checkbox" name="remember"> Remember me
          </label>
          <a href="#" class="forgot">Forgot password?</a>
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn-submit" id="submitBtn">
          Sign In to Donor Portal
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>

      </form>

      <div class="divider">or</div>

      <div class="register-link" id="registerLink">
        Don't have an account? <a href="{{ route('donor.register') }}">Register as a Donor →</a>
      </div>

      <div class="security-note">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Your data is encrypted and protected under LifeLink's security policy.
      </div>

    </div>
  </div>
</div>

<script>
// Route URLs from Laravel
const routes = {
  donor:    '{{ route("donor.login") }}',
  admin:    '{{ route("admin.login") }}',
   hospital: '{{ route("hospital.login") }}',
};

const config = {
  donor: {
    step:       'Donor Portal',
    title:      'Sign In',
    sub:        'Welcome back. Enter your credentials to continue.',
    badge:      'rb-donor',
    badgeText:  'Signing in as a registered blood donor',
    btnText:    'Sign In to Donor Portal',
    showHospital: false,
    showAdmin:    false,
    register:   'Don\'t have an account? <a href="{{ route("donor.register") }}">Register as a Donor →</a>',
  },
  hospital: {
    step:       'Hospital Portal',
    title:      'Hospital Login',
    sub:        'Access your dashboard to manage blood requests.',
    badge:      'rb-hospital',
    badgeText:  'Signing in as a registered hospital',
    btnText:    'Sign In to Hospital Portal',
    showHospital: false,
    showAdmin:    false,
    register:   'Not registered? <a href="{{ route("hospital.register") }}">Register your hospital →</a>',
  },
  admin: {
    step:       'Admin Portal',
    title:      'Admin Access',
    sub:        'Restricted area. Authorised personnel only.',
    badge:      'rb-admin',
    badgeText:  'Signing in as system administrator',
    btnText:    'Sign In as Admin',
    showHospital: false,
    showAdmin:    true,
    register:   'Need admin access? <a href="#">Contact IT support →</a>',
  },
};

let currentRole = '{{ $role ?? "donor" }}';

function setRole(role) {
  currentRole = role;
  const c = config[role];

  // Update form action
  document.getElementById('loginForm').action = routes[role];

  // Update text
  document.getElementById('formStep').textContent  = c.step;
  document.getElementById('formTitle').textContent = c.title;
  document.getElementById('formSub').textContent   = c.sub;
  document.getElementById('submitBtn').childNodes[0].textContent = c.btnText + ' ';
  document.getElementById('registerLink').innerHTML = c.register;

  // Update badge
  const badge = document.getElementById('roleBadge');
  badge.className = 'role-badge ' + c.badge;
  document.getElementById('roleBadgeText').textContent = c.badgeText;

  // Show/hide extras
  document.getElementById('adminHint').classList.toggle('show', c.showAdmin);

  // Update tabs
  ['donor','hospital','admin'].forEach(r => {
    document.getElementById('tab-'+r).classList.toggle('active', r === role);
    document.getElementById('card-'+r).classList.toggle('active-card', r === role);
    document.getElementById('arr-'+r).className = 'rc-arrow' + (r === role ? ' active-arrow' : '');
  });
}

function togglePass() {
  const inp = document.getElementById('passInput');
  inp.type = inp.type === 'password' ? 'text' : 'password';
}

// Initialize the form/tabs to match the role of the route that rendered this page
// (e.g. /admin/login), so the form doesn't silently submit as a donor login.
setRole(currentRole);

// An explicit ?role= query param (e.g. a shared link) can still override.
const urlRole = new URLSearchParams(window.location.search).get('role');
if (urlRole && ['donor','hospital','admin'].includes(urlRole)) {
  setRole(urlRole);
}
</script>