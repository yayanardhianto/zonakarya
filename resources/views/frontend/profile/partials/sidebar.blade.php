<div class="col-lg-4 col-xl-3 ">
    <div class="wsus__dashboard_sidebar">
        <div class="wsus__dashboard_sidebar_top">
            <div class="wsus__dashboard_profile_img">
                <img id="profile_img"
                    src="{{ !empty(userAuth()?->image) ? asset(userAuth()?->image) : asset($setting?->default_avatar) }}"
                    alt="{{ userAuth()?->name }}" class="img-fluid w-100 h-100">
                <label for="profile_photo"><i class="fas fa-camera fa-2x text-white"></i></label>
                <form action="{{ route('user.update.profile-image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="profile_photo" name="image" hidden>
                    <button id="update_profile_image" class="btn style2 py-1 px-2 my-1 d-none">
                        <span class="link-effect">
                            <span class="effect-1">{{ __('Update') }}</span>
                            <span class="effect-1">{{ __('Update') }}</span>
                        </span>
                    </button>
                </form>
            </div>
            <h5>{{ userAuth()?->name }}</h5>
            <p>{{ userAuth()?->address }}</p>
        </div>
        <ul class="wsus__deshboard_menu">
            <li>
                <a class="{{ isRoute(['user.dashboard', 'user.profile.edit'], 'active') }}"
                    href="{{ route('dashboard') }}"><i class="fas fa-user-tie"></i>
                    {{ __('Profile') }}</a>
            </li>

            <li>
                <a class="{{ isRoute('user.change-password', 'active') }}"
                    href="{{ route('user.change-password') }}"><i class="fas fa-key"></i>
                    {{ __('Change Password') }}</a>

            </li>
            <li>
                <a role="button" class="logout-button"><i class="fas fa-unlock-alt"></i> {{ __('Log Out') }}
                    <form action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            </li>
        </ul>
    </div>
</div>
