<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;
    --white:#FFFFFF;--off:#FDF7F7;--text:#1A0A0B;--muted:#6B3B40;
    --border:rgba(200,25,42,0.15);--gray:#F4F4F4;--gray-border:#E0E0E0;
    --success:#16A34A;--success-bg:#F0FDF4;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);min-height:100vh;}

  nav{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 3rem;background:white;border-bottom:1px solid var(--border);}
  .logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--red-dark);text-decoration:none;}
  .logo-icon{width:32px;height:32px;background:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;}
  .logo-icon svg{width:16px;height:16px;fill:white;}
  .nav-right{display:flex;align-items:center;gap:1rem;font-size:0.85rem;color:var(--muted);}
  .nav-right a{color:var(--red);text-decoration:none;font-weight:500;}

  .page{display:grid;grid-template-columns:1fr 2fr;min-height:calc(100vh - 60px);}

  .left-panel{background:var(--red-dark);padding:3rem 2.5rem;display:flex;flex-direction:column;justify-content:space-between;position:sticky;top:60px;height:calc(100vh - 60px);}
  .panel-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.7rem;font-weight:500;color:rgba(255,255,255,0.8);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:2rem;}
  .panel-badge span{width:5px;height:5px;border-radius:50%;background:#86EFAC;display:inline-block;}
  .left-panel h2{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:white;line-height:1.25;margin-bottom:1rem;}
  .left-panel p{font-size:0.875rem;color:rgba(255,255,255,0.6);line-height:1.7;font-weight:300;margin-bottom:2rem;}

  .progress-steps{display:flex;flex-direction:column;gap:0;}
  .prog-step{display:flex;align-items:flex-start;gap:14px;padding-bottom:1.5rem;position:relative;}
  .prog-step:last-child{padding-bottom:0;}
  .prog-line{position:absolute;left:15px;top:32px;bottom:0;width:1px;background:rgba(255,255,255,0.12);}
  .prog-step:last-child .prog-line{display:none;}
  .prog-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:500;flex-shrink:0;position:relative;z-index:1;}
  .prog-active{background:var(--red);color:white;border:2px solid rgba(255,255,255,0.3);}
  .prog-todo{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.1);}
  .prog-info{padding-top:4px;}
  .prog-title{font-size:0.875rem;font-weight:500;color:white;margin-bottom:2px;}
  .prog-title.todo{color:rgba(255,255,255,0.35);}
  .prog-sub{font-size:0.75rem;color:rgba(255,255,255,0.4);}

  .elig-box{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:1.25rem;}
  .elig-title{font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:0.75rem;}
  .elig-row{display:flex;align-items:center;gap:8px;margin-bottom:0.5rem;font-size:0.8rem;color:rgba(255,255,255,0.7);}
  .elig-row svg{width:14px;height:14px;stroke:#86EFAC;fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0;}

  .form-area{padding:3rem;overflow-y:auto;}
  .form-header{margin-bottom:2rem;}
  .step-indicator{font-size:0.75rem;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:0.5rem;}
  .form-header h1{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;}
  .form-header p{font-size:0.875rem;color:var(--muted);font-weight:300;}

  .progress-bar-wrap{background:#F0E8E9;border-radius:4px;height:4px;margin-bottom:2.5rem;}
  .progress-bar-fill{height:4px;border-radius:4px;background:var(--red);width:0%;transition:width 0.4s;}

  .form-section{margin-bottom:2.25rem;}
  .form-section-title{font-size:0.7rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:var(--red);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
  .form-row.full{grid-template-columns:1fr;}
  .form-row.tri{grid-template-columns:1fr 1fr 1fr;}
  .field{display:flex;flex-direction:column;gap:6px;}
  label{font-size:0.8rem;font-weight:500;color:var(--text);}
  label span.req{color:var(--red);margin-left:2px;}

  input[type=text],input[type=email],input[type=tel],input[type=number],input[type=date],input[type=password],select,textarea{
    width:100%;padding:0.65rem 0.9rem;
    border:1px solid var(--gray-border);border-radius:8px;
    font-family:'DM Sans',sans-serif;font-size:0.875rem;color:var(--text);
    background:white;outline:none;transition:border-color 0.2s;
    appearance:none;-webkit-appearance:none;
  }
  input:focus,select:focus,textarea:focus{border-color:var(--red);box-shadow:0 0 0 3px rgba(200,25,42,0.08);}
  input.is-invalid,select.is-invalid,textarea.is-invalid{border-color:var(--red)!important;}
  select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B3B40' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:2.5rem;}
  textarea{resize:vertical;min-height:80px;}
  input[readonly]{background:#F9F9F9;color:var(--muted);}

  .error-msg{font-size:0.72rem;color:var(--red);margin-top:3px;}

  .blood-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
  .blood-btn{padding:0.7rem 0.5rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.95rem;font-weight:500;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;type:button;}
  .blood-btn:hover{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .blood-btn.selected{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .blood-sub{font-size:0.65rem;display:block;color:inherit;opacity:0.7;margin-top:1px;}
  .blood-error{font-size:0.72rem;color:var(--red);margin-top:6px;display:none;}
  .blood-error.show{display:block;}

  .gender-toggle{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;}
  .gender-btn{padding:0.65rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;display:flex;align-items:center;justify-content:center;gap:6px;}
  .gender-btn:hover{border-color:var(--red);color:var(--red);}
  .gender-btn.selected{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .gender-btn svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.75;}

  .ai-preview{background:white;border:1px solid var(--border);border-radius:12px;padding:1.25rem;margin-bottom:2rem;}
  .ai-preview-title{font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:1rem;display:flex;align-items:center;gap:6px;}
  .ai-preview-title svg{width:14px;height:14px;stroke:var(--red);fill:none;stroke-width:2;}
  .ai-bars{display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;}
  .ai-bar-item{text-align:center;}
  .ai-bar-label{font-size:0.7rem;color:var(--muted);margin-bottom:6px;}
  .ai-bar-track{background:#F0E8E9;border-radius:4px;height:6px;overflow:hidden;}
  .ai-bar-fill{height:6px;border-radius:4px;background:var(--red);transition:width 0.5s;}
  .ai-bar-val{font-size:0.75rem;font-weight:500;color:var(--text);margin-top:4px;}
  .elig-result{margin-top:1rem;padding:0.75rem 1rem;border-radius:8px;display:flex;align-items:center;gap:10px;font-size:0.825rem;}
  .elig-yes{background:var(--success-bg);color:var(--success);}
  .elig-no{background:#FFF7ED;color:#C2410C;}
  .elig-unknown{background:var(--gray);color:var(--muted);}
  .elig-result svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}

  .checkbox-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:0.75rem;}
  .checkbox-row input[type=checkbox]{width:16px;height:16px;margin-top:2px;accent-color:var(--red);flex-shrink:0;}
  .checkbox-row label{font-size:0.825rem;color:var(--muted);font-weight:300;line-height:1.5;}

  .form-footer{display:flex;align-items:center;justify-content:space-between;padding-top:1.5rem;border-top:1px solid var(--border);margin-top:0.5rem;}
  .btn-back{padding:0.75rem 1.5rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .btn-back:hover{border-color:var(--red);color:var(--red);}
  .btn-submit{padding:0.75rem 2.5rem;border:none;border-radius:8px;background:var(--red);color:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:8px;}
  .btn-submit:hover{background:var(--red-dark);transform:translateY(-1px);}
  .btn-submit svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2;}

  .helper{font-size:0.72rem;color:var(--muted);margin-top:2px;}

  .alert-error{background:#FFF1F2;border:1px solid #FECDD3;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;}
  .alert-error p{font-size:0.85rem;font-weight:500;color:#9F1239;margin-bottom:0.5rem;}
  .alert-error ul{font-size:0.8rem;color:#BE123C;padding-left:1.25rem;}
  .alert-success{background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.85rem;color:#166534;}
</style>

<!-- NAV -->
<nav>
  <a class="logo" href="{{ url('/') }}">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg></div>
    LifeLink
  </a>
  <div class="nav-right">Already a donor? <a href="{{ route('donor.login') }}">Sign in →</a></div>
</nav>

<div class="page">

  <!-- LEFT PANEL -->
  <div class="left-panel">
    <div class="left-top">
      <div class="panel-badge"><span></span> Registration Open</div>
      <h2>Become a Blood Donor Today</h2>
      <p>Register once. Our AI will notify you whenever your blood type is needed nearby.</p>
      <div class="progress-steps">
        <div class="prog-step">
          <div class="prog-circle prog-active">1</div>
          <div class="prog-line"></div>
          <div class="prog-info">
            <div class="prog-title">Personal Details</div>
            <div class="prog-sub">Name, contact, location</div>
          </div>
        </div>
        <div class="prog-step">
          <div class="prog-circle prog-todo">2</div>
          <div class="prog-line"></div>
          <div class="prog-info">
            <div class="prog-title todo">Health Information</div>
            <div class="prog-sub">Blood group, hemoglobin</div>
          </div>
        </div>
        <div class="prog-step">
          <div class="prog-circle prog-todo">3</div>
          <div class="prog-line"></div>
          <div class="prog-info">
            <div class="prog-title todo">AI Eligibility Check</div>
            <div class="prog-sub">Instant prediction result</div>
          </div>
        </div>
        <div class="prog-step">
          <div class="prog-circle prog-todo">4</div>
          <div class="prog-info">
            <div class="prog-title todo">Confirm & Submit</div>
            <div class="prog-sub">Review and finish</div>
          </div>
        </div>
      </div>
    </div>
    <div class="elig-box">
      <div class="elig-title">Eligibility requirements</div>
      <div class="elig-row"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Age 18 or above</div>
      <div class="elig-row"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Weight at least 50 kg</div>
      <div class="elig-row"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Hemoglobin ≥ 12 g/dL</div>
      <div class="elig-row"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>No recent illness or surgery</div>
    </div>
  </div>

  <!-- FORM -->
  <form method="POST" action="{{ route('donor.register') }}">
    @csrf
    <div class="form-area">

      {{-- GLOBAL ALERTS --}}
      @if ($errors->any())
        <div class="alert-error">
          <p>Please fix the following errors:</p>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      <div class="form-header">
        <div class="step-indicator">Step 1 of 1 — Personal & Health Details</div>
        <h1>Create Your Donor Profile</h1>
        <p>Fill in your information below. All fields marked with <span style="color:var(--red)">*</span> are required.</p>
      </div>

      <div class="progress-bar-wrap"><div class="progress-bar-fill" id="pbar"></div></div>

      {{-- ── PERSONAL INFORMATION ── --}}
      <div class="form-section">
        <div class="form-section-title">Personal Information</div>

        <div class="form-row">
          <div class="field">
            <label>First Name <span class="req">*</span></label>
            <input type="text" name="first_name" id="fname"
                   value="{{ old('first_name') }}"
                   placeholder="Kasun"
                   class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                   oninput="updateProgress()">
            @error('first_name')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Last Name <span class="req">*</span></label>
            <input type="text" name="last_name" id="lname"
                   value="{{ old('last_name') }}"
                   placeholder="Perera"
                   class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                   oninput="updateProgress()">
            @error('last_name')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row tri">
          <div class="field">
            <label>Date of Birth <span class="req">*</span></label>
            <input type="date" name="date_of_birth" id="dob"
                   value="{{ old('date_of_birth') }}"
                   class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}"
                   oninput="calcAge();updateProgress()">
            @error('date_of_birth')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Age</label>
            <input type="number" name="age" id="age"
                   value="{{ old('age') }}"
                   placeholder="Auto-calculated" readonly>
          </div>
          <div class="field">
            <label>NIC Number</label>
            <input type="text" name="nic" id="fnic"
                   value="{{ old('nic') }}"
                   placeholder="991234567V"
                   class="{{ $errors->has('nic') ? 'is-invalid' : '' }}"
                   oninput="updateProgress()">
            @error('nic')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label>Password <span class="req">*</span></label>
            <input type="password" name="password"
                   placeholder="Min 8 characters"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            @error('password')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Confirm Password <span class="req">*</span></label>
            <input type="password" name="password_confirmation"
                   placeholder="Repeat password">
          </div>
        </div>

        <div class="form-row full">
          <div class="field">
            <label>Gender <span class="req">*</span></label>
            <input type="hidden" name="gender" id="genderInput" value="{{ old('gender') }}">
            <div class="gender-toggle">
              <button type="button" class="gender-btn {{ old('gender') === 'Male' ? 'selected' : '' }}"
                      onclick="selectGender(this,'Male')">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>Male
              </button>
              <button type="button" class="gender-btn {{ old('gender') === 'Female' ? 'selected' : '' }}"
                      onclick="selectGender(this,'Female')">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>Female
              </button>
              <button type="button" class="gender-btn {{ old('gender') === 'Other' ? 'selected' : '' }}"
                      onclick="selectGender(this,'Other')">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="6"/></svg>Other
              </button>
            </div>
            @error('gender')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      {{-- ── CONTACT & LOCATION ── --}}
      <div class="form-section">
        <div class="form-section-title">Contact & Location</div>

        <div class="form-row">
          <div class="field">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="kasun@email.com"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                   oninput="updateProgress()">
            @error('email')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input type="tel" name="phone"
                   value="{{ old('phone') }}"
                   placeholder="+94 71 234 5678">
          </div>
        </div>

        <div class="form-row tri">
          <div class="field">
            <label>City</label>
            <input type="text" name="city"
                   value="{{ old('city') }}"
                   placeholder="Colombo"
                   oninput="updateProgress()">
          </div>
          <div class="field">
            <label>District</label>
            <select name="district">
              <option value="">Select district</option>
              @foreach(['Colombo','Gampaha','Kalutara','Kandy','Matale','Nuwara Eliya','Galle','Matara','Hambantota','Jaffna','Kilinochchi','Mannar','Vavuniya','Trincomalee','Batticaloa','Ampara','Polonnaruwa','Anuradhapura','Puttalam','Kurunegala','Ratnapura','Kegalle','Badulla','Monaragala','Mullaitivu'] as $d)
                <option value="{{ $d }}" {{ old('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>Donation Center</label>
            <select name="donation_center">
              <option value="">Nearest center</option>
              @foreach(['NBTS Colombo','NBTS Kandy','NBTS Kurunegala','NBTS Galle','NBTS Jaffna'] as $c)
                <option value="{{ $c }}" {{ old('donation_center') === $c ? 'selected' : '' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- ── HEALTH INFORMATION ── --}}
      <div class="form-section">
        <div class="form-section-title">Health Information</div>

        <div class="form-row full" style="margin-bottom:1.25rem;">
          <div class="field">
            <label>Blood Group <span class="req">*</span></label>
            <input type="hidden" name="blood_group" id="bloodGroupInput" value="{{ old('blood_group') }}">
            <div class="blood-grid">
              @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <button type="button"
                        class="blood-btn {{ old('blood_group') === $bg ? 'selected' : '' }}"
                        onclick="selectBlood(this,'{{ $bg }}')">
                  {{ $bg }}<span class="blood-sub">{{ str_replace(['+','-'],['positive','negative'],$bg) }}</span>
                </button>
              @endforeach
            </div>
            @error('blood_group')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row tri">
          <div class="field">
            <label>Weight (kg)</label>
            <input type="number" name="weight_kg" id="weight"
                   value="{{ old('weight_kg') }}"
                   placeholder="65" min="30" max="200" step="0.1"
                   class="{{ $errors->has('weight_kg') ? 'is-invalid' : '' }}"
                   oninput="updateAI()">
            <span class="helper">Minimum 50 kg required</span>
            @error('weight_kg')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Hemoglobin (g/dL)</label>
            <input type="number" name="hemoglobin" id="hemo"
                   value="{{ old('hemoglobin') }}"
                   placeholder="13.5" step="0.1"
                   class="{{ $errors->has('hemoglobin') ? 'is-invalid' : '' }}"
                   oninput="updateAI()">
            <span class="helper">Minimum 12.0 required</span>
            @error('hemoglobin')<span class="error-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label>Total Past Donations</label>
            <input type="number" name="total_donations" id="donations"
                   value="{{ old('total_donations', 0) }}"
                   placeholder="0" min="0"
                   oninput="updateProgress()">
            <span class="helper">Enter 0 if first time</span>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label>Last Donation Date</label>
            <input type="date" name="last_donation_date"
                   value="{{ old('last_donation_date') }}">
            <span class="helper">Leave blank if never donated</span>
          </div>
          <div class="field">
            <label>Medical Conditions</label>
            <select name="medical_condition">
              @foreach(['' => 'None', 'Diabetes (controlled)' => 'Diabetes (controlled)', 'Hypertension (controlled)' => 'Hypertension (controlled)', 'Asthma' => 'Asthma', 'Other' => 'Other (specify below)'] as $val => $label)
                <option value="{{ $val }}" {{ old('medical_condition') === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-row full">
          <div class="field">
            <label>Additional Health Notes</label>
            <textarea name="medical_notes" placeholder="Any allergies, medications, or health conditions...">{{ old('medical_notes') }}</textarea>
          </div>
        </div>
      </div>

      {{-- ── AI ELIGIBILITY PREVIEW ── --}}
      <div class="ai-preview">
        <div class="ai-preview-title">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
          AI Eligibility Preview — updates as you type
        </div>
        <div class="ai-bars">
          <div class="ai-bar-item">
            <div class="ai-bar-label">Age score</div>
            <div class="ai-bar-track"><div class="ai-bar-fill" id="bar-age" style="width:0%"></div></div>
            <div class="ai-bar-val" id="val-age">—</div>
          </div>
          <div class="ai-bar-item">
            <div class="ai-bar-label">Weight score</div>
            <div class="ai-bar-track"><div class="ai-bar-fill" id="bar-weight" style="width:0%"></div></div>
            <div class="ai-bar-val" id="val-weight">—</div>
          </div>
          <div class="ai-bar-item">
            <div class="ai-bar-label">Hemoglobin score</div>
            <div class="ai-bar-track"><div class="ai-bar-fill" id="bar-hemo" style="width:0%"></div></div>
            <div class="ai-bar-val" id="val-hemo">—</div>
          </div>
        </div>
        <div class="elig-result elig-unknown" id="eligResult">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Fill in your weight and hemoglobin to see your AI eligibility prediction.
        </div>
      </div>

      {{-- ── CONSENT ── --}}
      <div class="form-section">
        <div class="form-section-title">Consent & Agreements</div>
        <div class="checkbox-row">
          <input type="checkbox" id="c1" required>
          <label for="c1">I confirm that all information provided is accurate and truthful.</label>
        </div>
        <div class="checkbox-row">
          <input type="checkbox" id="c2" required>
          <label for="c2">I consent to being contacted by LifeLink and partner hospitals when my blood type is needed.</label>
        </div>
        <div class="checkbox-row">
          <input type="checkbox" id="c3" required>
          <label for="c3">I agree to the <a href="#" style="color:var(--red)">Terms of Service</a> and <a href="#" style="color:var(--red)">Privacy Policy</a>.</label>
        </div>
      </div>

      <div class="form-footer">
        <a href="{{ route('donor.login') }}" class="btn-back">← Back to Login</a>
        <button type="submit" class="btn-submit">
          Register as Donor
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>

    </div>
  </form>
</div>

<script>
// ── Restore JS state from old() values on page reload ──
let selectedBlood = '{{ old('blood_group', '') }}';
let selectedGender = '{{ old('gender', '') }}';

// Restore age bar on reload
window.addEventListener('DOMContentLoaded', () => {
  const w = document.getElementById('weight').value;
  const h = document.getElementById('hemo').value;
  if (w || h) updateAI();
  updateProgress();
});

function selectBlood(btn, val) {
  document.querySelectorAll('.blood-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  selectedBlood = val;
  document.getElementById('bloodGroupInput').value = val;
  updateProgress();
  updateAI();
}

function selectGender(btn, val) {
  document.querySelectorAll('.gender-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  selectedGender = val;
  document.getElementById('genderInput').value = val;
  updateProgress();
}

function calcAge() {
  const dob = document.getElementById('dob').value;
  if (!dob) return;
  const age = Math.floor((new Date() - new Date(dob)) / (365.25 * 24 * 3600 * 1000));
  document.getElementById('age').value = age;
  updateAI();
}

function updateAI() {
  const age    = parseInt(document.getElementById('age').value)    || 0;
  const weight = parseFloat(document.getElementById('weight').value) || 0;
  const hemo   = parseFloat(document.getElementById('hemo').value)   || 0;

  const ageScore    = age >= 18 && age <= 65 ? Math.min(100, Math.round((age - 17) / 47 * 100)) : age < 18 ? 0 : 80;
  const weightScore = weight > 0 ? Math.min(100, Math.round((weight / 100) * 100)) : 0;
  const hemoScore   = hemo   > 0 ? Math.min(100, Math.round((hemo   / 18)  * 100)) : 0;

  document.getElementById('bar-age').style.width    = ageScore    + '%';
  document.getElementById('bar-weight').style.width = weightScore + '%';
  document.getElementById('bar-hemo').style.width   = hemoScore   + '%';
  document.getElementById('val-age').textContent    = age    > 0 ? age + ' yrs'        : '—';
  document.getElementById('val-weight').textContent = weight > 0 ? weight + ' kg'      : '—';
  document.getElementById('val-hemo').textContent   = hemo   > 0 ? hemo.toFixed(1) + ' g/dL' : '—';

  const result = document.getElementById('eligResult');
  if (weight > 0 || hemo > 0) {
    const eligible     = age >= 18 && weight >= 50 && hemo >= 12;
    const partialAge   = age > 0   && age    < 18;
    const partialWeight= weight > 0 && weight < 50;
    const partialHemo  = hemo   > 0 && hemo   < 12;

    if (eligible) {
      result.className = 'elig-result elig-yes';
      result.innerHTML = '<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> You appear eligible to donate! Our AI model predicts a high likelihood of approval.';
    } else {
      const reasons = [];
      if (partialAge)    reasons.push('age must be 18+');
      if (partialWeight) reasons.push('weight must be 50+ kg');
      if (partialHemo)   reasons.push('hemoglobin must be 12+ g/dL');
      result.className = 'elig-result elig-no';
      result.innerHTML = '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Not eligible: ' + (reasons.length ? reasons.join(', ') : 'criteria not fully met') + '.';
    }
  } else {
    result.className = 'elig-result elig-unknown';
    result.innerHTML = '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Fill in your weight and hemoglobin to see your AI eligibility prediction.';
  }
  updateProgress();
}

function updateProgress() {
  const fields = ['fname','lname','dob','weight','hemo'];
  let filled = fields.filter(id => document.getElementById(id)?.value?.trim()).length;
  if (selectedBlood)  filled++;
  if (selectedGender) filled++;
  const pct = Math.round((filled / 7) * 100);
  document.getElementById('pbar').style.width = Math.min(pct, 100) + '%';
}
</script>