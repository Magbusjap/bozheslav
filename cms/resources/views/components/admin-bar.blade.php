@auth
<div style="
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: #1a1a1a;
    color: #fff;
    font-family: system-ui, sans-serif;
    font-size: 13px;
    height: 36px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
">
    <span style="color: #f0a500; font-weight: 600;">⚡ bozheslav.com</span>
    
    <a href="/admin" style="color: #ccc; text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#ccc'">
        Админка
    </a>

    @isset($editUrl)
    <a href="{{ $editUrl }}" style="color: #ccc; text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#ccc'">
        ✏️ {{ $editLabel ?? 'Редактировать' }}
    </a>
    @endisset

    <span style="margin-left: auto; color: #888;">{{ auth()->user()->name }}</span>

    <a href="/admin/logout" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       style="color: #ccc; text-decoration: none;"
       onmouseover="this.style.color='#fff'" 
       onmouseout="this.style.color='#ccc'">
        Выйти
    </a>

    <form id="logout-form" action="/admin/logout" method="POST" style="display:none;">
        @csrf
    </form>
</div>
<div style="height: 36px;"></div>
<script>
    document.body.classList.add('has-admin-bar');
</script>
@endauth
