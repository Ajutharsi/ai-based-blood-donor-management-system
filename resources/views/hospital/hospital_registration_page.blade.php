<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --blue:#1D4ED8;--blue-dark:#1E3A8A;--blue-light:#EFF6FF;--blue-b:#BFDBFE;
    --white:#FFFFFF;--off:#F7F9FC;--text:#0F172A;--muted:#516079;
    --border:rgba(29,78,216,0.14);--gray:#F1F5F9;--gray-border:#E2E8F0;
    --warning:#D97706;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);min-height:100vh;}

  nav{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 3rem;background:white;border-bottom:1px solid var(--border);}
  .logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--blue-dark);text-decoration:none;}
  .logo-icon{width:32px;height:32px;background:var(--blue);border-radius:50%;display:flex;align-items:center;justify-content:center;}
  .logo-icon svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2;}
  .nav-right{font-size:0.85rem;color:var(--muted);}
  .nav-right a{color:var(--blue);text-decoration:none;font-weight:500;}

  .page{display:grid;grid-template-columns:1fr 1.5fr;min-height:calc(100vh - 60px);}

  .left-panel{background:var(--blue-dark);padding:3rem 2.5rem;display:flex;flex-direction:column;justify-content:space-between;position:sticky;top:60px;height:calc(100vh - 60px);}
  .panel-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.7rem;font-weight:500;color:rgba(255,255,255,0.8);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:2rem;width:fit-content;}
  .panel-badge span{width:5px;height:5px;border-radius:50%;background:#93C5FD;display:inline-block;}
  .left-panel h2{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:white;line-height:1.25;margin-bottom:1rem;}
  .left-panel p{font-size:0.875rem;color:rgba(255,255,255,0.65);line-height:1.7;font-weight:300;margin-bottom:2rem;}

  .benefit-list{display:flex;flex-direction:column;gap:1rem;}
  .benefit{display:flex;align-items:flex-start;gap:12px;}
  .benefit svg{width:18px;height:18px;stroke:#93C5FD;fill:none;stroke-width:2;flex-shrink:0;margin-top:2px;}
  .benefit-t{font-size:0.85rem;font-weight:500;color:white;margin-bottom:2px;}
  .benefit-s{font-size:0.78rem;color:rgba(255,255,255,0.55);}

  .verify-note{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:12px;padding:1rem 1.1rem;font-size:0.78rem;color:rgba(255,255,255,0.65);line-height:1.6;}

  .form-area{padding:3rem;display:flex;justify-content:center;}
  .form-card{width:100%;max-width:520px;}
  .form-header{margin-bottom:1.75rem;}
  .form-header h1{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:var(--text);margin-bottom:0.4rem;}
  .form-header p{font-size:0.85rem;color:var(--muted);font-weight:300;}

  .alert-error{background:#FFFBEB;border:1px solid #FDE68A;border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:#92400E;}
  .alert-error ul{margin:0.35rem 0 0 1.1rem;}

  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem 1.25rem;}
  .field{display:flex;flex-direction:column;gap:6px;}
  .field.full{grid-column:1/-1;}
  .field label{font-size:0.78rem;font-weight:500;color:var(--text);}
  .field .req{color:var(--warning);}
  .field input{font-family:'DM Sans',sans-serif;font-size:0.85rem;padding:0.65rem 0.8rem;border:1px solid var(--gray-border);border-radius:8px;background:var(--gray);color:var(--text);}
  .field input:focus{outline:none;border-color:var(--blue);background:white;}
  .field input.is-invalid{border-color:var(--warning);}
  .error-msg{font-size:0.72rem;color:var(--warning);}

  .submit-btn{width:100%;margin-top:1.5rem;padding:0.8rem;border:none;border-radius:9px;background:var(--blue);color:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;cursor:pointer;transition:background 0.2s;}
  .submit-btn:hover{background:var(--blue-dark);}

  .login-link{text-align:center;font-size:0.82rem;color:var(--muted);margin-top:1.25rem;}
  .login-link a{color:var(--blue);text-decoration:none;font-weight:500;}

  @media (max-width:900px){
    .page{grid-template-columns:1fr;}
    .left-panel{position:static;height:auto;}
  }
</style>

<nav>
  <a href="/" class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
    LifeLink
  </a>
  <div class="nav-right">{{ __('Already registered?') }} <a href="{{ route('hospital.login') }}">{{ __('Sign In →') }}</a></div>
</nav>

<div class="page">

  {{-- LEFT PANEL --}}
  <div class="left-panel">
    <div>
      <div class="panel-badge"><span></span> {{ __('Hospital Portal') }}</div>
      <h2>{{ __("Connect your hospital with LifeLink's donor network") }}</h2>
      <p>{{ __('Register your hospital to submit blood requests and reach an AI-ranked pool of eligible, compatible donors in minutes.') }}</p>

      <div class="benefit-list">
        <div class="benefit">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
          <div>
            <div class="benefit-t">{{ __('AI-ranked donor matching') }}</div>
            <div class="benefit-s">{{ __('Blood/Rh compatibility, proximity, and donor reliability, weighted by urgency.') }}</div>
          </div>
        </div>
        <div class="benefit">
          <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          <div>
            <div class="benefit-t">{{ __('Track requests end-to-end') }}</div>
            <div class="benefit-s">{{ __('From submission to fulfilment, all in one dashboard.') }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="verify-note">
      {{ __('New hospital accounts start') }} <strong>{{ __('unverified') }}</strong> {{ __('and are reviewed by a LifeLink administrator. You can submit requests right away while verification is pending.') }}
    </div>
  </div>

  {{-- FORM --}}
  <div class="form-area">
    <div class="form-card">
      <div class="form-header">
        <h1>{{ __('Register Your Hospital') }}</h1>
        <p>{{ __('Create a hospital account to start submitting blood requests.') }}</p>
      </div>

      @if($errors->any())
        <div class="alert-error">
          {{ __('Please fix the following:') }}
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('hospital.register') }}">
        @csrf

        <div class="form-grid">
          <div class="field full">
            <label>{{ __('Hospital Name') }} <span class="req">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
          </div>

          <div class="field full">
            <label>{{ __('Email') }} <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
          </div>

          <div class="field">
            <label>{{ __('Password') }} <span class="req">*</span></label>
            <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
          </div>

          <div class="field">
            <label>{{ __('Confirm Password') }} <span class="req">*</span></label>
            <input type="password" name="password_confirmation" required>
          </div>

          <div class="field">
            <label>{{ __('Registration ID') }}</label>
            <input type="text" name="registration_id" value="{{ old('registration_id') }}" placeholder="e.g. HOS-2024-0001" class="{{ $errors->has('registration_id') ? 'is-invalid' : '' }}">
          </div>

          <div class="field">
            <label>{{ __('Phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="{{ $errors->has('phone') ? 'is-invalid' : '' }}">
          </div>

          <div class="field">
            <label>{{ __('City') }}</label>
            <input type="text" name="city" value="{{ old('city') }}" class="{{ $errors->has('city') ? 'is-invalid' : '' }}">
          </div>

          <div class="field">
            <label>{{ __('District') }}</label>
            <input type="text" name="district" value="{{ old('district') }}" class="{{ $errors->has('district') ? 'is-invalid' : '' }}">
          </div>

          <div class="field full">
            <label>{{ __('Address') }}</label>
            <input type="text" name="address" value="{{ old('address') }}" class="{{ $errors->has('address') ? 'is-invalid' : '' }}">
          </div>
        </div>

        <button type="submit" class="submit-btn">{{ __('Create Hospital Account') }}</button>
      </form>

      <div class="login-link">
        {{ __('Already registered?') }} <a href="{{ route('hospital.login') }}">{{ __('Sign in →') }}</a>
      </div>
    </div>
  </div>
</div>
