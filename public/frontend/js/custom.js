"use strict";
/** ajax-loader */
const loader = `<div class="ajax-loader"><div class="ajax-loader-inner"><span></span><span></span><span></span><span></span></div></div>`;
/** Scroll to an element.
 *
 * @param {string} selector - jQuery selector for the element to scroll to.
 */
function scrollToElement(selector) {
    var $element = $(selector);
    if ($element.length)
        $("html, body").animate({ scrollTop: $element.offset().top }, 50);
}
// preloader show
function showPreLoader() {
    $("body").prepend(loader);
}
// preloader hide
function hidePreLoader() {
    $(".ajax-loader").remove();
}
//social share fuction
function openSharePopup(url, platform) {
    let shareUrl = "";
    const width = screen.width * 0.7;
    const height = screen.height * 0.7;
    const left = screen.width / 2 - width / 2;
    const top = screen.height / 2 - height / 2;

    switch (platform) {
        case "facebook":
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
                url
            )}`;
            break;
        case "twitter":
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(
                url
            )}`;
            break;
        case "linkedin":
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(
                url
            )}`;
            break;
        case "pinterest":
            shareUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(
                url
            )}`;
            break;
        case "pocket":
            shareUrl = `https://getpocket.com/edit.php?url=${encodeURIComponent(
                url
            )}`;
            break;
        case "telegram":
            shareUrl = `https://telegram.me/share/url?url=${encodeURIComponent(
                url
            )}`;
            break;
        default:
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
                url
            )}`;
    }

    window.open(
        shareUrl,
        `Share on ${platform.charAt(0).toUpperCase() + platform.slice(1)}`,
        `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no,resizable=yes,scrollbars=yes`
    );
}
(function ($) {
    // ajax csrf token setup
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // On Document Load
    $(document).ready(function () {
        $(document).on("click", ".share-social", function (e) {
            e.preventDefault();
            var url = $(this).prop("href");
            var platform = $(this).data("platform");
            openSharePopup(url, platform);
        });
        $(document).on("click", ".logout-button", function (e) {
            e.preventDefault();
            $(this).find("form").submit();
        });

        $(document).on("mouseover", '[data-bs-toggle="tooltip"]', function () {
            if (!$(this).data("bs.tooltip")) {
                $(this).tooltip("show");
            }
        });

        //profile image change
        $('input[name="image"]').on("change", function () {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#profile_img").attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $("#update_profile_image").removeClass("d-none");
            }
        });

        //newsletter subscription
        $(document).on("click", ".newsletter-submit-btn", function (e) {
            e.preventDefault();
            const email = $(".newsletter-email-input").val();
            if (email.length === 0) {
                toastr.error(newsletter_required_msg || "Email is required.");
                return;
            }

            $.ajax({
                url: `${base_url}/newsletter-subscribe`,
                type: "POST",
                dataType: "json",
                data: { email: email },
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        toastr.success(response.message);
                        $(".newsletter-email-input").val("");
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: (data) => {
                    toastr.error(
                        data.responseJSON?.message || basic_error_message
                    );
                },
                complete: hidePreLoader,
            });
        });

        //contact form submission
        $(document).on("click", ".contact-submit-btn", function (e) {
            e.preventDefault();
            const form = $(this).closest("form");
            const formData = new FormData(form[0]);

            $.ajax({
                url: `${base_url}/contact-submit`,
                type: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        toastr.success(response.message);
                        form[0].reset();
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: (data) => {
                    toastr.error(
                        data.responseJSON?.message || basic_error_message
                    );
                },
                complete: hidePreLoader,
            });
        });

        //print invoice
        $(document).on("click", ".print-invoice", function (e) {
            e.preventDefault();
            let $body = $("body").html();
            let $data = $("#order_invoice_print").html();
            $("body").html($data);
            window.print();
            $("body").html($body);
        });

        //language change
        $(document).on("change", ".language-change", function (e) {
            e.preventDefault();
            const language = $(this).val();
            $.ajax({
                url: `${base_url}/set-language`,
                type: "POST",
                dataType: "json",
                data: { language: language },
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        window.location.reload();
                    }
                },
                error: (data) => {
                    toastr.error(
                        data.responseJSON?.message || basic_error_message
                    );
                },
                complete: hidePreLoader,
            });
        });

        //search functionality
        $(document).on("keyup", ".search-input", function (e) {
            clearTimeout(timeout);
            const query = $(this).val();
            const searchType = $(this).data("type") || "all";
            
            if (query.length < 2) {
                $(".search-results").hide();
                return;
            }

            timeout = setTimeout(function() {
                $.ajax({
                    url: `${base_url}/search`,
                    type: "GET",
                    dataType: "json",
                    data: { 
                        query: query,
                        type: searchType
                    },
                    success: (response) => {
                        if (response.success && response.results.length > 0) {
                            $(".search-results").html(response.html).show();
                        } else {
                            $(".search-results").hide();
                        }
                    },
                    error: (data) => {
                        $(".search-results").hide();
                    }
                });
            }, 300);
        });

        //hide search results when clicking outside
        $(document).on("click", function(e) {
            if (!$(e.target).closest(".search-container").length) {
                $(".search-results").hide();
            }
        });
    });
})(jQuery);
