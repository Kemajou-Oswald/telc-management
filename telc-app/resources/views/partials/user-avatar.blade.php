@php
    $size = $size ?? 30;
    $class = $class ?? '';
    $user = $user ?? null;
    $avatarUrl = $user && $user->avatar_path ? Storage::url($user->avatar_path) : null;
    $initials = $user && $user->name ? strtoupper(substr($user->name, 0, 2)) : '??';
@endphp

<span class="user-avatar-chip {{ $class }}" style="width: {{ $size }}px; height: {{ $size }}px;">
    @if ($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="Avatar" class="user-avatar-img" style="width: {{ $size }}px; height: {{ $size }}px; object-fit: cover;">
    @else
        {{ $initials }}
    @endif
</span>
