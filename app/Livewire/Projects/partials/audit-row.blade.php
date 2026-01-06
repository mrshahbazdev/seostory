<tr class="hover:bg-gray-50/50 transition group">
    <td class="px-10 py-5 text-sm font-bold text-gray-600 italic group-hover:text-indigo-600 transition">{{ $label }}</td>
    <td class="px-10 py-5 font-black text-gray-900 text-lg">{{ $current }}</td>
    <td class="px-10 py-5">
        @php $diff = $current - $prev; @endphp
        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $diff > 0 ? 'bg-rose-50 text-rose-600' : ($diff < 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400') }}">
            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
        </span>
    </td>
</tr>