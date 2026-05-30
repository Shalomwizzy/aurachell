@extends('layouts.admin')
@section('title', 'Staff')

@section('content')
<div class="p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold" style="color:var(--adm-text);">Staff Members</h1>
            <p class="text-sm mt-1" style="color:var(--adm-muted);">{{ $staff->count() }} team members</p>
        </div>
        <button onclick="document.getElementById('invite-modal').classList.remove('hidden')"
                class="px-5 py-2.5 text-xs tracking-[0.15em] uppercase font-medium transition-all hover:opacity-90"
                style="background:#371220;color:#FFFFFF;">
            + Invite Member
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.10);border:1px solid rgba(201,169,111,0.25);color:rgba(247,242,235,0.85);">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(201,169,111,0.08);border:1px solid rgba(201,169,111,0.20);color:rgba(247,242,235,0.85);">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-hidden" style="border:1px solid var(--adm-border);">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom:1px solid var(--adm-border);background:rgba(55,18,32,0.04);">
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Member</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden md:table-cell" style="color:var(--adm-muted);">Role</th>
                    <th class="text-left px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal hidden lg:table-cell" style="color:var(--adm-muted);">Joined</th>
                    <th class="text-center px-5 py-3.5 text-[10px] tracking-[0.2em] uppercase font-normal" style="color:var(--adm-muted);">Status</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                <tr style="border-bottom:1px solid var(--adm-border);" class="transition-colors hover:bg-[rgba(55,18,32,0.03)]">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold shrink-0"
                                 style="background:rgba(55,18,32,0.30);color:#C9A96F;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium" style="color:var(--adm-text);">
                                    {{ $member->name }}
                                    @if($member->id === auth()->id())
                                    <span class="text-[10px] ml-1" style="color:var(--adm-muted);">(you)</span>
                                    @endif
                                </p>
                                <p class="text-xs mt-0.5" style="color:var(--adm-muted);">{{ $member->email }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 hidden md:table-cell">
                        @if($member->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.staff.role', $member) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role"
                                    onchange="this.form.submit()"
                                    class="text-xs px-3 py-1.5 focus:outline-none cursor-pointer"
                                    style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);border-radius:2px;">
                                @foreach($roles as $role)
                                @if($role->name !== 'super_admin')
                                <option value="{{ $role->name }}"
                                    {{ $member->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', ucfirst($role->name)) }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </form>
                        @else
                        @foreach($member->roles as $role)
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase"
                              style="border:1px solid rgba(201,169,111,0.25);color:#C9A96F;">
                            {{ str_replace('_', ' ', $role->name) }}
                        </span>
                        @endforeach
                        @endif
                    </td>

                    <td class="px-5 py-4 hidden lg:table-cell text-xs" style="color:var(--adm-muted);">
                        {{ $member->created_at->format('M j, Y') }}
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if(!($member->is_blocked ?? false))
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase"
                              style="background:rgba(201,169,111,0.12);color:#C9A96F;">Active</span>
                        @else
                        <span class="text-[10px] px-2.5 py-1 tracking-wider uppercase"
                              style="background:rgba(55,18,32,0.10);color:rgba(247,242,235,0.45);">Inactive</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-right">
                        @if($member->id !== auth()->id())
                        <div class="flex items-center justify-end gap-4">
                            <button class="text-xs transition-colors" style="color:var(--adm-gold);"
                                    onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'"
                                    onclick="openPermModal({{ $member->id }}, '{{ addslashes($member->name) }}', {{ $member->permissions->pluck('name')->toJson() }}, '{{ route('admin.staff.permissions', $member) }}')">
                                Permissions
                            </button>
                            <form method="POST" action="{{ route('admin.staff.toggle', $member) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs transition-colors" style="color:var(--adm-muted);"
                                        onmouseover="this.style.color='var(--adm-text)'" onmouseout="this.style.color='var(--adm-muted)'">
                                    {{ ($member->is_blocked ?? false) ? 'Activate' : 'Deactivate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.staff.destroy', $member) }}"
                                  onsubmit="return confirm('Remove {{ $member->name }}?')">
                                @csrf @method('DELETE')
                                <button class="text-xs transition-colors" style="color:rgba(55,18,32,0.5);"
                                        onmouseover="this.style.color='#371220'" onmouseout="this.style.color='rgba(55,18,32,0.5)'">Remove</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-sm" style="color:var(--adm-muted);">No staff members yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Permissions Modal --}}
@php
$permGroups = [
    'Products'         => ['products.view','products.create','products.edit','products.delete'],
    'Orders'           => ['orders.view','orders.create','orders.edit','orders.delete','orders.update_status'],
    'Customers'        => ['users.view','users.edit','users.delete'],
    'Content & Catalog'=> ['categories.manage','reviews.moderate','messages.respond','chat.view'],
    'Finance'          => ['coupons.manage','payments.view','reports.view'],
    'System'           => ['settings.manage','staff.invite','staff.manage','roles.manage'],
];
$existingPerms = $allPermissions->pluck('name')->toArray();
@endphp

<div id="perm-modal" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display:none;background:rgba(0,0,0,0.65);">
    <div class="w-full max-w-xl max-h-[90vh] flex flex-col" style="background:var(--adm-card);border:1px solid var(--adm-border);">
        <div class="flex items-center justify-between px-6 py-4 shrink-0" style="border-bottom:1px solid var(--adm-border);">
            <div>
                <h2 class="text-base font-semibold" style="color:var(--adm-text);">Permissions</h2>
                <p id="perm-modal-subtitle" class="text-xs mt-0.5" style="color:var(--adm-muted);"></p>
            </div>
            <button onclick="document.getElementById('perm-modal').style.display='none'" style="color:var(--adm-muted);" class="hover:opacity-70">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="perm-form" method="POST" class="flex-1 overflow-y-auto p-6 space-y-5">
            @csrf @method('PUT')
            @foreach($permGroups as $groupName => $perms)
            @php $available = array_intersect($perms, $existingPerms); @endphp
            @if(count($available))
            <div>
                <p class="text-[10px] tracking-[0.2em] uppercase mb-2.5" style="color:var(--adm-gold);">{{ $groupName }}</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($available as $perm)
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                               class="perm-checkbox w-4 h-4 cursor-pointer accent-[#371220]">
                        <span class="text-xs group-hover:opacity-100 transition-opacity" style="color:var(--adm-text);opacity:0.80;">
                            {{ str_replace(['.','_'], [': ', ' '], $perm) }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
            <div class="pt-2 flex gap-3 shrink-0">
                <button type="submit"
                        class="flex-1 py-2.5 text-xs tracking-[0.2em] uppercase font-medium hover:opacity-90 transition-opacity"
                        style="background:#371220;color:#FFFFFF;">
                    Save Permissions
                </button>
                <button type="button" onclick="document.getElementById('perm-modal').style.display='none'"
                        class="px-5 py-2.5 text-xs tracking-wider uppercase transition-colors"
                        style="border:1px solid var(--adm-border);color:var(--adm-muted);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPermModal(id, name, currentPerms, actionUrl) {
    document.getElementById('perm-modal-subtitle').textContent = name;
    document.getElementById('perm-form').action = actionUrl;
    document.querySelectorAll('.perm-checkbox').forEach(function(cb) {
        cb.checked = currentPerms.includes(cb.value);
    });
    document.getElementById('perm-modal').style.display = 'flex';
}
</script>

{{-- Invite Modal --}}
<div id="invite-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/60" onclick="document.getElementById('invite-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md p-8" style="background:var(--adm-card);border:1px solid var(--adm-border);">
        <h2 class="text-lg font-semibold mb-6" style="color:var(--adm-text);">Invite Team Member</h2>
        <form method="POST" action="{{ route('admin.staff.invite') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-2" style="color:var(--adm-muted);">Full Name *</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2.5 text-sm focus:outline-none"
                       style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-2" style="color:var(--adm-muted);">Email *</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2.5 text-sm focus:outline-none"
                       style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase mb-2" style="color:var(--adm-muted);">Role *</label>
                <select name="role" required
                        class="w-full px-4 py-2.5 text-sm focus:outline-none"
                        style="background:var(--adm-surface);border:1px solid var(--adm-border);color:var(--adm-text);">
                    @foreach($roles as $role)
                    @if($role->name !== 'super_admin')
                    <option value="{{ $role->name }}">{{ str_replace('_', ' ', ucfirst($role->name)) }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3 text-xs tracking-[0.2em] uppercase font-medium hover:opacity-90 transition-opacity"
                        style="background:#371220;color:#FFFFFF;">
                    Send Invite
                </button>
                <button type="button"
                        onclick="document.getElementById('invite-modal').classList.add('hidden')"
                        class="px-5 py-3 text-xs tracking-wider uppercase transition-colors"
                        style="border:1px solid var(--adm-border);color:var(--adm-muted);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
