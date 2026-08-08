<div data-simplebar>
    <ul class="app-menu">

        <li class="menu-title">Menu</li>

        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="airplay"></i></span>
                <span class="menu-text"> Dashboards </span>
            </a>
        </li>

        <li class="menu-title">Invoices</li>

        <li class="menu-item">
            <a href="{{ route('invoice.index') }}" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="file-text"></i></span>
                <span class="menu-text"> Invoice </span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('payment.index') }}" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="credit-card"></i></span>
                <span class="menu-text"> Payments </span>
            </a>
        </li>

        <li class="menu-title">Item Master</li>

        <li class="menu-item">
            <a href="{{ route('item.index') }}" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="archive"></i></span>
                <span class="menu-text"> Items </span>
            </a>
        </li>

        <li class="menu-title">People</li>

        <li class="menu-item">
            <a href="{{ route('customer.index') }}" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="users"></i></span>
                <span class="menu-text"> Customers </span>
            </a>
        </li>
        <li class="menu-title">Preferences</li>

        <li class="menu-item">
            <a href="#menuMaster" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="settings-2"></i></span>
                <span class="menu-text"> Masters </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuMaster">

                <ul class="sub-menu">

                    <li class="menu-item">
                        <a href="{{ route('category.index') }}" class="menu-link">
                            <span class="menu-text">Category</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('unit.index') }}" class="menu-link">
                            <span class="menu-text">Unit</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('tax.index') }}" class="menu-link">
                            <span class="menu-text">Tax Type</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('customization.index') }}" class="menu-link">
                            <span class="menu-text">Customization</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('finance-year.index') }}" class="menu-link">
                            <span class="menu-text">Finance Year</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('currency.index') }}" class="menu-link">
                            <span class="menu-text">Currency</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>


        <li class="menu-item">
            <a href="#menuSetting" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="settings"></i></span>
                <span class="menu-text"> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuSetting">
                <ul class="sub-menu">

                    <li class="menu-item">
                        <a href="{{ route('systemSettings.edit') }}" class="menu-link">
                            <span class="menu-text">System Settings</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('settings.edit') }}" class="menu-link">
                            <span class="menu-text">General Settings</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>


        <li class="menu-title">Accounts</li>

        <li class="menu-item">
            <a href="#menuAccount" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="copy"></i></span>
                <span class="menu-text"> My Account </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuAccount">
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a href="{{ route('account.index') }}" class="menu-link">
                            <span class="menu-text">My Account</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('account.update') }}" class="menu-link">
                            <span class="menu-text">Update Profile</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('account.password') }}" class="menu-link">
                            <span class="menu-text">Change Password</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

    </ul>
</div>
