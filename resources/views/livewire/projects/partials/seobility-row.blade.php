<tr class="hover:bg-slate-50/50 transition">
    <td class="px-8 py-4 font-bold text-slate-600 italic">{{ $label }}</td>
    <td class="px-8 py-4 font-black text-slate-900">{{ $curr }}</td>
    <td class="px-8 py-4">
        @php $diff = $curr - $old; @endphp
        @if($diff != 0)
            <span class="text-[10px] font-black px-2 py-1 rounded-lg {{ $diff > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                {{ $diff > 0 ? '+' : '' }}{{ $diff }}
            </span>
        @else
            <span class="text-slate-300 text-[10px]">0</span>
        @endif
    </td>
</tr>