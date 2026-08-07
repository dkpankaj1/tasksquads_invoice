  <form action="{{ route('logout') }}" method="post" id="adminLogoutForm">
      @csrf
  </form>
  <form action="{{ route('themes.toggle') }}" method="post" id="themesToggleForm">
      @csrf
  </form>
