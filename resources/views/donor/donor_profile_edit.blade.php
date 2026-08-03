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

  .form-area{max-width:760px;margin:2.5rem auto;padding:2.5rem;background:white;border:1px solid var(--border);border-radius:16px;}
  .form-header{margin-bottom:2rem;}
  .form-header h1{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;}
  .form-header p{font-size:0.875rem;color:var(--muted);font-weight:300;}

  .form-section{margin-bottom:2.25rem;}
  .form-section-title{font-size:0.7rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:var(--red);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border);}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
  .form-row.full{grid-template-columns:1fr;}
  .form-row.tri{grid-template-columns:1fr 1fr 1fr;}
  .field{display:flex;flex-direction:column;gap:6px;}
  label{font-size:0.8rem;font-weight:500;color:var(--text);}
  label span.req{color:var(--red);margin-left:2px;}

  input[type=text],input[type=email],input[type=tel],input[type=number],input[type=date],input[type=password],input[type=file],select,textarea{
    width:100%;padding:0.65rem 0.9rem;
    border:1px solid var(--gray-border);border-radius:8px;
    font-family:'DM Sans',sans-serif;font-size:0.875rem;color:var(--text);
    background:white;outline:none;transition:border-color 0.2s;
  }
  input:focus,select:focus,textarea:focus{border-color:var(--red);box-shadow:0 0 0 3px rgba(200,25,42,0.08);}
  input.is-invalid,select.is-invalid,textarea.is-invalid{border-color:var(--red)!important;}
  textarea{resize:vertical;min-height:80px;}

  .error-msg{font-size:0.72rem;color:var(--red);margin-top:3px;}
  .helper{font-size:0.72rem;color:var(--muted);margin-top:2px;}

  .blood-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
  .blood-btn{padding:0.7rem 0.5rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.95rem;font-weight:500;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;}
  .blood-btn:hover{border-color:var(--red);color:var(--red);background:var(--red-light);}
  .blood-btn.selected{border-color:var(--red);color:var(--red);background:var(--red-light);}

  .gender-toggle{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;}
  .gender-btn{padding:0.65rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;color:var(--muted);cursor:pointer;transition:all 0.2s;text-align:center;}
  .gender-btn:hover{border-color:var(--red);color:var(--red);}
  .gender-btn.selected{border-color:var(--red);color:var(--red);background:var(--red-light);}

  .current-avatar{width:64px;height:64px;border-radius:50%;background:var(--red-light);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--red-dark);margin-bottom:0.75rem;background-size:cover;background-position:center;}

  .form-footer{display:flex;align-items:center;justify-content:space-between;padding-top:1.5rem;border-top:1px solid var(--border);margin-top:0.5rem;}
  .btn-back{padding:0.75rem 1.5rem;border:1px solid var(--gray-border);border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;color:var(--muted);cursor:pointer;text-decoration:none;transition:all 0.2s;}
  .btn-back:hover{border-color:var(--red);color:var(--red);}
  .btn-submit{padding:0.75rem 2.5rem;border:none;border-radius:8px;background:var(--red);color:white;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:500;cursor:pointer;transition:all 0.2s;}
  .btn-submit:hover{background:var(--red-dark);}

  .alert-error{background:#FFF1F2;border:1px solid #FECDD3;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;}
  .alert-error p{font-size:0.85rem;font-weight:500;color:#9F1239;margin-bottom:0.5rem;}
  .alert-error ul{font-size:0.8rem;color:#BE123C;padding-left:1.25rem;}
</style>

<nav>
  <a class="logo" href="{{ url('/') }}">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg></div>
    LifeLink
  </a>
  <div class="nav-right"><a href="{{ route('donor.dashboard') }}">← Back to Dashboard</a></div>
</nav>

<form method="POST" action="{{ route('donor.profile.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="form-area">

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

    <div class="form-header">
      <h1>Edit Your Profile</h1>
      <p>Update your personal and health details. Changing your health details re-runs the AI eligibility check.</p>
    </div>

    {{-- ── PERSONAL INFORMATION ── --}}
    <div class="form-section">
      <div class="form-section-title">Personal Information</div>

      <div class="field" style="margin-bottom:1.25rem;">
        <label>Profile Photo</label>
        <div class="current-avatar" @if($donor->profile_image) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($donor->profile_image) }}');" @endif>
          @unless($donor->profile_image)
            {{ strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1)) }}
          @endunless
        </div>
        <input type="file" name="profile_image" accept="image/png,image/jpeg,image/webp"
               class="{{ $errors->has('profile_image') ? 'is-invalid' : '' }}">
        <span class="helper">Optional. JPG, PNG or WEBP, max 2MB. Leave blank to keep your current photo.</span>
        @error('profile_image')<span class="error-msg">{{ $message }}</span>@enderror
      </div>

      <div class="form-row">
        <div class="field">
          <label>First Name <span class="req">*</span></label>
          <input type="text" name="first_name" value="{{ old('first_name', $donor->first_name) }}"
                 class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}">
          @error('first_name')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" name="last_name" value="{{ old('last_name', $donor->last_name) }}"
                 class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}">
          @error('last_name')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="form-row tri">
        <div class="field">
          <label>Date of Birth</label>
          <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($donor->date_of_birth)->format('Y-m-d')) }}"
                 class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
          @error('date_of_birth')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>NIC Number</label>
          <input type="text" name="nic" value="{{ old('nic', $donor->nic) }}"
                 class="{{ $errors->has('nic') ? 'is-invalid' : '' }}">
          @error('nic')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Gender</label>
          <input type="hidden" name="gender" id="genderInput" value="{{ old('gender', $donor->gender) }}">
          <div class="gender-toggle">
            <button type="button" class="gender-btn {{ old('gender', $donor->gender) === 'Male' ? 'selected' : '' }}" onclick="selectGender(this,'Male')">Male</button>
            <button type="button" class="gender-btn {{ old('gender', $donor->gender) === 'Female' ? 'selected' : '' }}" onclick="selectGender(this,'Female')">Female</button>
            <button type="button" class="gender-btn {{ old('gender', $donor->gender) === 'Other' ? 'selected' : '' }}" onclick="selectGender(this,'Other')">Other</button>
          </div>
          @error('gender')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="form-row">
        <div class="field">
          <label>New Password</label>
          <input type="password" name="password" placeholder="Leave blank to keep current password"
                 class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
          @error('password')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Confirm New Password</label>
          <input type="password" name="password_confirmation" placeholder="Repeat new password">
        </div>
      </div>
    </div>

    {{-- ── CONTACT & LOCATION ── --}}
    <div class="form-section">
      <div class="form-section-title">Contact & Location</div>

      <div class="form-row">
        <div class="field">
          <label>Email Address <span class="req">*</span></label>
          <input type="email" name="email" value="{{ old('email', $donor->email) }}"
                 class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
          @error('email')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Phone Number</label>
          <input type="tel" name="phone" value="{{ old('phone', $donor->phone) }}">
        </div>
      </div>

      <div class="form-row tri">
        <div class="field">
          <label>City</label>
          <input type="text" name="city" value="{{ old('city', $donor->city) }}">
        </div>
        <div class="field">
          <label>District</label>
          <select name="district">
            <option value="">Select district</option>
            @foreach(['Colombo','Gampaha','Kalutara','Kandy','Matale','Nuwara Eliya','Galle','Matara','Hambantota','Jaffna','Kilinochchi','Mannar','Vavuniya','Trincomalee','Batticaloa','Ampara','Polonnaruwa','Anuradhapura','Puttalam','Kurunegala','Ratnapura','Kegalle','Badulla','Monaragala','Mullaitivu'] as $d)
              <option value="{{ $d }}" {{ old('district', $donor->district) === $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>Donation Center</label>
          <select name="donation_center">
            <option value="">Nearest center</option>
            @foreach(['NBTS Colombo','NBTS Kandy','NBTS Kurunegala','NBTS Galle','NBTS Jaffna'] as $c)
              <option value="{{ $c }}" {{ old('donation_center', $donor->donation_center) === $c ? 'selected' : '' }}>{{ $c }}</option>
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
          <label>Blood Group</label>
          <input type="hidden" name="blood_group" id="bloodGroupInput" value="{{ old('blood_group', $donor->blood_group) }}">
          <div class="blood-grid">
            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
              <button type="button" class="blood-btn {{ old('blood_group', $donor->blood_group) === $bg ? 'selected' : '' }}"
                      onclick="selectBlood(this,'{{ $bg }}')">{{ $bg }}</button>
            @endforeach
          </div>
          @error('blood_group')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="form-row tri">
        <div class="field">
          <label>Weight (kg)</label>
          <input type="number" name="weight_kg" value="{{ old('weight_kg', $donor->weight_kg) }}" min="30" max="200" step="0.1"
                 class="{{ $errors->has('weight_kg') ? 'is-invalid' : '' }}">
          @error('weight_kg')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Hemoglobin (g/dL)</label>
          <input type="number" name="hemoglobin" value="{{ old('hemoglobin', $donor->hemoglobin) }}" step="0.1"
                 class="{{ $errors->has('hemoglobin') ? 'is-invalid' : '' }}">
          @error('hemoglobin')<span class="error-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label>Medical Conditions</label>
          <select name="medical_condition">
            @foreach(['' => 'None', 'Diabetes (controlled)' => 'Diabetes (controlled)', 'Hypertension (controlled)' => 'Hypertension (controlled)', 'Asthma' => 'Asthma', 'Other' => 'Other (specify below)'] as $val => $label)
              <option value="{{ $val }}" {{ old('medical_condition', $donor->medical_condition) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row full">
        <div class="field">
          <label>Additional Health Notes</label>
          <textarea name="medical_notes" placeholder="Any allergies, medications, or health conditions...">{{ old('medical_notes', $donor->medical_notes) }}</textarea>
        </div>
      </div>

      <p class="helper">Total donations and last donation date are tracked automatically from your recorded donation history and can't be edited here.</p>
    </div>

    <div class="form-footer">
      <a href="{{ route('donor.dashboard') }}" class="btn-back">Cancel</a>
      <button type="submit" class="btn-submit">Save Changes</button>
    </div>

  </div>
</form>

<script>
function selectBlood(btn, val) {
  document.querySelectorAll('.blood-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  document.getElementById('bloodGroupInput').value = val;
}

function selectGender(btn, val) {
  document.querySelectorAll('.gender-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  document.getElementById('genderInput').value = val;
}
</script>
