<script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('frontend/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/js/gsap.min.js') }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>

<script src="{{ asset('frontend/js/twinmax.js') }}"></script>
<script src="{{ asset('frontend/js/imageRevealHover.js') }}"></script>
<script src="{{ asset('frontend/js/jarallax.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.marquee.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('frontend/js/waypoints.js') }}"></script>
<script src="{{ asset('frontend/js/wow.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('frontend/js/custom.js') }}?v={{ $setting?->version }}"></script>

<script>
    "use strict";
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.options.positionClass = 'toast-bottom-right';

    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            toastr.info("{{ $value }}");
            break;
        case 'success':
            toastr.success("{{ $value }}");
            break;
        case 'warning':
            toastr.warning("{{ $value }}");
            break;
        case 'error':
            toastr.error("{{ $value }}");
            break;
    }
    @endsession
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}', null, {
                timeOut: 10000
            });
        </script>
    @endforeach
@endif


@if ($setting?->recaptcha_status === 'active')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

@if ($setting?->tawk_status == 'active')
    <script type="text/javascript">
        "use strict";
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $setting?->tawk_chat_link }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif


@if ($setting?->cookie_status == 'active')
    <script src="{{ asset('frontend/js/cookieconsent.min.js') }}"></script>

    <script>
        "use strict";
        window.addEventListener("load", function() {
            window.wpcc.init({
                "border": "{{ $setting?->border }}",
                "corners": "{{ $setting?->corners }}",
                "colors": {
                    "popup": {
                        "background": "{{ $setting?->background_color }}",
                        "text": "{{ $setting?->text_color }} !important",
                        "border": "{{ $setting?->border_color }}"
                    },
                    "button": {
                        "background": "{{ $setting?->btn_bg_color }}",
                        "text": "{{ $setting?->btn_text_color }}"
                    }
                },
                "content": {
                    "href": "{{ route('privacy-policy') }}",
                    "message": "{{ $setting?->message }}",
                    "link": "{{ $setting?->link_text }}",
                    "button": "{{ $setting?->btn_text }}"
                }
            })
        });
    </script>
@endif
@include('frontend.js-variables')
@stack('js')
