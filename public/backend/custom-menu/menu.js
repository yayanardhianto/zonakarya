(function ($) {
    "use strict";
    $(document).ready(function () {
        $("#default-item-select").change(function () {
            var selectedOption = $(this).find("option:selected");
            var label = selectedOption.data("label") || "";
            var url = selectedOption.data("url") || "";
            $("#add_item_url").val(url);
            $("#add_item_name").val(label);

            if (url) {
                $("#custom_item").val(0);
                $("#add_item_url").prop("disabled", true);
            } else {
                $("#custom_item").val(1);
                $("#add_item_url").prop("disabled", false);
            }
        });

        // activate Nestable for list
        $("#nestable").nestable({ group: 1, maxDepth: 3 });
    });
    $(document).on("click", ".editItemData", function(e) {
        $("#update_item_name").val($(this).data('label'));
        $("#update_item_url").val($(this).data('link')).prop("readonly", !parseInt($(this).data('custom_item')));
        $("#update_item_id").val($(this).data('id'));
        $("#update_open_new_tab").prop("checked", Boolean(parseInt($(this).data('open_new_tab'))));
        $("#editModal").modal("show");
    });
    $(document).on("click", ".addMenuItem", function(e) {
        const button = $(this);
        const spinner = $(".item-spinner");
        $.ajax({
            data: {
                custom_item: $("#custom_item").val(),
                label: $("#add_item_name").val(),
                link: $("#add_item_url").val(),
                menu_id: $("#menu_id").val(),
                open_new_tab: $("#open_new_tab").is(":checked") ? 1 : 0,
            },
    
            url: addItemUrl,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            beforeSend: function (xhr) {
                spinner.toggleClass("d-none");
                $(button).prop("disabled", true);
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.reload();
                }else if(!response.success){
                    toastr.warning(response.message);
                } else {
                    toastr.error(menus.itemAddFailed);
                }
            },
            error: function (xhr, status, error) {
                toastr.error(menus.failed);
            },
            complete: function () {
                spinner.toggleClass("d-none");
                $(button).prop("disabled", false);
            },
        });
    });
    $(document).on("click", ".updateMenuName", function(e) {
        const button = $(this);
        $.ajax({
            data: {
                id: $("#menu_id").val(),
                name: $("#menu_name").val(),
                code: $("#language_code").val(),
            },

            url: menuNameUpdate,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            beforeSend: function (xhr) {
                $(button).find("i").toggleClass("update-icon-rotate");
                $(button).prop("disabled", true);
            },
            success: function (response) {
                if (response.success) {
                    updateMenu(e);
                }else if(!response.success){
                    toastr.warning(response.message);
                } else {
                    toastr.error(menus.itemAddFailed);
                }
            },
            error: function (xhr, status, error) {
                toastr.error(menus.failed);
            },
            complete: function () {
                $(button).find("i").toggleClass("update-icon-rotate");
                $(button).prop("disabled", false);
            },
        });
    });

    $(document).on("click", ".updateMenu", function(e) {
        var data = $("#nestable").nestable("serialize");
        const button = $(this);
        const spinner = $(".menu-update-spinner");
        $.ajax({
            data: { data },

            url: menuUpdate,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            beforeSend: function (xhr) {
                spinner.toggleClass("d-none");
                $(button).prop("disabled", true);
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(menus.updated);
                } else {
                    toastr.error(menus.updateFailed);
                }
            },
            error: function (xhr, status, error) {
                toastr.error(menus.failed);
            },
            complete: function () {
                spinner.toggleClass("d-none");
                $(button).prop("disabled", false);
            },
        });
    });
})(jQuery);