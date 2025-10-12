@if (Module::isEnabled('Blog') && Route::has('admin.blogs.index'))
    <li
        class="nav-item dropdown {{ isRoute(['admin.blogs.*', 'admin.blog-category.*', 'admin.blog-comment.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i
                class="ki-solid ki-notepad"></i><span>{{ __('Manage Blogs') }}</span></a>

        <ul class="dropdown-menu">
             @adminCan('blog.category.view')
                <li class="{{ isRoute('admin.blog-category.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.blog-category.index') }}">
                        {{ __('Category List') }}
                    </a>
                </li>
            @endadminCan
            @adminCan('blog.view')
                <li class="{{ isRoute('admin.blogs.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.blogs.index') }}">
                        {{ __('Blog List') }}
                    </a>
                </li>
            @endadminCan
            @adminCan('blog.comment.view')
                <li class="{{ isRoute('admin.blog-comment.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.blog-comment.index') }}">
                        {{ __('Blog Comments') }}
                    </a>
                </li>
            @endadminCan
        </ul>
    </li>
@endif
