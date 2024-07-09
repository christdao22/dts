/**
 * The function `initJQuery` ensures that a given function is executed when the jQuery library is
 * loaded on a web page.
 * @param func
 */
function initJQuery(func) {
    if (window.jQuery) $(document).ready(func);
}

/**
 * The function initializes a DataTable on the specified selector using jQuery.
 * @param selector
 */
function initializeDataTable(selector) {
    $(selector).DataTable();
}

/**
 * The function `handleModalCategoryChange` toggles the visibility of a specified element based on the
 * selected value of a category dropdown within a modal.
 * @param obj - {
 * modalSelector
 * categorySelector
 * typeSelector
 * }
 */
function handleModalCategoryChange(modalSelector, categorySelector, typeSelector) {
    $(modalSelector).on('shown.bs.modal', function () {
        $(categorySelector).on('change', function () {
            $(typeSelector).parent().toggleClass('d-none', $(this).val() !== 'others');
        });
    });
}

/**
 * The `validateForm` function checks if the required fields are empty and highlights them using
 * Bootstrap's invalid class if they are.
 * @param (array) requiredFields
 * @returns The `validateForm` function returns a boolean value indicating whether all the required
 * fields are filled out or not. If all required fields have a value, the function returns `true`,
 * otherwise it returns `false`.
 */
function validateForm(requiredFields) {
    let isValid = true;

    // Check each required field
    requiredFields.forEach(function (selector) {
        if ($(selector).val() === '' || $(selector).val() === null) {
            isValid = false;
            $(selector).addClass(
                'is-invalid'); // Add Bootstrap's invalid class to highlight empty fields
        } else {
            $(selector).removeClass(
                'is-invalid'); // Remove the invalid class if the field is not empty
        }
    });

    return isValid;
}

/**
 * The function `initClick` attaches a click event listener to elements matching the selector specified
 * in the `obj` parameter, and executes the function `obj.func` when the click event occurs.
 * @param obj {
 * selector
 * func
 * }
 * @returns The `initClick` function is returning the result of attaching a click event handler to the
 * document for elements matching the selector specified in the `obj` parameter, with the function
 * `obj.func` being executed when the click event occurs.
 */
function initClick(selector, func) {
    return $(document).on('click', selector, func);
}

/**
 * The function `initDtServerSide` initializes a DataTable with server-side processing and various
 * buttons for exporting data.
 * @param obj - {
 * selector
 * route
 * columns
 * }
 */
function initDtServerSide(obj) {
    const dt = $(obj.selector).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: obj.route,
            data: validateKey(obj, 'additionalData', null)
        },
        columns: obj.columns,
        dom: 'Bfrtip',
        pageLength: 10,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });

    const dtInputSelector = $("#document-datatable_filter input")
    dtInputSelector.unbind();
    initTypingChecker(dtInputSelector, function () {
        dt.search(dtInputSelector.val()).draw();
    });

    $('#filterType, #filterDateFrom, #filterDateTo, #filterStatus, #filterUser').on('change', function() {
        dt.ajax.reload();
    });

    return dt;
}

/**
 * The `initTypingChecker` function in JavaScript sets up a typing checker that triggers a specified
 * function after a certain typing interval.
 * @param obj {
 * selector
 * func
 * typingInterval
 * }
 */
function initTypingChecker(selector, func) {
    let typingTimer;

    $(selector).on('keyup', function () {
        clearTimeout(typingTimer);

        typingTimer = setTimeout(func, 250);
    });

    $(selector).on('keydown', function () {
        clearTimeout(typingTimer);
    });
}

/**
 * The function `validateKey` checks if a key exists in an object and returns its value, or a fallback
 * value if the key does not exist.
 * @param obj, key, fallback
 * @returns The function `validateKey` checks if the key exists in the object `obj`. If the key exists,
 * it returns the corresponding value from the object. If the key does not exist, it returns the
 * `fallback` value provided as the third argument.
 */
function validateKey(obj, key, fallback) {
    return key in obj ? obj[key] : fallback
}

function initExcerpt() {
    initClick('.see-more', function (e) {
        e.preventDefault();
        var excerpt = this.previousElementSibling.previousElementSibling;
        var fullText = this.previousElementSibling;
        if (fullText.classList.contains('d-none')) {
            fullText.classList.remove('d-none');
            excerpt.classList.add('d-none');
            this.textContent = 'See less';
        } else {
            fullText.classList.add('d-none');
            excerpt.classList.remove('d-none');
            this.textContent = 'See more';
        }
    })
}

function initDtDelete(btnSelector, formSelector) {
    initClick(btnSelector, function () {
        var id = $(this).data('bs-id');
        $('#confirmModal').modal('show');
        $('#confirmDelete').off('click').on('click', function () {
            $(formSelector + id).submit();
        });
    })
}

function initDtReceive(btnSelector, formSelector) {
    initClick(btnSelector, function () {
        var id = $(this).data('bs-id');
        $('#confirmModal').modal('show');
        $('#confirmDelete').off('click').on('click', function () {
            $(formSelector + id).submit();
        });
    })
}
