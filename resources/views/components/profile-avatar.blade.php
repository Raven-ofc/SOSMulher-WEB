@if(auth()->user()->photo_path)<img class="profile-avatar" src="{{ route('admin.profile.photo') }}" alt="Foto de perfil" style="object-fit:cover">
@else<div class="profile-avatar" role="img" aria-label="Perfil sem foto">{{ mb_strtoupper(mb_substr(auth()->user()->name,0,1)) }}</div>@endif
