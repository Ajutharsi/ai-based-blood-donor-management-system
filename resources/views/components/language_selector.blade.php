@php
  $langSelCurrent = app()->getLocale();
  $langSelOptions = ['en' => 'English', 'si' => 'සිංහල', 'ta' => 'தமிழ்'];
@endphp
<style>
  .lang-sel-wrap{position:relative;font-family:'DM Sans',sans-serif;}
  .lang-sel-btn{display:flex;align-items:center;gap:6px;padding:0.4rem 0.75rem;border:1px solid var(--gray-b,#E2E8F0);border-radius:7px;background:white;font-size:0.78rem;color:var(--muted,#64748B);cursor:pointer;font-family:inherit;}
  .lang-sel-btn:hover{border-color:var(--primary,#1D4ED8);color:var(--primary,#1D4ED8);}
  .lang-sel-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:1.75;flex-shrink:0;}
  .lang-sel-dd{position:absolute;top:calc(100% + 6px);right:0;background:white;border:1px solid var(--border,rgba(29,78,216,0.12));border-radius:10px;box-shadow:0 10px 30px rgba(15,23,42,0.14);min-width:150px;z-index:300;display:none;overflow:hidden;}
  .lang-sel-dd.open{display:block;}
  .lang-sel-dd form{margin:0;}
  .lang-sel-opt{display:block;width:100%;text-align:left;padding:0.6rem 0.9rem;border:none;background:none;font-family:inherit;font-size:0.82rem;color:var(--text,#1E293B);cursor:pointer;}
  .lang-sel-opt:hover{background:var(--off,#F8FAFC);}
  .lang-sel-opt.active{color:var(--primary,#1D4ED8);font-weight:600;background:var(--primary-light,#EFF6FF);}
</style>
<div class="lang-sel-wrap">
  <button type="button" class="lang-sel-btn" onclick="langSelToggle(event)">
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 010 20 15.3 15.3 0 010-20z"/></svg>
    {{ $langSelOptions[$langSelCurrent] ?? 'English' }}
  </button>
  <div class="lang-sel-dd" id="langSelDropdown">
    @foreach($langSelOptions as $code => $label)
      <form method="POST" action="{{ route('language.switch', $code) }}">
        @csrf
        <button type="submit" class="lang-sel-opt {{ $langSelCurrent === $code ? 'active' : '' }}">{{ $label }}</button>
      </form>
    @endforeach
  </div>
</div>
<script>
  function langSelToggle(e) {
    e.stopPropagation();
    document.querySelectorAll('.lang-sel-dd.open').forEach(function (el) {
      if (el.id !== 'langSelDropdown') el.classList.remove('open');
    });
    document.getElementById('langSelDropdown').classList.toggle('open');
  }
  document.addEventListener('click', function (e) {
    var dd = document.getElementById('langSelDropdown');
    if (dd && !dd.contains(e.target) && !e.target.closest('.lang-sel-btn')) {
      dd.classList.remove('open');
    }
  });
</script>
