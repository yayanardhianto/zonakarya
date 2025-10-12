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

        //add to cart method
        $(document).on("click", ".add-to-cart-btn", function (e) {
            e.preventDefault();
            const slug = $(this).data("slug");
            const qty = $(".quantity-input-value").val() ?? 1;

            $.ajax({
                url: `${base_url}/add-to-cart/${slug}?qty=${qty}`,
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        $(".widget_shopping_cart_content.cart-content").html(
                            response.content
                        );
                        cartItemRemoveFromSidebar();
                        $(".cart-count").html(response.count);
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: (data) => {
                    const errorMessage =
                        data.responseJSON?.message || basic_error_message;
                    toastr.error(errorMessage);
                },
                complete: hidePreLoader,
            });
        });
        //wishlist method
        $(document).on("click", ".wsus-wishlist-btn", function (e) {
            e.preventDefault();
            const slug = $(this).data("slug");

            $.ajax({
                url: `${base_url}/user/wishlist/${slug}`,
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        $(this).find("i").toggleClass("fas far");
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: (data) => {
                    const errorMessage =
                        data.responseJSON?.message || basic_error_message;
                    toastr.error(errorMessage);
                },
                complete: hidePreLoader,
            });
        });
        //wishlist method
        $(document).on("click", ".wishlist-remove", function (e) {
            e.preventDefault();
            const slug = $(this).data("slug");

            $.ajax({
                url: `${base_url}/user/wishlist/${slug}`,
                type: "DELETE",
                dataType: "json",
                beforeSend: function () {
                    showPreLoader();
                },
                success: (response) => {
                    if (response.success) {
                        $(
                            ".wsus__dashboard_main_contant.wishlist-content"
                        ).html(response.content);
                        toastr.success(response.message);
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

        let timeout;
        //cart qty plus
        $(document).on("click", ".cart-qty-plus", function (e) {
            e.preventDefault();
            var $qty = $(this).siblings(".qty-input");
            var currentVal = parseInt($qty.val(), 10);
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }

            var rowId = $(this).data("id");
            var qty = $qty.val();
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                updateQuantity(rowId, qty);
            }, 1000);
        });
        //cart qty plus
        $(document).on("click", ".cart-qty-minus", function (e) {
            e.preventDefault();
            var $qty = $(this).siblings(".qty-input");
            var currentVal = parseInt($qty.val(), 10);
            if (!isNaN(currentVal) && currentVal > 1) {
                $qty.val(currentVal - 1);
            }

            var rowId = $(this).data("id");
            var qty = $qty.val();
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                updateQuantity(rowId, qty);
            }, 1000);
        });
        //print method
        $(document).on("click", ".print_invoice_btn", function (e) {
            e.preventDefault();
            let $body = $("body").html();
            let $data = $("#order_invoice_print").html();
            $("body").html($data);
            window.print();
            $("body").html($body);
        });
        
        $(document).on("click", ".blog-comment-reply-toggle", function (e) {
            e.preventDefault();
            var selector = $(this).data("selector");
            $("." + selector).toggleClass("d-none");
        });
    });

    // Contact form submission handler
    function contactFormSubmission(formSelector) {
        $(document).on("submit", formSelector, function (e) {
            e.preventDefault();
            const form = $(formSelector);
            const formData = form.serialize();
            const btnSelector = ".contact-form .btn .effect-1";
            const btnText = $(btnSelector).html();
            const beforeSendText = `${sending} ... <i class="fas fa-spinner fa-spin"></i>`;

            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function () {
                    $(btnSelector).html(beforeSendText);
                    $(btnSelector).closest(".btn").attr("disabled", true);
                },
                success: (response) => {
                    if (response.success) {
                        form.find("input, textarea").val("");
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: (error) => {
                    if (error.status == 422) {
                        $.each(
                            error.responseJSON?.message,
                            function (key, message) {
                                toastr.error(message);
                            }
                        );
                    } else {
                        toastr.error(
                            error.responseJSON?.message || basic_error_message
                        );
                    }
                },
                complete: function () {
                    $(btnSelector).html(btnText);
                    $(btnSelector).closest(".btn").removeAttr("disabled");
                },
            });
        });
    }
    contactFormSubmission("#contact-form");
    contactFormSubmission("#team-form");

    // newsletter form submit
    $(document).on("submit", "#newsletter-form", function (e) {
        e.preventDefault();
        const form = $("#newsletter-form");
        const formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $(".newsletter-form .btn").html(
                    '<i class="fas fa-spinner fa-spin"></i>'
                );
                $(".newsletter-form .btn").attr("disabled", true);
            },
            success: (response) => {
                if (response.success) {
                    form.find("input").val("");
                    toastr.success(response.message);
                } else {
                    toastr.warning(response.message);
                }
            },
            error: (data) => {
                const errorMessage =
                    data.responseJSON?.message || basic_error_message;
                toastr.error(errorMessage);
            },
            complete: function () {
                $(".newsletter-form .btn").html(
                    '<i class="fas fa-paper-plane"></i>'
                );
                $(".newsletter-form .btn").removeAttr("disabled");
            },
        });
    });

    $(document).on("click", ".wpcc-btn", function () {
        $(".wpcc-container").fadeOut(1000);
    });

    // Nice select
    $(".select_js").niceSelect();

    ("use strict");
    $(document).ready(function () {
        setupChangeHandler("#setLanguageHeader");
        setupChangeHandler("#setCurrencyHeader");
        $("form").attr("autocomplete", "off");
    });
    function setupChangeHandler(formSelector) {
        var $form = $(formSelector);
        var $select = $form.find("select");
        var previousValue = $select.val();

        $select.on("change", function (e) {
            var currentValue = $(this).val();
            if (currentValue !== previousValue) $form.trigger("submit");
            previousValue = currentValue;
        });
    }
    $(document).on("click", ".removeFromCart", function (e) {
        e.preventDefault();
        var rowId = $(this).data("rowid");
        const path = window.location.pathname;
        removeFromCart(rowId);
    });
    cartItemRemoveFromSidebar();
})(jQuery);

