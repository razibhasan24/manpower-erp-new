<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:sans-serif;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #999;padding:4px;text-align:left}.r{text-align:right}</style></head><body><h2>Income Statement</h2><p>{{ $report_title ?? '' }}</p>
<p>Total Income: {{ number_format($total_income, 2) }}</p>
<p>Total Expenses: {{ number_format($total_expenses, 2) }}</p>
<p>Net Profit: {{ number_format($net_profit, 2) }}</p>
<table><thead><tr><th>Code</th><th>Account</th><th class="r">Amount</th></tr></thead>
<tbody>
@foreach($income as $row)<tr><td>{{ $row['code'] }}</td><td>{{ $row['name'] }}</td><td class="r">{{ number_format($row['amount'], 2) }}</td></tr>@endforeach
@foreach($expenses as $row)<tr><td>{{ $row['code'] }}</td><td>{{ $row['name'] }}</td><td class="r">{{ number_format($row['amount'], 2) }}</td></tr>@endforeach
</tbody></table>
</body></html>