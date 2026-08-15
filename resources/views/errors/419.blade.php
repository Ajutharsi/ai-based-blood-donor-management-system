<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Session Expired — LifeLink</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:#F8FAFC;color:#1E293B;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;}
  .code{font-family:'Playfair Display',serif;font-size:4rem;font-weight:700;color:#D97706;}
  h1{font-size:1.3rem;margin:0.5rem 0;}
  p{color:#64748B;margin-bottom:1.5rem;}
  a{display:inline-block;padding:0.65rem 1.4rem;background:#1D4ED8;color:#fff;border-radius:8px;text-decoration:none;font-weight:500;}
  a:hover{background:#1E3A8A;}
</style>
</head>
<body>
<div>
  <div class="code">419</div>
  <h1>Session Expired</h1>
  <p>Your session expired for your security. Please go back and try again.</p>
  <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}">Go Back</a>
</div>
</body>
</html>