function cartItemRemoveFromSidebar() {
    document.querySelectorAll(".remove_from_cart_button").forEach((item) => {
        item.addEventListener("click", function () {
            let rowId = this.getAttribute("data-rowid");
            if(rowId){
                removeFromCart(rowId);
            }
        });
    });
}
//remove from cart method
function removeFromCart(rowId) {
    const path = window.location.pathname;
    $.ajax({
        url: `${base_url}/remove-from-cart/${rowId}`,
        type: "GET",
        dataType: "json",
        beforeSend: function () {
            showPreLoader();
        },
        success: (response) => {
            if (response.success) {
                $(".cart-count").html(response.count);
                $(".widget_shopping_cart_content.cart-content").html(
                    response.sidebar
                );
                cartItemRemoveFromSidebar();
                $(".woocommerce-cart-form.checkout-summary-content").html(
                    response.checkout_page
                );
                $(".container.cart-page-content").html(response.cart_page);
                if (response.total_item == 0 && path != "/shop") {
                    window.location.href = `${base_url}/shop`;
                }
                toastr.success(response.message);
            } else {
                toastr.warning(response.message);
            }
        },
        error: (data) => {
            toastr.error(data.responseJSON?.message || basic_error_message);
        },
        complete: hidePreLoader,
    });
}
//Update cart quantity
function updateQuantity(rowId, qty) {
    $.ajax({
        url: `${base_url}/update-cart/${rowId}`,
        type: "POST",
        dataType: "json",
        data: { qty: qty },
        beforeSend: function () {
            showPreLoader();
        },
        success: (response) => {
            if (response.success) {
                $(".cart-count").html(response.count);
                $(".widget_shopping_cart_content.cart-content").html(
                    response.sidebar
                );
                cartItemRemoveFromSidebar();
                $(".container.cart-page-content").html(response.cart_page);
                toastr.success(response.message);
            } else {
                $(".container.cart-page-content").html(response.cart_page);
                toastr.warning(response.message);
            }
        },
        error: (data) => {
            toastr.error(data.responseJSON?.message || basic_error_message);
        },
        complete: hidePreLoader,
    });
}
