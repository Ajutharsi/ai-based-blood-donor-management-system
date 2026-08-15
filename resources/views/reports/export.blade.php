<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report</title>
<style>
  body{font-family:DejaVu Sans, Arial, sans-serif;color:#1E293B;font-size:12px;}
  h1{font-size:18px;color:#1E3A8A;margin-bottom:2px;}
  .meta{font-size:11px;color:#64748B;margin-bottom:4px;}
  .filters{font-size:10px;color:#64748B;margin-bottom:14px;}
  table{width:100%;border-collapse:collapse;margin-top:10px;}
  th{background:#EFF6FF;color:#1E3A8A;text-align:left;padding:6px 8px;font-size:10.5px;border:1px solid #BFDBFE;}
  td{padding:5px 8px;border:1px solid #E2E8F0;font-size:10.5px;}
  tr:nth-child(even) td{background:#F8FAFC;}
</style>
</head>
<body>
  <h1>{{ $title }}</h1>
  <div class="meta">Generated {{ $generatedAt }} &middot; {{ count($rows) }} record{{ count($rows) === 1 ? '' : 's' }}</div>
  @if($filtersSummary)
    <div class="filters">Filters: {{ $filtersSummary }}</div>
  @endif
  <table>
    <thead>
      <tr>
        @foreach($columns as $col)
          <th>{{ $col }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr>
          @foreach($row as $cell)
            <td>{{ $cell }}</td>
          @endforeach
        </tr>
      @empty
        <tr><td colspan="{{ count($columns) }}">No records match these filters.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
