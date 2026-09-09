@extends('estruturas.principal', ['title' => $pageTitle ?? 'Administração'])
@section('content')
<div class="admin-shell">
    <a class="admin-skip" href="#admin-content">Ir para o conteúdo</a>
    @include('parciais.menu-lateral')
    <main class="admin-main" id="admin-content">
        @if(session('status'))
            <div class="admin-panel backend-feedback" role="status">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="admin-panel backend-feedback" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('admin-content')
    </main>
</div>
@endsection
